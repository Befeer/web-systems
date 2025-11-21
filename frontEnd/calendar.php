<?php
// Set timezone
date_default_timezone_set('Asia/Manila');

// Get current month and year from URL or use today
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Adjust for overflow months (e.g. month 0 or 13)
if ($month < 1) {
    $month = 12;
    $year--;
}
elseif ($month > 12) {
    $month = 1;
    $year++;
}

// Get first day of the month
$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
$daysInMonth = date('t', $firstDayOfMonth);
$startDay = date('w', $firstDayOfMonth); // 0 = Sunday, 6 = Saturday

// Previous and next month for navigation
$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth < 1) {
    $prevMonth = 12;
    $prevYear--;
}

$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
}


$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth > 12) {
    $nextMonth = 1;
    $nextYear++;
}

// Month names
$months = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Homepage</title>
    <link rel="stylesheet" href="styling.css">
    <script src="eventscripts.js" defer></script>
</head>
<body>

<h1 id="head"> EVENT SCHEDULER </h1>
<br>
<!-- Admin Login Button -->
<a href="loginPage.php" class="admin-login-btn">Admin Login</a>
<br>
<div class="calendar">
    <div class="header">
        <a href="?month=<?= $prevMonth ?>&year=<?= $prevYear ?>"><</a>
        <div class="current-date"><?= $months[$month - 1] . " " . $year ?></div>
        <a href="?month=<?= $nextMonth ?>&year=<?= $nextYear ?>">></a>
    </div>

    <table>
        <tr class="week">
            <th>Sun</th>
            <th>Mon</th>
            <th>Tue</th>
            <th>Wed</th>
            <th>Thu</th>
            <th>Fri</th>
            <th>Sat</th>
        </tr>
        <tr>
            <?php
            // --- Previous month's last few days ---
            $prevMonth = $month - 1;
            $prevYear = $year;
            if ($prevMonth < 1) {
                $prevMonth = 12;
                $prevYear--;
            }
            $daysInPrevMonth = cal_days_in_month(CAL_GREGORIAN, $prevMonth, $prevYear);

            if ($startDay > 0) {
                for ($i = $startDay; $i > 0; $i--) {
                    echo "<td class='inactive'>" . ($daysInPrevMonth - $i + 1) . "</td>";
                }
            }

            $dayCount = $startDay;

            // --- Current month’s days ---
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $today = ($day == date('j') && $month == date('n') && $year == date('Y')) ? "today" : "";
               //echo "<td class='$today'>$day</td>";
                echo "<td class='day $today' data-date='{$year}-{$month}-{$day}'>$day</td>";
                $dayCount++;
                if ($dayCount % 7 == 0 && $day != $daysInMonth) {
                    echo "</tr><tr>";
                }
            }

            // --- Next month’s first few days ---
            $nextDay = 1;
            while ($dayCount % 7 != 0) {
                echo "<td class='inactive'>" . $nextDay++ . "</td>";
                $dayCount++;
            }
            ?>  
        </tr>
    </table>
</div>

</body>
    <!-- Popup Modal HTML -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <h2>Event Details</h2>
            <p>Selected date: <span id="modal-date">--</span></p>               
            <ul>
                <li>Example 1</li>
                <li>Example 2</li>
            </ul>
            <button class="modal-close">Close</button>
        </div>
    </div>
   
</html>
