<?php
/*
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Method:POST');
header('Content-Type: application/JSON');
*/
//declare(strict_types=1);
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once('vendor/autoload.php');

function checkToken(){
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $secreteKey = giveKey();
        $token = $_POST["token"];
        $userNr = $_POST["user_id"];
        try{
            $decodedToken = JWT::decode($token, new Key($secreteKey,'HS256'));
            $id =  $decodedToken->userId;
            if($id != $userNr){
                $result="unauthorized";
            }
            else{
                $result = "success";
            }
        }
        catch (ExpiredException $exception){
            $result = $exception->getMessage();
        }
        return $result;
    }
}

function giveToken($userId){
    $payload=[
        'iat' => time(),
        'iss' => 'localhost',
        'userId' => $userId,
        //'exp' => time() + (60) //will be valid for 60 seconds for testing purposes
    ];
    $secreteKey = giveKey();
    return JWT::encode($payload, $secreteKey, 'HS256');
}



/*
    //Überprüfung des Passworts mit Hash in der DB
    if ($user !== false && password_verify($password, $user['password'])) { //if the email is within the db and its password-hash matches the input
        $payload=[
            'iat' => time(),
            'iss' => 'localhost',
            'userId' => $userId,
            'exp' => time() + (60) //will be valid for 60 seconds for testing purposes
            ];
        $secreteKey = giveKey();

        $token = JWT::encode($payload, $secreteKey, 'HS256');
        echo $token;
    }
    else{
        echo 'no valid user';
    }

}
*/

?>





