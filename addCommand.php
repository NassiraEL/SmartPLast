<?php
$id = $_COOKIE['idPartner'];
$typeCmnd = $_GET["typeCommand"];

try{
    include_once 'connection.php';
    $date_cmnd = date('Y-m-d H:i:s');
    $state = "ATTEND";
    $stm = $db->prepare("INSERT INTO `command`(`PARTNER_ID`, `COMMAND_STATE` , `COMMAND_DATE`, `COMMAND_TYPE`) VALUES(:id,:stateCmnd, :dateCmnd, :typeCmnd)");

    $stm->bindParam(":id", $id);
    $stm->bindParam(":stateCmnd", $state);
    $stm->bindParam(":dateCmnd", $date_cmnd);
    $stm->bindParam(":typeCmnd", $typeCmnd);
    $stm->execute();

   echo "true";
}catch(PDOException $e){
    echo $e->getMessage();
}

?>