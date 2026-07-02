<?php
session_start();

include "db.php";
$limit = 5;

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$start = ($page - 1) * $limit;
if(isset($_GET['search']))
{
    $search = "%" . $_GET['search'] . "%";

    $stmt = $conn->prepare(
    "SELECT * FROM students
     WHERE name LIKE ?
     OR email LIKE ?
     LIMIT ?, ?"
    );

    $stmt->bind_param(
    "ssii",
    $search,
    $search,
    $start,
    $limit
    );

    $stmt->execute();

    $result = $stmt->get_result();
}
else
{
    $stmt = $conn->prepare(
    "SELECT * FROM students
     LIMIT ?, ?"
    );

    $stmt->bind_param(
    "ii",
    $start,
    $limit
    );

    $stmt->execute();

    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students</title>

    <style>
        
        body{
            font-family:Arial;
            background:#f4f4f4;
            padding:30px;
        }

        table{
            width:80%;
            margin:auto;
            border-collapse:collapse;
            background:white;
        }

        th,td{
            padding:15px;
            border:1px solid #ddd;
            text-align:center;
        }

        th{
            background:#1e3a8a;
            color:white;
        }

        a{
            text-decoration:none;
        }
        .header{

background:linear-gradient(135deg,#2563eb,#1e3a8a);

padding:25px;

border-radius:15px;

color:white;

text-align:center;

margin-bottom:25px;

box-shadow:0 5px 20px rgba(0,0,0,.2);

}

.header h1{

margin:0;

font-size:35px;

}

.header p{

font-size:18px;

margin-top:10px;

}
.welcome{
    background:white;
    padding:20px;
    margin-bottom:20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.2);
    text-align:center;
}
.action-bar{
    display:flex;
    justify-content:center;
    gap:20px;
    margin:25px 0;
}

.btn{
    background:#2563eb;
    color:white;
    padding:12px 22px;
    border-radius:8px;
    text-decoration:none;
    font-weight:bold;
    transition:0.3s;
}

.btn:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}
table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 5px 20px rgba(0,0,0,0.2);
}

th{
    background:#2563eb;
    color:white;
    padding:15px;
}

td{
    padding:15px;
    text-align:center;
}

tr:nth-child(even){
    background:#f8fafc;
}

tr:hover{
    background:#dbeafe;
}
footer{
    margin-top:40px;
    text-align:center;
    color:#555;
    font-size:14px;
}
/* Search Section */

.search-container{
    display:flex;
    justify-content:center;
    margin:30px 0;
}

.search-container form{
    width:80%;
    display:flex;
}

.search-input{
    flex:1;
    padding:16px;
    font-size:18px;
    border:2px solid #2563eb;
    border-radius:50px 0 0 50px;
    outline:none;
    box-shadow:0 3px 10px rgba(0,0,0,0.1);
}

.search-input:focus{
    border-color:#1d4ed8;
}

.search-btn{
    background:#2563eb;
    color:white;
    border:none;
    padding:16px 30px;
    font-size:17px;
    border-radius:0 50px 50px 0;
    cursor:pointer;
    transition:0.3s;
}

.search-btn:hover{
    background:#1d4ed8;
}
    </style>
</head>

<body>

<div class="header">

<h1>🎓 Student Authentication System</h1>

<p>Manage Student Records Efficiently</p>

</div>

<!-- Welcome Card Starts Here -->
<div class="welcome">

<h2>👋 Welcome</h2>

<h3><?php echo $_SESSION['email']; ?></h3>

<?php
if($_SESSION['role']=="admin")
{
    echo "<h2 style='color:#16a34a;'>🛡️ Administrator Panel</h2>";
    echo "<p>Manage Student Records Efficiently</p>";
}
else
{
    echo "<h2 style='color:#2563eb;'>🎓 Student Panel</h2>";
    echo "<p>View Student Records</p>";
}
?>

<p>Date: <?php echo date("d-m-Y"); ?></p>

</div>
<!-- Welcome Card Ends Here -->
<div class="search-container">

    <form method="GET">

        <input type="text"
               name="search"
               placeholder="🔍 Search Student by Name or Email..."
               class="search-input">

        <button type="submit" class="search-btn">
            Search
        </button>

    </form>

</div>
<div class="action-bar">
 <?php if($_SESSION['role'] == "admin") { ?>

<a href="add_student.php" class="btn">➕ Add Student</a>

<?php } ?>

<a href="dashboard.php" class="btn">🏠 Dashboard</a>

<a href="logout.php"
   class="btn"
   onclick="return confirm('Are you sure you want to logout?')">
   🚪 Logout
</a>
</div>
<table>

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Edit</th>
    <th>Delete</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)) { ?>

<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['email']; ?></td>
<td>

<?php
if($_SESSION['role']=="admin")
{
?>

<a href="edit_student.php?id=<?php echo $row['id']; ?>">
    Edit
</a>

<?php
}
?>

</td>
<td>

<?php
if($_SESSION['role']=="admin")
{
?>

<a href="delete_student.php?id=<?php echo $row['id']; ?>"
onclick="return confirm('Are you sure you want to delete this student?')">
Delete
</a>

<?php
}
?>

</td>
</tr>

<?php } ?>

</table>
<?php
$total_query = mysqli_query($conn,
    "SELECT COUNT(*) AS total FROM students");

$total_row = mysqli_fetch_assoc($total_query);

$total_pages = ceil($total_row['total'] / $limit);

echo "<center><br>";

for($i=1; $i<=$total_pages; $i++)
{
    echo "<a href='view_students.php?page=$i'
            style='margin:10px;
                   padding:8px 15px;
                   background:#2563eb;
                   color:white;
                   text-decoration:none;
                   border-radius:5px;'>
            $i
          </a>";
}

echo "</center>";
?>
<footer>
    <p>Developed by Ganesh</p>
    <p>ApexPlanet Web Development Internship</p>
</footer>
</body>
</html>