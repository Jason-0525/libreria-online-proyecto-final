<?php
include("config/conexion.php"); 

$stmt = $conexion->query("SELECT id_autor, nombre FROM autores ORDER BY nombre"); 
$autores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería Online - Lista de Autores</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">📚 Librería</span>
            <div>
                <a href="index.php" class="btn btn-outline-light me-2">Inicio</a>
                <a href="autores.php" class="btn btn-outline-light me-2">Autores</a>
                <a href="contacto.php" class="btn btn-outline-light">Contacto</a>
            </div>
        </div>
    </nav>
  
    <div class="container mt-4">
        <h2 class="mb-4">Lista de Autores</h2>
        <div class="list-group">
            <?php if (!empty($autores)): ?>
                <?php foreach ($autores as $autor): ?>
                    <a href="#" class="list-group-item list-group-item-action">
                        <?= htmlspecialchars($autor['nombre']) ?>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay autores disponibles.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>