<?php
function connect() {
    $username = 'root';
    $password = 'Aa123456';
    $mysqlhost = 'localhost';
    $dbname = 'restyle';
    $pdo = new PDO('mysql:host='.$mysqlhost.';dbname='.$dbname.';charset=utf8', $username, $password);
     if($pdo){
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_NATURAL);
        return $pdo;
    }else{
        die("Could not create PDO connection.");
    }
}

$sql = connect();

$mailer = array(
    'host'=>'smtp-relay.sendinblue.com',
    'username'=>'phpstuff1@gmail.com',
    'password'=>'kXISQvRPLf0YF2ya',
    'fromName'=>'Wesam',
    'from'=>'phpstuff1@gmail.com'
);

?>