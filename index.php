<?php
include("config/conexion.php"); 

$sql_count = "SELECT COUNT(*) AS total FROM titulos";
$total_libros = $conexion->query($sql_count)->fetch(PDO::FETCH_ASSOC)['total'];

$sql_libros = "
    SELECT 
        t.id_titulo,
        t.titulo,
        t.precio,
        t.tipo,
        t.fecha_pub,
        GROUP_CONCAT(CONCAT(a.nombre, ' ', a.apellido) SEPARATOR ', ') AS autores
    FROM 
        titulos t
    LEFT JOIN 
        titulo_autor ta ON t.id_titulo = ta.id_titulo
    LEFT JOIN 
        autores a ON ta.id_autor = a.id_autor
    GROUP BY 
        t.id_titulo, t.titulo, t.precio, t.tipo, t.fecha_pub
    ORDER BY 
        t.titulo ASC;
";

$stmt = $conexion->prepare($sql_libros);
$stmt->execute();
$libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería Online - Lista de Libros</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        .book-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .book-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .book-image {
            height: 260px;
            background: #f8f9fa;
            position: relative;
            overflow: hidden;
        }
        .book-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .book-card:hover .book-image img {
            transform: scale(1.08);
        }
        .card-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .card-title {
            font-size: 1.23rem;
            line-height: 1.35;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        .author { color: #6c757d; font-size: 0.97rem; margin-bottom: 12px; }
        .price { 
            font-size: 1.55rem; 
            font-weight: 700; 
            color: #27ae60; 
            margin: 10px 0;
        }
        .type {
            font-size: 0.85rem;
            background: #e9ecef;
            padding: 3px 10px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 10px;
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
                <a href="carrito.php" class="btn btn-outline-light position-relative">
                    <i class="fas fa-shopping-cart"></i> Carrito
                    <span id="carrito-contador" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">0</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Lista de Libros <span class="text-primary">(<?= $total_libros ?>)</span></h2>
            
            <select class="form-select w-auto" style="width: 220px;">
                <option>Ordenar por: Título A-Z</option>
                <option>Precio: Menor a mayor</option>
                <option>Precio: Mayor a menor</option>
                <option>Más recientes</option>
            </select>
        </div>

        <div class="row g-4">
            <?php foreach ($libros as $libro): ?>
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="book-card h-100">
                        <div class="book-image">
                          <img src="img/<?= htmlspecialchars($libro['id_titulo']) ?>.jpg" 
                                    onerror="this.onerror=null; this.src='img/portada-default.jpg';" 
                                    alt="<?= htmlspecialchars($libro['titulo']) ?>">
                        </div>
                        <div class="card-body">
                            <span class="type"><?= htmlspecialchars(ucfirst($libro['tipo'])) ?></span>
                            
                            <h5 class="card-title"><?= htmlspecialchars($libro['titulo']) ?></h5>
                            
                            <p class="author">
                                <strong>Autor:</strong><br>
                                <?= $libro['autores'] ? htmlspecialchars($libro['autores']) : 'Autor desconocido' ?>
                            </p>

                            <?php if ($libro['precio'] > 0): ?>
                                <div class="price">$<?= number_format($libro['precio'], 2) ?></div>
                            <?php endif; ?>

                            <div class="mt-auto d-flex gap-2">
                                <button onclick="agregarAlCarrito('<?= $libro['id_titulo'] ?>', '<?= addslashes($libro['titulo']) ?>', <?= $libro['precio'] ?? 0 ?>)" 
                                        class="btn btn-primary flex-fill">
                                        <i class="fas fa-cart-plus"></i> Añadir al carrito
                                </button>
                                <a href="detalle.php?id=<?= $libro['id_titulo'] ?>" 
                                   class="btn btn-outline-primary flex-fill">Detalles</a>
                            </div>
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