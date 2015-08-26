<?php
App::uses('AppModel', 'Model');
App::uses('Priority' ,'Lib/Enum');
App::uses('ReminderStatus' ,'Lib/Enum');
/**
 * Reminder Model
 *
 * @property User $User
 */
class Reminder extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'ReminderSetFor' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ReminderSetBy' => array(
			'className' => 'User',
			'foreignKey' => 'created_by',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ReminderModifiedBy' => array(
			'className' => 'User',
			'foreignKey' => 'modified_by',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

  public function getNewRemindersCount($userId , $lastSeenReminderTime) {
    $count = $this->find('count' , array(
      'conditions' => array(
        'user_id' => $userId,
        'created >=' => $lastSeenReminderTime
        )
    ));
    return $count;
  }

	public function fetchReminders($userId) {
		$currentDate = date('Y-m-d H:i:s');
		$tomorrowDate = date('Y-m-d H:i:s', strtotime(' +1 day'));
		//pr($currentDate);pr($tomorrowDate);
		$dataArray = $this->find('all', array(
      'conditions' => array(
        'UserToBeReminded.user_id' => $userId,
        'UserToBeReminded.status' => ReminderStatus::ENABLED,
        'UserToBeReminded.reminder_time >=' => $currentDate,
        'UserToBeReminded.reminder_time <' => $tomorrowDate
      ),
      'contain' => array('ReminderSetFor' => array('fields' => array('id'),
      	                                       'Profile' => array('fields' => array('first_name'))),
                          'ReminderSetBy' => array('fields' => array('id'),
      	                                       'Profile' => array('fields' => array('first_name'))),
                        ),
      'fields' => array('id','user_id','main_text','subtext','reminder_time','priority','status','created_by'),
      'order' => 'UserToBeReminded.reminder_time DESC'
		));//pr($dataArray);die;
		if(empty($dataArray) ) {
			return null;
		}
    $reminderDataArray = array();
    $lowPrReminderData = array();
    $mediumPrReminderData = array();
    $highPrReminderData = array();
    foreach ($dataArray as $reminderData) {
    	$data =  array(
        'id' => $reminderData['UserToBeReminded']['id'],
        'priority_type' => $reminderData['UserToBeReminded']['priority'],
        'reminder_set_for' => array('User' => array('id' => $reminderData['ReminderSetFor']['id'],
                                                         'name' => $reminderData['ReminderSetFor']['Profile']['first_name'])),
        'reminder_set_by' => array('User' => array('id' => $reminderData['ReminderSetBy']['id'],
                                                         'name' => $reminderData['ReminderSetBy']['Profile']['first_name'])),
        'main_text' => $reminderData['UserToBeReminded']['subtext'],
        'subtext' => $reminderData['UserToBeReminded']['subtext'],
        'reminder_time' => $reminderData['UserToBeReminded']['reminder_time']
    	);
    	if($data['priority_type'] == Priority::LOW){
    		array_push($lowPrReminderData, $data);
    	}
    	else if($data['priority_type'] == Priority::MEDIUM) {
        array_push($mediumPrReminderData, $data);
    	}
    	else if($data['priority_type'] == Priority::HIGH) {
        array_push($highPrReminderData, $data);    		
    	}
    }
    array_push($reminderDataArray, array('LOW' => $lowPrReminderData));
    array_push($reminderDataArray, array('MEDIUM' => $mediumPrReminderData));
    array_push($reminderDataArray, array('HIGH' => $highPrReminderData));
    pr($reminderDataArray);die;
    return $reminderDataArray;
	}

}
