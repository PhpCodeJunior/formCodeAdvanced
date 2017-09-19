<?php
class Register
{
    private $username;
    private $password;
    private $name;
    private $email;
    private $passwordmd5;

    private $errors;
    private $conn;

    public function __construct()
    {
        $this->errors = array();
        $this->username = @$_POST["username"];
        $this->password= @$_POST["password"];
        $this->name= @$_POST["name"];
        $this->email= @$_POST["email"];
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

    public function getConn()
    { return $this->conn; }

    public function register()
    {
        if ($this->getConn()) {

            $stmt = $this->conn->prepare("insert into  examp(username,password,name,email)values(:username,:password,:name,:email)");
            $stmt->bindValue(":username", $this->username);
            $stmt->bindValue(":password", $this->passwordmd5);
            $stmt->bindValue(":name", $this->name);
            $stmt->bindValue(":email", $this->email);
            return $stmt->execute();
        } else {
            $this->errors[] = "greska u bazi podataka<br>";
        }
    }
    public function valid_data(){
        if(empty($this->username)){
            $this->errors[] = "Upisite vas username<br>";
        }
        if(empty($this->password)){
            $this->errors[] = "Upisite vasu sifru<br>";
        }if(empty($this->name)){
            $this->errors[] = "Upisite vase ime<br>";
        }if(empty($this->email)){
            $this->errors[] = "Upiste vas email sa validnom vrednoscu<br>";
        }if($this->filterEmail()){
            $this->errors[]= "molimo unesite vas validan email<br>";
        }
        if($this->emailExist());
        return $this->errors ? 0 : 1;
    }
 public function proces(){
     if($this->valid_data()){
        return $this->register();
     }
 }

    public function filterEmail(){ return (!filter_var($this->email, FILTER_VALIDATE_EMAIL)); }

    public function emailExist(){
        $stmt = $this->conn->prepare("SELECT email FROM examp WHERE  email=:email");
        $stmt->execute(array(':email'=>$this->email));
        $row=$stmt->fetch(PDO::FETCH_ASSOC);
        if($row['email']==$this->email) {
            $this->errors[]= "email vec postoji u bazi!<br>";
        }
    }

    public function show_errors(){
        echo "<h1>ERROR</h1>";
        foreach($this->errors as $error){
            echo $error;
        }
    }



}