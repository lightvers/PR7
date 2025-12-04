<?php
    require_once("../../settings/connect_datebase.php");

    $Sql = "SELECT * FROM `logs` ORDER BY `Date`";
    $Query = $mysqli->query($Sql);

    $Events = array();

    while($Read = $Query->fetch_assoc()) {
        $Status = "";

        $Event = array(
            "Id" => $Read["Id"],
            "Ip" => $Read["Ip"],
            "Date" => $Read["Date"],
            "TimeOnline" => $Read["TimeOnline"],
            "Status" => $Status,
            "Event" => $Read["Event"]
        );
        array_push($Events, $Event);
    }

    echo json_encode($Events, JSON_UNESCAPED_UNICODE);

?>