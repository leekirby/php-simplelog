<?php

  include_once('simplelog.class.php');

  $log=new classSimpleLog();

  $log->commit(1, 9999);
  $log->commit(4, 9999);