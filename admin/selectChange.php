<?php
$data_json = file_get_contents('php://input');
$data = json_decode($data_json, true);
$type_change = $data[2];
$adminEmail = $_COOKIE['emailUser'];

try{
    include_once '../connection.php';

    $stm = $db->prepare("SELECT `ADMIN_ID` FROM `admin` WHERE `ADMIN_EMAIL` = :email");
    $stm->bindParam(":email", $adminEmail);
    $stm->execute();
    $res = $stm->fetch();
    $adminID = $res["ADMIN_ID"];

    // change collector the order
    if($type_change == "collector"){
        $commandID = $data[0];
        $collectorID = $data[1];
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


    //change state of the order
    else if($type_change == "state"){
        $commandID = $data[0];
        $collectorID = $data[1];
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

    //change state of the user
    else{
        $idUser = $data[0];
        $newStateUser = $data[1];
        $table = $data[2];

        //update state of user
        $column_idUser = strtoupper($table) . '_ID';
        $userState = strtoupper($table) . '_STATE';
        $stm = $db->prepare("UPDATE $table SET $userState = :newState WHERE $column_idUser = :id");
        $stm->bindParam(":newState", $newStateUser);
        $stm->bindParam(":id", $idUser);
        $stm->execute();

        

        //Insert into the manage_table records for admins who made modifications and for whom the modifications were made
        if($stm->rowCount() >0){

            if ($table == "admin") {
                $column_idUserManage = "OTHER_ADMIN_ID";
            } else {
                $column_idUserManage = $column_idUser;
            }

            $table_manage = "manage_" . $table;
            $actionDate = date('Y-m-d H:i:s');
            
            $stm2 = $db->prepare("INSERT INTO $table_manage (`ADMIN_ID`, $column_idUserManage, `ADMIN_ACTION`, `ADMIN_DATE_ACTION`) VALUES (:adminId, :idUser, 'DESACTIVATION', :actionDate)");
            $stm2->bindParam(":adminId", $adminID);
            $stm2->bindParam(":idUser", $idUser);
            $stm2->bindParam(":actionDate", $actionDate);
            $stm2->execute();
                if($stm2->rowCount() >0){
                    $response = true;
                    echo json_encode($response);
                }
        }

        

    }
    


    
    
}catch(PDOEXception $e){
    echo $e->getMessage();
}



    

?>