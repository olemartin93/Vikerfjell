<?php
  if(isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']>600)){
    // bruker blir logget ut dersom bruker ikke er aktiv i 10 minutter
    $fmessage = "Du har vært inaktiv i 10 minutter og ble dermed logget ut.";
    session_unset();
    session_destroy();
    echo"<script> window.location.href='../php/logginn.php';alert('$fmessage'); </script>";
    die();
  }
  $_SESSION['LAST_ACTIVITY'] = time();
  //oppdaterer siste aktivitet time stamp
?>
