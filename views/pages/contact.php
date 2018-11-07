<?php
$path_camera = 'camera.php';
$path_sign = 'sign.php';
$path_galery = 'galery.php';
$path_logout = 'logout.php';
$path_gestion = 'gestion.php';
$path_bootcss = '../css/bootstrap.min.css';
$path_headercss = '../css/header.css';
$path_icon = '../../public/transparent/camera.jpg';

require_once('header.php');
?>

<html>
<head>
<style>
</style>
</head>
<body>
        <center><div id="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2622.9856572369904!2d2.31630695180041!3d48.89661050586645!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66fa9e73a1ef7%3A0x4e808812dd36a382!2s42!5e0!3m2!1sfr!2sfr!4v1538861079831"
                width="600" height="450" frameborder="0" style="border:0" allowfullscreen>
            </iframe>
        </div></center>

        <table class="table table-bordered table-striped table-hover">
            <tr>
                <td><b>Contact us</b></td>
            </tr>
            <tr>
                <td>Address</td>
                <td>96 Boulevard Bessières<br />75017 Paris</td>
            </tr>
            <tr>
                <td>Telephone</td>
                <td>+33 6 12 12 18 81</td>
            </tr>
            <tr>
                <td rowspan="2">Send a Mail</td>
                <td><a style="color:blue;" href="mailto:zxu@student.42.fr">zxu@student.42.fr</a></td>
            </tr>
        </table>
</body>
</html>
