<?php
ini_set('session.cookie_httponly','true');
session_start();

if(isset($_SESSION['last_ip']) === false){
    $_SESSION['last_ip'] = $_SERVER['REMOTE_ADDR'];
}

//IF any remote requests are sent and they dont match, then destroy the session
if($_SESSION['last_ip'] != $_SERVER['REMOTE_ADDR']){
    session_unset();
    session_destroy();
    die();
}

$dir = dirname(__FILE__);
$route = "/secure_software/";
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <style>
        <?php
        include ($dir . '/stylesheets/main.css');
        ?>
        </style>
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:600&display=swap" rel="stylesheet">
        <title>Secure Software Development</title>
    </head>
    <body>
        <header class="site-header">
            <nav class="navbar navbar-expand-lg fixed-top pb-1 bg-dark">
                <div class="container-fluid">
                    <a style="color: #FFFF" class="navbar-brand mr-4" href="">Bug Tracking Application</a>
                    <button class="navbar-toggler ml-auto custom-toggler" type="button" data-toggle="collapse" data-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarToggle">
                        <div class="navbar-nav mr-auto">
                            
                        </div>
                        <!-- Navbar Right Side -->
                        <div class="navbar-nav text-center">
                            <?php
                            

                            if (isset($_SESSION['admin_session']))
                            {
                                echo '<a class="nav-item nav-link" href="' .$route .'index.php">Welcome, ' .$_SESSION["admin_session"]; '</a>';
                                echo '<a class="nav-item nav-link" href="' .$route . 'admin/create_user.php">Create User</a>';
                                echo '<a class="nav-item nav-link" href="' .$route . 'create_ticket.php">Create Tickets</a>';
                                echo '<a class="nav-item nav-link" href="' .$route . 'logout.php">Logout</a>';
                            }
                            elseif(isset($_SESSION['user_session'])){
                                echo '<a class="nav-item nav-link" href="' .$route .'index.php">Welcome, ' .$_SESSION["user_session"]; '</a>';
                                echo '<a class="nav-item nav-link" href="' .$route . 'app.php">View Tickets</a>';
                                echo '<a class="nav-item nav-link" href="' .$route . 'create_ticket.php">Create Tickets</a>';
                                echo '<a class="nav-item nav-link" href="' .$route . 'logout.php">Logout</a>';
                            }

                           else{ 
                            }
                            ?>
                            

                        </div>
                     </div>
                    </div>
                </nav>
            </header>
            
        
    



    

