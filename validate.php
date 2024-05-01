<?php
session_start();
$codeTrue = $_SESSION["code"];
$code = $_POST["code"];

if($code == $codeTrue){
    echo "true";
}else{
    echo "الرمز غير صحيح";
}
?>