<?php
session_start();
require_once("../settings/connect_datebase.php");

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user']) || $_SESSION['user'] == -1) {
    // Просто завершаем сессию
    session_destroy();
    echo "success";
    exit();
}

$idUser = $_SESSION['user'];
$idSession = isset($_SESSION['IdSession']) ? $_SESSION['IdSession'] : (isset($_SESSION['idSession']) ? $_SESSION['idSession'] : 0);

// Если есть ID сессии, логируем выход
if ($idSession > 0) {
    // Получаем данные о сессии с защитой от SQL-инъекций
    $sql = "SELECT s.*, u.login 
            FROM `session` s 
            JOIN `users` u ON u.id = s.id_user 
            WHERE s.id = ?";
    
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $idSession);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $row = $result->fetch_assoc()) {
            $time_start = strtotime($row['date_start']);
            $time_now = time();
            $ip = $row['ip'] ?? $_SERVER['REMOTE_ADDR'];
            $time_delta = gmdate("H:i:s", ($time_now - $time_start));
            $date = date("Y-m-d H:i:s");
            $login = $row['login'] ?? 'Неизвестный';
            
            // Логируем выход
            $sql2 = "INSERT INTO `logs`(`ip`, `id_user`, `date`, `time_online`, `event`) 
                     VALUES (?, ?, ?, ?, ?)";
            
            if ($stmt2 = $mysqli->prepare($sql2)) {
                $event = "Пользователь {$login} вышел из системы";
                $stmt2->bind_param("siss", $ip, $idUser, $date, $time_delta, $event);
                $stmt2->execute();
            }
            
            // Удаляем сессию из БД
            $sql3 = "DELETE FROM `session` WHERE id = ?";
            if ($stmt3 = $mysqli->prepare($sql3)) {
                $stmt3->bind_param("i", $idSession);
                $stmt3->execute();
            }
        }
    }
}

// Уничтожаем сессию PHP
session_destroy();

// Удаляем куки сессии
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Возвращаем ответ для AJAX
echo "success";
exit();
?>