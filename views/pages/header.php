<?php
if (!isset($_SESSION))
	session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<link rel="stylesheet" href=<?= $path_bootcss ?>>
	<link rel="stylesheet" href=<?= $path_headercss ?>>
	<link rel="shortcut icon" href=<?= $path_icon ?>>
	<style>
	#footer
	{
		position:fixed;
		/* border:1px solid black; */
		background-color:grey;
		width:100%;
		height:35px;
		bottom:0px;
		z-index:10;
	}
	</style>
</head>
<body>
	<nav>
		<ul>
			<li class="menu_b"><a href=<?= $path_galery ?>>Galery</a>
			</li>
	<?php
		if (!isset($_SESSION['login']) || $_SESSION['login'] == '')
		{
			echo
			"
			<li class='sign'><a href= $path_sign > Sign Up / Sign In </a>
			</li>
			";
		}
		else
		{
			echo
			"
			<li class='menu_a'><a href= $path_camera >Camera</a>
			</li>
			<li class='gestion'><a href= $path_gestion >Gestion</a>
			</li>
			<li class='sign'><a href= $path_logout >Log out</a>
			</li>
			";
		}
	?>
		</ul>
	</nav>
<div id="div_inscrire"></div>

<div id='footer' class='form-control'>
	<p style="position:absolute; color:white; right:10px;">&#9400 <b>Zhuda XU</b> | <a style="color:white;" href="contact.php">Contact</a></p>
</div>
</body>
</html>
