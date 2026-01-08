<?php
session_start();
require_once __DIR__ . "/json_db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $data = readData();
    $users = $data['users'];

    $foundUser = null;

    foreach ($users as $user) {
        if ($user['email'] === $email) {
            $foundUser = $user;
            break;
        }
    }

    if ($foundUser) {
        if (password_verify($password, $foundUser['password'])) {
            $_SESSION['user_id'] = $foundUser['id'];
            $_SESSION['user_name'] = $foundUser['name'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "❌ Wrong password!";
        }
    } else {
        $error = "❌ No user found with this email!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8bbd0, #e1bee7);
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border-radius: 20px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .btn-pink {
            background-color: #d81b60;
            color: white;
        }

        .btn-pink:hover {
            background-color: #ec407a;
            color: #fff;
        }

        h2 {
            color: #ad1457;
            font-weight: bold;
        }

        .form-label {
            color: #8e24aa;
            font-weight: 600;
        }

        a {
            color: #d81b60;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center" style="min-height:100vh;">
    <div class="col-md-5">
        <div class="card p-4">
            <div class="card-body">
                <h2 class="text-center mb-4">💖 Login 💖</h2>

                <?php if (!empty($error)) { ?>
                    <div class="alert alert-danger text-center"><?= $error ?></div>
                <?php } ?>

                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               placeholder="Enter your email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control"
                               placeholder="Enter your password" required>
                    </div>

                    <button type="submit" class="btn btn-pink w-100">Login</button>
                </form>

                <p class="mt-3 text-center">
                    Don’t have an account?
                    <a href="registre.php">Register here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
