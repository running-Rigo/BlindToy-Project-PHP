<?php
include "7LKDo6vB8qVEF2y4yb2.php";
include "Ua2DyFuv7vt63wZ54Z3.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $ownerId = $_POST["user_id"];
    $petId = $_POST["pet_id"];
    $token = $_POST["token"];

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
    deletePet($petId,$db);
}

function deletePet($petId,$db){
    $statement1 = $db->prepare("delete from pets_of_users where pet = ?");
    $statement2 = $db->prepare("delete from pets where pet_id = ?");
    try{
        $db->beginTransaction();
        $statement1->execute(array($petId));
        $statement2->execute(array($petId));
        $db->commit();
        echo json_encode("success");
    }
    catch (PDOException $ex){
        $db->rollBack();
        echo json_encode($ex->getMessage());
    }
}








