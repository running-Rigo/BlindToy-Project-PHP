<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $username = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    include "7LKDo6vB8qVEF2y4yb2.php";

    if(strlen($password)<10){
        $jsonResult = json_encode("Registration Error");
        echo $jsonResult;
        exit();
    }

    try {
        $db = pdo_connect_mysql();
    }
    catch (PDOException $ex){
        echo "db_connection_error";
    }

    if ($db) {
        try{
            $statement = $db->prepare("insert into users (name,email,password) values (?,?,?)");
            $statement->execute(array($username,$email,password_hash($password,PASSWORD_DEFAULT)));
            $jsonResult = json_encode("success");
            echo $jsonResult;
        }
        catch (PDOException $e){
            $jsonResult = json_encode("Registration Error");
            echo $jsonResult;
        }

    }
}
?>

