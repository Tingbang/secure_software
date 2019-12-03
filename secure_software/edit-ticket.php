<?php
include("token.php");
include("header.php");
include("ticket/ticket-class.php");
include("connect.php");

if(isset($_SESSION['admin_session']) || (isset($_SESSION['user_session']))){
    $timer = new Token();
    $timer->sessionCheck();
    
}else{
    header("Location: index.php");
    exit(); 
}

if(isset($_POST['edit_tick'])){
    $ticket_id = strip_tags($_POST['tickID']);
    $ticket_status = strip_tags($_POST['tickStatus']);
    $title = strip_tags($_POST['title']);
    $desc = strip_tags($_POST['desc']);
    $rec = strip_tags($_POST['rec']);
    $prio = strip_tags($_POST['prio']);
    $user = strip_tags($_POST['user']);
    $ticket_object = new Ticket();
    $ticket_object->getSes();

    if($ticket_object->getSes() == $user){
        
    }else{
        //if they dont' match, then they aren't the author of the post
        header("Location: invalid_user.php");
        exit();
    }
}

if(isset($_POST['delete_tick'])){
    $delete_obj = new Ticket();
    $user = $_POST['user'];
    if($delete_obj->getSes() == $user){
        $tick = $_POST['tickID'];
        
        $delete_obj->deleteTicket($conn,$tick);
        
    }else{
        //if they dont' match, then they aren't the author of the post
        header("Location: invalid_user.php");
        exit();
    }
}

if(isset($_POST['edit'])){
    $token = $_POST['token'];
    
    if(Token::check($token)){

        $sub_title = strip_tags($_POST['title1']);
        $sub_desc = strip_tags($_POST['description']);
        $sub_status = strip_tags($_POST['status']);
        $sub_rec = strip_tags($_POST['Recipient']);
        $sub_prio = strip_tags($_POST['Level']);
        $sub_tick = strip_tags($_POST['ticketID']);

        $sub = new Ticket();
        $sub->editTicket($conn, $sub_title, $sub_desc, $sub_status, $sub_rec, $sub_prio, $sub_tick);
        
    }
}

?>

<!-- Update Status -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-8">
        <h3 class="text-center">Update Ticket</h3>   
        <form action="" method="POST">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title1" class="form-control" id="title" value="<?php echo $title; ?>" aria-describedby="title" placeholder="Title">
        </div>
        <div class="form-group">
            <label for="description">Error Message: </label>
            <input name="description" type="description" class="form-control" value="<?php echo $desc; ?>"  id="description" placeholder="Error Message: ">
        </div>
        <div class="form-group">
            <label for="type">Recipient of Ticket</label>
            <select class="form-control" name="Recipient" id ="Recipient">
                <?php
                    $dp_obj = new Ticket();
                    $dp_obj->find_members($conn);
                    for($x = 0; $x < $dp_obj->getRows(); $x++){
                    ?>
                    <option value="<?php echo $dp_obj->getUserName($x); ?>"><?php echo "". $dp_obj->getUserName($x); ?></option><?php }?>                              
                                
            </select>
        </div>
        <div class="form-group">
            <label for="status">Ticket Status</label>
            <select class="form-control" name="status" id ="status">
                <option value = 'Open'>Open</option>
                <option value = 'Closed'>Closed</option>
                <option value = 'Resolved'>Resolved</option>
            </select>
        </div>
        <div class="form-group">
            <label for="Level">Priority Level</label>
            <select class="form-control" name="Level" id ="Level">
                <option class="" value="<?php echo $prio; ?>"><?php echo $prio . '<hr>'; ?></option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
        </div>

        <button type = "submit" name = "edit" class = "btn btn-primary">Change Status</button>
        <input type="hidden" name ="token" value="<?php echo Token::generate();?>">
        <input type="hidden" name ="ticketID" value="<?php echo $ticket_id; ?>">
        </form>
        </div>
    </div>

</div>
 
</div>
