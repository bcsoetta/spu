<?php
//output all errors
error_reporting(E_ALL);
//some pre-defined shit
include 'system/config.php';
//helper
include 'system/helper.php';
//here we should include base class of everything
include 'system/base_model.php';
include 'system/base_controller.php';
//include all models
include_all($config['path']['models']);
//then we include every controller and model we've got
include_all($config['path']['controllers']);
//start it
require_once 'system/boot.php';
?>