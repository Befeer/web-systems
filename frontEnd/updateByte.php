<?php 
    $id = $_GET['id'];
    $convertions =(int) $id;
    include "../backEnd/byteController.php";

    $controller = new Controller();
    $use_get = $controller->update_take_data($convertions);
    $events = $controller-> readByte();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="eventscripts.js" defer></script>
    <title>Update</title>
</head>
<body>
    <h1>This is the update</h1>
    <br>
    <div>
        <form action="../backEnd/byteController.php?method_finder=update" method="post">
            <div>
                <input type="hidden" name="id" value="<?=htmlspecialchars($use_get['id']) ?>">
            </div>
            <br>
            <div>
                <input type="text" name="event_title" value="<?= htmlspecialchars($use_get['event_title']) ?>" required>
            </div>
            <br>
            <div>
                <textarea name="event_description" rows="4" cols="40"><?= htmlspecialchars($use_get['event_description']) ?></textarea>
            </div>
            <br>
            <div>
                <input type="datetime-local" name="event_start" value="<?= htmlspecialchars($use_get['event_start']) ?>" required>
            </div>
            <br>
            <div>
                <input type="datetime-local" name="event_end" value="<?= htmlspecialchars($use_get['event_end']) ?>" required>
            </div>
            <br>
            <div>
                <input type="text" name="event_location" value="<?= htmlspecialchars($use_get['event_location']) ?>">
            </div>
            <br>
            <div>
                <select name="event_status" required>
                    <option value="Pending" <?= ($use_get['event_status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="Completed" <?= ($use_get['event_status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
                    <option value="Cancelled" <?= ($use_get['event_status'] == 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
            <br>
            <button type="submit">Update Event</button>
            <br>
            <br>
            <button onclick="window.location.href='byte.php'">Cancel</button>
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
                        <form action="../backEnd/byteController.php?method_finder=edit" method="post">
                            <input type="hidden" name="method_finder" value="edit">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($event['id'])?>">
                            <button type="submit">edit</button>
                        </form>
                        <form action="../backEnd/byteController.php?method_finder=delete" method="get" style="display: inline;">
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