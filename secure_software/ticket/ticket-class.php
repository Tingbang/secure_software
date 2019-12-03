<?php

class Ticket{
    //Private Variables
    private $session;
    private $team_id;
    private $user_names;
    private $title;
    private $description;
    private $type;
    private $Recipient;
    private $status;
    private $levels;
    private $commentID;
    private $comments;
    private $tick_ids;
   
    public function __construct()
    {   
        
        if(isset($_SESSION['admin_session'])){

            $this->session = $_SESSION['admin_session'];

        }else{

            $this->session = $_SESSION['user_session'];

        }

        $this->ticket2 = array();
        $this->user_names = array();

    } //end of construct();

    public function getSes(){
        return $this->session;
    }



    public function getTeamID($conn){
        $user_team = "SELECT teamID FROM user WHERE username = ?";
        $stmt = $conn->prepare($user_team);
        $stmt->bindParam(1, $this->session);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->team_id = $result['teamID'];
    } //getTeamID

    public function getUserName($index){return $this->user_names[$index];}
    public function getRows(){return sizeof($this->user_names);}

    public function displayRec(){

        foreach($this->user_names as $result1 ){
            echo $result1;
        }
    }

    public function find_members($conn){
    
        $this->getTeamID($conn);
        $user_team = "SELECT username FROM user WHERE teamID = ?";
        $stmt = $conn->prepare($user_team);
        $stmt->bindParam(1, $this->team_id);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            while($results = $stmt->fetch(PDO::FETCH_ASSOC)){
                $this->user_names[] = $results['username'];
            }
        }
    }

    public function getTickets($conn){
        $token = Token::generate();
        $comment_form = '<form action="" method = "POST">
                        <div class ="input-group">
                        <input name = "comment" placeholder="Your Comment" type = "text" class="form-control"/>
                        <span class="input-group-btn">
                            <button type = "submit" name = "submit" class = "btn btn-primary mb-2">Submit</button>
                        </span>
 
                        </div>
                        <input type="hidden" name ="token" value="' . $token .'">';
                        
        $this->getTeamID($conn);
        $sql = "SELECT * FROM tickets WHERE teamID = ?";
        $stmt_1 = $conn->prepare($sql);
        $stmt_1->bindParam(1, $this->team_id);
        $stmt_1->execute();

        if($stmt_1->rowCount() > 0){
            while($tickets = $stmt_1->fetch(PDO::FETCH_ASSOC)){

                echo "<h4 class='font-weight-bold' >".$tickets['title']. "</h4> " . '<p class="">' . $tickets['ticketDescription'] .'</p>' .
                     '<p class= "">Ticket Type:' . $tickets['ticketType']. '</p>' .'<p>Ticket for: '. $tickets['recipient'] .' </p>' .'<p> Ticket Status: ' .
                      $tickets['status']. '</p>' .  '<p>Priority Level: ' .$tickets['priorityLevel'] . '</p>'. '<p class="text-muted"> Posted By '. $tickets['ticketAuthor'] .
                      ' at: <br> '. $tickets['datePosted'] . '</p>' . $comment_form .
                      '<input type = "hidden" name = "id" value ="' .$tickets['ticketID'] .'"/> '. 
                      '<input type = "hidden" name = "commentCheck" value ="' .$tickets['status'] .'"/> '.
                      '</form>
                      <form action="edit-ticket.php" method="POST">
                      <button type = "submit" name = "edit_tick" class = "btn btn-primary">Edit Ticket</button>
                      <button type = "submit" name = "delete_tick" class = "btn btn-primary">Delete Ticket</button>
                      <input type="hidden" name ="token" value="' . $token .'">
                      <input type = "hidden" name = "tickID" value ="' .$tickets['ticketID'] .'"/>
                      <input type = "hidden" name = "user" value ="' .$tickets['ticketAuthor'] .'"/>
                      <input type = "hidden" name = "title" value ="' .$tickets['title'] .'"/>
                      <input type = "hidden" name = "desc" value ="' .$tickets['ticketDescription'] .'"/>
                      <input type = "hidden" name = "rec" value ="' .$tickets['recipient'] .'"/>
                      <input type = "hidden" name = "tickStatus" value ="' .$tickets['status'] .'"/>
                      <input type = "hidden" name = "prio" value ="' .$tickets['priorityLevel'] .'"/>
                      </form>
                      <hr>' . '<div style="width:100%; background-color:#3196f5; color: black;" class=""><h3>Comments</h3></div>';

                        $this->getTeamID($conn);
                        $sql2 = "SELECT * FROM comments WHERE ticketID = ?";
                        $stmt_2 = $conn->prepare($sql2);
                        $stmt_2->bindParam(1, $tickets['ticketID']);
                        $stmt_2->execute();
                        if($stmt_2->rowCount() > 0){
                            while($comments = $stmt_2->fetch(PDO::FETCH_ASSOC)){
                                
                                echo '<p class="">' . $comments['commentDescription'] .'</p>' .
                                                    '<p class= "mb-3 text-muted">Comment By: ' .$comments['user'] . '  at: '. $comments['timeStamp']. '</p><hr>';
                            }

        }else{   
            echo " No to display";
        }
            }

            //$this->retrieveComments($conn);

        }else{   
            echo " Nothing found.";
        }
    }

    public function deleteTicket($conn, $tick){

        $sql = "DELETE FROM tickets WHERE ticketID =? ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(1, $tick);
        $stmt->execute();

        $sql2 = "DELETE FROM comments WHERE ticketID = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bindParam(1, $tick);
        $stmt2->execute();

        header("Location: app.php");
        exit();

    }

    public function get_ticket($index){}

    public function Display(){

        foreach($this->ticket2 as $result1 ){
            echo $result1;
        }
    }


    


    public function submitTicket($conn,$title, $Level, $description, $type, $Recipient, $status)
    {
        $this->getTeamID($conn);
        $this->title = $title;
        $this->description = $description;
        $this->type = $type;
        $this->Recipient =  $Recipient;
        $this->status = $status;
        $this->levels = $Level;
        $this->commentID = 1;
        //$time = date('Y/m/d h:i:s a');

        $sql = "INSERT INTO tickets (title, ticketDescription, ticketType, ticketAuthor,
                recipient, status, priorityLevel, commentID, teamID) VALUES (?,?,?,?,?,?,?,?,?)";
        $stmt = $conn->prepare($sql);

        $stmt->bindParam(1, $this->title);
        $stmt->bindParam(2, $this->description);
        $stmt->bindParam(3, $this->type);
        $stmt->bindParam(4, $this->session);
        $stmt->bindParam(5, $this->Recipient);
        $stmt->bindParam(6, $this->status);
        $stmt->bindParam(7, $this->levels);
        $stmt->bindParam(8, $this->commentID);
        $stmt->bindParam(9, $this->team_id);

        if($stmt->execute()){
            header('Location: app.php');
            exit;
        }else{
            echo "Failed";
            
        }
        
    }

    //Updates the Ticket Status
    public function editTicket($conn, $sub_title, $sub_desc, $sub_status, $sub_rec, $sub_prio, $sub_tick){
        

        $sql = "UPDATE tickets SET title = ?, ticketDescription = ?,
                recipient = ?, status = ?, priorityLevel = ? WHERE ticketID = ?";

        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(1, $sub_title);
        $stmt->bindParam(2, $sub_desc);
        $stmt->bindParam(3, $sub_rec);
        $stmt->bindParam(4, $sub_status);
        $stmt->bindParam(5, $sub_prio);
        $stmt->bindParam(6, $sub_tick);

        if($stmt->execute()){
            header("Location: app.php");
            exit();
        }else{
            echo "failed";
        }
    
    }

    public function retrieveComments($conn){
        $this->getTeamID($conn);
        $sql = "SELECT * FROM comments WHERE ticketID = ?";
        $stmt_1 = $conn->prepare($sql);
        $stmt_1->bindParam(1, $this->team_id);
        $stmt_1->execute();
        if($stmt_1->rowCount() > 0){
            while($comments = $stmt_1->fetch(PDO::FETCH_ASSOC)){

                $this->comments[] =  '<p class="">' . $comments['commentDescription'] .'</p>' .
                                    '<p class= "">Comment Made' . $comments['timeStamp']. '</p>';
            }

        }else{   
            echo " No tickets found";
        }

    }


    public function insertComment($conn,$comment,$id, $check){

        if($check == "Closed"){
            header("Location: closed.php");
        }else{
            $sql ="INSERT INTO comments (commentDescription, ticketID, user) VALUES (?, ?, ?)";

            $stmt =$conn->prepare($sql);
            $stmt->bindParam(1, $comment);
            $stmt->bindParam(2, $id);
            $stmt->bindParam(3, $this->session);
            if($stmt->execute()){
                header("Location: app.php");
            }else{
                echo "failed";
            }

        }
        
    }

    public function displayComments(){

        foreach($this->comments as $result){
            echo $result;
        }

    }


}

?>