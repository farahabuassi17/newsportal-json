<?php
session_start();
require_once __DIR__ . "/json_db.php";

// التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// قراءة البيانات (التصنيفات)
$data = readData();
$categories = $data['categories'] ?? [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $category_id = $_POST['category'];
    $details = trim($_POST['details']);

    if ($title === "" || $details === "" || $category_id === "") {
        $error = "All fields except image are required!";
    } else {
        // رفع الصورة (اختياري)
        $image_name = "";
        if (!empty($_FILES['image']['name'])) {
            $image_name = time() . "_" . basename($_FILES['image']['name']);
            $uploadPath = __DIR__ . "/uploads/" . $image_name;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $error = "Failed to upload image.";
            }
        }

        if (!isset($error)) {
            $newNews = [
                "id" => time(),
                "title" => $title,
                "category_id" => (int) $category_id,
                "details" => $details,
                "image" => $image_name,
                "user_id" => $_SESSION['user_id'],
                "deleted" => false
            ];

            $data['news'][] = $newNews;
            saveData($data);

            $success = "News added successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add News</title>
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

    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">💖 News Dashboard</a>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="add_category.php">Add Category</a></li>
                <li class="nav-item"><a class="nav-link" href="categories.php">View Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="add_news.php">Add News</a></li>
                <li class="nav-item"><a class="nav-link" href="new.php">All News</a></li>
                <li class="nav-item"><a class="nav-link" href="deleted_news.php">Deleted News</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Add News</h2>
        <div class="card p-4 mt-3">

            <?php if (!empty($success)) { ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php } ?>

            <?php if (!empty($error)) { ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php } ?>

            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Enter news title" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="">Select Category</option>
                        <?php
                        foreach ($categories as $cat) {
                            echo "<option value='{$cat['id']}'>" .
                                htmlspecialchars($cat['name']) .
                                "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Details</label>
                    <textarea name="details" class="form-control" rows="5" placeholder="Enter news details"
                        required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Image (optional)</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <button type="submit" class="btn btn-pink w-100">Add News</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>