<?php
require_once __DIR__ . '/yardimci.php';
session_unset();
session_destroy();
header('Location: anasayfa.php');
exit;
