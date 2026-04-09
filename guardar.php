<?php
include("config/conexion.php"); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'] ?? ''; 
    $correo = $_POST['correo'] ?? '';
    $asunto = $_POST['asunto'] ?? '';
    $comentario = $_POST['comentario'] ?? '';

    $errores = [];
    if (empty($nombre)) {
        $errores['nombre'] = "El nombre es obligatorio.";
    }
    if (empty($correo)) {
        $errores['correo'] = "El correo es obligatorio.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores['correo'] = "Formato de correo inválido.";
    }
    if (empty($asunto)) {
        $errores['asunto'] = "El asunto es obligatorio.";
    }
    if (empty($comentario)) {
        $errores['comentario'] = "El comentario es obligatorio.";
    }

    if (empty($errores)) {
        try {
            $sql = "INSERT INTO contacto (fecha, correo, nombre, asunto, comentario)
                    VALUES (NOW(), :correo, :nombre, :asunto, :comentario)";

            $stmt = $conexion->prepare($sql);

            $stmt->execute([
                ':correo' => $correo,
                ':nombre' => $nombre,
                ':asunto' => $asunto,
                ':comentario' => $comentario
            ]);

            echo "¡Mensaje enviado correctamente!";

        } catch (PDOException $e) {
            echo "Error al guardar el mensaje: " . $e->getMessage();
        }
    } else {
        echo "Hubo errores en el formulario. Por favor, revisa los campos.";
    }
} else {
    echo "Método de solicitud no válido.";
}
?>