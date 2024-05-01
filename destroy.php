<?php
    $expiration_passée = time() - 3600; 
    setcookie("emailUser", "", $expiration_passée);
    setcookie("idPartner", "", $expiration_passée);
    setcookie("connected", "", $expiration_passée);
    echo "true";
?>