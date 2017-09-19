<?php
session_start();
include_once("classLogin.php");
$login = new Login();
if($_SERVER["REQUEST_METHOD"]=="POST"){
    if($login->proces()){
        echo "ok";
    }else{
        $login->show_errors();
    }
}
?>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <input type="password" name="password" placeholder="password"><br>
        <input type="text" name="email" placeholder="email"><br>
        <input type="submit" name="login">
    </form>

<p>Niste se jos registrovli<a href="register.php"> registracija</a></p>



