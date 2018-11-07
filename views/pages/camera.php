<?php
$path_camera = '#';
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
<link rel="stylesheet" type="text/css" href="../css/header.css">
<style>
	#booth
	{
		margin: 10px 0 0 10px;
		border:1px solid grey;
		width:412px;
		position: relative;
	}
	#booth img
	{
		position:absolute;
		/* height:90px; */
	}

	#video
	{
		border:1px solid grey;
		width:400px;
		height:300px;
	}
	#canvas
	{
	    /* border:2px solid blue; */
		display:none;
		width:400px;
		height:300px;
	}
	#side
	{
	    border:1px solid grey;
		position:absolute;
		overflow:auto;
		height:800px;
		width:300px;
		right:5px;
		top:190px;
	}
	#side img
	{
		margin:5px 0 0 5px;
		width:200px;
	}
	#transparent
	{
		margin:5px 0 5px 0;
		position:relative;
	    border:1px solid grey;
	    height:115px;
		overflow:auto;
	}
	#transparent img
	{
	    height:100px;
	}
	#img_transparent
	{
		/* border:1px solid red; */
		position:absolute;
		z-index: 5;
	}
	#test
	{
		position:relative;
		/* border:2px solid red; */
		width:400px;
		height:300px;
		margin:5px;
	}
</style>
</head>

<body>

<div id='transparent' class='form-control'>
	<img src="../../public/transparent/halloween.png" />
	<img src="../../public/transparent/halloween2.png" />
	<img src="../../public/transparent/halloween3.png" />
	<img src="../../public/transparent/zombie_footer.png" /><br>
	<img src="../../public/transparent/choco.png" />
	<img src="../../public/transparent/choco1.png" />
	<img src="../../public/transparent/choco2.png" />
	<img src="../../public/transparent/choco5.png" />
	<img src="../../public/transparent/choco6.png" /><br />
	<img src="../../public/transparent/lunette.png" />
	<img src="../../public/transparent/joint.png" />
	<img src="../../public/transparent/chain.png" /><br/>
	<img src="../../public/transparent/dawn.png" />
	<img src="../../public/transparent/ball.png" />
	<img src="../../public/transparent/meowth.png" />
	<img src="../../public/transparent/minun.png" />
	<img src="../../public/transparent/pikachu.png" />
	<img src="../../public/transparent/charizard.png" /><br/>
</div>

<div id='booth' class="booth">
	<div id="test">
		<img id="img_upload" />
		<img id='img_transparent' />
		<video id="video"></video><br />
	</div>
	<canvas id="canvas" width="400" height="300"></canvas>
	<input id='btn_upload' type='file' name='upload photo' accept="image/*">
	<button id='snap' class='form-control' style='width:400px; margin:5px;' disabled>snap shot</button>
</div>

<div id="side">
</div>

<script type="text/javascript" src="../js/camera.js"></script>
<script>
var	oTransparent = document.getElementById('transparent');
var	oBooth = document.getElementById('booth');
var oImg_upload = document.getElementById('img_upload');
var oImg_transparent = document.getElementById('img_transparent');
var video = document.getElementById('video');
var	canvas = document.getElementById('canvas');
var	oSnap = document.getElementById('snap');
var oBtn_upload = document.getElementById('btn_upload');
var oSide = document.getElementById('side');
var oTest = document.getElementById('test');

var	vendorUrl = window.URL || window.webkitURL;

//media
navigator.getMedia = navigator.getUserMedia ||
					 navagator.webkitGetUserMedia ||
					 navigator.mozGetUserMedia ||
					 navigator.msGetUserMedia;

navigator.getMedia(
	{video:true, audio:false},
	function(strem)
	{
		// console.log(strem);
		vendorUrl = window.URL || window.webkitURL;
		video.srcObject = strem;
		//video.src = vendorUrl.createObjectURL(strem);
		video.play();
	},
	function(error)
	{
		// console.log(error);
	});

