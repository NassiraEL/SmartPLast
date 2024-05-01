<?php
    $data_json = file_get_contents('php://input');
    $data = json_decode($data_json, true);
    $commandID = $data[0];
    $collectorID = $data[1];
    $type_change = $data[2];
    $adminEmail = $_COOKIE['emailUser'];

    try{
        $db = new PDO("mysql:host=localhost:3307;dbname=coolplast", "root", "");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stm = $db->prepare("SELECT `ADMIN_ID` FROM `admin` WHERE `ADMIN_EMAIL` = :email");
        $stm->bindParam(":email", $adminEmail);
        $stm->execute();
        $res = $stm->fetch();
        $adminID = $res["ADMIN_ID"];

        // change collector of command
        if($type_change == "collector"){
            if($collectorID === "null"){
                $sql = "UPDATE `command` SET `COLLECTOR_ID` = NULL, `COMMAND_STATE` ='ATTEND', `COMMAND_COLLECT_DATE` = NULL,  `ADMIN_ID` = :adminID WHERE `COMMAND_ID` = :commandID";
                $stm = $db->prepare($sql);
                $stm->bindParam(":adminID", $adminID);
                $stm->bindParam(":commandID", $commandID);
                $stm->execute();
                $reponce = 'ATTEND';
            }else{
                $date_cmnd = date('Y-m-d H:i:s');
                $sql = "UPDATE `command` SET `COLLECTOR_ID` = :collectorID, `COMMAND_STATE` ='INPROCESS', `COMMAND_COLLECT_DATE` = :date_cmnd,  `ADMIN_ID` = :adminID WHERE `COMMAND_ID` = :commandID";
                
                $stm = $db->prepare($sql);
                $stm->bindParam(":date_cmnd", $date_cmnd);
                $stm->bindParam(":adminID", $adminID);
                $stm->bindParam(":commandID", $commandID);
                $stm->bindParam(":collectorID", $collectorID);
                $stm->execute();
                $reponce = 'INPROCESS';
            }
            echo json_encode($reponce);
        }


        //change state of command
        if($type_change == "state"){
            $state = $data[3];
            if($state == "ATTEND"){
                $stm = $db->prepare("UPDATE `command` SET `COLLECTOR_ID` = NULL, `COMMAND_STATE` ='ATTEND', `COMMAND_COLLECT_DATE` = NULL,  `ADMIN_ID` = :adminID WHERE `COMMAND_ID` = :commandID");
                $stm->bindParam(":adminID", $adminID);
                $stm->bindParam(":commandID", $commandID);
                $stm->execute();
                $reponce = 'ATTEND';
                echo json_encode($reponce);
            }else if($state == "DONE"){
                $date_cmnd = date('Y-m-d H:i:s');
                $sql = "UPDATE `command` SET `COLLECTOR_ID` = :collectorID, `COMMAND_STATE` ='DONE', `COMMAND_DELIVREY_DATE` = :date_cmnd,  `ADMIN_ID` = :adminID WHERE `COMMAND_ID` = :commandID";
                
                $stm = $db->prepare($sql);
                $stm->bindParam(":date_cmnd", $date_cmnd);
                $stm->bindParam(":adminID", $adminID);
                $stm->bindParam(":commandID", $commandID);
                $stm->bindParam(":collectorID", $collectorID);
                $stm->execute();
                $reponce = 'DONE';
                echo json_encode($reponce);
            }
        }


        
        
    }catch(PDOEXception $e){
        echo $e->getMessage();
    }



    

?>