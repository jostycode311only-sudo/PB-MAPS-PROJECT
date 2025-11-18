<?php
// app/Controllers/LogoutController.php

// 1. Iniciar la sesión
// Se necesita para acceder a la sesión actual que se va a destruir
session_start();

// 2. Destruir todas las variables de sesión
// Esto limpia los datos de usuario (id, rol, nombre) del servidor
$_SESSION = array();

// 3. Invalidar la cookie de sesión (opcional pero recomendado)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. Finalmente, destruir la sesión
session_destroy();

// 5. Redirigir al usuario a la página de inicio pública
header("Location: ../../public/index.html");
exit;

?>