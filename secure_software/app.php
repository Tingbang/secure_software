<?php
include("header.php");
include("connect.php");

if(isset($_SESSION['admin_session']) || (isset($_SESSION['user_session']))){
    include ($dir ."../ticket/ticket-class.php");
    include ("token.php");

    $timer = new Token();
    $timer->sessionCheck();
    
}else{
    header("Location: index.php");
    exit(); 
}


if (isset($_POST['submit'])){
    if(Token::check($_POST['token'])){
    $check = $_POST['commentCheck'];
    $comment = strip_tags($_POST['comment']);
    $id = $_POST['id'];
    $comment_ob = new Ticket();
    $comment_ob->insertComment($conn, $comment, $id, $check);

    }else{
        header("Location: invalid.php");
    }
}

?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div style="width:100%; background-color:#3196f5;" class=""><h3>Tickets</h3></div>
            <div class="">Displaying Tickets</div>
            <hr>
            <?php
                $ticket_obj = new Ticket();
                $ticket_obj->getTickets($conn);
            ?>
        </div>

    </div>
</div>




<?php include("eof.php");?>
