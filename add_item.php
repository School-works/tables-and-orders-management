<?php
session_start();
if (empty($_SESSION['waiter'])) { header('Location: index.php'); exit; }
$table = $_POST['table_id'] ?? null;
if (!$table) { header('Location: index.php'); exit; }
$name = trim($_POST['name'] ?? '');
$price = floatval($_POST['price'] ?? 0);
$qty = max(1, intval($_POST['qty'] ?? 1));
$notes = trim($_POST['notes'] ?? '');

if (!isset($_SESSION['tables'][$table])) {
    $_SESSION['tables'][$table] = ['opened_at'=>time(),'items'=>[]];
}
$found = false;
foreach ($_SESSION['tables'][$table]['items'] as &$it) {
    if ($it['name'] === $name && $it['price'] == $price) {
        $it['qty'] += $qty;
        $found = true;
        break;
    }
}
unset($it);
if (!$found) {
    $_SESSION['tables'][$table]['items'][] = ['name'=>$name,'price'=>$price,'qty'=>$qty,'notes'=>$notes];
}
header('Location: table.php?table=' . urlencode($table));
exit;
?>