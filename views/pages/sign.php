<?php
    $path_camera = 'camera.php';
    $path_sign = '#';
    $path_galery = 'galery.php';
    $path_logout = 'logout.php';
    $path_gestion = 'gestion.php';
    $path_bootcss = '../css/bootstrap.min.css';
    $path_headercss = '../css/header.css';
    $path_icon = '../../public/transparent/camera.jpg';
    require_once('header.php');
?>

<!doctype html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../css/header.css">
    <script type="text/javascript" src='../js/tool.js'></script>
</head>

<body>
<table class="table">
    <tr>
    <td>
        <table class="table">
            <tr>
                <td>
                    Identifiant:<br />
                    <input id='login' class="form-control"
                        type="text" name="login" maxlength="9"/>
                </td>
            </tr>
            <tr>
                <td>
                    Mot de pass:<br />
                    <input id='pw1' class="form-control"
                            type="password" name="pw1" maxlength="16"/>
                </td>
            </tr>
            <tr>
                <td>
                    Confirmer le mot de pass:<br />
                    <input id='pw2' class="form-control"
                            type="password" name="pw2" maxlength="16"/>
                </td>
            </tr>
            <tr>
                <td>
                    Mail:<br />
                    <input id='mail' class="form-control"
                            type="email" name="mail" />
                </td>
            </tr>
            <tr>
                <td>
                    Sign Up:<br />
                    <input id='btn_signUp' class="form-control"
                            type="submit" value="signUp" name="action" />
                </td>
            </tr>
        </table>
    </td>
    <td>
        <table class="table">
            <tr>
                <td>
                    Identifiant:<br />
                    <input id='login2' class="form-control"
                        type="text" name="login" maxlength="9"/>
                </td>
            </tr>
            <tr>
                <td>
                    Mot de pass:<br />
                    <input id='password2' class="form-control"
                            type="password" name="password" maxlength="16"/>
                </td>
            </tr>
            <tr>
                <td>
                    Sign In:<br />
                    <input id='btn_signIn' class="form-control"
                            type="submit" value="signIn" name="action" />
                </td>
            </tr>
            <tr>
                <td style="text-align:right">
                    <a id="a_pw">Mot de passe oublier</a>
                </td>
            </tr>
            <tr>
                <table id='tr_pw' style='display:none;' class='table'>
                    <tr>
                        <td>
                            Connection via email:<br />
                            <input id='mail_pw' class='form-control'
                                type='email'>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input id='btn_pw' type='button'
                                class='form-control' value='Envoyer'>
                        </td>
                    </tr>
                </table>
            </tr>
        </table>
    </td>
    </tr>
    <tr>
        <td colspan='2'>
            <textarea id='info_res' class='form-control' disabled style="resize:none; height:100px;"></textarea>
        </td>
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

var a_pw = document.getElementById('a_pw');
var tr_pw = document.getElementById('tr_pw');
var mail_pw = document.getElementById('mail_pw');
var btn_pw = document.getElementById('btn_pw');
a_pw.onclick = function()
{
    if (tr_pw.style.display == 'none')
        tr_pw.style.display = '';
    else
        tr_pw.style.display = 'none';
}



var oLogin = document.getElementById('login');
var oPw1 = document.getElementById('pw1');
var oPw2 = document.getElementById('pw2');
var oMail = document.getElementById('mail');
var oBtn_signUp = document.getElementById('btn_signUp');
var oLogin2 = document.getElementById('login2');
var oPassword2 = document.getElementById('password2');
var oBtn_signIn = document.getElementById('btn_signIn');
var oInfo_res = document.getElementById('info_res');

oLogin.onclick = function()
{
    oInfo_res.innerText = 'Votre login doit être compris entre 3 et 9 caractères.';
}

oPw1.onclick = function()
{
    oInfo_res.innerText =
    "Votre mot de passe doit être compris entre 6 et 16 caractères,\n"
    + "et contenir au moins un chiffre, une majuscule et une minuscule.";
}

oPw2.onclick = function()
{
    oInfo_res.innerText =
        "Votre mot de passe doit être compris entre 6 et 16 caractères,\n"
        + "et contenir au moins un chiffre, une majuscule et une minuscule.";
}

oBtn_signUp.onclick = function()
{
    var url = '../../controllers/input.c.php';
    var data = "action=signUp"
        + "&login=" + oLogin.value
        + "&pw1=" + oPw1.value
        + "&pw2=" + oPw2.value
        + "&mail=" + oMail.value;
    ft_ajax(url, data, function(response)
    {
        oInfo_res.innerText = response;
        oPw1.value = '';
        oPw2.value = '';
    });
}

oBtn_signIn.onclick = function()
{
    var url = '../../controllers/input.c.php';
    var data = "action=signIn"
        + "&login=" + oLogin2.value
        + "&password=" + oPassword2.value;
    ft_ajax(url, data, function(response)
    {
        oInfo_res.innerText = response;
        oPassword2.value = '';
        if (response == 'OK')
        {
            window.location.href = 'galery.php';
        }
    });
}

btn_pw.onclick = function()
{
    var url = '../../controllers/input.c.php';
    var data = "action=forget_pw"
        + "&mail=" + mail_pw.value;
    ft_ajax(url, data, function(response)
    {
        oInfo_res.innerText = response;
    });
}
</script>
</body>
</html>
