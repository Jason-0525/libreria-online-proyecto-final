<?php
include("config/conexion.php"); 


$sql_count = "SELECT COUNT(*) AS total_libros FROM titulos"; 
$stmt_count = $conexion->query($sql_count);
$resultado_count = $stmt_count->fetch(PDO::FETCH_ASSOC); 

$total_libros = $resultado_count['total_libros']; 


$sql_libros = "
    SELECT 
        t.id_titulo,
        t.titulo,
        GROUP_CONCAT(a.nombre SEPARATOR ', ') AS autores
    FROM 
        titulos t
    LEFT JOIN 
        titulo_autor ta ON t.id_titulo = ta.id_titulo
    LEFT JOIN 
        autores a ON ta.id_autor = a.id_autor
    GROUP BY 
        t.id_titulo, t.titulo
    ORDER BY 
        t.titulo;
";
$stmt_libros = $conexion->query($sql_libros);
$libros = $stmt_libros->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería Online - Lista de Libros</title>

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
        
        <h2 class="mb-4">Lista de Libros (Total: <?= $total_libros ?>)</h2> 
        
        <div class="row book-list-row"> 
            <?php if (!empty($libros)): ?>
                <?php foreach ($libros as $libro): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($libro['titulo']) ?></h5>
                                <p class="card-text">
                                    Autor:
                                    <?php
                                    if (isset($libro['autores']) && !empty($libro['autores'])) {
                                        echo htmlspecialchars($libro['autores']);
                                    } else {
                                        echo "Autor desconocido";
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay libros disponibles.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="js/app.js"></script>
</body>
</html>