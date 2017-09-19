<?php
session_start();

if(isset($_SESSION["email"])){
    echo "welcome " . $_SESSION["email"];
    echo '<a href="logout.php"> LOGOUT</a>';
}else{
    echo "dobrodosli korisnice, ulogujte se<br>.";
}