<?php
	session_start();
	require_once("../settings/connect_datebase.php");

	$IdUser = $_SESSION["user"];
	$IdSession = $_SESSION["IdSession"];

	$Sql = "SELECT `session` . *, `users`.`login`".
	"FROM `session` `session` ".
	"JOIN `users` `users` ON `users`.`id` = `session`.`IdUser`".
	"WHERE `session`.`Id` = {$IdSession}";

	$Query = $mysqli->query($Sql);
	$Read = $Query->fetch_array();

	$TimeStart = strtotime($Read["DateStart"]);
	$TimeNow = time();
	$Ip = $Read["Ip"];
	$TimeDelta = gmdate("H:i:s", ($TimeDelta - $TimeStart));
	$DateStart = date("Y-m-d H:i:s");
	$login = $Read["login"];

	$Sql = "INSERT INTO ".
	" `logs`( `Ip`, `IdUser`, `Date`, `TimeOnline`, `Event`) ".
	"VALUES ('{$Ip}','{$IdUser}','{$Date}','{$TimeDelta}','Пользователь {$Login} покинул этот мир')";
	$mysqli->query($Sql);

	session_destroy();
?>