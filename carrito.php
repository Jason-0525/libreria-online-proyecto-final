<?php
session_start();
include("config/conexion.php");

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Librería Online</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        .cart-item {
            transition: all 0.3s ease;
        }
        .cart-item:hover {
            background-color: #f8f9fa;
        }
        .total-row {
            font-size: 1.3rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
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
        <h2 class="mb-4">🛒 Mi Carrito de Compras</h2>

        <div id="carrito-vacio" class="alert alert-info text-center" style="display: none;">
            <h4>Tu carrito está vacío</h4>
            <p class="mb-0">¡Agrega algunos libros para comenzar!</p>
            <a href="index.php" class="btn btn-primary mt-3">Ir a la tienda</a>
        </div>

        <div id="carrito-contenido">
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <table class="table table-hover mb-0" id="tabla-carrito">
                                <thead class="table-light">
                                    <tr>
                                        <th>Libro</th>
                                        <th class="text-end">Precio</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-end">Subtotal</th>
                                        <th class="text-center">Acción</th>
                                    </tr>
                                </thead>
                                <tbody id="lista-carrito">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm sticky-top" style="top: 20px;">
                        <div class="card-body">
                            <h5 class="card-title">Resumen del Pedido</h5>
                            <hr>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotal">$0.00</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Envío:</span>
                                <span class="text-success">Gratis</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between total-row">
                                <span>Total:</span>
                                <span id="total">$0.00</span>
                            </div>

                            <button onclick="finalizarCompra()" class="btn btn-success btn-lg w-100 mt-4">
                                <i class="fas fa-credit-card"></i> Finalizar Compra
                            </button>
                            
                            <a href="index.php" class="btn btn-outline-secondary w-100 mt-2">
                                Seguir Comprando
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("includes/footer.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>

    <script>

        let carrito = [];

        function cargarCarrito() {
            const carritoGuardado = localStorage.getItem('carrito');
            if (carritoGuardado) {
                carrito = JSON.parse(carritoGuardado);
            }
            renderizarCarrito();
        }

        function renderizarCarrito() {
            const tbody = document.getElementById('lista-carrito');
            tbody.innerHTML = '';

            if (carrito.length === 0) {
                document.getElementById('carrito-vacio').style.display = 'block';
                document.getElementById('carrito-contenido').style.display = 'none';
                actualizarContadorCarrito();
                return;
            }

            document.getElementById('carrito-vacio').style.display = 'none';
            document.getElementById('carrito-contenido').style.display = 'block';

            let subtotal = 0;

            carrito.forEach((item, index) => {
                const subtotalItem = item.precio * item.cantidad;
                subtotal += subtotalItem;

                const fila = `
                    <tr class="cart-item">
                        <td>
                            <strong>${item.titulo}</strong><br>
                            <small class="text-muted">ID: ${item.id}</small>
                        </td>
                        <td class="text-end">$${parseFloat(item.precio).toFixed(2)}</td>
                        <td class="text-center">
                            <button onclick="cambiarCantidad(${index}, -1)" class="btn btn-sm btn-outline-secondary">-</button>
                            <span class="mx-2 fw-bold">${item.cantidad}</span>
                            <button onclick="cambiarCantidad(${index}, 1)" class="btn btn-sm btn-outline-secondary">+</button>
                        </td>
                        <td class="text-end fw-bold">$${subtotalItem.toFixed(2)}</td>
                        <td class="text-center">
                            <button onclick="eliminarDelCarrito(${index})" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += fila;
            });

            document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('total').textContent = '$' + subtotal.toFixed(2);

            actualizarContadorCarrito();
        }

        function cambiarCantidad(index, cambio) {
            carrito[index].cantidad += cambio;
            
            if (carrito[index].cantidad < 1) {
                carrito.splice(index, 1);
            }
            
            localStorage.setItem('carrito', JSON.stringify(carrito));
            renderizarCarrito();
        }

        function eliminarDelCarrito(index) {
            if (confirm('¿Eliminar este libro del carrito?')) {
                carrito.splice(index, 1);
                localStorage.setItem('carrito', JSON.stringify(carrito));
                renderizarCarrito();
            }
        }

        function finalizarCompra() {
            if (carrito.length === 0) return;
            
            alert('🎉 ¡Compra realizada con éxito!\n\nTotal pagado: ' + document.getElementById('total').textContent);
            carrito = [];
            localStorage.setItem('carrito', JSON.stringify(carrito));
            renderizarCarrito();
        }

        window.onload = cargarCarrito;
    </script>

    <?php include("includes/footer.php"); ?>
</body>
</html>