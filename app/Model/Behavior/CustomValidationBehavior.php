<?php

class CustomValidationBehavior extends ModelBehavior {
  
   public function idExist(Model $model,$check) {
    foreach ($check as $key => $value){
        $count = $model->find('count', array("conditions" => array('id' => $value )));
    }
    if(!$count){
      return "Id does not exist  $value:: $model->alias model";
    }
    return true;
  }

  public function seriesExist(Model $model,$check) {
    foreach ($check as $key => $value){
      if ($model->alias == 'Series')
        $count = $model->find('count', array("conditions" => array('Series.id' => $value )));
      else
        $count = $model->Series->find('count', array("conditions" => array('Series.id' => $value )));
    }
    if(!$count){
      return "series id $value does not exist :: $model->alias model";
    }
    return true;
  }

  public function teamExist(Model $model,$check) {
    foreach ($check as $key => $value){
      if ($model->alias == 'Team')
        $count = $model->find('count', array("conditions" => array('Team.id' => $value )));
      else
        $count = $model->Team->find('count', array("conditions" => array('Team.id' => $value )));
    }
    if(!$count){
      return "team id $value does not exist :: $model->alias model";
    }
    return true;
  }

  public function userExist(Model $model,$check) {
    foreach ($check as $key => $value){
      if ($model->alias == 'User')
        $count = $model->find('count', array("conditions" => array('User.id' => $value )));
      else
        $count = $model->User->find('count', array("conditions" => array('User.id' => $value )));
    }
    if(!$count){
      return "user id $value does not exist :: $model->alias model";
    }
    return true;
  }

  public function matchExist(Model $model,$check) {
    foreach ($check as $key => $value){
      if ($model->alias == 'Match')
        $count = $model->find('count', array("conditions" => array('Match.id' => $value )));
      else
        $count = $model->Match->find('count', array("conditions" => array('Match.id' => $value )));
    }
    if(!$count){
      return "match id $value does not exist :: $model->alias model";
    }
    return true;
  }

  public function locationExist(Model $model,$check) {
    foreach ($check as $key => $value){
      if ($model->alias == 'Location')
        $count = $model->find('count', array("conditions" => array('Location.id' => $value )));
      else
        $count = $model->Location->find('count', array("conditions" => array('Location.id' => $value )));
    }
    if(!$count){
      return "location id $value does not exist :: $model->alias model";
    }
    return true;
  }

  public function pastDatetime(Model $model,$check) {
    foreach ($check as $key => $value){
      $dateTime = date('Y-m-d H:i:s');
      if( $value >  $dateTime ) {
        return "DateTime cannot be of Future date";
      }
    }
    return true;
  }

  public function futureDatetime(Model $model,$check) {
    foreach ($check as $key => $value){
      $dateTime = date('Y-m-d H:i:s', strtotime('- 10 minutes'));
      if( $value <  $dateTime ) {
        return "dateTime cannot be of Past date";
      }
    }
    return true;
  }

   public function futureDate(Model $model,$check) {
    foreach ($check as $key => $value){
      $dateTime = date('Y-m-d', strtotime('- 10 minutes'));
      if( $value <  $dateTime ) {
        return "date cannot be of Past date";
      }
    }
    return true;
  }

  public function validateId(Model $model) {
      $model->validator()->add(
        'id', array(
          'idExist' => array(
            'rule' => 'idExist',
            'required' => false,
            "allowEmpty" => true
          )
      ));
    }
}