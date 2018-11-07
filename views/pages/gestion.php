<?php
    $path_camera = 'camera.php';
    $path_sign = 'sign.php';
    $path_galery = 'galery.php';
    $path_logout = 'logout.php';
    $path_gestion = '#';
    $path_bootcss = '../css/bootstrap.min.css';
    $path_headercss = '../css/header.css';
    $path_icon = '../../public/transparent/camera.jpg';
    require_once('header.php');

?>

<!doctype html>
<html>

<head>
<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href= <?= $path_headercss ?>>
<style>

</style>
</head>
<body>
<!-- <?php var_dump($_SESSION);?> -->
<table class="table">
    <tr>
        <td colspan='2'>
            <input id='login' class="form-control" type="text" name="login" maxlength="10">
        </td>
        <td colspan='2'>
            <input id='btn_login' class="form-control" type="button" value="changer mon login">
        </td>
    </tr>
    <tr>
        <td colspan='2'>
            <input id='mail' class="form-control" type="email" name="mail">
        </td>
        <td colspan='2'>
            <input id='btn_mail' class="form-control" type="button" value="changer mon email">
        </td>
    </tr>
    <tr>
        <td>
            <input id='pw1' class="form-control" type='password' name='pw1'
                placeholder='Nouveau mot de passe'>
        </td>
        <td>
            <input id='pw2' class='form-control' type='password' name='pw2'
                placeholder='Confirmer mot de passe'>
        </td>
        <td colspan='2'>
            <input id='btn_pw' class='form-control' type='button' value='changer mon mdp'>
        </td>
    </tr>
    <tr>
        <td colspan='2'>
            <input class='form-control' type='text' disabled
            value='Réception des mails quand une de mes images est commenté'>
        </td>
        <td>
            <input id='mail_activer' class='form-control' type='button' value='activer'>
        </td>
        <td>
            <input id='mail_desactiver' class='form-control' type='button' value='Desactiver'>
        </td>
    </tr>
    <tr>
        <td colspan='4'><textarea id='info_res' style="resize:none;" class='form-control' disabled ></textarea></td>
    </tr>
</table>

<script>
function	ft_new_ajax()
{
    if (window.XMLHttpRequest)
        var oAjax = new XMLHttpRequest();
    else {
        var oAjax = new ActiveXObject("Microsoft.XMLHTTP");
    }
    return (oAjax);
}

function	ft_ajax(url, data, ft_succes, ft_faild)
{
    var oAjax = ft_new_ajax();
    if (!oAjax)
        return (0);
    oAjax.open('post', url, true);
    oAjax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    oAjax.onreadystatechange = function()
    {
        if (oAjax.readyState == 4)
        {
            if (oAjax.status == 200)
            {
                ft_succes(oAjax.responseText);
            }
            else if (ft_faild)
            {
                ft_faild(oAjax.status);
            }
        }
    }
    oAjax.send(data);
}

var oLogin = document.getElementById('login');
var oBtn_login = document.getElementById('btn_login');
var oMail = document.getElementById('mail');
var oBtn_mail = document.getElementById('btn_mail');
var oPw1 = document.getElementById('pw1');
var oPw2 = document.getElementById('pw2');
var oBtn_pw = document.getElementById('btn_pw');
var oMail_activer = document.getElementById('mail_activer');
var oMail_desactiver = document.getElementById('mail_desactiver');
var oInfo_res = document.getElementById('info_res');
oLogin.placeholder = "<?= $_SESSION['login']; ?>";
oMail.placeholder = "<?= $_SESSION['mail']; ?>";

oLogin.onclick = function()
{
    oInfo_res.innerText = 'Votre login doit être compris entre 3 et 9 caractères.';
}

oPw1.onclick = function()
{
    oInfo_res.innerText =
        "Votre mot de passe doit être compris entre 6 et 16 caractères,\n"
        + "et contenir au moins un chiffre, une majuscule et une minuscule."
}

oPw2.onclick = function()
{
    oInfo_res.innerText =
        "Votre mot de passe doit être compris entre 6 et 16 caractères,\n"
        + "et contenir au moins un chiffre, une majuscule et une minuscule.";
}

oMail_desactiver.onclick = function()
{
    var url = '../../controllers/input.c.php';
    var data = 'action=mail_desactiver'
        + '&id_user=' + <?= $_SESSION['id_user']; ?>;
    ft_ajax(url, data, function(response)
    {
        oInfo_res.innerText = response;
    });
    ft_mail_onload();
}

oMail_activer.onclick = function()
{
    var url = '../../controllers/input.c.php';
    var data = 'action=mail_activer'
        + '&id_user=' + <?= $_SESSION['id_user']; ?>;
    ft_ajax(url, data, function(response)
    {
        oInfo_res.innerText = response;
    });
    ft_mail_onload();
}

function ft_mail_onload()
{
    var url = '../../controllers/input.c.php';
    var data = 'action=mail_onload'
        + '&id_user=' + <?= $_SESSION['id_user'] ?>;
    ft_ajax(url, data, function(response)
    {
        if (response == '1')
        {
            oMail_desactiver.className = 'form-control';
            oMail_activer.className = 'form-control btn-primary';
        }
        else if (response == '0')
        {
            oMail_desactiver.className = 'form-control btn-primary';
            oMail_activer.className = 'form-control';
        }
    });
}

oBtn_login.onclick = function()
{
    var url = '../../controllers/input.c.php';
    var data = 'action=login_modif'
        + '&login=' + oLogin.value
        + '&id_user=' + <?= $_SESSION['id_user'] ?>;
    // alert(data);
    ft_ajax(url, data, function(response)
    {
        oInfo_res.innerText = response;
        ft_info_onload();
    });
}

oBtn_mail.onclick = function()
{
    var url = '../../controllers/input.c.php';
    var data = 'action=mail_modif'
        + '&mail=' + oMail.value
        + '&id_user=' + <?= $_SESSION['id_user'] ?>;
    // alert(data);
    ft_ajax(url, data, function(response)
    {
        oInfo_res.innerText = response;
        ft_info_onload();
    });
}

oBtn_pw.onclick = function()
{
    var url = '../../controllers/input.c.php';
    var data = 'action=pw_modif'
        + '&pw1=' + oPw1.value
        + '&pw2=' + oPw2.value
        + '&id_user=' + <?= $_SESSION['id_user'] ?>;
    // alert(data);
    ft_ajax(url, data, function(response)
    {
        oInfo_res.innerText = response;
        oPw1.value = '';
        oPw2.value = '';
    });
}

function ft_info_onload()
{
    var url = '../../controllers/input.c.php';
    var data = 'action=info_onload'
        + '&id_user=' + <?= $_SESSION['id_user'] ?>;
    ft_ajax(url, data, function(response)
    {
        var res = JSON.parse(response);
        // console.log(response);
        oLogin.value = '';
        oMail.value = '';
        oLogin.placeholder = res[0]['login'];
        oMail.placeholder = res[0]['mail'];
    });
}

window.onload = function()
{
    ft_mail_onload();
    ft_info_onload();
}

</script>
</body>
</html>
