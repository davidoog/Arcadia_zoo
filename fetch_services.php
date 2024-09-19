<?php
require 'db.php';

$services = $pdo->query("SELECT * FROM services")->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(['services' => $services]);
?>