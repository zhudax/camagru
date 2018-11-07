<?php
// var_dump($_POST);
$path_camera = '../views/pages/camera.php';
$path_sign = '../views/pages/sign.php';
$path_galery = '../views/pages/galery.php';
$path_logout = '../views/pages/logout.php';
$path_bootcss = '../views/css/bootstrap.min.css';
$path_headercss = '../views/css/header.css';
$path_icon = '../public/transparent/camera.jpg';

// require_once("../views/pages/header.php");
require_once("../config/database.php");
require_once("../models/tools.class.php");

$db_mysql = ft_connection($DB_DSN_MYSQL, $DB_USER, $DB_PASSWORD);
$db = ft_connection($DB_DSN, $DB_USER, $DB_PASSWORD);

if ($_POST['action'] == 'signUp' && ft_verif($_POST))
{
	foreach($_POST as $elem => $value)
	{
		if (($elem == 'login' || $elem == 'mail')
			&& ft_inTab($db, 'user', $elem, $value))
		{
			echo "sorry $elem a déjà été utiliser.\n";
			return (-1);
		}
	}
	if (strlen($_POST['login']) < 3 || strlen($_POST['login']) > 9)
	{
		echo "Erreur: votre login doit être compris entre 3 et 9 caractères.";
		return (-1);
	}
	if (strcmp($_POST['pw1'], $_POST['pw2']) != 0)
	{
		echo "Erreur: les deux mots de passe sont différent.\n";
		return (-1);
	}
	if (strlen($_POST['pw1']) < 6 || strlen($_POST['pw1']) > 16)
	{
		echo "Erreur: votre mot de passe doit être compris entre 6 et 16 caractères,\n"
		. "et contenir au moins un chiffre, une majuscule et une minuscule.";
		return (-1);
	}
	if (preg_match('/[A-Z]+/', $_POST['pw1']) == 0
		|| preg_match('/[0-9]+/', $_POST['pw1']) == 0
		|| preg_match('/[a-z]+/', $_POST['pw1']) == 0)
	{
		echo "Erreur: votre mot de passe doit être compris entre 6 et 16 caractères,\n"
		. "et contenir au moins un chiffre, une majuscule et une minuscule.";
		return (-1);
	}
	if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))
	{
		echo "Erreur: le format d'email n'est pas bon, veillez entrer un nouveau.";
		return (-1);
	}
	ft_create_user($db, $_POST);
	echo "Votre compte a bien ete creer, veuillez confirmer votre mail.\n";
    ft_mailconfirme($db, $_POST);
}

else if ($_POST['action'] == 'signIn' && ft_verif($_POST))
{
	$id_user = ft_get_id($db, 'id_user', 'user', 'login', $_POST['login']);
	$tmp = ft_get_all($db, 'user', 'id_user', $id_user);
	// var_dump($tmp);
	if ($id_user == false || count($tmp) == 0
		|| strcmp(hash('whirlpool', $_POST['password']), $tmp[0]['password']) != 0)
	{
		echo "Erreur, identifiant ou mot de passe incorrect.";
		return (false);
	}
	if ($tmp[0]['mail_v'] == 0)
	{
		echo "Erreur: veuillez confirmer d'aborde votre adresse mail.";
		return (false);
	}
	$_SESSION['login'] = $_POST['login'];
	$_SESSION['id_user'] = $id_user;
	$_SESSION['mail'] = $tmp[0]['mail'];
	$_SESSION['mail_reception'] = $tmp[0]['mail_reception'];
	// echo "Bienvenu " . $_POST['login'];
	echo 'OK';
}

else if ($_POST['action'] == 'forget_pw' && ft_verif($_POST))
{
	if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))
	{
		echo "Erreur: le format d'email n'est pas bon, veillez entrer un nouveau.";
		return (-1);
	}
	ft_mail_pw($db, $_POST['mail']);
}

// camera.php ------------------------------------------------- camera.php //

else if ($_POST['action'] == 'upload' && ft_verif($_POST))
{
	echo ft_create_img($db, $_POST);
}

else if ($_POST['action'] == 'onload_img' && ft_verif($_POST))
{
	echo json_encode(ft_get_img($db, $_POST['id_user']));
}

else if ($_POST['action'] == 'del_img' && ft_verif($_POST))
{
	ft_del_img($db, $_POST['src']);
}

// Chat.php -------------------------------------------------------------

else if ($_POST['action'] == 'get_photo' && ft_verif($_POST))
{
	echo json_encode(ft_get_photo($db, $_POST['page']));
}

