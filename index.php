<?php

$error = "";
$flag = "";

$db = mysqli_connect("localhost", "root", "", "to-do-list");
if (mysqli_connect_errno()) {
    die(mysqli_connect_error());
}

if($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["task"])) {
        $task = $_POST["task"];
        if(!empty($task)) {
            $isExist = mysqli_query($db, "SELECT * FROM list WHERE task='$task'");
            if(mysqli_num_rows($isExist) === 0) {
                mysqli_query($db, "INSERT IGNORE INTO list (task, status) VALUES ('$task', FALSE)");
                header("Location: index.php");
            } else {
                $error = "This task has already exist! ";
                $flag = "exist";
            }
        } else {
            $error = "You should do something...";
        }
    }
}

if($_SERVER["REQUEST_METHOD"] === "GET") {
    if (isset($_GET["task"])) {
        $task = $_GET["task"];
        $id = $_GET["id"];
        mysqli_query($db, "DELETE FROM list WHERE task='$task' and id='$id'");
        header("Location: index.php");
    }
    elseif (isset($_GET["id"])) {
        $id = $_GET["id"];
        mysqli_query($db, "UPDATE list SET status=TRUE WHERE id='$id'");
        header("Location: index.php");
    }
}

$tasks = mysqli_query($db, "SELECT * FROM list");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/style.css">
    <title>ToDo-list</title>
</head>
<body>
<div class="container">
    <header>
        <h1>ToDo-list application</h1>
    </header>
    <div class="wrapper">
        <div class="mssg alert">
            <p>Just write your tasks for this day<br>in the form below<br><span><?php echo $error;?></span></p>
        </div>
        <form method="post" action="index.php">
            <div class="input_form">
                <input id="input_task" class="input_task" name="task" type="text">
                <button class="add_btn" type="submit" name="submit">Add</button>
            </div>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Task</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $i = 1;
                    while ($row = mysqli_fetch_array($tasks)):?>
                    <tr>
                        <td><?php echo $i++?></td>
                        <td><?php echo $row['task']?></td>
                        <td>
                            <a href="index.php?id=<?php echo $row['id']?>">
                                <?php echo $row['status'] ? "Done" : "Undone"?>
                            </a>
                        </td>
                        <td class="del-btn">
                            <button type="button">
                                <a href="index.php?id=<?php echo $row['id']?>&task=<?php echo $row['task']?>">X</a>
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>