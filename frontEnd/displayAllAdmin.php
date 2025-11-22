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
    <title>Super Admin Dashboard</title>
</head>
<body>
    <table>
        <thead>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Office</th>
            <th>Is Admin</th>
            <th>Created at</th>
        </thead>
        <tbody>
            <?php 

                foreach($users as $user):
            ?>
            <tr> 
                <td><?= htmlspecialchars($user['fname']) ?> </td>
                <td><?= htmlspecialchars($user['lname']) ?> </td>
                <td><?= htmlspecialchars($user['username']) ?> </td>
                <td><?= htmlspecialchars($user['email']) ?> </td>
                <td><?= htmlspecialchars($user['office']) ?> </td>
                <td><?= $user['is_ultimate_admin'] == 1 ? 'Yes' : 'No' ?> </td>
                <td><?= htmlspecialchars($user['admin_created_at']) ?> </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <br>
    <button onclick="window.location.href='AdminDashboard.php'">Back</button>
</body>

</html>
