<?php
$email = $_COOKIE['emailUser'];

try{
    include_once 'connection.php';

    $stm = $db->prepare("SELECT `COLLECTOR_ID` FROM `collector` WHERE `COLLECTOR_EMAIL` = :email");
    $stm->bindParam(":email", $email);
    $stm->execute();

    foreach($stm->fetchAll() as $row){
        $collectorID = $row["COLLECTOR_ID"] ;

        $stm = $db->prepare("SELECT cmd.COMMAND_ID, cmd.COMMAND_STATE, p.PARTNER_NAME, p.PARTNER_PHONE, p.PARTNER_LATITUDE, p.PARTNER_LONGITUDE
                            FROM  command cmd  INNER JOIN partner p ON cmd.PARTNER_ID = p.PARTNER_ID 
                            WHERE (cmd.COMMAND_STATE = 'ATTEND' OR cmd.COMMAND_STATE = 'INPROCESS')");
        $stm->execute();
        $results = $stm->fetchAll();

        foreach ($results as $row) {
            $commandID= $row["COMMAND_ID"];
            $commandState = $row["COMMAND_STATE"];
            $partnerName = $row["PARTNER_NAME"];
            $partnerPhone = $row["PARTNER_PHONE"];
            $partnerLatitude = $row["PARTNER_LATITUDE"];
            $partnerLongitude = $row["PARTNER_LONGITUDE"];

            echo "<div class='command' id='$commandID'>
                    <div class='allBottons'>
                        <button class='done' onclick='done($commandID, $collectorID)'>تم  التسليم</button>";

            if ($commandState == "INPROCESS") {
                echo "<button class='encour' style='background:#4788f469;'>في الطريق</button>";
            } else {
                echo "<button class='encour' onclick='process($commandID, $collectorID)'>في الطريق</button>";
            }

            echo "</div>
                    <div class='info_cmnd'>
                        <div class='info1'>
                            <i class='fas fa-map-marker-alt' onclick='locationUser($partnerLatitude, $partnerLongitude)'></i>
                            <h4>$partnerName</h4>
                        </div>
                        <div class='info2' dir='rtl'>
                            <p class='tele'>0$partnerPhone</p>";

            if ($commandState == "ATTEND") {
                echo "<p style='color:red;' class='state'>في الإنتظار</p>";
            } else {
                echo "<p style='color:orange;' class='state'> قيد التجميع</p>";
            }

            echo "</div></div></div>";
        }

        }

}catch(PDOException $e){
    echo $e->getMessage();
}

?>