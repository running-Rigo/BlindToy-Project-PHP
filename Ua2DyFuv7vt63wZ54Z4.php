<?php
include "7LKDo6vB8qVEF2y4yb2.php";
include "Ua2DyFuv7vt63wZ54Z3.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $ownerId = $_POST["user_id"];
    $petId = $_POST["pet_id"];
    $token = $_POST["token"];
    $sounds = $_POST["sounds"];
    try {
        $tokenResult = checkToken();
    } catch (DomainException $e) {
        echo "Invalid token leading to " . json_encode($e->getMessage);
        exit;
        $tokenResult = "";
    }

    if ($tokenResult == "success") {
        //only if the token was verified, db operations are done:
        try {
            $db = pdo_connect_mysql();
        } catch (PDOException $ex) {
            echo json_encode($ex->getMessage());
            exit;
        }
    }
    changePetSounds($petId,$sounds,$db);
}
function changePetSounds($petId,$sounds,$db){
$statement = $db->prepare("update pets set sounds =? where pet_id=?");
    try{
        $statement->execute(array($sounds,$petId));
        echo json_encode("success");
    }
    catch(PDOException $ex){
        echo json_encode($ex->getMessage());
    }
}



