<?php 
if(isset($_POST['btnsubmit']))
{
    require_once "config.php";

    $sql = "SELECT * FROM tblaccounts WHERE username = ? AND password = ? AND status = 'ACTIVE'";
   
    if($stmt = mysqli_prepare($link, $sql)) {
       
        mysqli_stmt_bind_param($stmt, "ss", $_POST['txtusername'], $_POST['txtpassword']);
        
        if(mysqli_stmt_execute($stmt)) {
           
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) > 0) {
                
                //echo "Login Successful. ";
                $accounts = mysqli_fetch_array($result, MYSQLI_ASSOC);

                session_start();

                $_SESSION['username'] = $accounts['username'];
                $_SESSION['usertype'] = $accounts['usertype'];

                if ($accounts['usertype'] == 'USER') {
                    header("location: user-account.php");
                } else {
                    header("location: accounts-management.php");
                }
            }
            else {
                $error = "Incorrect login details or account is inactive";
            }
        }
    }
    else {
        $error = "Error on the select statement.";
    }
}
?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" conten t ="width=device-width, initial-scale=1.0">
    <title>Login - AU Technical Support Management System</title>

    <!-- Bootstrap CSS -->
    <link href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel = "stylesheet">
    
    <style>
        body {  
            background: linear-gradient(270deg,rgba(17, 17, 234, 0.88),rgba(239, 7, 7, 0.91));
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .login-container {
            background: rgba(249, 249, 249, 0.92);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="login-container col-md-4">

   <center> <img class = "col-md-3" style = "padding-bottom:25px;" src = "picture/Arellano_University_logo.png"></center> 
    <h3 class = "text-center mb-4">Arellano Account Management System</h3>
    <?php 
        if(!empty($error)) {
            echo"<p class='error'>$error</p>";
        }
    ?>

    <form action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method = "POST">
        <div class = "mb-3">
            <label for = "txtusername" class = "form-label">Username</label>
            <input type = "text" name = "txtusername" placeholder="Enter your Username" id = "txtusername" class = "form-control" required>
        </div>
        <div class = "mb-3">
            <label for = "txtpassword" class = "form-label">Password</label>
            <input type = "password" name = "txtpassword" placeholder="Enter your Password" id = "txtpassword" class = "form-control" required>
        </div>
        <div class = "d-grid">
            <button type = "submit" name = "btnsubmit" class = "btn btn-primary">Login</button>
        </div>
    </form>
</div>

<!-- Bootstrap JS (Optional) -->
<script src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>