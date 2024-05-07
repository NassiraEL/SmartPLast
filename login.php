<?php

function validate($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if(!empty($_POST['email'])  && !empty($_POST['password'])){

        $email = validate($_POST['email']);
        $password = validate($_POST['password']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "صيغة البريد الإلكتروني غير صالحة";
        }else {
            try{
                include_once 'connection.php';
                $stm = $db->prepare("SELECT * FROM `partner` WHERE `PARTNER_EMAIL`=:email AND `PARTNER_PASSWORD`=:password AND `PARTNER_STATE` ='ACTIVE'");
                $stm->bindParam(":email", $email);
                $stm->bindParam(":password", $password);
                $stm->execute();
    
                if($stm->rowCount() > 0){
                    $expiration = time() + (10 * 365 * 24 * 60 * 60); 
                    setcookie("emailUser", $email, $expiration);
                    setcookie("connected", "no", $expiration);
                    echo "partner";
                }else{
                    $stm = $db->prepare("SELECT * FROM `collector` WHERE `COLLECTOR_EMAIL`=:email AND `COLLECTOR_PASSWORD`=:password AND `COLLECTOR_STATE` ='ACTIVE'");
                    $stm->bindParam(":email", $email);
                    $stm->bindParam(":password", $password);
                    $stm->execute();
                    if($stm->rowCount() > 0){
                        $expiration = time() + (10 * 365 * 24 * 60 * 60); 
                        setcookie("emailUser", $email, $expiration);
                        setcookie("connected", "no", $expiration);
                        echo "collector";
                    }else{
                        $stm = $db->prepare("SELECT * FROM `admin` WHERE `ADMIN_EMAIL`=:email AND `ADMIN_PASSWORD`=:password AND `ADMIN_STATE` ='ACTIVE'");
                        $stm->bindParam(":email", $email);
                        $stm->bindParam(":password", $password);
                        $stm->execute();
                        if($stm->rowCount() > 0){
                            $expiration = time() + (10 * 365 * 24 * 60 * 60); 
                            setcookie("emailUser", $email, $expiration);
                            setcookie("connected", "no", $expiration);
                            echo "admin";
                        }else{
                            echo "كلمة المرور أو البريد الإلكتروني غير صحيح";
                        }
                    }
                }
                
            }catch(PDOException $e){
                echo $e->getMessage();
            }
        }
  
}else{
    echo "يرجى ملء جميع الحقول";
}




