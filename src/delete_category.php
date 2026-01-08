<?php
session_start();
require_once __DIR__ . "/json_db.php";

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: categories.php");
    exit;
}

$id = (int)$_GET['id'];

// قراءة البيانات
$data = readData();
$categories = $data['categories'] ?? [];
$news = $data['news'] ?? [];

// التحقق إذا كان التصنيف مرتبط بأخبار
foreach ($news as $item) {
    if (
        isset($item['category_id']) &&
        $item['category_id'] == $id &&
        empty($item['deleted'])
    ) {
        echo "<script>
                alert('⚠️ Cannot delete this category because it is linked to existing news.');
                window.location.href='categories.php';
              </script>";
        exit;
    }
}

// حذف التصنيف (فلترة المصفوفة)
$beforeCount = count($categories);
$categories = array_filter($categories, function ($cat) use ($id) {
    return $cat['id'] != $id;
});

// إذا لم يتم حذف أي شيء (id غير موجود)
if (count($categories) === $beforeCount) {
    header("Location: categories.php");
    exit;
}

// حفظ التعديلات
$data['categories'] = array_values($categories);
saveData($data);

// الرجوع لصفحة التصنيفات
header("Location: categories.php");
exit;
