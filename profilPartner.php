<?php
$email = $_COOKIE['emailUser'];
$data = [];
$nbr_cmnd = 0;


try{
    include_once 'connection.php';
    $db->beginTransaction();
    
    $stm = $db->prepare("SELECT * FROM `partner` WHERE PARTNER_EMAIL = :email ");
    $stm->bindParam(":email", $email);
    $stm->execute();

    foreach($stm->fetchAll() as $row){
        $id = $row["PARTNER_ID"];
        $expiration = time() + (10 * 365 * 24 * 60 * 60); 
        setcookie("idPartner", $id, $expiration);

        $stm = $db->prepare("SELECT * FROM command WHERE PARTNER_ID = :id ORDER BY COMMAND_DATE DESC LIMIT 1");
        $stm->bindParam(":id", $id);
        $stm->execute();

        if($stm->rowCount() > 0){
            foreach($stm->fetchAll() as $row){
                $rep = $row["COMMAND_STATE"];
                array_push($data, $rep);
            }
        }else{
            $rep = NULL;
            array_push($data, $rep);
        }


        $stm = $db->prepare("SELECT * FROM command WHERE PARTNER_ID = :id ");
        $stm->bindParam(":id", $id);
        $stm->execute();

        $history = "<h2>جميع الطلبات </h2>
                    <div class='allcommand'>";

        foreach($stm->fetchAll() as $row){
            $nbr_cmnd++;
            $history .= "<div class='command'>
                            <h4>طلب : {$nbr_cmnd}</h4>
                            <div class='time'>
                                <div class='time1'>
                                    <p>{$row["COMMAND_DATE"]}</p>
                                    <p class='time2'>تاريخ الطلب</p>          
                                </div>
                                <div  class='time1'>
                                    <p>{$row["COMMAND_DELIVREY_DATE"]}</p>
                                    <p class='time2'>تاريخ التسليم</p>          
                                </div>
                            </div>
                        </div>";
        }

        $history .= "</div>";
        array_push($data, $history);
        array_push($data, $_COOKIE['connected']);

        $expiration = time() + (10 * 365 * 24 * 60 * 60);
        setcookie("connected", "yes", $expiration);

        echo json_encode($data);
    }

    

    $db->commit();
}catch(PDOException $e){
    $db->rollback();
    $rep = $e->getMessage();
    echo json_encode($rep);
}
?>