<?php
include "db.php";

$id = $_GET['id'];
$stmt = $conn->prepare(
"SELECT * FROM students WHERE id=?"
);

$stmt->bind_param("i", $id);

$stmt->execute();

$result = $stmt->get_result();

$row = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];

  $stmt = $conn->prepare(
"UPDATE students
SET name=?,
    email=?
WHERE id=?"
);

$stmt->bind_param(
"ssi",
$name,
$email,
$id
);

$stmt->execute();

    header("Location: view_students.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<h2>Edit Student</h2>

<form method="POST">

<input type="text"
       name="name"
       value="<?php echo $row['name']; ?>"
       required>

<input type="email"
       name="email"
       value="<?php echo $row['email']; ?>"
       required>

<button name="update">
    Update
</button>

</form>

</div>

</body>
</html>