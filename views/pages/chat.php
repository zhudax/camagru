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

<!doctype html>
<html>

<head>
<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href= <?= $path_headercss ?>>
<style>
    #img
    {
        /* float:left; */
        border:1px solid grey;
        position:absolute;
        width:600px;
        height:315px;
        left:50%;
        transform:translate(-50%, 0);
    }
    #img img
    {
        /* float:left; */
        max-height:300px;
        max-width:400px;
    }
    #info
    {
        /* float:left; */
        position:absolute;
        right:0px;
        top:0px;
        height:300px;
        width:180px;
        /* left:80%; */
    }
    #info img
    {
        height:20px;
        position:absolute;
        /* bottom:200px; */
        transform:translate(-50%, 0);
        left:50%;
    }
    #chat
    {
        position:absolute;
        top:380px;
        height:300px;
        width:600px;
        left:50%;
        transform:translate(-50%, 0);
        overflow:auto;
    }
    #send
    {
        position:absolute;
        top:700px;
        left:50%;
        transform:translate(-50%, 0);
    }
</style>
</head>
<body>
    <div id='img' class='form-control'>
        <img src=<?= '../../public/img/' . $_GET['img_name'] ?> >
        <div id='info'>
            <div>Nombre de like: <span id='nb_like'></span></div>
            <img id='img_like'><br/><br/>
            <a id='partage' target='_blank'>
                <input class='btn btn-primary' type='button' value='fb partager'>
            </a>
        </div>
    </div>
    <div id='chat' class='form-control'>
    </div>
    <?php
    if ($_SESSION)
    {
        echo "
        <div id='send'>
            <table>
                <tr>
                    <td><input id='msg_send' class='form-control' type='text' maxlength='1020' placeholder='seul les commentaires moins de 1Ko peuvent etre enregistrer' style='width:520px;'></td>
                    <td><input id='btn_send' class='form-control' type='button' value='envoyer'></td>
                </tr>
            </table>
        </div>
        ";
    }

    ?>

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

var oBtn_send = document.getElementById('btn_send');
var oMsg_send = document.getElementById('msg_send');
var oChat = document.getElementById('chat');
var oImg_like = document.getElementById('img_like');
var oNb_like = document.getElementById('nb_like');

<?php
if ($_SESSION)
{
    echo "
        oImg_like.onclick = function()
        {
            var url = '../../controllers/input.c.php';
            if (this.src == 'http://localhost:8080/camagru/public/transparent/coeurRouge.png')
            {
                var data = 'action=unlike'
                    + '&id_user=' + '$_SESSION[id_user]'
                    + '&id_img=' + '$_GET[id_img]';
                ft_ajax(url, data, function(response)
                {
                    // alert(response);
                });
                this.src = 'http://localhost:8080/camagru/public/transparent/coeur.jpeg';
            }
            else
            {
                var data = 'action=like'
                    + '&id_user=' + '$_SESSION[id_user]'
                    + '&id_img=' + '$_GET[id_img]';
                ft_ajax(url, data, function(response)
                {
                    // alert(response);
                });
                this.src = 'http://localhost:8080/camagru/public/transparent/coeurRouge.png';
            }
            ft_like_onload();
        }

        oBtn_send.onclick = function()
        {
            var url = '../../controllers/input.c.php';
            var data = 'action=send'
                + '&id_img=' + '$_GET[id_img]'
                + '&login=' + '$_SESSION[login]'
                + '&msg=' + oMsg_send.value;
            // alert(data);
            ft_ajax(url, data, function(response)
            {
                if (response == 'ok send')
                {
                    oMsg_send.value = '';
                    ft_chat_onload();
                }
            });
        }
    ";
}
?>

function    ft_chat_onload()
{
    var url = '../../controllers/input.c.php';
    var data = 'action=get&id_img=' + <?= $_GET['id_img'] ?>;

    ft_ajax(url, data, function(response)
    {
        // console.log(response);
        oChat.innerHTML = '';
        var res = JSON.parse(response);
        var i = 0;
        while (i < res.length)
        {
            var nDiv = document.createElement('div');
            nDiv.innerText = res[i]['login'] + ': ' + res[i]['commentaire'];
            oChat.appendChild(nDiv);
            i++;
        }
    });
}

function    ft_like_onload()
{
    var url = '../../controllers/input.c.php';
    var data = 'action=like_onload'
        + '&id_img=' + <?= $_GET['id_img']?>;
    ft_ajax(url, data, function(response)
    {
        // alert(response);
        oNb_like.innerText = response;
    });
}

setInterval(function()
{
    ft_chat_onload();
}, 2000);

setInterval(function()
{
    ft_like_onload();
}, 5000);

window.onload = function()
{
    var href = window.location.href;
    var url = 'https://www.facebook.com/sharer/sharer.php?u=' + window.location.href;
    // alert(url);
    document.getElementById('partage').href = url;
    ft_chat_onload();
    ft_like_onload();
    <?php
    if ($_SESSION)
    {
        echo "
            var url = '../../controllers/input.c.php';
            var data = 'action=is_like'
                + '&id_user=' +  '$_SESSION[id_user]'
                + '&id_img=' +  '$_GET[id_img]';
            ft_ajax(url, data, function(response)
            {
                if (response == '0')
                    oImg_like.src = 'http://localhost:8080/camagru/public/transparent/coeur.jpeg';
                else if (response == '1')
                    oImg_like.src = 'http://localhost:8080/camagru/public/transparent/coeurRouge.png';
            });
        ";
    }
    ?>
}
</script>
</body>

</html>
