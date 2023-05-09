<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // collect value of input field
    $ownerId = $_POST["user_id"];
    $name = $_POST["name"];
    $species = $_POST["species"];
    $sounds = $_POST["sounds"];

    include "7LKDo6vB8qVEF2y4yb2.php";
    include "Ua2DyFuv7vt63wZ54Z3.php";

    try{
        $tokenResult = checkToken();
    }catch(DomainException $e){
        echo "Invalid token leading to ".json_encode($e->getMessage());
        exit;
        $tokenResult = "";
    }

    if($tokenResult == "success"){
        //only if the token was verified, db operations are done:
        try {
            $db = pdo_connect_mysql();
        } catch (PDOException $ex) {
            echo json_encode("db_connection_error");
            exit;
        }
//ev transaction for multiple table operations
        if ($db) {
            $statement1 = $db->prepare("insert into pets (name,species,sounds) values (?,?,?)");
            $statement2 = $db->prepare("insert into pets_of_users values (?,?)");
            try{
                $db->beginTransaction();
                $statement1->execute(array($name,$species,$sounds));
                $petId = $db->lastInsertId();
                $statement2->execute(array($petId,$ownerId));
                $db->commit();
                echo json_encode($petId);
            }
            catch (PDOException $ex){
                $db->rollBack();
                echo json_encode("Saving new Pet failed. ".$ex->getMessage());
            }
        }
    }
    else{
        echo json_encode($tokenResult);
    }

}





