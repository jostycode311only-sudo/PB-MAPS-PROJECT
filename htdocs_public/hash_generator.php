<?php
// htdocs_public/hash_generator.php

$password_plana = "123456"; // <-- ¡CAMBIA ESTA CLAVE!
$password_hash = password_hash($password_plana, PASSWORD_DEFAULT);

echo "Contraseña Plana: " . $password_plana . "<br>";
echo "HASH Generado (Cópialo): <br><strong>" . $password_hash . "</strong><br><br>";
echo "Una vez copiado, borra este archivo inmediatamente por seguridad.";
?>