<?php
if (!isset($_SESSION))
	session_start();

// tools ------------------------------------------------------
function	ft_connection($DB_DSN, $DB_USER, $DB_PASSWORD)
{
	if (!($db = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD)))
	{
		echo "Error connection data base.\n";
		return (-1);
	}
	return ($db);
}

function	ft_query($db, $sql, $array = array())
{
	$sth = $db->prepare($sql);
	$sth->execute($array);
	return ($sth);
}

// fusion img --------------------------------------------- fusion img //

function ft_resize($path_in, $path_out, $alpha)
{
    $image = imagecreatefrompng($path_in);
    list($width, $height) = getimagesize($path_in);
    $new_width = $width * $alpha;
    $new_height = $height * $alpha;
    $image_new = imagecreatetruecolor($new_width, $new_height);
    $int_color = imagecolorallocatealpha($image_new, 0, 0, 0, 127);
    imagefill($image_new, 0, 0, $int_color);
    imagecopyresampled($image_new, $image, 0, 0, 0, 0,
        $new_width, $new_height, $width, $height);
    imagealphablending($image_new, false);
    imagesavealpha($image_new, true);
    imagepng($image_new, $path_out);
}

// verif -----------------------------------------------------

function	ft_verif($_array)
{
	$verif = true;
	foreach($_array as $elem => $value)
	{
		if ($value == null || !isset($value))
		{
			echo "$elem ne peut pas etre vide.\n";
			$verif = false;
		}
	}
	return ($verif);
}

// get elem ------------------------------------------------

function 	ft_get_all($db, $table, $elem, $value)
{
	$sql = "SELECT * FROM `$table` WHERE `$elem` = ?";
	$sth = ft_query($db, $sql, array($value));
	$tmp = $sth->fetchAll();
	$sth->closeCursor();
	if (count($tmp) != 1)
		return (false);
	return ($tmp);
}

function    ft_get_id($db, $id_name, $table, $elem, $value)
{
    $sql = "SELECT $id_name FROM $table WHERE $elem = ?";
    $sth = ft_query($db, $sql, array($value));
    $tmp = $sth->fetchAll();
    $sth->closeCursor();
    if (count($tmp) != 1)
    {
        return (false);
    }
    $id = $tmp[0][$id_name];
    return ($id);
}

function	ft_inTab($db, $table, $elem, $value)
{
	$sql = "SELECT `$elem` FROM `$table` WHERE `$elem` = ?";
	$sth = ft_query($db, $sql, array($value));
	$tmp = $sth->fetchAll();
	// print_r($tmp);
	$sth->closeCursor();
//	echo "<br />";
	if (count($tmp) != 0)
		return (true);
	return (false);
}

function	ft_inTab2($db, $table, $elem, $value, $elem2, $value2)
{
	$sql = "SELECT `$elem` FROM `$table` WHERE `$elem` = ? AND `$elem2` = ?";
	$sth = ft_query($db, $sql, array($value, $value2));
	$tmp = $sth->fetchAll();
	// print_r($tmp);
	$sth->closeCursor();
//	echo "<br />";
	if (count($tmp) != 0)
		return (true);
	return (false);
}

function	ft_create_user($db, $_array)
{
	$pw_hash = hash("whirlpool", $_array['pw1']);
	$sql = "INSERT INTO user (login, password, mail) VALUES (?, ?, ?)";
	ft_query($db, $sql, array($_array['login'], $pw_hash, $_array['mail']));
	return (true);
}

// mail ----------------------------------------------------- mail //

