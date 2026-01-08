<?php
session_start();
require_once __DIR__ . "/json_db.php";

// التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    if ($name === "") {
        $error = "Category name cannot be empty!";
    } else {
        $data = readData();
        $categories = $data['categories'] ?? [];

        // التأكد من عدم تكرار اسم التصنيف
        foreach ($categories as $cat) {
            if (strtolower($cat['name']) === strtolower($name)) {
                $error = "This category already exists!";
                break;
            }
        }

        if (!isset($error)) {
            $newCategory = [
                "id" => time(), // id بسيط
                "name" => $name
            ];

            $data['categories'][] = $newCategory;
            saveData($data);

            $success = "Category added successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #fce4ec, #e1bee7);
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
            color: white;
        }

        .navbar {
            background-color: #d81b60 !important;
        }

        .navbar-brand,
        .nav-link {
            color: white !important;
            font-weight: bold;
        }

        .nav-link:hover {
            color: #fce4ec !important;
        }

        h2 {
            color: #ad1457;
            font-weight: bold;
            margin-top: 20px;
        }

        .alert {
            margin-top: 15px;
        }
    </style>
</head>

<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">💖 My News Dashboard</a>
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="add_category.php">Add Category</a></li>
            <li class="nav-item"><a class="nav-link" href="categories.php">View Categories</a></li>
            <li class="nav-item"><a class="nav-link" href="add_news.php">Add News</a></li>
            <li class="nav-item"><a class="nav-link" href="dashboard.php">My News</a></li>
            <li class="nav-item"><a class="nav-link" href="deleted_news.php">Deleted News</a></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <h2>Add New Category</h2>
    <div class="card p-4 mt-3">

        <?php if (!empty($success)) { ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php } ?>

        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php } ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Category Name</label>
                <input type="text" name="name" class="form-control"
                       placeholder="Enter category name" required>
            </div>
            <button type="submit" class="btn btn-pink w-100">Add Category</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
