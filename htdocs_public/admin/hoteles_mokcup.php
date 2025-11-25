<?php
// htdocs_public/admin/hoteles.php

// 1. MANTENEMOS LA SEGURIDAD (Para que solo entre el admin)
require_once __DIR__ . '/../../app/Controllers/CheckAuth.php'; 

// 2. DATOS SIMULADOS (MOCKUP)
// En lugar de llamar a la Base de Datos, creamos una lista falsa aquí mismo.
// Esto es lo que el foreach va a recorrer.
$hoteles = [
    [
        'id' => 1, 
        'nombre' => 'Hotel Plaza Puerto', 
        'descripcion' => 'Hotel de lujo ubicado en el centro, con piscina y aire acondicionado.', 
        'precio' => '$120.000 / noche',
        'url_imagen' => '#'
    ],
    [
        'id' => 2, 
        'nombre' => 'Reserva Natural Eco-Hotel', 
        'descripcion' => 'Experiencia ecológica a las afueras del municipio.', 
        'precio' => '$250.000 / noche',
        'url_imagen' => '#'
    ],
    [
        'id' => 3, 
        'nombre' => 'Hostal El Viajero', 
        'descripcion' => 'Opción económica para mochileros cerca al río.', 
        'precio' => '$60.000 / noche',
        'url_imagen' => '#'
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Hoteles - PB-MAPS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">PB-MAPS Admin</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Lugares</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#">Hoteles</a> </li>
            </ul>
        </div>
        <div class="d-flex">
            <span class="navbar-text me-3">
                Hola, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></strong>
            </span>
            <a href="../../app/Controllers/LogoutController.php" class="btn btn-outline-light btn-sm">Salir</a>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4">

    <h2 class="mb-4 text-primary"><i class="bi bi-buildings"></i> Gestión de Hoteles</h2>

    <button type="button" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle"></i> Agregar Nuevo Hotel
    </button>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre del Hotel</th>
                    <th>Descripción</th>
                    <th>Precio Promedio</th>
                    <th>Imagen</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hoteles as $hotel): ?>
                    <tr>
                        <td><?= $hotel['id'] ?></td>
                        <td class="fw-bold"><?= $hotel['nombre'] ?></td>
                        <td><?= $hotel['descripcion'] ?></td>
                        <td><span class="badge bg-info text-dark"><?= $hotel['precio'] ?></span></td>
                        <td><a href="#" class="btn btn-sm btn-outline-secondary">Ver Foto</a></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-warning" title="Editar"><i class="bi bi-pencil-square"></i></button>
                            <button class="btn btn-sm btn-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
        </table>
    </div>

    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
        </ul>
    </nav>

</div> 

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>