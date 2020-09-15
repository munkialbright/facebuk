<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" href="../bootstrap/3.3.6/css/bootstrap.min.css">
        <script src="../bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="../ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    </head>
    <body style="background-color: #171718;">
        <div class="dialogbox">
            <?php
                include ("inc/connect.php");

                if (isset($_GET['id'])) {
                    $getid = mysqli_real_escape_string($conn,$_GET['id']);
                    if (ctype_alnum($getid)) {
                         //check post exists
                        $check = mysqli_query($conn,"SELECT * FROM users WHERE id='$getid'");
                        if (mysqli_num_rows($check)===1) {
                            $get = mysqli_fetch_assoc($check);
                            $getid = $get['id'];
                            $username = $get['username'];

                            if (isset($_POST['message'])) {
                                $get_user_to = mysqli_query($conn, "SELECT * FROM users WHERE id = '$getid'");
                                $user_to_info = mysqli_fetch_assoc($get_user_to);

                                $message = htmlentities($_POST['message']);
                                $user_from = "User";
                                $user_to = $user_to_info['username'];

                                mysqli_query($conn, "INSERT INTO messages VALUES('', '$message', '$user_from', '$user_to')");

                                header("Location: dialog_frame.php?id=$getid");   
                            }

                            echo '<div class="container-chat">';

                            $user = 'User';

                            $get_chats = mysqli_query($conn, "SELECT * FROM messages WHERE (user_from = '$user' OR user_to = '$user') AND (user_from = '$username' OR user_to = '$username')");
                            $chat_numrows = mysqli_num_rows($get_chats);

                            if ($chat_numrows > 0) {
                                while ($chat_content = mysqli_fetch_assoc($get_chats)) {
                                    $message = $chat_content['message'];
                                    $user_from = $chat_content['user_from'];

                                    if ($user_from == $user) {
                                        echo "<div class='well-sm from-user'>".$message."</div><br>";
                                    }
                                    else {
                                        echo "<div class='well-sm to-user'>".$message."</div><br>";
                                    }

                                }
                            }

                            echo '</div>';

                            ?>


                            <form class="form-group form-reply" method="POST" action="dialog_frame.php?id=<?php echo $getid; ?>"  autocomplete="off">
                                <input type="text" placeholder="Enter message..." class="form-control" name="message" autocomplete="off" required>
                            </form>
                            <?php
                        }
                    }
                }
            ?>
        </div>
    </body>
</html>