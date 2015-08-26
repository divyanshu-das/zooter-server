<?php
App::uses('ComponentCollection', 'Controller');
App::uses('ZooterEmailComponent', 'Controller/Component');
class EmailTask extends AppShell {

  public $ZooterEmail;

  public $uses = array('TransactionalEmail');

  public function initialize(){
    parent::initialize();
    $collection = new ComponentCollection();
    $this->ZooterEmail = new ZooterEmailComponent($collection);
  }

  public function sendZooterTransactionalEmails(){
    $emails = $this->TransactionalEmail->getEmailsToSend();
    foreach ($emails as $email) {
      $template = $email['TransactionalEmail']['template'];
      $toEmail = $email['TransactionalEmail']['to_email'];
      $fromEmail = $email['TransactionalEmail']['from_email'];
      $emailVars = $email['TransactionalEmail']['merge_vars'];
      $subjectText = $email['TransactionalEmail']['subject'];
      $emailAttachment = $email['TransactionalEmail']['attachments'];
      $this->out(__('Sending %s : "%s" email to %s', $template, $subjectText, $toEmail));
      if ( ! empty($emailAttachment)) {
        if ( ! file_exists($emailAttachment)) {
          $this->out(__('Attachment does not exist. So Email Not Sent'));
          continue;
        }
      }
      $result = $this->ZooterEmail->sendMandrillEmail($template, $toEmail, $fromEmail, $emailVars, $subjectText, $emailAttachment);
      if($result){
        $this->out('Email Sent');
        $this->TransactionalEmail->updateEmailsSent($email['TransactionalEmail']['id']);
      }else{
        $this->out(__('Email sending failed'));
      }
    }
  }


  public function sendVerificationEmailsToNewUsers(){
    $userIDs = $this->User->getUsersToSendVerificationEmail();
    foreach($userIDs as $userID){
      $this->out(__('Sending verification email for User ID : %d', $userID));
      $result = $this->ZooterEmail->sendVerificationEmail($userID);
      if($result){
        $this->out(__('Email Sent successfully'));
        if($this->User->updateVerificationEmailSent($userID)){
          $this->out(__('Updating User ID %d : Success', $userID));
        }else{
          $this->out(__('Updating user ID %d : Failed', $userID));
        }
      }else{
        $response = json_encode($result);
        $this->out(__('Email could not be sent. Response : %s', $response));
      }
    }
  }
}