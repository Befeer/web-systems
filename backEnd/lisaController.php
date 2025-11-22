<?php
    class Controller {
        private $connection;

        public function __construct() {
            $this->connection = $this->connection();
        }

        public function readLisa() {
            $sql = "SELECT * FROM eventlist WHERE event_author = 'LISA'";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function connection() {
            // connection logic
            if(!defined('DB_HOST')) {
                define('DB_HOST', 'localhost');
            }

            if(!defined('DB_USER')) {
                define('DB_USER', 'root');
            }

            if(!defined('DB_PASS')) {
                define('DB_PASS', '');
            }

            if(!defined('DB_NAME')) {
                define('DB_NAME', 'event_registration_db');
            }

            $this-> connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if ($this->connection->connect_error) {
                die("Connection Failed: " .$this->connection->connect_error);
            }

            echo "<script> console.log('There was a connection'); </script>";

            return $this->connection;
        }

        public function actionReader() {
            if(isset($_GET['method_finder'])) {
                $action = $_GET['method_finder'];
                if($action === 'create') {
                    $this->create();
                }
                else if ($action === 'delete') {
                    $this->delete();
                }
                else if ($action === 'edit') {
                    $this->edit();
                }
                else if ($action === 'update') {
                    $this->update();
                }
            }
        }

        public function delete() {
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $stmt = $this->connection->prepare("DELETE FROM eventlist WHERE id=? AND event_author='LISA'");
                if($stmt) {
                    $stmt->bind_param("i", $id);
                    if ($stmt->execute()) {
                        $location = "../frontEnd/lisa.php";
                        header("Location:$location");
                    }
                }
            }
        }

        public function edit() {
            if(isset($_POST['id'])) {
                $id = (int) $_POST['id'];
                // redirect to lisa.php with id so modal opens
                header("Location: ../frontEnd/lisa.php?id=$id");
                exit();
            }
        }

        public function update() {
            $id = $_POST['id'];
            $event_title = $_POST['event_title'];
            $event_description = $_POST['event_description'];
            $event_start = $_POST['event_start'];
            $event_end = $_POST['event_end'];
            $event_location = $_POST['event_location'];
            $event_status = $_POST['event_status'];
            $event_author = "LISA";

            if (strtotime($event_end) < strtotime($event_start)) {
                $location = "../frontEnd/lisa.php?error=invalid_date&id=$id"; // id if updating
                header("Location: $location");
                exit();
            }

            if($id) {
                $sql = "UPDATE eventlist SET event_title=?, 
                                            event_description=?, 
                                            event_start=?, 
                                            event_end=?, 
                                            event_location=?, 
                                            event_status=?, 
                                            event_author=? 
                                            WHERE id=? AND event_author='LISA'";
                $stmt = $this->connection->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param(
                        "sssssssi",
                        $event_title,
                        $event_description,
                        $event_start,
                        $event_end,
                        $event_location,
                        $event_status,
                        $event_author,
                        $id
                    );

                    $stmt->execute();

                    if($stmt) {
                        $location = "../frontEnd/lisa.php";
                        header("Location: $location");
                    }
                }
            }
        }

        public function update_take_data ($id) {
            $query = "SELECT * FROM eventlist WHERE id=? AND event_author='LISA'";
            $stmt = $this->connection->prepare($query);
            if ($stmt) {
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $result = $stmt->get_result();
                    return $result->fetch_assoc();
                }
                else {
                    echo "There is an error in the execution.";
                }
            }
            else {
                echo "The statement is not correct.";
            }
        }

        public function create() {
            $event_title = $_POST['event_title'];
            $event_description = $_POST['event_description'];
            $event_start = $_POST['event_start'];
            $event_end = $_POST['event_end'];
            $event_location = $_POST['event_location'];
            $event_author = "LISA";

            if (strtotime($event_end) < strtotime($event_start)) {
                return "Error: Event end date cannot be earlier than event start date.";
            }

            $sql = "INSERT INTO eventlist (event_title, 
                                            event_description, 
                                            event_start, 
                                            event_end, 
                                            event_location, 
                                            event_author)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_param("ssssss", $event_title, 
                                        $event_description, 
                                        $event_start, 
                                        $event_end, 
                                        $event_location, 
                                        $event_author);

            if ($stmt->execute()) {
                $location = "../frontEnd/lisa.php";
                header("Location:$location?success=1");
                exit();
            }
            else {
                echo "Error creation use: " .$stmt->error;
            }
        }

        public function sortAndfilter ($events, $sortType, $filterType) {
            if ($filterType == "all" && $sortType == "nearest") {
                $sql = "SELECT * FROM eventlist WHERE event_author = 'LISA'";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                $events = $result->fetch_all(MYSQLI_ASSOC);
                usort($events, function($a, $b) {
                    return strtotime($a['event_start']) - strtotime($b['event_start']);
                });
            return $events;
            }
            
            if ($filterType == "pending" && $sortType == "nearest") {
                $sql = "SELECT * FROM eventlist WHERE event_status = 'pending' AND event_author = 'LISA'";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                $events = $result->fetch_all(MYSQLI_ASSOC);
                usort($events, function($a, $b) {
                    return strtotime($a['event_start']) - strtotime($b['event_start']);
                });
            return $events;
            }

            if ($filterType == "completed" && $sortType == "nearest") {
                $sql = "SELECT * FROM eventlist WHERE event_status = 'completed' AND event_author = 'LISA'";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                $events = $result->fetch_all(MYSQLI_ASSOC);
                usort($events, function($a, $b) {
                    return strtotime($a['event_start']) - strtotime($b['event_start']);
                });
            return $events;
            }

            if ($filterType == "cancelled" && $sortType == "nearest") {
                $sql = "SELECT * FROM eventlist WHERE event_status = 'cancelled' AND event_author = 'LISA'";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                $events = $result->fetch_all(MYSQLI_ASSOC);
                usort($events, function($a, $b) {
                    return strtotime($a['event_start']) - strtotime($b['event_start']);
                });
            return $events;
            }

            if ($filterType == "all" && $sortType == "farthest") {
                $sql = "SELECT * FROM eventlist WHERE event_author = 'LISA'";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                $events = $result->fetch_all(MYSQLI_ASSOC);
                usort($events, function($a, $b) {
                    return strtotime($b['event_start']) - strtotime($a['event_start']);
                });
            return $events;
            }

            if ($filterType == "pending" && $sortType == "farthest") {
                $sql = "SELECT * FROM eventlist WHERE event_status = 'pending' AND event_author = 'LISA'";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                $events = $result->fetch_all(MYSQLI_ASSOC);
                usort($events, function($a, $b) {
                    return strtotime($b['event_start']) - strtotime($a['event_start']);
                });
            return $events;
            }

            if ($filterType == "completed" && $sortType == "farthest") {
                $sql = "SELECT * FROM eventlist WHERE event_status = 'completed' AND event_author = 'LISA'";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                $events = $result->fetch_all(MYSQLI_ASSOC);
                usort($events, function($a, $b) {
                    return strtotime($b['event_start']) - strtotime($a['event_start']);
                });
            return $events;
            }
            if ($filterType == "cancelled" && $sortType == "farthest") {
                $sql = "SELECT * FROM eventlist WHERE event_status = 'cancelled' AND event_author = 'LISA'";
                $stmt = $this->connection->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                $events = $result->fetch_all(MYSQLI_ASSOC);
                usort($events, function($a, $b) {
                    return strtotime($b['event_start']) - strtotime($a['event_start']);
                });
            return $events;
            }
        }
    }

    $controller = new Controller();
    $controller->actionReader();
?>
