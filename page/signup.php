<?php
session_start();
require_once '../php/class.user.php';

$reg_user = new USER();

if($reg_user->is_logged_in()!="")
{
    $reg_user->redirect('home.php');
}


if(isset($_POST['btn-signup']))
{
    $uname = trim($_POST['txtuname']);
    $email = trim($_POST['txtemail']);
    $upass = trim($_POST['txtpass']);
    $code = md5(uniqid(rand()));

    $stmt = $reg_user->runQuery("SELECT * FROM tbl_users WHERE userEmail=:email_id");
    $stmt->execute(array(":email_id"=>$email));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if($stmt->rowCount() > 0)
    {
        $msg = "
            <div class='alert alert-error'>
            <button class='close' data-dismiss='alert'>&times;</button>
            <strong>Sorry!</strong>  This email is already registered; please try another one.
            </div>
            ";
    }
    else
    {
        if($reg_user->register($uname,$email,$upass,$code))
        {
            $id = $reg_user->lasdID();
            //$key = base64_encode($id);
            //$id = $key;

            $path = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
            $path .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER['SERVER_PORT'] . dirname($_SERVER["PHP_SELF"]);

            $url = "${path}/verify.php?id=${id}&code=${code}";

            $message = "
                <p>
                    Hello ${uname},
                    <br /><br />
                    Welcome to the Citizen Problem Reporter!<br/>
                    To complete your registration, please click the following link:<br/>
                    <br /><br />
                    <a href='${url}'>Click HERE to Activate :)</a> <br />
                    <br />

                    Direct activation link:

                    <a href='${url}'>${url}</a><br />
                    <br />
                    Thanks!
                </p>
                ";

            $subject = "Confirm Registration";

            $reg_user->send_mail($email,$message,$subject);
            $msg = "
                <div class='alert alert-success'>
                <button class='close' data-dismiss='alert'>&times;</button>
                <strong>Success!</strong>  
                    We've sent an email to $email.
                    Please click on the confirmation link in the email to create your account. 
                </div>
                ";
        }
        else
        {
            echo "Sorry, query could not execute...";
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <!-- Import common header -->
    <?php require_once "../partials/head.php"; ?>

    <title>Sign Up | Orono Problem Reporter</title>
    
    <script>
    function check(){
        if (document.getElementById('pw1').value !== document.getElementById('pw2').value){
            window.alert('Passwords do not match!');
            document.getElementById('pw1').value="";
            document.getElementById('pw2').value="";
        }

    }
    </script>
</head>

<body id="login">

<!-- Import navbar -->
<?php require_once "../partials/navbar.php"; ?>

<?php if(isset($msg)) echo $msg;  ?>
<div class="container">
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <form method="post">
                    <h2>Sign Up for an Account</h2><hr />
                    <h4>Only users with accounts can enter problems</h4>
                    <label for="txtuname" class="control-label">Pick a user name:</label>
                    <input type="text" class="form-control" placeholder="JaneDoe2017" name="txtuname" required /><br />
                    <label for="txtemail">please enter your email address:</label>
                    <input type="email" class="form-control" placeholder="Email address" name="txtemail" required /><br />
                    <label for="txtpass">pick a password:</label>
                    <input type="password" class="form-control" placeholder="Password" name="txtpass" required id='pw1'/><br />
                    <label for="txtpass2">enter it again:</label>
                    <input type="password" class="form-control" placeholder="re-enter password" name="txtpass2" required id='pw2' onblur="check()"/><br />

                    <hr />
                    <button class="btn btn-large btn-primary" type="submit" name="btn-signup">Sign Up</button>
                    <a href="index.php"  class="btn btn-large">Log In</a>
                </form>
            </div>
        </div>
    </div>
    <!-- /container -->
</div>
</body>
</html>