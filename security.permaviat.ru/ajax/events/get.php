<?php

    require_once('../../settings/connect_datebase.php');    

    $sql = "SELECT * FROM `logs` ORDER BY `date`";
    $query = $mysqli->query($sql);

    $events = array();

    while($read = $query->fetch_assoc()){
        $status = "";

        $sqlSession = "SELECT * FROM `session` WHERE `id_user` = {$read['id_user']} ORDER BY `date_start` DESC";
        $querySession = $mysqli->query($sqlSession);
        if($querySession->num_rows > 0){
            $readSession = $querySession->fetch_assoc();


            $time_end = strtotime($readSession['date_now']) + 5 * 60;
            $time_now = time();

            if($time_end > $time_now){
                $status = "онлайн";
            } else {
                $time_end = strtotime($readSession['date_now']);
                if(round(($time_now - $time_end) / 60) > 59){
                    $time_delta = round(($time_now - $time_end) / 60 / 60);
                    $status = "Был в сети: {$time_delta} часов назад";
                    if(round(($time_now - $time_end) / 60 / 60) > 24){
                        $time_delta = round(($time_now - $time_end) / 60 / 60 / 24);
                        $status = "Был в сети: {$time_delta} дней назад";
                    }

                } else {
                    $time_delta = round(($time_now - $time_end) / 60);
                    $status = "Был в сети: {$time_delta} минут назад";
                }
            }
        } 
        
        
        $event = array(
            "id" => $read['id'],
            "ip" => $read['ip'],
            "date" => $read['date'],
            "time_online" => $read['time_online'],
            "status" => $status,
            "event" => $read['event'],
        );

        array_push($events, $event);
    }

    echo json_encode($events, JSON_UNESCAPED_UNICODE);

?>