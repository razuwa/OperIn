<?php require 'dataProduk.php'; ?>
<?php
$id = $_GET['id'];
$p = $products[$id];
?>

<h1><?= $p['name'] ?></h1>
<img src="<?= $p['image'] ?>">
<p>Harga: Rp<?= number_format($p['price'], 0, ',', '.') ?></p>
<p>Kondisi: <?= $p['kondisi'] ?></p>
<p>Fakultas: <?= $p['fakultas'] ?></p>