<?php
session_start();
require_once __DIR__ . "/json_db.php";

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: deleted_news.php");
    exit;
}

$id = (int)$_GET['id'];

// قراءة البيانات
$data = readData();
$news = $data['news'] ?? [];

$restored = false;

// إرجاع الخبر (deleted = false)
foreach ($news as &$item) {
    if ($item['id'] === $id && $item['user_id'] === $_SESSION['user_id']) {
        $item['deleted'] = false;
        $restored = true;
        break;
    }
}

// إذا لم يتم العثور على الخبر
if (!$restored) {
    header("Location: deleted_news.php");
    exit;
}

// حفظ التعديلات
$data['news'] = $news;
saveData($data);

// الرجوع لصفحة الأخبار المحذوفة
header("Location: deleted_news.php?msg=restored");
exit;
?>
