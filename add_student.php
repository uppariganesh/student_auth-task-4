<?php
include "db.php";

if(isset($_POST['submit']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    if(empty($name))
{
    die("Student Name is required.");
}

if(empty($email))
{
    die("Student Email is required.");
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL))
{
    die("Invalid Email Address.");
}

   $stmt = $conn->prepare(
"INSERT INTO students(name,email)
VALUES(?,?)"
);

$stmt->bind_param(
"ss",
$name,
$email
);

$stmt->execute();

    header("Location: view_students.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">
    <h2>Add Student</h2>

   <form method="POST" onsubmit="return validateForm()">

        <input type="text"
               name="name"
               placeholder="Student Name"
               required>

        <input type="email"
               name="email"
               placeholder="Student Email"
               required>

        <button name="submit">
            Add Student
        </button>

    </form>
</div>
<script>

function validateForm()
{
    let name = document.getElementsByName("name")[0].value;
    let email = document.getElementsByName("email")[0].value;

    if(name=="")
    {
        alert("Please Enter Student Name");
        return false;
    }

    if(email=="")
    {
        alert("Please Enter Student Email");
        return false;
    }

    return true;
}

</script>
</body>
</html>