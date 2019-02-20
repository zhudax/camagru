<?php
require_once("database.php");
require_once("../models/tools.class.php");

$db_mysql = ft_connection($DB_DSN_MYSQL, $DB_USER, $DB_PASSWORD);
ft_query($db_mysql, 'CREATE DATABASE camagru');

$db = ft_connection($DB_DSN, $DB_USER, $DB_PASSWORD);

try
{
	ft_query($db, 'CREATE TABLE `user`
	(
		`id_user` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
		`login` VARCHAR(16) NOT NULL,
		`password` VARCHAR(128) NOT NULL,
		`mail` VARCHAR(64) NOT NULL,
		`mail_v` TINYINT(1) NOT NULL DEFAULT 0,
		`mail_reception` TINYINT(1) NOT NULL DEFAULT 1,
		`is_login` TINYINT(1) NOT NULL DEFAULT 0
	)');

	ft_query($db, 'CREATE TABLE `cle`
	(
		`id_cle` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
		`id_user` INT NOT NULL,
		`mail_confirme` VARCHAR(128) NOT NULL,
		`pw_forget` VARCHAR(128)
	)');

	ft_query($db, 'CREATE TABLE `img`
	(
		`id_img` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
		`id_user` INT NOT NULL,
		`img_name` VARCHAR(64) NOT NULL,
		`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`partager` TINYINT(1) NOT NULL DEFAULT 0
	)');

	ft_query($db, 'CREATE TABLE `commentaire`
	(
		`id_commentaire` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
		`id_user` INT NOT NULL,
		`login` VARCHAR(16) NOT NULL,
		`id_img` INT NOT NULL,
		`date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		`commentaire` VARCHAR(1024)
	)');

	ft_query($db, 'CREATE TABLE `like`
	(
		`id_like` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
		`id_user` INT NOT NULL,
		`id_img` INT NOT NULL
	)');

	$i = 0;
	while ($i < 10)
	{
		$login = "User0" . $i;
		$pw_hash = hash("whirlpool", "User0" . $i);
		$mail = $login . '@zxu.fr';
		if (ft_inTab($db, 'user', 'login', $login) ||
			ft_inTab($db, 'user', 'mail', $mail))
		{
			echo "sorry login ou email a deja ete utiliser :(<br />";
			break ;
		}
		else
		{
			$sql = "INSERT INTO `user` (`login`, `password`, `mail`, `mail_v`)
			VALUES('$login', '$pw_hash', '$mail', '1')";
		}
		// echo "$sql";
		ft_query($db, $sql);
		$i++;
	}
	echo "Batabase creer avec succes. <br />";
}
catch(PDOException $e)
{
	echo "Batabase fail. " . $e->getMessage() . "<br/>";
}
?>
