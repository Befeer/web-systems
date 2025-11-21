<?php 
session_start();

    class AdminController {
        private $connection;

        public function __construct() {
            $this->connection = $this->connection();
        }

        public function readAdmin() {
            $sql = "SELECT * FROM admins";

            $stmt = $this->connection->prepare($sql);
            $stmt->execute();

            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        public function readAll() {
            $sql = "SELECT * FROM eventlist";
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

        public function register_admin() {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                
                $fname = $_POST['fname'];
                $lname = $_POST['lname'];
                $email = $_POST['email'];
                $username = $_POST['username'];
                $password = $_POST['admin_password'];
                $office = $_POST['office'];
                $is_ultimate_admin = $_POST['is_ultimate_admin'];
                

                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert SQL
                $sql = "INSERT INTO admins (fname, lname, email, username, admin_password, office, is_ultimate_admin) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

                $stmt = $this->connection->prepare($sql);
                $stmt->bind_param("sssssss", $fname, $lname, $email, $username,$hashed_password,$office, $is_ultimate_admin);

                if ($stmt->execute()) {
                    echo "Admin registered successfully!";
                    $location = "../frontEnd/AdminDashboard.php";
                    header("Location: $location");

                } else {
                    echo "Error: " . $stmt->error;
                }
            }
        }

        public function login_admin() {
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $username = $_POST['username'];
                $password = $_POST['admin_password'];

                // Fetch admin by username
                $sql = "SELECT * FROM admins WHERE username = ?";
                $stmt = $this->connection->prepare($sql);
                $stmt->bind_param("s", $username);
                $stmt->execute();

                $result = $stmt->get_result();
                $admin = $result->fetch_assoc();

                if ($admin && password_verify($password, $admin['admin_password'])) {
                    $_SESSION['admin_id'] = $admin['admin_id'];
                    $_SESSION['username'] = $admin['username'];
                    $_SESSION['office'] = $admin['office'];
                    $_SESSION['is_ultimate_admin'] = $admin['is_ultimate_admin'];

                    // Redirect based on ultimate admin status
                    if ($admin['is_ultimate_admin'] == 1) {
                        $location = "../frontEnd/AdminDashboard.php"; // ultimate admin page
                    } 
                    else {
                        $location = "../frontEnd/UserDashboard.php"; // regular admin page
                    }

                    header("Location: $location");
                    exit;
                }
                else {
                    echo "<script>
                            alert('Invalid username or password!');
                            window.location.href='../frontEnd/loginPage.php';
                        </script>";
                    exit;
                }
            }
        }

        public function logout_admin() {
            session_unset();    // Remove all session variables
            session_destroy();  // Destroy the session
            echo "<script>
                    alert('You have been logged out.');
                    window.location.href='../frontEnd/calendar.php';
                </script>";
            exit;
        }

        public function officeCheck ($officeRequired) {
            if (!isset($_SESSION['admin_id'])) {
                echo "<script>
                        alert('You must log in first.');
                        window.location.href='../frontEnd/loginPage.php';
                    </script>";
                exit();
            }

            $isUltimate = $_SESSION['is_ultimate_admin'] ?? 0;
            $office = $_SESSION['office'] ?? "";

            if ($isUltimate == 1) return;
            
            if ($office !== $officeRequired) {
            echo "<script>
                    alert('You are not allowed to access this page!');
                    window.location.href='../frontEnd/UserDashboard.php';
                </script>";
            exit();
            }
        }

        public function HomePage() {
            $isUltimate = $_SESSION['is_ultimate_admin'] ?? 0;

            if ($isUltimate == 1) {
                return "AdminDashboard.php";
            } else {
                return "UserDashboard.php";
            }
        }

        public function sortEvents($events, $sortType) {

            if ($sortType === "nearest") {
                usort($events, function($a, $b) {
                    return strtotime($a['event_start']) <=> strtotime($b['event_start']);
                });
            }

            else if ($sortType === "farthest") {
                usort($events, function($a, $b) {
                    return strtotime($b['event_start']) - strtotime($a['event_start']);
                });
            }

            return $events;
        }

        public function filterEvents($events, $filterStatus) {

            // Allowed statuses
            $allowedStatuses = ["pending", "completed", "cancelled"];

            // Filter events if a valid status is selected
            if (in_array(strtolower($filterStatus), $allowedStatuses)) {
                $events = array_filter($events, function($event) use ($filterStatus) {
                    return strtolower($event['event_status']) === strtolower($filterStatus);
                });
            }

            return $events;
        }

        public function actionReader() {
            if(isset($_GET['method_finder'])) {
                $action = $_GET['method_finder'];
                if($action === 'register_admin') {
                    $this->register_admin();
                }
                else if($action === 'login_admin') {
                    $this->login_admin();
                }
                else if($action === 'logout') {
                    $this->logout_admin();
                } 
            }
        }

    }

$admin = new AdminController();
$admin->actionReader();
?>