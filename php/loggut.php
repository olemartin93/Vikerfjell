<?php
  session_start();
  $_SESSION = array();
  session_unset(); //Frigjør alle session-variabler
  session_destroy(); //ødelegger alle data til session-variabler
  echo"<script> window.location.href='../default.php';</script>";
  die();
?>
