<?php
// kontakt mot felles db
  class vikerfjell extends mysqli{
    function __construct(
    $host = "158.36.139.21",
    $base =  "vikerfjell",
    $user = "brViker",
    $pw = "pw_Viker",
    $port = "3306"
    ){
      parent::__construct($host, $user, $pw, $base, $port);
    }
  }
  $db = new vikerfjell();
  // kontakt mot vår DB:


  /*
  class vikerfjell extends mysqli{
    function __construct(
    $host = "158.36.139.21";
    $base =  "vikerfjell";
    $user = "brViker";
    $pw = "pw_Viker";
    $port = "3306";
    ){
      parent::__construct($host, $user, $pw, $base, $port);
    }
  }


  */
?>
