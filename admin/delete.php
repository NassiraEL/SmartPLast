<?php
$data_json = file_get_contents('php://input');
$data = json_decode($data_json, true);
$adminEmail = $_COOKIE['emailUser'];
$idUser = $data[0];
$table = $data[1];

try{
    include_once '../connection.php';

    // $stm = $db->prepare("SELECT `ADMIN_ID` FROM `admin` WHERE `ADMIN_EMAIL` = :email");
    // $stm->bindParam(":email", $adminEmail);
    // $stm->execute();
    // $res = $stm->fetch();
    // $adminID = $res["ADMIN_ID"];

    // $column_user_id = strtoupper($table) . '_ID'; 
    // $stm2 = $db->prepare("DELETE FROM $table WHERE  $column_user_id = :iduser");
    // $stm2->bindParam(":iduser", $idUser);
    // $stm2->execute();
    $r = true;
    echo json_encode($r);

    //Insert into the manage_table records for admins who made modifications and for whom the modifications were made
    // if($stm2->rowCount() >0){

    //     if ($table == "admin") {
    //         $column_idUserManage = "OTHER_ADMIN_ID";
    //     } else {
    //         $column_idUserManage = $column_user_id;
    //     }

    //     $table_manage = "manage_" . $table;
    //     $actionDate = date('Y-m-d H:i:s');
        
    //     $stm3 = $db->prepare("INSERT INTO $table_manage (`ADMIN_ID`, $column_idUserManage, `ADMIN_ACTION`, `ADMIN_DATE_ACTION`) VALUES (:adminId, :idUser, 'DELETE', :actionDate)");
    //     $stm3->bindParam(":adminId", $adminID);
    //     $stm3->bindParam(":idUser", $idUser);
    //     $stm3->bindParam(":actionDate", $actionDate);
    //     $stm3->execute();
    //         if($stm3->rowCount() >0){
    //             $response = true;
    //             echo json_encode($response);
    //         }
    // }
        

    
}catch(PDOEXception $e){
    echo $e->getMessage();
}



    

?>