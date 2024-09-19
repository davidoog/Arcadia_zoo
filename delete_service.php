<?php
require 'db.php';

$id = $_GET['id'];
$pdo->prepare("DELETE FROM services WHERE id = ?")->execute([$id]);

echo json_encode(['status' => 'success']);
?>