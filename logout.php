<?php
require_once 'core/init.php';

$user = new User();
$user->logout();

Session::flash('index', 'Session end');
Redirect::to('index.php');

?>