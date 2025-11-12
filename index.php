<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['waiter_name'])) {
    session_regenerate_id(true);
    $_SESSION['waiter'] = ['id' => rand(1000,9999), 'name' => trim($_POST['waiter_name'])];
    if (!isset($_SESSION['tables'])) $_SESSION['tables'] = [];
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="utf-8">
  <title>Dashboard - Tavoli</title>
</head>
<body>
<?php if (empty($_SESSION['waiter'])): ?>
  <h2>Login cameriere</h2>
  <form method="post">
    <input name="waiter_name" required placeholder="Nome cameriere">
    <button>Entra</button>
  </form>
<?php else: ?>
  <p>Camereire: <strong><?= htmlspecialchars($_SESSION['waiter']['name']) ?></strong>
     <a href="logout.php">Esci (fine turno)</a></p>

  <h3>Apri / Seleziona tavolo</h3>
  <form method="post" action="table.php">
    <input name="table_id" required placeholder="Numero tavolo">
    <button>Apri/Apri comanda</button>
  </form>

  <h3>Tavoli aperti</h3>
  <?php if (empty($_SESSION['tables'])): ?>
    <p>Nessun tavolo aperto.</p>
  <?php else: ?>
    <ul>
    <?php foreach ($_SESSION['tables'] as $tid => $t): ?>
      <li>
        Tavolo <?= htmlspecialchars($tid) ?> —
        piatti: <?= count($t['items'] ?? []) ?>
        <a href="table.php?table=<?= urlencode($tid) ?>">Apri</a>
        <form style="display:inline" method="post" action="close_table.php">
          <input type="hidden" name="table_id" value="<?= htmlspecialchars($tid) ?>">
          <button>Chiudi tavolo</button>
        </form>
      </li>
    <?php endforeach; ?>
    </ul>
  <?php endif; ?>
<?php endif; ?>
</body>
</html>