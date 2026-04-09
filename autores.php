<?php
include("config/conexion.php");

$sql = "
    SELECT 
        a.id_autor,
        a.nombre,
        a.apellido,
        a.ciudad,
        a.pais,
        a.telefono,
        b.biografia
    FROM autores a
    LEFT JOIN biografias b ON a.id_autor = b.id_autor
    ORDER BY a.apellido, a.nombre;
";

$stmt = $conexion->prepare($sql);
$stmt->execute();
$autores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autores - Librería Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .author-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            height: 100%;
        }
        .author-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .author-header {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: white;
            padding: 20px;
            text-align: center;
        }
    </style>
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

    <div class="container mt-5">
        <h2 class="mb-4">Lista de Autores (<?= count($autores) ?>)</h2>

        <div class="row g-4">
            <?php foreach ($autores as $autor): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="author-card">
                        <div class="author-header">
                            <h5 class="mb-0">
                                <?= htmlspecialchars($autor['nombre'] . ' ' . $autor['apellido']) ?>
                            </h5>
                            <small><?= htmlspecialchars($autor['ciudad']) ?>, <?= htmlspecialchars($autor['pais']) ?></small>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($autor['biografia'])): ?>
                                <p class="text-muted small">
                                    <?= htmlspecialchars(substr($autor['biografia'], 0, 180)) ?>...
                                </p>
                            <?php endif; ?>
                            
                            <p class="mb-1"><strong>Teléfono:</strong> <?= htmlspecialchars($autor['telefono']) ?></p>
                            <a href="libros-por-autor.php?id=<?= $autor['id_autor'] ?>" class="btn btn-outline-primary btn-sm mt-3">
                                Ver libros de este autor
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include("includes/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>