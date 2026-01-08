<?php
session_start();
require_once __DIR__ . "/json_db.php";

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: new.php");
    exit;
}

$id = (int)$_GET['id'];

// قراءة البيانات
$data = readData();
$news = $data['news'] ?? [];

$found = false;

// تحديث حالة الخبر إلى محذوف (Soft Delete)
foreach ($news as &$item) {
    if ($item['id'] == $id && $item['user_id'] == $_SESSION['user_id']) {
        $item['deleted'] = true;
        $found = true;
        break;
    }
}

if (!$found) {
    header("Location: new.php");
    exit;
}

// حفظ التعديلات
$data['news'] = $news;
saveData($data);

// الرجوع لصفحة الأخبار
header("Location: new.php");
exit;

