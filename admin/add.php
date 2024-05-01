<?php
    $data_json = file_get_contents("php://input");
    $data = json_decode($data_json, true);

try{
    $db = new PDO("mysql:host=localhost:3307;dbname=coolplast", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if($data["typeTable"] == "command"){
        $date = date('Y-m-d H:i:s');
        if($data["content"][1] == "null"){
            $sql = "INSERT INTO `command` (PARTNER_ID, COLLECTOR_ID, COMMAND_STATE, COMMAND_DATE) VALUES(:idP, NULL, 'ATTEND', :cmndDate)";
            $stm = $db->prepare($sql);
            $stm->bindParam(":idP", $data["content"][0]);
            $stm->bindParam(":cmndDate", $date);
            $stm->execute();
        }else{
            $sql = "INSERT INTO `command` (PARTNER_ID, COLLECTOR_ID, COMMAND_STATE, COMMAND_DATE, COMMAND_COLLECT_DATE) VALUES(:idP, :idC, 'INPROCESS', :cmndDate, :dateCollect)";
            $stm = $db->prepare($sql);
            $stm->bindParam(":idP", $data["content"][0]);
            $stm->bindParam(":idC", $data["content"][1]);
            $stm->bindParam(":dateCollect", $date);
            $stm->bindParam(":cmndDate", $date);
            $stm->execute();
            
        }

        

        if($stm->rowCount() >0){
            $rep = true;
        }else{
            $rep = false;
        }
        
        echo json_encode($rep);
    }
}catch(PDOEXception $e){
    echo $e->getMessage();
}
    
?>