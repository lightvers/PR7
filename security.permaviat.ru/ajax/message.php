<?
    session_start();
	include("../settings/connect_datebase.php");

    $IdUser = $_SESSION['user'];
    $Message = $_POST["Message"];
    $IdPost = $_POST["IdPost"];
    $Ip = $_SERVER["REMOTE_ADDR"];
    $Date = date("Y-m-d H:i:s");
    
    $mysqli->query("INSERT INTO `comments`(`IdUser`, `IdPost`, `Messages`) VALUES ({$IdUser}, {$IdPost}, '{$Message}');");

    // $Sql = "SELECT `session` . *, `users`.`login`".
	// "FROM `session` `session` ".
	// "JOIN `users` `users` ON `users`.`id` = `session`.`IdUser`".
	// "WHERE `session`.`Id` = {$IdSession}";

	// $Query = $mysqli->query($Sql);
	// $Read = $Query->fetch_array();

	// $TimeStart = strtotime($Read["DateStart"]);
	// $TimeNow = time();
	// $Ip = $Read["Ip"];
	// $TimeDelta = gmdate("H:i:s", ($TimeNow - $TimeStart));
	// $DateStart = date("Y-m-d H:i:s");
	// $Login = $Read["login"];

	$Sql = "INSERT INTO ".
	" `logs`( `Ip`, `IdUser`, `Date`, `TimeOnline`, `Event`) ".
	"VALUES ('{$Ip}','{$IdUser}','{$Date}','{$TimeDelta}','Пользователь {$Login} оставил комментарий к записи [Id: {$IdPost}]: {$Message}')";
	$mysqli->query($Sql);

?>