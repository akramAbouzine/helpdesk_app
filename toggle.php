<?php
require_once __DIR__.'/functions.php';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['id'])){ toggle_status((int)$_POST['id']); }
header('Location: index.php'); exit;
