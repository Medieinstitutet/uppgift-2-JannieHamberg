<?php
session_start();
session_unset();
session_destroy();
header("Location: pages/logged_out.php");
exit;
?>
