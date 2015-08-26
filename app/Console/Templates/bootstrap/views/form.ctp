<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Console.Templates.default.views
 * @since         CakePHP(tm) v 1.2.0.5234
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
	<div class="row">
		<div class="col-md-12">
			<h1><?php printf("<?php echo __('%s %s'); ?>", Inflector::humanize($action), $singularHumanName); ?></h1>
		</div>
	</div>

<?php 		echo "\t\t\t<?php echo \$this->Form->create('{$modelClass}', array('role' => 'form')); ?>\n\n"; ?>
<?php
	foreach ($fields as $field) {
		if (strpos($action, 'add') !== false && $field == $primaryKey) {
			continue;
		} elseif (!in_array($field, array('created', 'modified', 'updated'))) {
			echo "\t\t\t\t<div class=\"form-group\">\n";
			echo "\t\t\t\t\t<?php echo \$this->Form->input('{$field}', array('class' => 'form-control', 'placeholder' => '".Inflector::humanize($field)."'));?>\n";
			echo "\t\t\t\t</div>\n";
		}
	}
	if (!empty($associations['hasAndBelongsToMany'])) {
		foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
			echo "\t\t\t\t<div class=\"form-group\">\n";
			echo "\t\t\t\t\t<?php echo \$this->Form->input('{$assocName}', array('class' => 'form-control', 'placeholder' => '".Inflector::humanize($field)."'));?>\n";
			echo "\t\t\t\t</div>\n";
		}
	}
?>
<?php
	echo "\t\t\t\t<div class=\"form-group\">\n";
	echo "\t\t\t\t\t<?php echo \$this->Form->submit(__('Submit'), array('class' => 'btn btn-default')); ?>\n";
	echo "\t\t\t\t</div>\n\n";
	echo "\t\t\t<?php echo \$this->Form->end() ?>\n\n";

?>