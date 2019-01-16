<?php
    session_start();
    $error = false;
    if(isset($_SESSION['user']))
        header("Location: index.php");
    if(isset($_POST['email'])){
        require_once "../php/db.php";
        if($user = DB::checkLogin($_POST['email'],$_POST['password'])){
            $_SESSION['user'] = $user;
            header("Location: index.php?loggedin");
        }else{
            $error = true;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- toast CSS -->
    <link href="../plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <title>Admin Login</title>
</head>
<body>
    <div class="wrapper">
        <form method="POST" class="login">
            <p class="title">Log in</p>
            <input name="email" type="email" placeholder="E-mail"  required autofocus/>
            <i class="fa fa-user"></i>
            <input name="password" type="password" placeholder="Password" required  />
            <i class="fa fa-key"></i>
            <button>
            <i class="spinner"></i>
            <span class="state">Log in</span>
            </button>
        </form>
    <footer>
        <a href="../">Home</a>
    </footer>
    </p>
    </div>

<script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../plugins/bower_components/toast-master/js/jquery.toast.js"></script>

<?php 
    if($error){
?> 
    <script>
        $.toast({
            heading: "Error",
            text: "E-mail/password combination is incorrect.",
            position: "top-right",
            loaderBg: "#fff",
            icon: "warning",
            hideAfter: 3500,
            stack: 6
        });
    </script>
<?php } ?>
</body>
</html>