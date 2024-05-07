<?php
$data = array(
    "allPartner" => array(),
    "allCollector" => array()
);

array_push($data["allCollector"] , ["null", "لا أحد"]);
try{
    include_once '../connection.php';

    //get name and id of all the partners
    $stm = $db->prepare("SELECT `PARTNER_ID`, `PARTNER_NAME` FROM `partner`");
    $stm->execute();

    foreach($stm->fetchAll() as $row){
        $id = $row["PARTNER_ID"];
        $name = $row["PARTNER_NAME"];
        array_push($data["allPartner"] , [$id, $name]);
    }

    //get name and id of all the collectors
    $stm = $db->prepare("SELECT `COLLECTOR_ID`, `COLLECTOR_NAME` FROM `collector`");
    $stm->execute();

    foreach($stm->fetchAll() as $row){
        $id = $row["COLLECTOR_ID"];
        $name = $row["COLLECTOR_NAME"];
        array_push($data["allCollector"] , [$id, $name]);
    }

    echo json_encode($data);

}catch(PDOEXception $e){
    echo $e->getMessage();
}
?>