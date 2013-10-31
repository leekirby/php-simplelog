<?php


  class classSimpleLog {

    private $enabled       = true;

    private $logType       = 3; /* 0 for PHP error log, 3 for custom log */
    private $logCustomFile = '/var/www/logs/custom.log';
    private $logMessageIDs = array();

    private $logMessageID = 0;
    private $logUniqueID  = 0;
    private $logMessageString = '';

    public function __construct() {

      if(!file_exists($this->logCustomFile)) {
        print "Custom log file does not exist!";
        $this->enabled = false;
        exit();
      }

      if(!is_writable($this->logCustomFile)) {
        print "Custom log file is not writable!";
        $this->enabled = false;
        exit();
      }

      $this->messageIDs = array(

        0 => 'Message Not Set',

        1 => 'Accessed‎',
        2 => 'Created',
        3 => 'Updated',
        4 => 'Deleted',
        5 => 'Signed in',
        6 => 'Signed out'

      );

    }

    private function lookupMessageID() {
      $this->logMessageString = (is_numeric($this->logMessageID) && isset($this->messageIDs[$this->logMessageID])) 
        ? sprintf('%s', trim($this->messageIDs[$this->logMessageID]))
        : '';
    }

    private function checkID($id) {
      return (isset($id) && !empty($id) && is_numeric($id)) 
        ? $id 
        : 0;
    }

    public function commit($mid, $uid) {

      $this->logMessageID = $this->checkID($mid);
      $this->logUniqueID = $this->checkID($uid);

      $this->lookupMessageID();

      $this->logMessageString = sprintf(
        "%s[%s] [UID:%d] [%d][%s] [%s]%s", 
          ($this->logType === 3) ? sprintf('[%s]', date("r")) : '', 
          $_SERVER['REMOTE_ADDR'], 
          $this->logUniqueID,
          $this->logMessageID,
          $this->logMessageString, 
          $_SERVER['HTTP_USER_AGENT'],
          ($this->logType === 3) ? "\n" : ''
      );

      error_log(
        $this->logMessageString, 
        $this->logType, 
        $this->logCustomFile
      );

    }

  }