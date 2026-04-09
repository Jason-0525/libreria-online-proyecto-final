<?php
include("config/conexion.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: autores.php");
    exit;
}

$id_autor = $_GET['id'];

$sql_autor = "SELECT nombre, apellido FROM autores WHERE id_autor = :id";
$stmt = $conexion->prepare($sql_autor);
$stmt->bindParam(':id', $id_autor);
$stmt->execute();
$autor = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$autor) {
    echo "<h2 class='text-center mt-5'>Autor no encontrado</h2>";
    exit;
}

$sql_libros = "
    SELECT 
        t.id_titulo,
        t.titulo,
        t.precio,
        t.tipo,
        t.notas
    FROM titulos t
    INNER JOIN titulo_autor ta ON t.id_titulo = ta.id_titulo
    WHERE ta.id_autor = :id_autor
    GROUP BY t.id_titulo
    ORDER BY t.titulo ASC;
";

$stmt = $conexion->prepare($sql_libros);
$stmt->bindParam(':id_autor', $id_autor);
$stmt->execute();
$libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libros de <?= htmlspecialchars($autor['nombre'] . ' ' . $autor['apellido']) ?> - Librería</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">📚 Librería</span>
            <div>
                <a href="index.php" class="btn btn-outline-light me-2">Inicio</a>
                <a href="autores.php" class="btn btn-outline-light me-2">Autores</a>
                <a href="contacto.php" class="btn btn-outline-light me-2">Contacto</a>
                <a href="carrito.php" class="btn btn-outline-light position-relative">
                    <i class="fas fa-shopping-cart"></i> Carrito
                    <span id="carrito-contador" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2>Libros de <strong><?= htmlspecialchars($autor['nombre'] . ' ' . $autor['apellido']) ?></strong></h2>
                <p class="text-muted"><?= count($libros) ?> libro(s) encontrado(s)</p>
            </div>
            <a href="autores.php" class="btn btn-outline-secondary">
                ← Volver a Autores
            </a>
        </div>

        <?php if (empty($libros)): ?>
            <div class="alert alert-info text-center py-5">
                <h4>Este autor aún no tiene libros registrados.</h4>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($libros as $libro): ?>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="book-card h-100">
                            <div class="book-image">
                                <img src="img/<?= htmlspecialchars($libro['id_titulo']) ?>.jpg" 
                                     onerror="this.onerror=null; this.src='img/portada-default.jpg';" 
                                     alt="<?= htmlspecialchars($libro['titulo']) ?>">
                            </div>

                            <div class="card-body d-flex flex-column">
                                <span class="type"><?= ucfirst(htmlspecialchars($libro['tipo'])) ?></span>
                                
                                <h5 class="card-title"><?= htmlspecialchars($libro['titulo']) ?></h5>
                                
                                <p class="author mb-3">
                                    <strong>Autor:</strong> <?= htmlspecialchars($autor['nombre'] . ' ' . $autor['apellido']) ?>
                                </p>

                                <?php if (!empty($libro['precio'])): ?>
                                    <div class="price mb-3">
                                        $<?= number_format($libro['precio'], 2) ?>
                                    </div>
                                <?php endif; ?>

                                <div class="mt-auto d-flex gap-2">
                                    <button onclick="agregarAlCarrito('<?= $libro['id_titulo'] ?>', '<?= addslashes($libro['titulo']) ?>', <?= $libro['precio'] ?? 0 ?>)" 
                                            class="btn btn-primary flex-fill">
                                        <i class="fas fa-cart-plus"></i> Añadir
                                    </button>
                                    <a href="detalle.php?id=<?= $libro['id_titulo'] ?>" 
                                       class="btn btn-outline-primary flex-fill">
                                        Ver detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include("includes/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>