<?php
App::uses('CakeEmail', 'Network/Email');
App::uses('Component', 'Controller');
Class ZooterEmailComponent extends Component {

  public function _isFakeEmail($email) {
    $endsWith = '@mailinator.com';
    $startsWith = 'user_';
    if ((substr_compare($email, $endsWith, -strlen($endsWith), strlen($endsWith)) === 0) &&
      (substr_compare($email, $startsWith, 0, strlen($startsWith)) === 0)) {
      return true;
    } else {
      return false;
    }
  }

  public function getSalutation($profile){
    $salutation = 'Mr.';
    if(!empty($profile['Profile']['last_name']) && !empty($profile['Profile']['gender'])){
      if($profile['Profile']['gender'] == 'male'){
        $salutation = 'Hello Mr '.$profile['Profile']['last_name'];
      }elseif($profile['Profile']['gender'] == 'femail'){
        $salutation = 'Hello Madam '.$profile['Profile']['last_name'];
      }
    }
    return $salutation;
  }

  public function sendMandrillEmail($template, $toEmail, $fromEmail, $emailVars, $subjectText, $emailAttachment = ''){
    if (!$this->_isFakeEmail($toEmail)) {
      $email = new CakeEmail();
      $email->config('mandrill')
            ->to($toEmail)
            ->from($fromEmail)
            ->viewVars($emailVars);
      $email->subject($subjectText);
      if(!empty($emailAttachment)){
        $email->attachments($emailAttachment);
      }
      if(!empty($template)){
        $email->config(array('mandrillTemplate' => $template));   
      }
      $result = $email->send();
      return $result;
    }
    return false;
  }
}