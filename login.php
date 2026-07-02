<?php
session_start();
include "db.php";

if(isset($_POST['login']))
{
    $email = $_POST['email'];
    $password = $_POST['password'];
    if(empty($email))
{
    die("Email is required.");
}

if(empty($password))
{
    die("Password is required.");
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL))
{
    die("Invalid Email Address.");
}
    $stmt = $conn->prepare(
"SELECT * FROM users WHERE email=?"
);

$stmt->bind_param("s",$email);

$stmt->execute();

$result = $stmt->get_result();

  if(mysqli_num_rows($result)>0)
{
    $row = mysqli_fetch_assoc($result);
if($password == $row['password'])
{
    $_SESSION['email'] = $email;
    $_SESSION['role'] = $row['role'];

    header("Location: dashboard.php");
    exit();
}
    else
    {
        echo "<script>alert('Wrong Password');</script>";
    }
}
    else
{
    echo "<script>alert('Email Not Found');</script>";
}
    
    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">
    <h2>User Login</h2>

  <form method="POST" onsubmit="return validateForm()">
        <input type="email"
               name="email"
               placeholder="Email"
               required>

        <input type="password"
               name="password"
               placeholder="Password"
               required>

        <button name="login">
            Login
        </button>

        <p>
            New User?
            <a href="register.php">Register</a>
        </p>

    </form>

</div>
<script>

function validateForm()
{
    let email = document.getElementsByName("email")[0].value;
    let password = document.getElementsByName("password")[0].value;

    if(email=="")
    {
        alert("Please Enter Email");
        return false;
    }

    if(password=="")
    {
        alert("Please Enter Password");
        return false;
    }

    return true;
}

</script>
</body>
</html>