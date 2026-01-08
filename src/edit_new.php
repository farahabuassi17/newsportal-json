<?php
session_start();
require_once __DIR__ . "/json_db.php";

// التأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// قراءة البيانات
$data = readData();
$newsList = $data['news'] ?? [];
$categories = $data['categories'] ?? [];

// جلب بيانات الخبر
$news = null;
foreach ($newsList as $item) {
    if ($item['id'] === $id && empty($item['deleted'])) {
        $news = $item;
        break;
    }
}

if (!$news) {
    header("Location: new.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $category_id = (int) $_POST['category'];
    $details = trim($_POST['details']);

    if ($title === "" || $details === "" || !$category_id) {
        $error = "All fields except image are required!";
    } else {
        // الصورة الحالية
        $image_name = $news['image'];

        // رفع صورة جديدة إن وُجدت
        if (!empty($_FILES['image']['name'])) {
            $image_name = time() . "_" . basename($_FILES['image']['name']);
            $uploadPath = __DIR__ . "/uploads/" . $image_name;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                $error = "Failed to upload image.";
            }
        }

        if (!isset($error)) {
            // تحديث الخبر داخل المصفوفة
            foreach ($newsList as &$item) {
                if ($item['id'] === $id && $item['user_id'] === $_SESSION['user_id']) {
                    $item['title'] = $title;
                    $item['category_id'] = $category_id;
                    $item['details'] = $details;
                    $item['image'] = $image_name;

                    // تحديث القيم المعروضة
                    $news = $item;
                    break;
                }
            }

            $data['news'] = $newsList;
            saveData($data);

            $success = "News updated successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit News</title>
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
        <h2>Edit News</h2>
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
                    <input type="text" name="title" class="form-control"
                        value="<?= htmlspecialchars($news['title']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                        <?php
                        foreach ($categories as $cat) {
                            $selected = ($cat['id'] == $news['category_id']) ? "selected" : "";
                            echo "<option value='{$cat['id']}' $selected>" .
                                htmlspecialchars($cat['name']) .
                                "</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Details</label>
                    <textarea name="details" class="form-control" rows="5"
                        required><?= htmlspecialchars($news['details']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Image (optional)</label>
                    <input type="file" name="image" class="form-control">
                    <?php if (!empty($news['image'])) { ?>
                        <img src="uploads/<?= htmlspecialchars($news['image']); ?>" width="100" class="mt-2 rounded">
                    <?php } ?>
                </div>

                <button type="submit" class="btn btn-pink w-100">Update News</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>