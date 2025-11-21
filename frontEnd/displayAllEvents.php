<?php
include "../backEnd/adminController.php";
$conn = new AdminController();
$conn->connection();

$sort = $_GET['sort'] ?? "";
$filter = $_GET['filter'] ?? "";
$events = $conn->readAll();

if ($sort) {
    $events = $conn->sortEvents($events, $sort);
}

if ($filter) {
    $events = $conn->filterEvents($events, $filter);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="eventscripts.js" defer></script>
    <title>Super Admin Dashboard</title>
</head>
<body>
    <div>
        <form method="GET" id="sortForm">
            <select name="sort" onchange="document.getElementById('sortForm').submit()">
                <option disabled selected>-- Sort Events --</option>
                <option value="nearest" <?= ($sort=="nearest"?"selected":"") ?>>Nearest Events</option>
                <option value="farthest" <?= ($sort=="farthest"?"selected":"") ?>>Farthest Events</option>
            </select>
        </form>
        <form method="GET" id="filterForm">
            <select select name="filter" onchange="document.getElementById('filterForm').submit()">
                <option disabled selected>--Filter Events --</option>
                <option value="all" <?= ($filter=="all"?"selected":"") ?>>All</option>
                <option value="pending" <?= ($filter=="pending"?"selected":"") ?>>Pending</option>
                <option value="completed" <?= ($filter=="completed"?"selected":"") ?>>Completed</option>
                <option value="cancelled" <?= ($filter=="cancelled"?"selected":"") ?>>Cancelled</option>
            </select>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>EVENT TITLE</th>
                    <th>DESCRIPTION</th>
                    <th>START</th>
                    <th>END</th>
                    <th>LOCATION</th>
                    <th>STATUS</th>
                    <th>CREATED AT</th>
                    <th>AUTHOR</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach($events as $event):
                ?>
                <tr>
                    <td> <?=htmlspecialchars($event['id'])?> </td>
                    <td> <?=htmlspecialchars($event['event_title'])?> </td>
                    <td> <?=htmlspecialchars($event['event_description'])?> </td>
                    <td><?= htmlspecialchars(date("M d, Y h:i A", strtotime($event['event_start']))) ?></td>
                    <td><?= htmlspecialchars(date("M d, Y h:i A", strtotime($event['event_end']))) ?></td>
                    <td> <?=htmlspecialchars($event['event_location'])?> </td>
                    <td> <?=htmlspecialchars($event['event_status'])?> </td>
                    <td> <?= htmlspecialchars(date("M d, Y h:i A", strtotime($event['event_created_at']))) ?> </td>
                    <td> <?=htmlspecialchars($event['event_author'])?> </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <br>
    <button onclick="window.location.href='AdminDashboard.php'">Back</button>

</body>
</html>
