<?php
$data_json = file_get_contents("php://input");
$data = json_decode($data_json, true);
$adminEmail = $_COOKIE['emailUser'];

//validation the data
function validate($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

try{
    include_once '../connection.php';

    $stm = $db->prepare("SELECT `ADMIN_ID` FROM `admin` WHERE `ADMIN_EMAIL` = :email");
    $stm->bindParam(":email", $adminEmail);
    $stm->execute();
    $res = $stm->fetch();
    $adminID = $res["ADMIN_ID"];

    //add an order
    if($data[0] == "command"){
        $date = date('Y-m-d H:i:s');
        if($data[1][1] == "null"){
            $sql = "INSERT INTO `command` (PARTNER_ID, COLLECTOR_ID, ADMIN_ID ,COMMAND_STATE, COMMAND_DATE) VALUES(:idP, NULL, :adminID, 'ATTEND', :cmndDate)";
            $stm = $db->prepare($sql);
            $stm->bindParam(":idP", $data[1][0]);
            $stm->bindParam(":adminID", $adminID);
            $stm->bindParam(":cmndDate", $date);
            $stm->execute();
        }else{
            $sql = "INSERT INTO `command` (PARTNER_ID, COLLECTOR_ID, ADMIN_ID, COMMAND_STATE, COMMAND_DATE, COMMAND_COLLECT_DATE) VALUES(:idP, :idC, :adminID,  'INPROCESS', :cmndDate, :dateCollect)";
            $stm = $db->prepare($sql);
            $stm->bindParam(":idP", $data[1][0]);
            $stm->bindParam(":idC", $data[1][1]);
            $stm->bindParam(":adminID", $adminID);
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
    //add a user
    else{
        $table = $data[0] ;
        $name = validate($data[1][0]);
        $email = validate($data[1][1]);
        $password = validate($data[1][2]);
        $tele = validate($data[1][3]);
        $url = validate($data[1][4]);
        $nbrErr = 0;
        $reponce = "";
        $isEmpty = true;

        //valider si tous les values n'est pas null
        for($i =0; $i<4; $i++){
            if(empty($data[1][$i])){
                $isEmpty = false;
                $reponce = "يرجى ملء جميع الحقول";
                break;
            }
        }

        if($isEmpty){
            // validate password
            if(strlen($password) < 8){
                $reponce = "يجب أن تكون كلمة المرور أطول من 8 أحرف";
                $nbrErr++;
            }

            //validate email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {  
                $reponce = "صيغة البريد الإلكتروني غير صالحة";
                $nbrErr++;
            }else{
                $column_email = strtoupper($table) . "_EMAIL";
                $stm = $db->prepare("SELECT * FROM $table WHERE $column_email=:email");
                $stm->bindParam(":email", $email);
                $stm->execute();

                if($stm->rowCount() >0){
                    $reponce = "البريد الإلكتروني موجود بالفعل";
                    $nbrErr++;
                }
            }

            //validate URL 
            $parsed_url = parse_url($url);
            if (isset($parsed_url['path'])) {
                $coordinates = explode(',', trim($parsed_url['path'], '@')); 

                //longitude
                $longitude = $coordinates[1];

                //latitude
                $getLatitude =explode('@', $coordinates[0]) ;
                $latitude = $getLatitude[1]; 
            }else{
                $latitude = NULL;
                $longitude = NULL;
            }

            //insert the data in the table 
            if($nbrErr <= 0){
                $column_nameUser = strtoupper($table) . "_NAME";
                $column_emailUser = strtoupper($table) . "_EMAIL";
                $column_tele = strtoupper($table) . "_PHONE";
                $column_lat = strtoupper($table) . "_LATITUDE";
                $column_lng = strtoupper($table) . "_LONGITUDE";
                $column_password = strtoupper($table) . "_PASSWORD";

                $stm = $db->prepare("INSERT INTO $table( $column_nameUser, $column_tele, $column_emailUser, $column_password , $column_lng, $column_lat, `PARTNER_STATE`) VALUES (:nameU, :tele, :email,:pass, :lng, :lat,'ACTIVE')");
                $stm->bindParam(":nameU", $name);
                $stm->bindParam(":tele", $tele);
                $stm->bindParam(":email", $email);
                $stm->bindParam(":pass", $password);
                $stm->bindParam(":lng", $longitude);
                $stm->bindParam(":lat", $latitude);
                $stm->execute();

                if($stm->rowCount() >0){
                    $reponce = true;
                }

                
            }

        }

 
        echo json_encode($reponce); 
        
    }
}catch(PDOEXception $e){
    echo $e->getMessage();
}
    
?>