else if ($_POST['action'] == 'send' && ft_verif($_POST))
{
	$tmp = ft_get_all($db, 'user', 'login', $_POST['login']);
	if (count($tmp) == 0)
	{
		echo "ERREUR";
		return (-1);
	}
	$_POST['id_user'] = $tmp[0]['id_user'];
	ft_send_msg($db, $_POST);

	$id_dst = ft_get_id($db, 'id_user', 'img', 'id_img', $_POST['id_img']);
	$dst = ft_get_all($db, 'user', 'id_user', $id_dst);
	if (count($dst) == 0)
	{
		echo 'ERREUR';
		return (-1);
	}
	if ($dst[0]['mail_reception'] == 1)
	{
		mail($dst[0]['mail'], 'Camagru photo commentaire',
		"Vous avez recus un commentaire de " . $_POST['login'] . ': ' . $_POST['msg']);
	}
	echo 'ok send';
}

else if ($_POST['action'] == 'get' && ft_verif($_POST))
{
	echo json_encode(ft_get_msg($db, $_POST['id_img']));
}

else if ($_POST['action'] == 'like_onload')
{
	echo ft_nb_like($db, $_POST['id_img']);
}

else if ($_POST['action'] == 'is_like' && ft_verif($_POST))
{
	if (ft_inTab2($db, 'like', 'id_user', $_POST['id_user'], 'id_img', $_POST['id_img']))
		echo '1';
	else
		echo '0';
}

else if ($_POST['action'] == 'like' && ft_verif($_POST))
{
	ft_like($db, $_POST);
}

else if ($_POST['action'] == 'unlike' && ft_verif($_POST))
{
	ft_unlike($db, $_POST);
}

// gestion -------------------------------------------------------------- //

else if ($_POST['action'] == 'info_onload' && ft_verif($_POST))
{
	echo json_encode(ft_get_all($db, 'user', 'id_user', $_POST['id_user']));
}

else if ($_POST['action'] == 'login_modif' && ft_verif($_POST))
{
	if (strlen($_POST['login']) < 3 || strlen($_POST['login']) > 9)
	{
		echo "Erreur: votre login doit être compris entre 3 et 9 caractères.";
		return (-1);
	}
	if (ft_inTab($db, 'user', 'login', $_POST['login']))
	{
		echo "Erreur: login a déjà été utiliser";
		return (-1);
	}
	ft_login_modif($db, $_POST);
	$_SESSION['login'] = $_POST['login'];
	echo "Votre login est modifié avec succes";
}

else if ($_POST['action'] == 'mail_modif' && ft_verif($_POST))
{
	if (ft_inTab($db, 'user', 'mail', $_POST['mail']))
	{
		echo "sorry l'email a déjà été utiliser";
		return (-1);
	}
	if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL))
	{
		echo "Erreur: le format d'email n'est pas bon, veillez entrer un nouveau.";
		return (-1);
	}
	ft_mail_modif($db, $_POST);
	$_SESSION['mail'] = $_POST['mail'];
	echo "votre l'email a bien été modifier, vous devez la reconfirmer";
}

else if ($_POST['action'] == 'pw_modif' && ft_verif($_POST))
{
	if (strcmp($_POST['pw1'], $_POST['pw2']) != 0)
	{
		echo "Erreur: les deux mots de passe sont différent.\n";
		return (-1);
	}
	if (strlen($_POST['pw1']) < 6 || strlen($_POST['pw1']) > 16)
	{
		echo "Erreur: votre mot de passe doit être compris entre 6 et 16 caractères,\n"
		. "et contenir au moins un chiffre, une majuscule et une minuscule.";
		return (-1);
	}
	if (preg_match('/[A-Z]+/', $_POST['pw1']) == 0
		|| preg_match('/[0-9]+/', $_POST['pw1']) == 0
		|| preg_match('/[a-z]+/', $_POST['pw1']) == 0)
	{
		echo "Erreur: votre mot de passe doit être compris entre 6 et 16 caractères,\n"
		. "et contenir au moins un chiffre, une majuscule et une minuscule.";
		return (-1);
	}
	ft_pw_modif($db, $_POST);
	echo "Votre mot de passe a bien été modifier";
}

else if ($_POST['action'] == 'mail_onload' && ft_verif($_POST))
{
	$tmp = ft_get_elem($db, 'user', 'mail_reception', 'id_user', $_POST['id_user']);
	echo ($tmp);
}

else if ($_POST['action'] == 'mail_desactiver' && ft_verif($_POST))
{
	ft_mail_desactiver($db, $_POST);
	echo "La réception des mails est desactivé avec succes.";
}

else if ($_POST['action'] == 'mail_activer' && ft_verif($_POST))
{
	ft_mail_activer($db, $_POST);
	echo "La réception des mails est activé avec succes.";
}

else {
	echo "ERREUR\n";
}
?>
