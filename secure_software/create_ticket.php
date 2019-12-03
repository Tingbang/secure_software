<?php
include("header.php");
include_once("ticket/ticket-class.php");
include_once("token.php");
include_once("functions.php");
include_once("connect.php");

if(isset($_SESSION['admin_session']) || (isset($_SESSION['user_session']))){
    $timer = new Token();
    $timer->sessionCheck();
    
}else{
    header('Location: index.php');
    exit;  
}

if(isset($_POST['submit'])){
    if(Token::check($_POST['token'])){
    $errors = "<div id='error' class='alert alert-danger' role='alert'>";
    $title = strip_tags($_POST['title']);
    $description = strip_tags($_POST['description']);
    $type = strip_tags($_POST['type']);
    $Recipient = strip_tags($_POST['Recipient']);
    $status = strip_tags($_POST['status']);
    $Level = strip_tags($_POST['Level']);

    if(empty($title)){  
        $errors.= "Title cannot be empty";
    }elseif(strlen($title) < 5){
        $errors .= "Titles must be atleast 5 characters";
    }
    elseif (empty($description)){
        $errors.= "The description cannot be empty";
    }
    elseif (empty($type)){
        $errors.= "Please Enter a valid Type";
    }
    elseif (empty($Recipient)){
        $errors.= "Please Enter a Recipient";
    }
    elseif (empty($status)){
        $errors.= "Please ensure nothing is empty";
    }
    else{
        try{
            $submit_obj = new Ticket();
            $submit_obj->submitTicket($conn, $title, $Level, $description, $type, $Recipient, $status);

        } catch(PDOException $e){
            $errors.= $e->getMessage();
            echo "fail";
        }
        
    }
    $errors .= '</div>';

    echo $errors;
    
    }else{
        header("Location: invalid.php");
        exit();
    }

}
?>

<div class="container-fluid">
    <div class = "row justify-content-center">
        <div class="col-6">
            <div style="height: 650px;" class = "login">
                <div class="container">
                    <h3 class="text-center mx-auto mt-2">Submit Ticket</h3><hr>
                    <form action="#" method="POST">
                        <div class="form-group">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" id="title" aria-describedby="title" placeholder="Title">
                        </div>
                        <div class="form-group">
                            <label for="description">Error Message: </label>
                            <input name="description" type="description" class="form-control" id="description" placeholder="Error Message: ">
                        </div>
                        <div class="form-group">
                            <label for="type">Bug Found in: </label>
                            <select class="form-control" name="type" id ="type">
                                <option value="Development">Development</option>
                                <option value="Production">Testing</option>
                                <option value="Testing">Production</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="type">Recipient of Ticket</label>
                            <select class="form-control" name="Recipient" id ="Recipient">
                                <?php
                                    $dp_obj = new Ticket();
                                    $dp_obj->find_members($conn);
                                    //echo $dp_obj->getUserName(1);
                                    for($x = 0; $x < $dp_obj->getRows(); $x++){
                                    ?>
                                    <option value="<?php echo $dp_obj->getUserName($x); ?>"><?php echo "". $dp_obj->getUserName($x); ?></option><?php }?>                              
                                    
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="status">Ticket Status</label>
                            <select class="form-control" name="status" id ="status">
                                <option value="Open">Open</option>
                                <option value="Closed">Closed</option>
                                <option value="Resolved">Resolved</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="Level">Priority Level</label>
                            <select class="form-control" name="Level" id ="Level">
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        <input type="hidden" name ="token" value="<?php echo Token::generate(); ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


</div>
<?php
include("eof.php");
?>