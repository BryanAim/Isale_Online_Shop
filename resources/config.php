<?php

ob_start();
session_start();
// paths definitions with constants
defined("DS") ? null: define("DS", DIRECTORY_SEPARATOR);
// front-end
defined("TEMPLATE_FRONT") ? null: define("TEMPLATE_FRONT", __DIR__ . DS . "templates/front");
// backend
defined("TEMPLATE_BACK") ? null: define("TEMPLATE_BACK", __DIR__ . DS . "templates/back");
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


?>