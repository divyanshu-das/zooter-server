<?php

class SoftDeleteBehavior extends ModelBehavior {
/**
 * Default settings
 *
 * @var array $default
 */
	public $default = array(
		'deleted' => 'deleted_date'
	);

/**
 * Holds activity flags for models
 *
 * @var array $runtime
 */
	public $runtime = array();

/**
 * Setup callback
 *
 * @param Model $model
 * @param array $settings
 */
	public function setup(Model $model, $settings = array()) {
		if (empty($settings)) {
			$settings = $this->default;
		} elseif (!is_array($settings)) {
			$settings = array($settings);
		}

		$error = 'SoftDeleteBehavior::setup(): model ' . $model->alias . ' has no field ';
		$fields = $this->_normalizeFields($model->alias, $settings); 
		foreach ($fields as $flag => $date) {
			if ($model->hasField($flag)) {
				if ($date && !$model->hasField($date)) {
					trigger_error($error . $date, E_USER_NOTICE);
					return;
				}
				continue;
			}
			trigger_error($error . $flag, E_USER_NOTICE);
			return;
		}

		$this->settings[$model->alias] = $fields;
		$this->softDeleteActive($model, true);
	}

/**
 * Before find callback
 *
 * @param Model $model
 * @param array $query
 * @return array
 */
	public function beforeFind(Model $model, $query = null) {
		$runtime = $this->runtime[$model->alias];
		if ($runtime) {
			if (!is_array($query['conditions'])) {
				$query['conditions'] = array();
			}
			$conditions = array_filter(array_keys($query['conditions']));
			$fields = $this->_normalizeFields($model->alias);
			foreach ($fields as $flag => $date) {
				if (true === $runtime || $flag === $runtime) {
					if (!in_array($flag, $conditions) && !in_array($model->name . '.' . $flag, $conditions)) {
						$query['conditions'][$model->alias . '.' . $flag] = false;
					}

					if ($flag === $runtime) {
						break; 
					}
				}
			}
			
			$finalContain = array();
			if (!empty($query['contain']))
				$contain = $query['contain'];
			if (isset($contain)){	
				foreach ($contain as $modelName => $value) {
					if (is_numeric($modelName)) $modelName = $value;
					if (!empty($this->runtime[$modelName])){
						$fields = $this->_normalizeFields($modelName);
						foreach ($fields as $flag => $date) {
							if (array_key_exists($modelName, $model->hasMany)) $model->hasMany[$modelName]['conditions'][$modelName . '.' . $flag ]= false;
							if (array_key_exists($modelName, $model->hasOne)) $model->hasOne[$modelName]['conditions'][$modelName . '.' . $flag ]= false;
							if (array_key_exists($modelName, $model->belongsTo)) $model->belongsTo[$modelName]['conditions'][$modelName . '.' . $flag ]= false;
							if (array_key_exists($modelName, $model->hasAndBelongsToMany)){
								$model->hasAndBelongsToMany[$modelName]['conditions'][$modelName . '.' . $flag ]= false;
								$joinTableModel = $model->hasAndBelongsToMany[$modelName]['with'];
								$model->hasAndBelongsToMany[$modelName]['conditions'][$joinTableModel . '.' . $flag ] = false;

							} 
						}
					}
				}
			}
			return $query;
		}
	}

/**
 * Before find callback
 *
 * @param Model $model
 * @param array $array, $query(optional)
 * All the associated table to be deleted can be added as $query in the form
 * array('contain' => array('AssociatedModelName1','AssociatedModelName2'))
 *array of id can also be passed for for deletion
 * @return true/false
 */

public function softDelete($model,$array, $query = null){
	if (empty($array)){
		trigger_error('No data is passed in softDelete function', E_USER_NOTICE);
		return false;
	}
	$runtime = $this->runtime[$model->alias];
	if ($runtime){
		$date = date('Y-m-d H:i:s') ;
		$modelName = $model->alias;
		$primaryKey = $model->primaryKey;
		$model->updateAll(
			array(
				"$modelName.deleted" => true,
				"$modelName.deleted_date" => "'$date'"
				),
			array(
				"$modelName.deleted" => false,
				"$modelName.$primaryKey" => $array	
			));
		if (!is_array($query['contain'])) {
				$query['contain'] = array();
		}
		$contain = $query['contain'];
		foreach ($contain as $associatedModel) {
			$associatedModelexists = false;
			if (array_key_exists($associatedModel, $model->hasMany)){
				$foreignKey = $model->hasMany[$associatedModel]['foreignKey'];
				$associatedModelexists = true;
			} elseif (array_key_exists($associatedModel, $model->hasOne)){
				$foreignKey = $model->hasOne[$associatedModel]['foreignKey'];
				$associatedModelexists = true;
			} elseif (array_key_exists($associatedModel, $model->hasAndBelongsToMany)){
				$foreignKey = $model->hasAndBelongsToMany[$associatedModel]['foreignKey'];
				$associatedModel = $model->hasAndBelongsToMany[$associatedModel]['with'];
				$associatedModelexists = true;
			}
			if ($associatedModelexists){
				$model->$associatedModel->updateAll(
					array(
					"$associatedModel.deleted" => true,
					"$associatedModel.deleted_date" => "'$date'"
					),
					array(
					"$associatedModel.deleted" => false,
					"$associatedModel.$foreignKey" => $array	
				));
			}

		}
		return true;	
	}
	return false;
}


/**
 * Enable/disable SoftDelete functionality
 *
 * Usage from model:
 * $this->softDeleteActive(false); deactivate this behavior for model
 * $this->softDeleteActive('field_two'); enabled only for this flag field
 * $this->softDeleteActive(true); enable again for all flag fields
 * $config = $this->softDeleteActive(null); for obtaining current setting
 *
 * @param object $model
 * @param mixed $active
 * @return mixed if $active is null, then current setting/null, or boolean if runtime setting for model was changed
 */
	public function softDeleteActive($model, $active) {
		if (is_null($active)) {
			return isset($this->runtime[$model->alias]) ? @$this->runtime[$model->alias] : null;
		}

		$result = !isset($this->runtime[$model->alias]) || $this->runtime[$model->alias] !== $active;
		$this->runtime[$model->alias] = $active;
		$this->_softDeleteAssociations($model, $active);
		return $result;
	}


/**
 * Return normalized field array
 *
 * @param object $model
 * @param array $settings
 * @return array
 */
	protected function _normalizeFields($model, $settings = array()) {
		if (empty($settings)) {
			$settings = $this->settings[$model];
		}
		$result = array();
		foreach ($settings as $flag => $date) {
			if (is_numeric($flag)) {
				$flag = $date;
				$date = false;
			}
			$result[$flag] = $date;
		}
		return $result;
	}

/**
 * Modifies conditions of hasOne and hasMany associations
 *
 * If multiple delete flags are configured for model, then $active=true doesn't
 * do anything - you have to alter conditions in association definition
 *
 * @param object $model
 * @param mixed $active
 */
	protected function _softDeleteAssociations($model, $active) {
		if (empty($model->belongsTo)) {
			return;
		}

		$fields = array_keys($this->_normalizeFields($model->alias));
		$parentModels = array_keys($model->belongsTo);

		foreach ($parentModels as $parentModel) {
			foreach (array('hasOne', 'hasMany') as $assocType) {
				if (empty($model->{$parentModel}->{$assocType})) {
					continue;
				}

				foreach ($model->{$parentModel}->{$assocType} as $assoc => $assocConfig) {
					$modelName = empty($assocConfig['className']) ? $assoc : @$assocConfig['className'];
					if ((!empty($model->plugin) && strstr($model->plugin . '.', $model->alias) === false ? $model->plugin . '.' : '') . $model->alias !== $modelName) {
						continue;
					}

					$conditions =& $model->{$parentModel}->{$assocType}[$assoc]['conditions'];
					if (!is_array($conditions)) {
						$model->{$parentModel}->{$assocType}[$assoc]['conditions'] = array();
					}

					$multiFields = 1 < count($fields);
					foreach ($fields as $field) {
						if ($active) {
							if (!isset($conditions[$field]) && !isset($conditions[$assoc . '.' . $field])) {
								if (is_string($active)) {
									if ($field == $active) {
										$conditions[$assoc . '.' . $field] = false;
									} elseif (isset($conditions[$assoc . '.' . $field])) {
										unset($conditions[$assoc . '.' . $field]);
									}
								} elseif (!$multiFields) {
									$conditions[$assoc . '.' . $field] = false;
								}
							}
						} elseif (isset($conditions[$assoc . '.' . $field])) {
							unset($conditions[$assoc . '.' . $field]);
						}
					}
				}
			}
		}
	}
}
