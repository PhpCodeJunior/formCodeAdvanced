<?php
class Login
{
    private $password;
    private $email;
    private $passwordmd5;

    private $errors;
    private $conn;

    public function __construct()
    {
        $this->errors = array();
        $this->password = @$_POST["password"];
        $this->email = @$_POST["email"];;
        $this->database();
        $this->passwordmd5 = md5($this->password);
    }

    public function database()
    {
        $conn = null;
        $host = "localhost";
        $user = "root";
        $pass = "";
        $dbname = "example";
        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// $this->conn->message = "connection successfully";
        } catch (PDOException $e) {
            $this->errors[] = $e->getMessage();
        }
        $this->conn = $conn;
    }

    public function getConn(){return $this->conn;}

    public function login()
    {

        if ($this->getConn()) {
            $stmt = $this->conn->prepare("select * from examp where email=:email and password=:password");
            $stmt->bindValue(":email", $this->email);
            $stmt->bindValue(":password", $this->passwordmd5);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                 $this->session();
             } else {
                 $this->errors[] = "pogresna sifra ili email<br>";
             }
         } else {
             $this->errors[] = "greska u bazi podataka<br>";
         }
        }

    public function session(){
        $_SESSION['email']= $this->email;
        if(isset($_SESSION['email'])){
            $this->redirect("welcome.php");
        }}

    public function redirect($url){ header("location:$url"); }

    public function valid_data()
    {
        if (empty($this->password)) {
            $this->errors[] = "Upisite vasu sifru<br>";
        }

        if (empty($this->email)) {
            $this->errors[] = "Upiste vas email<br>";
        }
        if ($this->filterEmail()) {
            $this->errors[] = "molimo unesite vas validan email<br>";
        }
        if ($this->emailExist()) ;
        return $this->errors ? 0 : 1;
    }

    public function proces()
    {
        if ($this->valid_data()) {
            return $this->login();
        }
    }
    public function logout(){
        session_start();
        session_destroy();
        echo "<a href='login.php'>Logujte se ponovo</a>";
    }

    public function filterEmail()
    {
        return (!filter_var($this->email, FILTER_VALIDATE_EMAIL));
    }

    public function emailExist()
    {
        $stmt = $this->conn->prepare("SELECT email FROM examp WHERE  email=:email");
        $stmt->execute(array(':email' => $this->email));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row['email'] != $this->email) {
            $this->errors[] = "email ne postoji u bazi!<br>";
        }
    }

    public function show_errors()
    {
        echo "<h1>ERROR</h1>";
        foreach ($this->errors as $error) {
            echo $error;
        }
    }
}



