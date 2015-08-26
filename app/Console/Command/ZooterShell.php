<?php

class ZooterShell extends AppShell {

  var $tasks = array('Email');

  public function main() {
    $this->out('Hello world.');
  }

  public function send_transactional_emails(){
    $this->Email->sendZooterTransactionalEmails();
  }
}