<?php
$json_data = file_get_contents('php://input');
$data = json_decode($json_data, true);

if($data[1] == "command"){
    $word = $data[0];
    $all_command = array();

    try{
        $db = new PDO("mysql:host=localhost:3307;dbname=coolplast", "root", "");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //get all command with informations of partner and collector
        $stm = $db->prepare("SELECT
                            p.PARTNER_NAME ,
                            p.PARTNER_EMAIL,
                            p.PARTNER_PHONE,
                            p.PARTNER_LONGITUDE, 
                            p.PARTNER_LATITUDE, 
                            C.COLLECTOR_NAME, 
                            C.COLLECTOR_ID, 
                            cmnd.COMMAND_STATE,
                            cmnd.COMMAND_ID
                            FROM `partner` AS p INNER JOIN `command` AS cmnd ON p.PARTNER_ID = cmnd.PARTNER_ID
                            LEFT JOIN `collector` AS c ON cmnd.COLLECTOR_ID = c.COLLECTOR_ID 
                            WHERE cmnd.COMMAND_STATE LIKE '%$word%' OR c.COLLECTOR_NAME LIKE '%$word%'
                            ORDER BY cmnd.COMMAND_DATE DESC;");

        $stm->execute();

        if($stm->rowCount() > 0){
            foreach ($stm->fetchAll() as $row) {
                $id_collector = $row["COLLECTOR_ID"];
    
                $command = array(
                    "partner" => array(
                        $row["PARTNER_NAME"],
                        $row["PARTNER_EMAIL"],
                        $row["PARTNER_PHONE"],
                        $row["PARTNER_LONGITUDE"],
                        $row["PARTNER_LATITUDE"]
                    ),
                    "state" => $row["COMMAND_STATE"], 
                    "location" => array(
                        $row["PARTNER_LONGITUDE"],
                        $row["PARTNER_LATITUDE"]
                    ),
                    "collector" => array(),
                    "commandID" => $row["COMMAND_ID"]
                );

                //informations of collector
                $info_collector = array( 
                    "id" => $row["COLLECTOR_ID"],
                    "name" => $row["COLLECTOR_NAME"],
                );

                array_push($command["collector"], $info_collector);
    
                //get all collector name exipt this collector
                if($command["collector"][0]["name"] == NULL){
                    $stm2 = $db->prepare("SELECT COLLECTOR_ID, COLLECTOR_NAME FROM `collector`");
                    $stm2->execute();
                }else{
                    $stm2 = $db->prepare("SELECT COLLECTOR_ID, COLLECTOR_NAME FROM `collector` WHERE COLLECTOR_ID <> :idCollector");
                    $stm2->bindParam(":idCollector", $id_collector);
                    $stm2->execute();
                }
                
    
                foreach($stm2->fetchAll() as $row2){
                    $info_collector = array( 
                        "id" => $row2["COLLECTOR_ID"],
                        "name" => $row2["COLLECTOR_NAME"]
                    );
                    array_push($command["collector"], $info_collector);
                };
    
                if($command["collector"][0]["name"] != NULL){
                    $info_collector = array( 
                        "id" => NULL,
                        "name" => "لا أحد "
                    );
                    array_push($command["collector"], $info_collector);
                }
    
    
                // Ajouter la commande au tableau complet de commandes
                array_push($all_command, $command);
            }
    
            echo json_encode($all_command);
        }else{
            $res = NULL;
            echo json_encode($res);
        }

        


    }catch(PDOEXception $e){
        echo $e->getMessage();
    }
}
?>