function	ft_mailconfirme($db, $_array)
{
    if (!($id_user = ft_get_id($db, 'id_user', 'user', 'login', $_array['login'])))
		return (0);
	$cle_mail_confirme = hash("whirlpool", time());
	$sql = "INSERT INTO cle (id_user, mail_confirme) VALUES (?, ?)";
	$sth = ft_query($db, $sql, array($id_user, $cle_mail_confirme));
	$url = "http://localhost:8080/camagru/controllers/get.c.php?action=mail_confirme&cle=$cle_mail_confirme&id_user=$id_user";
	// echo "$url\n";
	mail("$_array[mail]", "Camagru mail confirme",
		"
	Bienvenue sur le site Camagru de zxu,
	pour activer votre compte, veuillez cliquer sur le lien ci dessous
	ou copier/coller dans votre navigateur internet.

	$url");
}

function	ft_mail_pw($db, $mail)
{
	if (!($tmp = ft_get_all($db, 'user', 'mail', $mail)))
	{
		echo 'Erreur: email n\'existe pas.';
		return (false);
	}
	if ($tmp[0]['mail_v'] == 0)
	{
		echo "Erreur: veuillez confirmer d'aborde votre adresse mail.";
		return (false);
	}
	$cle_pw_forget = hash("whirlpool", time());
	$sql = "UPDATE `cle` SET `pw_forget` = ? WHERE `id_user` = ?";
	$sth = ft_query($db, $sql, array($cle_pw_forget, $tmp[0]['id_user']));
	$url = "http://localhost:8080/camagru/controllers/get.c.php?action=mail_pw&cle=$cle_pw_forget&id_user=" . $tmp[0]['id_user'];
	// echo "$url\n";
	echo "$mail\n";
	mail("$mail", "Camagru mot de passe oublier",
		"
	Bienvenu sur le site Camagru de zxu,
	pour reinitialiser votre mot de passe veuillez cliquer sur le lien ci dessous
	ou copier/coller dans votre navigateur internet.

	$url");
	echo "Une email de réinitialisation a été envoyer dans votre boite email.\n";
}

function	ft_mail_verif($db, $_array)
{
	$sql = "SELECT mail_confirme FROM cle WHERE id_user = ?";
	$sth = ft_query($db, $sql, array($_array['id_user']));
	$tmp = $sth->fetchAll();
	$sth->closeCursor();
	if (count($tmp == 1) && strcmp($tmp[0]['mail_confirme'], $_array['cle']) == 0)
	{
		$sql = "UPDATE user set mail_v = 1 WHERE id_user = ?";
		$sth = ft_query($db, $sql, array($_array['id_user']));
        // modif cle mail confirme
        $cle_mail_confirme = hash("whirlpool", time());
        $sql = "UPDATE cle set mail_confirme = $cle_mail_confirme)
            WHERE id_user = ?)";
        $sth = ft_query($db, $sql, array($_array['id_user']));
        echo "Votre mail a bien ete activer !";
		return (true);
	}
	else
	{
		echo "cle erroner, veuillez recommencer.\n";
		return (false);
	}
}

function	ft_pw_verif($db, $_array)
{
	$sql = "SELECT id_cle FROM cle WHERE id_user = ? AND pw_forget = ?";
	$sth = ft_query($db, $sql, array($_array['id_user'], $_array['cle']));
	$tmp = $sth->fetchAll();
	$sth->closeCursor();
	if (count($tmp) != 1)
	{
		echo "Erreur: cle erroner, veuillez recommencer.\n";
		return (false);
	}

	$cle_pw_forget = hash("whirlpool", time());
	$sql = "UPDATE `cle` set `pw_forget` = ? WHERE `id_user` = ?";
	$sth = ft_query($db, $sql, array($cle_pw_forget, $_array['id_user']));

	$tmp = ft_get_all($db, 'user', 'id_user', $_array['id_user']);
	$_SESSION['login'] = $tmp[0]['login'];
	$_SESSION['id_user'] = $tmp[0]['id_user'];
	$_SESSION['mail'] = $tmp[0]['mail'];
	$_SESSION['mail_reception'] = $tmp[0]['mail_reception'];
	header("Location: ../views/pages/gestion.php");
}

function	ft_get_elem($db, $table, $elem, $elem2, $value)
{
	$sql = "SELECT `$elem` FROM `$table` WHERE `$elem2` = ?";
	$sth = ft_query($db, $sql, array($value));
	$tmp = $sth->fetchAll();
//	print_r($tmp);
//	echo "<br />";
	if (count($tmp) == 0)
	{
		echo "pas de resultat.\n";
		return (false);
	}
	$resultat = $tmp[0][$elem];
	$sth->closeCursor();
	return ($resultat);
}

function ft_fusion($dst_img, $src_img, $x, $y, $path_out)
{
    $image_dst = imagecreatefrompng($dst_img);
    $image_src = imagecreatefrompng($src_img);
    list($width, $height) = getimagesize($src_img);
    imagecopyresampled($image_dst, $image_src, $x, $y, 0, 0,
        $width, $height, $width, $height);
    imagepng($image_dst, $path_out);
}

function	ft_create_img($db, $_array)
{
//	file_put_contents(print_r($_array), "./img/data");
	$data = $_array['video_data'];
	$data = str_replace('data:image/png;base64,', '', $data);
	$data = str_replace(' ', '+', $data);
	$img = base64_decode($data);
	$sql = "SELECT `id_user` FROM `user` WHERE `login` = ?";
	$sth = ft_query($db, $sql, array($_SESSION['login']));
	$tmp = $sth->fetchAll();
	$sth->closeCursor();
	$id_user = $tmp[0]['id_user'];

	$img_name = $_SESSION['login'] . "_" . uniqid() . ".png";
	$sql = "INSERT INTO `img` (id_user, img_name) VALUES (?, ?)";
	$sth = ft_query($db, $sql, array($id_user, $img_name));

	file_put_contents("../public/img/tmp.png", $img);
	ft_fusion("../public/img/tmp.png", $_array['img_transparent'], $_array['disX'], $_array['disY'], "../public/img/$img_name");
	// echo "$img_name\n";
	// echo "$_array[disX]\n$_array[disY]\n";
}

function	ft_get_img($db, $id_user)
{
	$sql = "SELECT `img_name` FROM `img` WHERE `id_user` = $id_user ORDER BY `date` DESC";
	$sth = ft_query($db, $sql);
	$tmp = $sth->fetchAll();
	$sth->closeCursor();
	return ($tmp);
}

function	ft_del_img($db, $src)
{
	$sql = "DELETE FROM `img` WHERE `img`.`img_name` = '" . $src . "'";
	$sth = ft_query($db, $sql);
	unlink("../public/img/" . $src);
}

// chat.php -----------------------------------------------------

function	ft_get_photo($db, $page)
{
	$sql = "SELECT * FROM `img` ORDER BY `date` DESC LIMIT $page,6";
	$sth = ft_query($db, $sql);
	$tmp = $sth->fetchAll();
	$sth->closeCursor();
	return ($tmp);
}

function 	ft_get_msg($db, $id_img)
{
	$sql = "SELECT `login`, `date`, `commentaire` FROM `commentaire`
		WHERE `id_img` = $id_img ORDER BY `date` DESC";
	$sth = ft_query($db, $sql);
	$tmp = $sth->fetchAll();
	$sth->closeCursor();
	return ($tmp);
}

function 	ft_send_msg($db, $_array)
{
	// var_dump($_array);
	$sql = "INSERT INTO `commentaire` (id_user, login, id_img, commentaire)
		VALUES (?, ?, ?, ?)";
	$sth = ft_query($db, $sql, array($_array['id_user'], $_array['login'], $_array['id_img'], $_array['msg']));
}

function 	ft_nb_like($db, $id_img)
{
	$sql = "SELECT `id_like` FROM `like` WHERE `id_img` = $id_img";
	$sth = ft_query($db, $sql);
	$tmp = $sth->fetchAll();
	$sth->closeCursor();
	$nb = count($tmp);
	return ($nb);
}

function 	ft_like($db, $_array)
{
	// if (ft_inTab2($db, 'like', `id_user`, $_array['id_user'], `id_img`, $_array['id_img']));
	// 	return (false);
	$sql = "INSERT INTO `like` (id_user, id_img) VALUES (?, ?)";
	$sth = ft_query($db, $sql, array($_array['id_user'], $_array['id_img']));
	echo "vous avez liker l'image " . $_array['id_img'];
}

function 	ft_unlike($db, $_array)
{
	$sql = "DELETE FROM `like` WHERE `id_user` = ? AND `id_img` = ?";
	$sth = ft_query($db, $sql, array($_array['id_user'], $_array['id_img']));
	echo "vous avez unliker l'image " . $_array['id_img'];
}

// gestion ------------------------------------------------

function 	ft_mail_desactiver($db, $_array)
{
	$sql = "UPDATE `user` SET `mail_reception` = 0 WHERE `id_user` = ?";
	$sth = ft_query($db, $sql, array($_array['id_user']));
}

function 	ft_mail_activer($db, $_array)
{
	$sql = "UPDATE `user` SET `mail_reception` = 1 WHERE `id_user` = ?";
	$sth = ft_query($db, $sql, array($_array['id_user']));
}

function 	ft_login_modif($db, $_array)
{
	$sql = "UPDATE `user` SET `login` = ? WHERE `id_user` = ?";
	$sth = ft_query($db, $sql, array($_array['login'], $_array['id_user']));
}

function 	ft_mail_modif($db, $_array)
{
	$sql = "UPDATE `user` SET `mail` = ? WHERE `id_user` = ?";
	$sth = ft_query($db, $sql, array($_array['mail'], $_array['id_user']));

	$sql = "UPDATE `user` set `mail_v` = 0 WHERE id_user = ?";
	$sth = ft_query($db, $sql, array($_array['id_user']));

	$cle_mail_confirme = hash("whirlpool", time());
	$sql = "UPDATE `cle` set `mail_confirme` = ? WHERE `id_user` = ?";
	$sth = ft_query($db, $sql, array($cle_mail_confirme, $_array['id_user']));
	$id_user = $_array['id_user'];
	$url = "http://localhost:8080/camagru/controllers/get.c.php?action=mail_confirme&cle=$cle_mail_confirme&id_user=$id_user";
	// echo "$url\n";
	mail("$_array[mail]", "Camagru mail confirme",
		"
	Bienvenue sur le site Camagru de zxu,
	pour activer votre compte, veuillez cliquer sur le lien ci dessous
	ou copier/coller dans votre navigateur internet.

	$url");
}

function 	ft_pw_modif($db, $_array)
{
	$sql = "UPDATE `user` SET `password` = ? WHERE `id_user` = ?";
	$sth = ft_query($db, $sql, array(hash('whirlpool', $_array['pw1']), $_array['id_user']));
}
?>
