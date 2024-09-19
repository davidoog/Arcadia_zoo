<?php
$admin_password = 'admin_password'; // Change ici le mot de passe que tu veux pour l'admin
$hashed_password = password_hash($admin_password, PASSWORD_BCRYPT);
echo "Hash du mot de passe : " . $hashed_password;
?>