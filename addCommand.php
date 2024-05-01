<?php
$id = $_COOKIE['idPartner'];

try{
    $db = new PDO("mysql:host=localhost:3307;dbname=coolplast", "root", "");
    $date_cmnd = date('Y-m-d H:i:s');
    $state = "ATTEND";
    $stm = $db->prepare("INSERT INTO `command`(`PARTNER_ID`, `COMMAND_STATE` , `COMMAND_DATE`) VALUES(:id,:stateCmnd, :dateCmnd)");

    $stm->bindParam(":id", $id);
    $stm->bindParam(":stateCmnd", $state);
    $stm->bindParam(":dateCmnd", $date_cmnd);
    $stm->execute();

   echo "true";
}catch(PDOException $e){
    echo $e->getMessage();
}

?>