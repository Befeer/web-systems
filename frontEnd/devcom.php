<?php 
   include "../backEnd/devcomController.php";

    // Initialize Controller
    $connect = new Controller();
    $connect->connection();

    // Check if an ID was provided (from edit redirect)
    $use_get = null;
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = (int) $_GET['id']; // convert to int safely
        $use_get = $connect->update_take_data($id);
    }
    $events = $connect-> readDevcom();

    // Get sort and filter from GET request
    $sort = $_GET['sort'] ?? "nearest";
    $filter = $_GET['filter'] ?? "all";

    if ($sort && $filter) {
        $events = $connect->sortAndfilter($events, $sort, $filter);
    }

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
    <!-- pop up add event -->
    <div id="eventAddModal" style="display: none">
        <form action="../backEnd/devcomController.php?method_finder=create" method="post" onsubmit="return validateEvent();">
        <h3>Create Event</h3>
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
            <button type="button" onclick="closeModal()">Cancel</button>
        </form>
    </div>
    <!-- until dito -->

    <!-- pop up edit event -->
    <?php if ($use_get): ?>
    <div id="eventEditModal" style="display: flex">
        <form action="../backEnd/devcomController.php?method_finder=update" method="post">
        <h4>This is the update</h4>
            <div>
                <input type="hidden" name="id" value="<?=htmlspecialchars($use_get['id']) ?>">
            </div>
            <br>
            <div>
                <label for="event_title">Event Title</label>
                <input type="text" name="event_title" value="<?= htmlspecialchars($use_get['event_title']) ?>" required>
            </div>
            <br>
            <div>
                <label for="event_description">Description</label>
                <textarea name="event_description" rows="4" cols="40"><?= htmlspecialchars($use_get['event_description']) ?></textarea>
            </div>
            <br>
            <div>
                <label for="event_start">Start Time</label>
                <input type="datetime-local" name="event_start" value="<?= htmlspecialchars($use_get['event_start']) ?>" required>
            </div>
            <br>
            <div>
                <label for="event_end">End Time</label>
                <input type="datetime-local" name="event_end" value="<?= htmlspecialchars($use_get['event_end']) ?>" required>
                <?php if (isset($_GET['error']) && $_GET['error'] == 'invalid_date'): ?>
                    <p style="color:red; font-weight:bold;">    
                        Event end date cannot be earlier than event start date.
                    </p>
                <?php endif; ?>
            </div>
            <br>
            <div>
                <label for="event_location">Location</label>
                <input type="text" name="event_location" value="<?= htmlspecialchars($use_get['event_location']) ?>">
            </div>
            <br>
            <div>
                <label for="event_status">Status</label>
                <select name="event_status" required>
                    <option value="Pending" <?= ($use_get['event_status'] == 'pending') ? 'selected' : '' ?>>Pending</option>
                    <option value="Completed" <?= ($use_get['event_status'] == 'completed') ? 'selected' : '' ?>>Completed</option>
                    <option value="Cancelled" <?= ($use_get['event_status'] == 'cancelled') ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>
            <br>
            <button type="submit">Update Event</button>
            <button type="button" onclick="closeModal()">Cancel</button>
        </form>
    </div>
    <?php endif; ?>
    <!-- until dito -->


    <div>
        <!-- Button to open modal -->
        <button onclick="openAddModal()">Add Event</button>

        <!-- button to return to dashboard -->
        <button onclick="window.location.href='<?= $homePage ?>'">Home</button>
        <br>
        <br>
        <form method="GET" id="eventForm">
            <select name="sort" onchange="document.getElementById('eventForm').submit()">
                <option disabled selected>-- Sort Events --</option>
                <option value="nearest" <?= ($sort=="nearest"?"selected":"") ?>>Nearest Events</option>
                <option value="farthest" <?= ($sort=="farthest"?"selected":"") ?>>Farthest Events</option>
            </select>
            <select select name="filter" onchange="document.getElementById('eventForm').submit()">
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
