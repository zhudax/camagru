<?php
    $path_camera = '../views/pages/camera.php';
    $path_sign = '../views/pages/sign.php';
    $path_galery = '../views/pages/galery.php';
    $path_logout = '../views/pages/logout.php';
    $path_gestion = '../views/pages/gestion.php';
    $path_bootcss = '../views/pages/../css/bootstrap.min.css';
    $path_headercss = '../views/pages/../css/header.css';
    $path_icon = '../public/transparent/camera.jpg';
    require_once('../views/pages/header.php');

require_once("../config/database.php");
require_once("../models/tools.class.php");

$db_mysql = ft_connection($DB_DSN_MYSQL, $DB_USER, $DB_PASSWORD);
$db = ft_connection($DB_DSN, $DB_USER, $DB_PASSWORD);

if ($_GET['action'] == 'mail_confirme' && ft_verif($_GET))
{
	ft_mail_verif($db, $_GET);
}

if ($_GET['action'] == 'mail_pw' && ft_verif($_GET))
{
	ft_pw_verif($db, $_GET);
}
?>
