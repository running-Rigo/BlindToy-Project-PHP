<?php
include "7LKDo6vB8qVEF2y4yb2.php";
include "Ua2DyFuv7vt63wZ54Z3.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) { // in this case it's a new login via password;
    // collect value of input field
    $email = $_POST["email"];
    $password = $_POST["password"];

    try {
        $db = pdo_connect_mysql();
    } catch (PDOException $ex) {
        echo "db_connection_error";
        exit;
    }
    if ($db) {
        $statement = $db->prepare("select * from users where email = ?");
        $statement->execute(array($email));
        $user = $statement->fetch(PDO::FETCH_ASSOC);
        //Überprüfung des Passworts mit Hash in der DB
        if ($user !== false && password_verify($password, $user['password'])) { //if the email is within the db and its password-hash matches the input
            $token = giveToken($user['user_id']);
            //echo "User angemeldet:".json_encode($user);
            $petsResult = queryPets($db, $user['user_id']);
            //echo "PetsResult:".json_encode($petsResult);

            $resultUser = new DatabaseUser();
            $resultUser->set_userID($user['user_id']);
            $resultUser->set_name($user['name']);
            $resultUser->set_petsList($petsResult);
            $resultUser->set_apiKey($token);
            /*
            echo $resultUser->get_userID();
            echo $resultUser->get_name();
            echo $resultUser->get_petsList();
            */
            //echo "Key:".$resultUser->getApiKey();
            $jsonResult = json_encode($resultUser);
        } else {
            $jsonResult = json_encode("");
        }
        echo $jsonResult;
    }
}

else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) { // known user authentificates with token;

    try {
        $tokenResult = checkToken();
    } catch (DomainException $e) {
        echo "Invalid token leading to " . json_encode($e->getMessage());
        exit;
        $tokenResult = "";
    }

    if ($tokenResult == "success") {
        //only if the token was verified, db operations are done:
        try {
            $db = pdo_connect_mysql();
        } catch (PDOException $ex) {
            echo json_encode("db_connection_error");
            exit;
        }
    }
    $statement = $db->prepare("select * from users where user_id = ?");
    $statement->execute(array($_POST['user_id']));
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    $petsResult = queryPets($db, $_POST['user_id']);
    $resultUser = new DatabaseUser();
    $resultUser->set_userID($user['user_id']);
    $resultUser->set_name($user['name']);
    $resultUser->set_petsList($petsResult);
    $resultUser->set_apiKey($_POST['token']);
    $jsonResult = json_encode($resultUser);
    echo $jsonResult;
}


    function queryPets($db,$userID): array
    {
        $petsArray = array();
        $statement = $db->prepare("select pet from pets_of_users where user = ?");
        $statement->execute(array($userID));
        $petsNums = $statement->fetchAll(PDO::FETCH_ASSOC);
        //$petsCount = $statement->rowCount();
        //echo "Anzahl gefundener Tiere für den User:".$petsCount;
        for ($i = 0; $i<count($petsNums); $i++){
            $statement = $db->prepare("select * from pets where pet_id=?");
            $statement->execute(array($petsNums[$i]['pet']));
            $onePet = $statement->fetch(PDO::FETCH_ASSOC);
            array_push($petsArray,$onePet);
        }
        return $petsArray;
    }

class DatabaseUser
{
    //Properties
    public $name;
    public $user_id;
    public $petsList;
    public $apiKey;

    function set_apiKey($token){
        $this->apiKey = $token;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    function set_name($name) {
        $this->name = $name;
    }
    function get_name() {
        return $this->name;
    }

    function set_userID($user_id){
        $this->user_id = $user_id;
    }
    function get_userID() {
        return $this->user_id;
    }

    function set_petsList($petsList){
        $this->petsList = $petsList;
    }
    function get_petsList() {
        return $this->petsList;
    }
}

?>
