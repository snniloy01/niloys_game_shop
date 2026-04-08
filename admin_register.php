<?php
session_start();
include 'db.php';

// Check if form is submitted
if (isset($_POST['register'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Check if username already exists
        $sql = "SELECT * FROM admins WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $error_message = "Username already exists.";
        } else {
            // Insert new admin into the database
            $sql = "INSERT INTO admins (username, password) VALUES ('$username', '$hashed_password')";
            if ($conn->query($sql)) {
                header('Location: admin_login.php');
                exit();
            } else {
                $error_message = "Error registering user.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&display=swap" rel="stylesheet">
    <style>
        body {
            background: url('https://img.redbull.com/images/c_limit,w_1500,h_1000/f_auto,q_auto/redbullcom/2020/6/5/ctsejxmdtw9inp8zqqqd/red-bull-campus-clutch-valorant-agents') center/cover no-repeat;
            color: #fff;
            height: 100vh;
            font-family: "Parkinsans", sans-serif;
        }

        .register-container {
            background: rgba(0, 0, 0, 0.7);
            border-radius: 15px;
            padding: 30px;
            max-width: 400px;
            margin: auto;
            margin-top: 100px;
        }

        .register-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-custom {
            background: linear-gradient(45deg, #ff7e5f, #feb47b);
            color: white;
            border: none;
            padding: 0.6rem 1.2rem;
            font-size: 0.9rem;
            font-weight: bold;
            transition: background 0.3s ease, transform 0.2s ease;
            border-radius: 20px;
        }

        .btn-custom:hover {
            background: linear-gradient(45deg, #feb47b, #ff7e5f);
            transform: scale(1.05);
        }

        .error-message {
            color: #ff4d4d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>Admin Registration</h2>

        <?php if (isset($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" name="register" class="btn btn-custom w-100">Register</button>
            <div class="register-link">
            <p>Already have an account? <a href="admin_login.php" class="text-white">Login here</a></p> <!-- Register link text -->
        </div>
        </form>
    </div>
</body>
</html>
