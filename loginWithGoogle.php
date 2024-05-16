<?php 
// Load the database configuration file 
require_once 'connection.php'; 
 
// Retrieve JSON from POST body 
$jsonStr = file_get_contents('php://input'); 
$jsonObj = json_decode($jsonStr); 
 
if(!empty($jsonObj->request_type) && $jsonObj->request_type == 'user_auth'){ 
    $credential = !empty($jsonObj->credential)?$jsonObj->credential:''; 
 
    // Decode response payload from JWT token 
    list($header, $payload, $signature) = explode (".", $credential); 
    $responsePayload = json_decode(base64_decode($payload)); 
 
    if(!empty($responsePayload)){ 
        // The user's profile info 
        $first_name = !empty($responsePayload->given_name)?$responsePayload->given_name:''; 
        $last_name  = !empty($responsePayload->family_name)?$responsePayload->family_name:''; 
        $email      = !empty($responsePayload->email)?$responsePayload->email:''; 
        $name = "$first_name $last_name" ;
 
        // Check whether the user data already exist in the database 
        $sql = "SELECT * FROM `partner` WHERE `PARTNER_EMAIL` = :email"; 
        $stm = $db->prepare($sql);
        $stm->bindParam(":email", $email); 
        $stm->execute();
         
        if($stm->rowCount() > 0){  
            // Update user data if already exists 
            $sql = "UPDATE `partner` SET `PARTNER_NAME` = :namep, `PARTNER_STATE` = 'ACTIVE' WHERE `PARTNER_EMAIL` = :email"; 
            $stm = $db->prepare($sql);
            $stm->bindParam(":namep", $name);
            $stm->bindParam(":email", $email); 
             
        }else{ 
            // Insert user data 
            $sql = "INSERT INTO `partner`(PARTNER_NAME, PARTNER_EMAIL, PARTNER_STATE) VALUES ( :namep, :email, 'ACTIVE')"; 
            $stm = $db->prepare($sql);
            $stm->bindParam(":namep", $name);
            $stm->bindParam(":email", $email); 
            
        } 
         
        $stm->execute();

        $expiration = time() + (10 * 365 * 24 * 60 * 60); 
        setcookie("emailUser", $email, $expiration);
        setcookie("connected", "no", $expiration);

        $rep = true;   
    }else{ 
        $rep = 'Account data is not available!'; 
    }
    
    echo json_encode($rep); 
} 
?>