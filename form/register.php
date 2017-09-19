<?php
include_once("classRegister.php");
$reg = new Register();
if($_SERVER["REQUEST_METHOD"]=="POST"){
    if($reg->proces($_POST["username"],$_POST["password"],$_POST["name"],$_POST["email"])){
        echo "ok";
    }else{
        $reg->show_errors();
    }
}
?>
<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <input type="text" name="username" placeholder="username"><br>
    <input type="password" name="password" placeholder="pass"><br>
    <input type="text" name="name" placeholder="name"><br>
    <input type="text" name="email" placeholder="email"><br>
    <input type="submit" name="reg"><br>
</form>
<p>Imate registraciju<a href="login.php"> ulogujte se</a></p>
