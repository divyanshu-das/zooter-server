<?php
App::uses('AppModel', 'Model');
/**
 * TransactionalEmail Model
 *
 */
class TransactionalEmail extends AppModel {

  /**
   * beforeSave callback
   *
   * @param $options array
   * @return boolean
   */
    public function beforeSave($options = array()) {
      if(is_array($this->data['TransactionalEmail']['merge_vars'])){
        $this->data['TransactionalEmail']['merge_vars'] = json_encode($this->data['TransactionalEmail']['merge_vars']); 
      }
      if(empty($this->data['TransactionalEmail']['from_email'])){
        $this->data['TransactionalEmail']['from_email'] = array('donotreply@zooter.in' => 'Zooter');
      }
      if(is_array($this->data['TransactionalEmail']['from_email'])){
        $this->data['TransactionalEmail']['from_email'] = json_encode($this->data['TransactionalEmail']['from_email']); 
      }   
      return true;
    }

  /**
   * afterFind callback
   *
   * @param $results array
   * @param $primary boolean
   * @return mixed
   */
    public function afterFind($results, $primary = false) {
      foreach ($results as $key => $val) {
        if (isset($val['TransactionalEmail']['merge_vars'])) {
          $results[$key]['TransactionalEmail']['merge_vars'] = json_decode($val['TransactionalEmail']['merge_vars'], true);
        }

        if (isset($val['TransactionalEmail']['from_email'])) {
          $results[$key]['TransactionalEmail']['from_email'] = json_decode($val['TransactionalEmail']['from_email'], true);
        }
      }
      return $results;
    }
    

    public function queueTransactionEmail($fromEmail, $toEmail, $subject, $merge_vars, $attachments, $template){
      // $salutation = $this->getSalutation($toEmail);
      if(empty($merge_vars)){
        $merge_vars = array();
      }
      // $merge_vars = am($merge_vars, array('salutation' => $salutation));
      $data = array(
        'TransactionalEmail' => array(
          'from_email' => $fromEmail,
          'to_email' => $toEmail,
          'subject' => $subject,
          'merge_vars' => $merge_vars,
          'attachments' => $attachments,
          'template' => $template
        )
      );
      $this->create();
      return $this->save($data);
    }

    public function getEmailsToSend(){
      $emails = $this->find('all', array(
        'conditions' => array(
          'TransactionalEmail.is_sent' => false
        )
      ));
      return $emails;
    }

    public function updateEmailsSent($ids){
      $dateNow = date('Y-m-d H:i:s');
      return $this->updateAll(
        array('TransactionalEmail.delivery_datetime' => "'".$dateNow."'", 'TransactionalEmail.is_sent' => true),
        array('TransactionalEmail.id' => $ids)
      );
    }
}
