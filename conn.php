<?php
    $host = 'localhost';
    $user = 'root';
    $password = 'root';
    $db = 'sigcvrs';

   $conn= @mysqli_connect($host,$user,$password,$db);

    

    if(!$conn){
            echo "Conexão falhou";
    }
    ?>
    