<?php
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);
$commandId =  $data["CM_id"];
$collectorId =  $data["CL_id"];



try{
    $db = new PDO("mysql:host=localhost:3307;dbname=coolplast", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $date_cmnd = date('Y-m-d H:i:s');
    $state = "INPROCESS";
    $stm = $db->prepare("UPDATE `command` SET `COMMAND_STATE`=:stateCmnd, `COMMAND_COLLECT_DATE`=:dateCmnd, `COLLECTOR_ID`=:idcollector  WHERE `COMMAND_ID`=:idcmnd");
    

    $stm->bindParam(":idcmnd", $commandId);
    $stm->bindParam(":idcollector", $collectorId);
    $stm->bindParam(":stateCmnd", $state);
    $stm->bindParam(":dateCmnd", $date_cmnd);
    $stm->execute();

   echo "true";
}catch(PDOEXception $e){
    echo $e->getMessage();
}
?>