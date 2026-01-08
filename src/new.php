<?php
session_start();
require_once __DIR__ . "/json_db.php";

// تأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// قراءة البيانات
$data = readData();
$news = $data['news'] ?? [];
$categories = $data['categories'] ?? [];

// تجهيز خريطة التصنيفات (id => name)
$categoryMap = [];
foreach ($categories as $cat) {
    $categoryMap[$cat['id']] = $cat['name'];
}

// فلترة الأخبار الخاصة بالمستخدم (غير المحذوفة)
$userNews = array_filter($news, function ($item) use ($user_id) {
    return $item['user_id'] == $user_id && empty($item['deleted']);
});

// ترتيب الأخبار تنازليًا حسب id
usort($userNews, function ($a, $b) {
    return ($b['id'] ?? 0) <=> ($a['id'] ?? 0);
});
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #fce4ec, #f3e5f5);
            font-family: 'Poppins', sans-serif;
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

        .table th {
            background-color: #f8bbd0;
            color: #880e4f;
        }

        .btn-edit {
            background-color: #ab47bc;
            color: white;
        }

        .btn-edit:hover {
            background-color: #8e24aa;
        }

        .btn-delete {
            background-color: #e53935;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c62828;
        }

        img {
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">💖 News Dashboard</a>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="add_category.php">Add Category</a></li>
                <li class="nav-item"><a class="nav-link" href="categories.php">View Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="add_news.php">Add News</a></li>
                <li class="nav-item"><a class="nav-link" href="new.php">My News</a></li>
                <li class="nav-item"><a class="nav-link" href="deleted_news.php">Deleted News</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>My News</h2>
        <div class="card p-4 mt-3 shadow-sm">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Details</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($userNews)) {
                        foreach ($userNews as $row) {
                            $categoryName = $categoryMap[$row['category_id']] ?? 'No Category';

                            echo "<tr>";
                            echo "<td>{$row['id']}</td>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($categoryName) . "</td>";
                            echo "<td>" . htmlspecialchars(substr($row['details'], 0, 50)) . "...</td>";
                            echo "<td>";

                            if (!empty($row['image']) && file_exists('uploads/' . $row['image'])) {
                                echo "<img src='uploads/{$row['image']}' width='80'>";
                            } else {
                                echo "No Image";
                            }

                            echo "</td>";
                            echo "<td>
                            <a href='edit_news.php?id={$row['id']}' class='btn btn-sm btn-edit'>Edit</a>
                            <a href='delete_news.php?id={$row['id']}'
                               class='btn btn-sm btn-delete'
                               onclick='return confirm(\"Are you sure you want to delete this news?\");'>
                               Delete
                            </a>
                          </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr>
                        <td colspan='6' class='text-center text-muted'>
                            No news added yet
                        </td>
                      </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>