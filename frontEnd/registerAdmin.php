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
        <form action="../backEnd/AdminController.php?method_finder=register_admin" method="POST" onsubmit="return validatePassword();">
            <div>
                <label for="fname">First Name</label>
                <input type="text" name="fname" placeholder="First Name" required>
            </div>
            <br>
            <div>
                <label for="lname">First Name</label>
                <input type="text" name="lname" placeholder="Last Name" required>
            </div>
            <br>
            <div>
                <label for="Username">Username</label>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <br>
            <div>
                <label for="Email">Email</label>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <br>
            <div>
                <label for="office">Office</label>
                <select name="office" required>
                    <option value="BYTe">BYTe</option>
                    <option value="LISA">LISA</option>
                    <option value="DevCom">DevCom</option>
                    <option value="Hyperlink">Hyperlink</option>
                    <option value="SG">SG</option>
                </select>
            </div>
            <br>
            <div>
                <label for="is_ultimate_admin">Is Admin</label>
                <input type="radio" name="is_ultimate_admin" value="1"> Admin
                <input type="radio" name="is_ultimate_admin" value="0" checked> User          
            </div>
            <br>
            <div>
                <label for="admin_password">Password</label>
                <input type="password" name="admin_password" id="admin_password" placeholder="Password" required>
            </div>
            <br>
            <div>
                <label for="admin_confirm">Confirm password</label>
                <input type="password" name="admin_confirm" id="admin_confirm" placeholder="Confirm password" required>
                <p id="error" style="display:none; color:red; font-weight:bold;">Password do not match</p>
            </div>
            <br>
            <div>
                <button>Register</button>
            </div>
        </form>
    </div>
    <br>
    <button onclick="window.location.href='AdminDashboard.php'">Back</button>
</body>
</html>
