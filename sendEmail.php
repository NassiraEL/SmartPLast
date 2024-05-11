<?php
include_once 'phpmailer-master/mail.php';
session_start();

//validation input
function validate($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if(!empty($_POST['email'])){
    try{
        $email = validate($_POST["email"]);
        include_once 'connection.php';

        //verefier si il est partner
        $stm = $db->prepare("SELECT * FROM `partner` WHERE PARTNER_EMAIL=:email");
        $stm->bindParam(":email", $email);
        $stm->execute();

        if($stm->rowCount() <= 0){

            //verefier si il est collector
            $stm = $db->prepare("SELECT * FROM `collector` WHERE COLLECTOR_EMAIL=:email");
            $stm->bindParam(":email", $email);
            $stm->execute();

            if($stm->rowCount() <= 0){

                //verefier si il est admin
                $stm = $db->prepare("SELECT * FROM `admin` WHERE ADMIN_EMAIL=:email");
                $stm->bindParam(":email", $email);
                $stm->execute();

                if($stm->rowCount() <= 0){
                    echo "البريد الإلكتروني غير موجود";
                }else{
                    $_SESSION["who_forgot"] = "admin";
                    $mail->setFrom('coolplast51@gmail.com','SmartPlast');
                    $mail->addAddress($email);
                    $mail->Subject = "رمز التحقق";
                    $code = rand(100000, 999999);
                    $_SESSION["code"] = $code;
                    $_SESSION["email"] = $email;
                    $mail->Body = "رمز التحقق من عنوان البريد الإلكتروني : <h3 style='color:blue;'>$code</h3>";
                    $mail->send();
                    echo "true";
                }
            }else{
                $_SESSION["who_forgot"] = "collector";
                $mail->setFrom('coolplast51@gmail.com','SmartPlast');
                $mail->addAddress($email);
                $mail->Subject = "رمز التحقق";
                $code = rand(100000, 999999);
                $_SESSION["code"] = $code;
                $_SESSION["email"] = $email;
                $mail->Body = "رمز التحقق من عنوان البريد الإلكتروني : <h3 style='color:blue;'>$code</h3>";
                $mail->send();
                echo "true";
            }
        }else{
            $_SESSION["who_forgot"] = "partner";
            $mail->setFrom('coolplast51@gmail.com','SmartPlast');
            $mail->addAddress($email);
            $mail->Subject = "رمز التحقق";
            $code = rand(100000, 999999);
            $_SESSION["code"] = $code;
            $_SESSION["email"] = $email;
            $mail->Body = "رمز التحقق من عنوان البريد الإلكتروني : <h3 style='color:blue;'>$code</h3>";
            $mail->send();
            echo "true";

        }
    }catch(PDOException $e){
        echo $e->getMessage();
    }
}else{
    echo "يرجى ملء هذا الحقل";
}
?>

