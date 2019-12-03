<?php
class Admin{
    //Database Variables
    private $db;
    private $servername;
    private $db_username;
    private $db_password;
    private $salt;

    //Teams
    private $teamID;
    private $teamName;

    //User Form Variables
    private $user_name;
    private $user_password;
    private $user_type;
    private $user_fname;
    private $user_lname;
    private $user_email;
    private $user_team;

    //Establishes the database connection
    public function __construct(){

        //Team Initialisation
        $this->teamID = array();
        $this->teamName = array();

        //Database Credentials
        $this->servername = "localhost";
        $this->db_username ="admin";
        $this->db_password = "MEKwI2QowF30BHDt";
        $this->salt = "faiugh2asnasvzn38n1pok";
        try{
            $this->db = new PDO("mysql:host=$this->servername;dbname=bug_tracking", $this->db_username, $this->db_password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        } catch(PDOException $e){
            echo "Connection Failed: " . $e->getMessage();
        }
    }

    //Gets the salt
    public function getSalt(){
        return $this->salt;
    }

    //Checks if the user already exists inside the database
    public function checkExisting($username, $password, $type, $firstname, $surname, $email, $team){
    $this->user_name = $username;
    $this->user_password = $password;
    $this->user_type = $type;
    $this->user_fname = $firstname;
    $this->user_lname = $surname;
    $this->user_email = $email;
    $this->user_team = $team;

    $sql = "SELECT username, password FROM user WHERE username = ?";
    $stmt = $this->db->prepare($sql);

    $stmt->bindParam(1, $this->user_name);
    $stmt->execute();

    if($stmt->rowCount() > 0){echo'<div id="error" class="alert alert-danger" role="alert">
        <strong>Oh snap!</strong> It appears that username is already taken
        </div>';
    }else{
        //If the user doesn't already exist, register as a new User
        $this->registerUser();
    }


    } // End of checkExisting();

    //Registers a new user to the database
    public function registerUser(){
        //$this->user_password = sha1($this->user_password.$this->salt);
        $this->user_password = password_hash($this->user_password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (username, password, type, firstname, surname, email, teamID) VALUES (?,?,?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(1, $this->user_name);
        $stmt->bindParam(2, $this->user_password);
        $stmt->bindParam(3, $this->user_type);
        $stmt->bindParam(4, $this->user_fname);
        $stmt->bindParam(5, $this->user_lname);
        $stmt->bindParam(6, $this->user_email);
        $stmt->bindParam(7, $this->user_team);

        if($stmt->execute()){
            header('Location: ../app.php');
            exit;
        }
    }

    public function getTeams(){
        $sql = "SELECT teamID, teamName FROM team";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            while($team = $stmt->fetch(PDO::FETCH_ASSOC)){
                $this->teamID[] = $team['teamID'];
                $this->teamName[] = $team['teamName'];
            }

        }else{
            //If the user doesn't already exist, register as a new User
            echo " No teams found ";
        }
    }
    //Getters
    //Index gets passed in from the loop when the getters are used.
    public function getTeamName($index){return $this->teamName[$index];}
    public function getTeamID($index){return $this->teamID[$index];}
    public function getRows(){return sizeof($this->teamID);}


}


?>