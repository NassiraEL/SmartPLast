<?php
$nbrErr =0;
function validate($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if(!empty($_POST['name'])  && !empty($_POST['tel'])  && !empty($_POST['email'])  && !empty($_POST['password'])  && !empty($_POST['passwordConf'])){
        $name = validate($_POST['name']);
        $tel = validate($_POST['tel']);
        $email = validate($_POST['email']);
        $password = validate($_POST['password']);
        $passwordConf = validate($_POST['passwordConf']);
        $latitude = validate($_POST['latitude']);
        $longitude = validate($_POST['longitude']);
    
        // validate password
        if(strlen($password) < 8){
            $nbrErr++;
            echo "يجب أن تكون كلمة المرور أطول من 8 أحرف";
        }else if($password !=  $passwordConf){
            $nbrErr++;
            echo "كلمات المرور غير متطابقة";
        }else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            //validate email
            $nbrErr++;
            echo "صيغة البريد الإلكتروني غير صالحة";
        }else{
            try{
                $db= new PDO("mysql:host=localhost:3307;dbname=coolplast", "root", "");
                $stm = $db->prepare("SELECT * FROM `partner` WHERE PARTNER_EMAIL=:email");
                $stm->bindParam(":email", $email);
                $stm->execute();
    
                if($stm->rowCount() >0){
                    $nbrErr++;
                    echo "البريد الإلكتروني موجود بالفعل";
                }
                
    
                //insert data of user
    
                if($nbrErr<=0){
                    $stm = $db->prepare("INSERT INTO `partner` (PARTNER_NAME,PARTNER_PHONE,PARTNER_EMAIL,PARTNER_PASSWORD,PARTNER_LONGITUDE,PARTNER_LATITUDE,PARTNER_STATE) VALUES(:name, :tele, :email, :password, :longitude, :latitude, 'ACTIVE')");
                    $stm->bindParam(":name", $name);
                    $stm->bindParam(":tele", $tel);
                    $stm->bindParam(":email", $email);
                    $stm->bindParam(":password", $password);
                    $stm->bindParam(":longitude", $longitude);
                    $stm->bindParam(":latitude", $latitude);
                    $stm->execute();
    
                    echo "true";
                }
            }catch(PDOException $e){
                echo $e->getMessage();
            }
        }

}else{
    echo "يرجى ملء جميع الحقول";
}


