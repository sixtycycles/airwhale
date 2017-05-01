<?php

require_once 'dbconfig.php';
require_once 'PHPMailerAutoload.php';

class USER
{

    private $conn;

    public function __construct()
    {
        $database = new Database();
        $db = $database->dbConnection();
        $this->conn = $db;
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }

    public function lasdID()
    {
        $stmt = $this->conn->lastInsertId();
        return $stmt;
    }

    public function register($uname, $email, $upass, $code)
    {
        try {
            $password = sha1($upass);
            $stmt = $this->conn->prepare("INSERT INTO tbl_users(userName,userEmail,userPass,tokenCode) 
                                                VALUES(:user_name, :user_mail, :user_pass, :active_code)");
            $stmt->bindparam(":user_name", $uname);
            $stmt->bindparam(":user_mail", $email);
            $stmt->bindparam(":user_pass", $password);
            $stmt->bindparam(":active_code", $code);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

    public function login($email, $upass)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tbl_users WHERE userEmail=:email_id");
            $stmt->execute(array(":email_id" => $email));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 1) {
                if ($userRow['userStatus'] == "A") {
                    $_SESSION['userSession'] = $userRow['userID'];
                    $_SESSION['isAdmin'] = true;

                    return true;
                } elseif ($userRow['userStatus'] == "Y") {
                    if ($userRow['userPass'] == sha1($upass) && $userRow['userStatus'] == "Y") {
                        $_SESSION['userSession'] = $userRow['userID'];
                        return true;
                    } else {
                        header("Location: index.php?error");
                        exit;
                    }
                } else {
                    header("Location: index.php?inactive");
                    exit;
                }
            } else {
                header("Location: index.php?error");
                exit;
            }
        } catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }


    public function is_logged_in()
    {
        if (isset($_SESSION['userSession'])) {
            return true;
        }
    }

    public function is_admin()
    {
        if ($_SESSION['isAdmin'] != 0) {
            return true;
        }
    }

    public function redirect($url)
    {
        header("Location: $url");
    }

    public function logout()
    {
        session_destroy();
        $_SESSION['userSession'] = false;
        $_SESSION['isAdmin'] = false;

    }

    function send_mail($email, $message, $subject)
    {

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = "smtp.gmail.com";
        $mail->Port = 587;
        $mail->AddAddress($email);
        $mail->Username = "rodtest1985@gmail.com";
        $mail->Password = "955615go";
        $mail->SetFrom('rodtest1985@gmail.com', 'Citizen Reporter Robot');
        $mail->AddReplyTo("rodtest1985@gmail.com", " Town Office");
        $mail->Subject = $subject;
        $mail->MsgHTML($message);
        $mail->Send();
    }
}
