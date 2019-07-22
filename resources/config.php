<?php

ob_start();
session_start();
// session_destroy();
// paths definitions with constants.. DS-Directory Separator
//__DIR__ = magic constant
defined("DS") ? null: define("DS", DIRECTORY_SEPARATOR);
// front-end

defined("TEMPLATE_FRONT") ? null: define("TEMPLATE_FRONT", __DIR__ . DS . "templates/front");
// backend
defined("TEMPLATE_BACK") ? null: define("TEMPLATE_BACK", __DIR__ . DS . "templates/back");
//folder that contains admin uploads
defined("UPLOAD_DIRECTORY") ? null: define("UPLOAD_DIRECTORY", __DIR__ . DS . "uploads");

// databases paths definition
// host databse
defined("DB_HOST") ? null: define("DB_HOST","localhost");
defined("DB_USER") ? null: define("DB_USER","root");
defined("DB_PASS") ? null: define("DB_PASS","");
defined("DB_NAME") ? null: define("DB_NAME","isaleHomestore_db");

// create connection to the databases using mysqli
$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

// function.php file
require_once("functions.php");
require_once("cart.php");


?>