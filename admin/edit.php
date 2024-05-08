<?php
$data_json = file_get_contents('php://input');
$data = json_decode($data_json, true);
$adminEmail = $_COOKIE['emailUser'];
$newData = $data[0];
$table = $data[1];
$response = '';
$latitude = NULL;
$longitude = NULL;

try {
    include_once '../connection.php';

    
    for ($i = 0; $i < 4; $i++) {
        if (empty($newData[$i])) {
            $response = false;
            break;
        }
    }

    //validate URL 
    $url = $newData[4];
    $latitude = NULL;
    $longitude = NULL;

    if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$url)) {
        $parsed_url = parse_url($url);
        if (isset($parsed_url['path'])) {
            $coordinates = explode(',', trim($parsed_url['path'], '@')); 

            //longitude
            $longitude = $coordinates[1];

            //latitude
            $getLatitude =explode('@', $coordinates[0]) ;
            $latitude = $getLatitude[1]; 
        }
    }

    //update the data
    if ($response !== false) {
        $idUser = strtoupper($table) . "_ID";
        $nameUser = strtoupper($table) . "_NAME";
        $emailUser = strtoupper($table) . "_EMAIL";
        $tele = strtoupper($table) . "_PHONE";
        $lat = strtoupper($table) . "_LATITUDE";
        $lng = strtoupper($table) . "_LONGITUDE";

        $stm = $db->prepare("UPDATE $table SET $nameUser = :nameU, $emailUser = :email, $tele = :tele, $lat = :lat, $lng = :lng WHERE $idUser = :iduser");
        $stm->bindParam(":iduser", $newData[0]);
        $stm->bindParam(":nameU", $newData[1]);
        $stm->bindParam(":email", $newData[2]);
        $stm->bindParam(":tele", $newData[3]);
        $stm->bindParam(":lat", $latitude);
        $stm->bindParam(":lng", $longitude);
        $stm->execute();

        //Insert into the manage_table records for admins who made modifications and for whom the modifications were made
        if($stm->rowCount() >0){

            //get id of admin
            $stm2 = $db->prepare("SELECT `ADMIN_ID` FROM `admin` WHERE `ADMIN_EMAIL` = :email");
            $stm2->bindParam(":email", $adminEmail);
            $stm2->execute();
            $res = $stm2->fetch();
            $adminID = $res["ADMIN_ID"];
 
            if ($table == "admin") {
                $column_idUserManage = "OTHER_ADMIN_ID";
            } else {
                $column_idUserManage = $idUser;
            }

            $table_manage = "manage_" . $table;
            $actionDate = date('Y-m-d H:i:s');
            
            $stm3 = $db->prepare("INSERT INTO $table_manage (`ADMIN_ID`, $column_idUserManage, `ADMIN_ACTION`, `ADMIN_DATE_ACTION`) VALUES (:adminId, :idUser, 'UPDATE', :actionDate)");
            $stm3->bindParam(":adminId", $adminID);
            $stm3->bindParam(":idUser", $newData[0]);
            $stm3->bindParam(":actionDate", $actionDate);
            $stm3->execute();
                if($stm3->rowCount() >0){
                    $response = "succes";
                }
        }
        
    } else {
        $response = "يرجى ملء جميع الحقول";
    }

    echo json_encode([$response, $latitude, $longitude]);
} catch (PDOException $e) {
    echo $e->getMessage();
}


    
    

  

?>