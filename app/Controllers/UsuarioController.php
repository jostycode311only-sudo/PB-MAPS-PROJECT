<?php
// app/Controllers/UsuarioController.php
session_start();
require_once __DIR__ . '/../Models/Usuario.php';

// Seguridad: Solo admin
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
    header("Location: ../../htdocs_public/login.php");
    exit;
}

$userModel = new Usuario();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // ACCIÓN: CONVERTIR EN OPERADOR
    if (isset($_POST['action']) && $_POST['action'] === 'hacer_operador') {
        $id = $_POST['usuario_id'];
        $agencia = $_POST['nombre_agencia'];
        $desc = $_POST['descripcion'];
        $tel = $_POST['telefono'];
        $logo = $_POST['logo_url']; // URL por ahora

        if ($userModel->promoverAOperador($id, $agencia, $desc, $tel, $logo)) {
            header("Location: ../../htdocs_public/admin/usuarios.php?msg=promovido");
        } else {
            header("Location: ../../htdocs_public/admin/usuarios.php?error=fallo");
        }
    }

    // ACCIÓN: QUITAR ROL
    if (isset($_POST['action']) && $_POST['action'] === 'revocar_rol') {
        $id = $_POST['usuario_id'];
        $userModel->revocarRol($id);
        header("Location: ../../htdocs_public/admin/usuarios.php?msg=revocado");
    }
}