oSnap.onclick = function()
{
	var disX = oImg_transparent.offsetLeft;
	var disY = oImg_transparent.offsetTop;
	var aImg = oSide.getElementsByTagName('img');
	var oImg = document.createElement('img');
	// dessiner dans canvas
	canvas.getContext('2d').drawImage(video, 0, 0, 400, 300);
    canvas.getContext('2d').drawImage(oImg_upload, 0, 0, 400, 300);
	var video_data = canvas.toDataURL("image/png");
	canvas.getContext('2d').drawImage(oImg_transparent, disX, disY, oImg_transparent.width, oImg_transparent.height);
	//convertir en img
	// alert(oImg_transparent.src);
	oImg.src = canvas.toDataURL("image/png");
	// oImg.src = video_data;
	oImg.onclick = function()
	{
		if (confirm('Enregistrer la photo ?'))
		{
	        var data = "action=upload"
				+ "&video_data=" + video_data
				+ "&img_transparent=" + oImg_transparent.src
				+ "&disX=" + disX
				+ "&disY=" + disY;
	        var url = "../../controllers/input.c.php";
	        ft_ajax(url, data, function(response)
	        {
	            // alert(response);
				// console.log(response);
				oImg.alt = response;
				oImg.onclick = function()
				{
					var del = confirm('Être vous vraiment sûr de la supprimer ?');
					if (del == 1)
					{
						var url1 = '../../controllers/input.c.php';
						var data1 = "action=del_img"
							+ '&src=' + this.alt;
						// alert(data1);
						ft_ajax(url1, data1, function(response)
						{
							// console.log(response);
						});
						oSide.removeChild(this);
					}
				}
				oImg.style.border = "3px solid green";
	        });
		}
	}
	if (aImg.length > 0)
		oSide.insertBefore(oImg, aImg[0]);
	else
		oSide.appendChild(oImg);
}

var aTransparentImg = oTransparent.getElementsByTagName('img');
var i = 0;
while (i < aTransparentImg.length)
{
	aTransparentImg[i].onclick = function()
	{
		document.getElementById('img_transparent').src = this.src;
		oSnap.removeAttribute('disabled');
	}
	i++;
}

oBtn_upload.onchange = function()
{
	var target = this.files[0];
	// alert(target.type);
	var reader = new FileReader();
	reader.readAsDataURL(target);
	reader.onload = function(e)
	{
		oImg_upload.style.height = 300 + 'px';
		oImg_upload.style.width = 400 + 'px';
		oImg_upload.src = e.target.result;
	}
}

function ft_onload_img()
{
	var url = '../../controllers/input.c.php';
	var data = "action=onload_img"
		+ "&id_user=" + <?= $_SESSION['id_user'] ?>;
	ft_ajax(url, data, function(response)
	{
		// console.log(response);
		var res = JSON.parse(response);
		var i = 0;
		while (i < res.length)
		{
			var oImg = document.createElement('img');
			oImg.src = "../../public/img/" + res[i]['img_name'];
			oImg.alt = res[i]['img_name'];
			oImg.style.border = '3px solid green';
			oImg.onclick = function()
			{
				var del = confirm('Être vous vraiment sûr de la supprimer ?');
				if (del == 1)
				{
					var url1 = '../../controllers/input.c.php';
					var data1 = "action=del_img"
						+ '&src=' + this.alt;
					// alert(data1);
					ft_ajax(url1, data1, function(response)
					{
						// console.log(response);
					});
					oSide.removeChild(this);
				}
			}
			oSide.appendChild(oImg);
			i++;
		}
	});
}

window.onload = function()
{
	ft_onload_img();

	var oDiv = document.getElementById('img_transparent');

	var disX = 0;
	var disY = 0;

	oDiv.onmousedown = function(ev)
	{
		var oEvent = ev || event;
		// alert(oDiv.clientWidth);
		disX = oEvent.clientX - oDiv.offsetLeft;
		disY = oEvent.clientY - oDiv.offsetTop;

		document.onmousemove = function(ev)
		{
			var oEvent = ev || event;
			var l = oEvent.clientX - disX;
			var t = oEvent.clientY - disY;
			if (l < 0)
				l = 0;
			else if (l > oTest.clientWidth - oDiv.offsetWidth)
				l = oTest.clientWidth - oDiv.offsetWidth;
			if (t < 0)
				t = 0;
			else if (t > oTest.clientHeight - oDiv.offsetHeight)
				t = oTest.clientHeight - oDiv.offsetHeight;
			oDiv.style.left = l + 'px';
			oDiv.style.top = t + 'px';
		}

		document.onmouseup = function()
		{
			document.onmousemove = null;
			document.onmouseup = null;
		}
		return false;
	}
}
</script>

</body>
</html>
