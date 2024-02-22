<?php
ob_start();
session_start();

$_SESSION=[];
session_unset();
session_destroy();
setcookie(session_name(), "", 0, "/");
header("Location: /");
ob_end_flush();
