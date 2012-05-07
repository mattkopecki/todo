<?php

  setcookie("UserID", "", time() - 3600);
  header('Location: index.php');

?>