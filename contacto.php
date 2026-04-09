<?php
include("config/conexion.php");

$mensaje_exito = "";
$errores = [];


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['nombre'])) {
        $errores['nombre'] = "El nombre es obligatorio.";
    }
    if (empty($_POST['correo'])) {
        $errores['correo'] = "El correo es obligatorio.";
    } elseif (!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
        $errores['correo'] = "Formato de correo inválido.";
    }
    if (empty($_POST['asunto'])) {
        $errores['asunto'] = "El asunto es obligatorio.";
    }
    if (empty($_POST['comentario'])) {
        $errores['comentario'] = "El comentario es obligatorio.";
    }

    if (empty($errores)) {
        try {
            $sql = "INSERT INTO contacto (correo, nombre, asunto, comentario) VALUES (:correo, :nombre, :asunto, :comentario)";
            $stmt = $conexion->prepare($sql);

            $stmt->bindParam(':correo', $_POST['correo']);
            $stmt->bindParam(':nombre', $_POST['nombre']);
            $stmt->bindParam(':asunto', $_POST['asunto']);
            $stmt->bindParam(':comentario', $_POST['comentario']);

            if ($stmt->execute()) {
                $mensaje_exito = "¡Tu mensaje ha sido enviado con éxito!";
                $_POST = array(); 
            } else {
                $errores['general'] = "Ocurrió un error al intentar enviar tu mensaje. Por favor, inténtalo de nuevo.";
            }
        } catch (PDOException $e) {
            $errores['general'] = "Error de base de datos: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería Online - Contacto</title>

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
        <h2 class="mb-4">Formulario de Contacto</h2>

        <?php if (!empty($mensaje_exito)): ?>
            <div class="alert alert-success" role="alert">
                <?= $mensaje_exito ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errores)): ?>
            <div class="alert alert-danger" role="alert">
                <strong>Error:</strong> Por favor, revisa los siguientes campos.
                <ul>
                    <?php foreach ($errores as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="contacto.php">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?= isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '' ?>" required>
            </div>
            <div class="mb-3">
                <label for="correo" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="correo" name="correo" value="<?= isset($_POST['correo']) ? htmlspecialchars($_POST['correo']) : '' ?>" required>
            </div>
            <div class="mb-3">
                <label for="asunto" class="form-label">Asunto</label>
                <input type="text" class="form-control" id="asunto" name="asunto" value="<?= isset($_POST['asunto']) ? htmlspecialchars($_POST['asunto']) : '' ?>" required>
            </div>
            <div class="mb-3">
                <label for="comentario" class="form-label">Comentario</label>
                <textarea class="form-control" id="comentario" name="comentario" rows="5" required><?= isset($_POST['comentario']) ? htmlspecialchars($_POST['comentario']) : '' ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Mensaje</button>
        </form>
    </div>
 
    <script src="js/script.js"></script>
</body>
</html>