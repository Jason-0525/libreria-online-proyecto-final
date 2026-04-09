<?php
include("config/conexion.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id_titulo = $_GET['id'];

$sql = "
    SELECT 
        t.*,
        GROUP_CONCAT(CONCAT(a.nombre, ' ', a.apellido) SEPARATOR ', ') AS autores,
        p.nombre_pub AS editorial
    FROM titulos t
    LEFT JOIN titulo_autor ta ON t.id_titulo = ta.id_titulo
    LEFT JOIN autores a ON ta.id_autor = a.id_autor
    LEFT JOIN publicadores p ON t.id_pub = p.id_pub
    WHERE t.id_titulo = :id
    GROUP BY t.id_titulo;
";

$stmt = $conexion->prepare($sql);
$stmt->bindParam(':id', $id_titulo);
$stmt->execute();
$libro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$libro) {
    echo "<h2 class='text-center mt-5'>Libro no encontrado</h2>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($libro['titulo']) ?> - Librería Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <?php include("includes/header.php"); ?>   

    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-4 col-md-5 text-center">
                <div class="book-image mb-4" style="height: 420px; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                    <img src="img/<?= htmlspecialchars($libro['id_titulo']) ?>.jpg" 
                              onerror="this.onerror=null; this.src='img/portada-default.jpg';" 
                              alt="<?= htmlspecialchars($libro['titulo']) ?>"
                              class="img-fluid h-100 w-100 object-fit-cover">
                </div>
            </div>

            <div class="col-lg-8 col-md-7">
                <h1 class="display-6 fw-bold"><?= htmlspecialchars($libro['titulo']) ?></h1>
                
                <p class="lead text-muted">
                    <strong>Autor(es):</strong> 
                    <?= $libro['autores'] ? htmlspecialchars($libro['autores']) : 'Autor desconocido' ?>
                </p>

                <p><strong>Editorial:</strong> <?= htmlspecialchars($libro['editorial'] ?? 'No disponible') ?></p>
                <p><strong>Tipo:</strong> <?= ucfirst(htmlspecialchars($libro['tipo'])) ?></p>
                <p><strong>Publicado:</strong> <?= date("d/m/Y", strtotime($libro['fecha_pub'])) ?></p>

                <?php if ($libro['precio'] > 0): ?>
                    <h3 class="text-success fw-bold mt-4">
                        $<?= number_format($libro['precio'], 2) ?>
                    </h3>
                <?php endif; ?>

                <div class="mt-4">
                    <h5>Notas / Descripción:</h5>
                    <p class="text-muted">
                        <?= htmlspecialchars($libro['notas'] ?? 'No hay descripción disponible para este libro.') ?>
                    </p>
                </div>

                <div class="mt-5 d-flex gap-3">
                    <button onclick="agregarAlCarrito('<?= $libro['id_titulo'] ?>', '<?= addslashes($libro['titulo']) ?>', <?= $libro['precio'] ?? 0 ?>)" 
                            class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-cart-plus"></i> Añadir al Carrito
                    </button>
                    <a href="index.php" class="btn btn-outline-secondary btn-lg px-5">
                        Volver a la lista
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php include("includes/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>