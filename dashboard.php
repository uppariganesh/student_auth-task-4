
<?php
session_start();
echo $_SESSION['role'];
include "db.php";

$sql = "SELECT COUNT(*) AS total FROM students";
$result = mysqli_query($conn,$sql);
$row = mysqli_fetch_assoc($result);

if(!isset($_SESSION['email']))
{
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <style>

        body{
            margin:0;
            padding:0;
            font-family:Arial, sans-serif;
            background:linear-gradient(135deg,#141e30,#243b55);
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .container{
            width:400px;
            padding:40px;
            background:rgba(255,255,255,0.1);
            backdrop-filter:blur(10px);
            border-radius:20px;
            text-align:center;
            box-shadow:0 8px 32px rgba(0,0,0,0.3);
        }

        h1{
            color:white;
        }

        p{
            color:#ddd;
            margin-bottom:30px;
        }

        a{
            display:block;
            margin:15px 0;
            padding:15px;
            border-radius:10px;
            text-decoration:none;
            color:white;
            font-size:18px;
            transition:0.3s;
        }

        .add{
            background:#2563eb;
        }

        .view{
            background:#16a34a;
        }

        .logout{
            background:#dc2626;
        }

       a:hover{
    transform:scale(1.08);
    transition:0.3s;
    box-shadow:0 0 15px rgba(255,255,255,0.4);
}
    </style>

</head>

<body>

<div class="container">

   <h1>Student Management System</h1>
<h2>👋 Welcome</h2>

<h3><?php echo $_SESSION['email']; ?></h3>

<?php
if($_SESSION['role']=="admin")
{
    echo "<h3 style='color:#00ff99;'>🛡️ Administrator Dashboard</h3>";
}
else
{
    echo "<h3 style='color:#ffd700;'>🎓 Student Dashboard</h3>";
}
?>
    <h3>Total Students: <?php echo $row['total']; ?></h3>
    <p>Date: <?php echo date("d-m-Y"); ?></p>


      

   <?php if($_SESSION['role']=="admin") { ?>

<a class="add" href="add_student.php">
    📚 Add Student
</a>

<?php } ?>

<a class="view" href="view_students.php">
    👨‍🎓 View Students
</a>

<a href="logout.php"
   class="logout"
   onclick="return confirm('Are you sure you want to logout?')">
   Logout
</a>
</div>

</body>
</html>