<?php
require 'db.php';

$id = $_GET['id'];
$service = $pdo->prepare("SELECT * FROM services WHERE id = ?");
$service->execute([$id]);
echo json_encode($service->fetch(PDO::FETCH_ASSOC));
?>