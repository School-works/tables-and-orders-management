<?php
session_start();
if (empty($_SESSION['waiter'])) { header('Location: index.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['table_id'])) {
    $table = trim($_POST['table_id']);
    if (!isset($_SESSION['tables'][$table])) {
        $_SESSION['tables'][$table] = ['opened_at' => time(), 'items' => []];
    }
    header("Location: table.php?table=" . urlencode($table));
    exit;
}
$table = $_GET['table'] ?? null;
if (!$table || !isset($_SESSION['tables'][$table])) {
    echo "Tavolo non aperto. <a href='index.php'>Torna</a>";
    exit;
}
$T = &$_SESSION['tables'][$table];
function h($s){ return htmlspecialchars($s); }
?>
<!DOCTYPE html>
<html lang="it">
<head><meta charset="utf-8"><title>Tavolo <?= h($table) ?></title></head>

<body>
<p><a href="index.php">Dashboard</a> — Tavolo <?= h($table) ?></p>

<h3>Comanda</h3>
<?php if (empty($T['items'])): ?>
  <p>Nessun piatto.</p>
<?php else: ?>
  <table border="1" cellpadding="4">
    <tr><th>#</th><th>Nome</th><th>Prezzo</th><th>Qty</th><th>Note</th><th>Azione</th></tr>
    <?php foreach ($T['items'] as $i => $it): ?>
      <tr>
        <td><?= $i ?></td>
        <td><?= h($it['name']) ?></td>
        <td><?= number_format($it['price'],2) ?></td>
        <td><?= (int)$it['qty'] ?></td>
        <td><?= h($it['notes'] ?? '') ?></td>
        <td>
          <form method="post" action="remove_item.php" style="display:inline">
            <input type="hidden" name="table_id" value="<?= h($table) ?>">
            <input type="hidden" name="item_index" value="<?= $i ?>">
            <button>Rimuovi</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
  <p>
    Totale: €
    <?= number_format(array_sum(array_map(function($it){ return $it['price']*$it['qty']; }, $T['items'])), 2) ?>
  </p>
<?php endif; ?>

<h3>Aggiungi piatto</h3>
<form method="post" action="add_item.php">
  <input type="hidden" name="table_id" value="<?= h($table) ?>">
  <input name="name" placeholder="Nome piatto" required>
  <input name="price" placeholder="Prezzo" required pattern="^\d+(\.\d{1,2})?$">
  <input name="qty" type="number" value="1" min="1">
  <input name="notes" placeholder="Note (opz.)">
  <button>Aggiungi</button>
</form>

<form method="post" action="close_table.php">
  <input type="hidden" name="table_id" value="<?= h($table) ?>">
  <button>Chiudi tavolo (salva comanda in sessione)</button>
</form>

</body>
</html>