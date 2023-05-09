<?php
//include 'db_settings_local.php';
include '9e7tz9666cEzTY5Fuay.php';
function pdo_connect_mysql()  {
    // Update the details below with your MySQL details
    $DATABASE_HOST = giveHost();
    $DATABASE_USER = giveUser();
    $DATABASE_PASS = givePassword();
    $DATABASE_NAME = giveName();


    return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);

    /*
    try {
        return new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);
    } catch (PDOException $exception) {
        exit('Failed to connect to database!');
    }
    */
}


function givePets($userId)
{
    $db = pdo_connect_mysql();
    if ($db) {
        $petsArray = array();
        $statement = $db->prepare("select pet from pets_of_users where user=?");
        $statement->execute([$userId]);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        for ($i = 0; $i < count($result); $i++){
            $petId = $result[$i]['pet'];
            $statement = $db->prepare("select * from pets where pet_id=?");
            $statement->execute([$petId]);
            //creating a multidimensional array where each pet with its data is listed;
            $petsArray[$i] = $statement->fetch(PDO::FETCH_ASSOC);
        }
        $jsonResult = json_encode($petsArray);
        echo $jsonResult;
    }
}
?>


