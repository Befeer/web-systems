<?php 
    include "../backEnd/devcomController.php";

    $connect = new Controller();
    $connect ->connection();
    $events = $connect-> readDevcom();

    require "../backEnd/AdminController.php";
    $admin = new AdminController();
    $admin->officeCheck("DevCom");
    $homePage = $admin->HomePage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="eventscripts.js" defer></script>
    <title>DevCom</title>
</head>
<body>
    <div>
        <form action="../backEnd/devcomController.php?method_finder=create" method="post" onsubmit="return validateEvent();">
            <div>
                <label for="event_title">Event Title</label>
                <input type="text" id="event_title" name="event_title" required>
            </div>
            <br>
            <div>
                <label for="event_description">Description</label>
                <textarea id="event_description" name="event_description" rows="4"></textarea>
            </div>
            <br>
            <div>
                <label for="event_start">Start Time</label>
                <input type="datetime-local" id="event_start" name="event_start" required>
            </div>
            <br>
            <div>
                <label for="event_end">End Time</label>
                <input type="datetime-local" id="event_end" name="event_end" required>
            </div>
            <br>
            <div>
                <label for="event_location">Location</label>
                <input type="text" id="event_location" name="event_location">
            </div>
            <br>
            <button type="submit" id="submit_button" name="submit_button">Create Event</button>
            <br>
            <br>
            <button onclick="window.location.href='<?= $homePage ?>'">Home</button>
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
                    <td>
                        <form action="../backEnd/devcomController.php?method_finder=edit" method="post">
                            <input type="hidden" name="method_finder" value="edit">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($event['id'])?>">
                            <button type="submit">edit</button>
                        </form>
                        <form action="../backEnd/devcomController.php?method_finder=delete" method="get" style="display: inline;">
                            <input type="hidden" name="method_finder" value="delete">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($event['id'])?>">
                            <button type="submit" onclick="return confirm('Are you sure you want to delete')">delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>