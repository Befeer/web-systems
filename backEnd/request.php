<?php 
    if (isset($_POST['submit_button'])) {
        $event_title = $_POST['event_title'];
        $event_description = $_POST['event_description'];
        $event_start = $_POST['event_start'];
        $event_end = $_POST['event_end'];
        $event_location = $_POST['event_location'];
        $event_status = $_POST['event_status'];
        $event_author = $_POST['event_creator'];
    }
?>