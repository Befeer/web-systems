<?php
    include "../backEnd/adminController.php";
    $conn = new AdminController();
    $conn -> connection();
    $users = $conn-> readAdmin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>EVENT SCHEDULER</h1>
    <div>
        <div class="byte-tab">
            <a href="byte.php">BYTe</a>
        </div>
        <div class="lisa-tab">
            <a href="lisa.php">LISA</a>
        </div>
        <div class="devcom-tab">
            <a href="devcom.php">DevCom</a>
        </div>
        <div class="hyperlink-tab">
            <a href="hyperlink.php">Hyperlink</a>
        </div>
        <div class="sg-tab">
            <a href="sg.php">SG</a>
        </div>
    </div>
    <br>
    <div>
        <form action="../backEnd/adminController.php?method_finder=logout" method="POST" style="display:inline;">
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>