<?php
    $path_camera = 'camera.php';
    $path_sign = 'sign.php';
    $path_galery = 'galery.php';
    $path_logout = 'logout.php';
    $path_bootcss = '../css/bootstrap.min.css';
    $path_headercss = '../css/header.css';
    $path_icon = '../../public/transparent/camera.jpg';
    require_once('header.php');

    $_SESSION = array();
    header('Location: galery.php');
?>

<!doctype html>
<html>

<head>
<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href= <?= $path_headercss ?>>
</head>
<body>
    logout
</body>
</html>
