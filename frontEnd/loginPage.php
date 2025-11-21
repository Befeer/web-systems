<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <script src="eventscripts.js" defer></script>
    <title>Admin Log-in</title>
</head>
<body>
    <h1>Admin Log-in</h1>

    <div>
        <form action="../backEnd/AdminController.php?method_finder=login_admin" method="POST">
            <div>
                <i class = "fas fa-user"></i>
                <input type="text" name="username" placeholder="Username">
            </div>
            <div>
                <i class = "fas fa-key"></i>
                <input type="password" name="admin_password" placeholder="Password">
            </div>
            <button>Login</button>
        </form>
    </div>
</body>
</html>