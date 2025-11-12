<?php
session_start();
if (empty($_SESSION['waiter'])) { header('Location: index.php'); exit; }
$table = $_POST['table_id'] ?? null;
$idx = isset($_POST['item_index']) ? intval($_POST['item_index']) : null;
if ($table !== null && isset($_SESSION['tables'][$table]['items'][$idx])) {
    unset($_SESSION['tables'][$table]['items'][$idx]);
    $_SESSION['tables'][$table]['items'] = array_values($_SESSION['tables'][$table]['items']);
}
header('Location: table.php?table=' . urlencode($table));
exit;