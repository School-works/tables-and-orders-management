<?php
session_start();
if (empty($_SESSION['waiter'])) { header('Location: index.php'); exit; }
$table = $_POST['table_id'] ?? null;
if ($table && isset($_SESSION['tables'][$table])) {
    unset($_SESSION['tables'][$table]);
}
header('Location: index.php');
exit;