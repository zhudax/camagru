<?php
    $path_camera = 'camera.php';
    $path_sign = 'sign.php';
    $path_galery = '#';
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
<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href= <?= $path_headercss ?>>
<style>
#profil_match td
{
    width:30%;
    padding:0px 20px 0px 20px;
}

#profil_match td > div > div > img
{
    height:20vmin;
}
#div_btn
{
    position:fixed;
    bottom:50px;
    left:50%;
    transform:translate(-50%, 0);
}
</style>
</head>
<body>
<table class="table" id="profil_match">
    <tr>
        <td id="td0" style="display:none">
            <div class="row">
                <div class="thumbnail">
                    <img id="img0">
                    <div class="caption">
                        <h5 id="name0"></h5>
                        <p>
                            <a href="#" id="btn0" class="btn btn-primary"
                                role="button">voir son profil</a>
                        </p>
                    </div>
                </div>
            </div>
        </td>
        <td id="td1" style="display:none">
            <div class="row">
                <div class="thumbnail">
                    <img id="img1">
                    <div class="caption">
                        <h5 id="name1"></h5>
                        <p>
                            <a href="#" id="btn1" class="btn btn-primary"
                                role="button">voir son profil</a>
                        </p>
                    </div>
                </div>
            </div>
        </td>
        <td id="td2" style="display:none">
            <div class="row">
                <div class="thumbnail">
                    <img id="img2">
                    <div class="caption">
                        <h5 id="name2"></h5>
                        <p>
                            <a href="#" id="btn2" class="btn btn-primary"
                                role="button">voir son profil</a>
                        </p>
                    </div>
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td id="td3" style="display:none">
            <div class="row">
                <div class="thumbnail">
                    <img id="img3">
                    <div class="caption">
                        <h5 id="name3"></h5>
                        <p>
                            <a href="#" id="btn3" class="btn btn-primary"
                                role="button">voir son profil</a>
                        </p>
                    </div>
                </div>
            </div>
        </td>
        <td id="td4" style="display:none">
            <div class="row">
                <div class="thumbnail">
                    <img id="img4">
                    <div class="caption">
                        <h5 id="name4"></h5>
                        <p>
                            <a href="#" id="btn4" class="btn btn-primary"
                                role="button">voir son profil</a>
                        </p>
                    </div>
                </div>
            </div>
        </td>
        <td id="td5" style="display:none">
            <div class="row">
                <div class="thumbnail">
                    <img id="img5">
                    <div class="caption">
                        <h5 id="name5"></h5>
                        <p>
                            <a href="#" id="btn5" class="btn btn-primary"
                                role="button">voir son profil</a>
                        </p>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>

<div id='div_btn'>
    <input id='btn_debut' type="button" value='revenir au debut' class='btn btn-primary'>
    <input id='newer' style="display:none" type="button" value='Newer' disabled>
    <input style="display:none" id='page' type='button' value='0' disabled>
    <input id='next' type="button" value='next' class='btn btn-primary'>
</div>

<script>

var oNewer = document.getElementById('newer');
var oNext = document.getElementById('next');
var oPage = document.getElementById('page');
var oBtn_debut = document.getElementById('btn_debut');
// alert(parseInt(oPage.value) + 1);

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

function ft_onload(page)
{
    var url = "../../controllers/input.c.php";
    var data = "action=get_photo&page=" + page;
    ft_ajax(url, data, function(response)
    {
        // console.log(response);
        var res = JSON.parse(response);
        // var i = 0;
        // while (i < 6)
        // {
        //     document.getElementById('td' + i).style = "display:none";
        //     i++;
        // }
        if (res.length < 6)
        {
            oPage.value = -1;
            // oNext.disabled = 'true';
        }
        i = 0;
        while (i < res.length)
        {
            // alert(res[i]['img_name']);
            document.getElementById('img' + i).src = '../../public/img/' + res[i]['img_name'];
            document.getElementById('btn' + i).href =
                'chat.php?id_img=' + res[i]['id_img']
                + '&img_name=' + res[i]['img_name'];
            document.getElementById('td' + i).style = "";
            i++;
        }
        // alert(res.length);

    });
}

oBtn_debut.onclick = function()
{
    ft_onload(0);
}

oNext.onclick = function()
{
    oPage.value = parseInt(oPage.value) + 1;
    var page = parseInt(oPage.value) * 6;
    oNewer.removeAttribute('disabled');
    ft_onload(page);
}

oNewer.onclick = function()
{
    oPage.value = parseInt(oPage.value) - 1;
    if (parseInt(oPage.value) <= 0)
        this.disabled = 'true';
    if (oNext.disabled)
        oNext.removeAttribute('disabled');
    var page = parseInt(oPage.value) * 6;
    ft_onload(page);
}

window.onload = ft_onload(0);
</script>
</body>
</html>
