document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Librería Online - JavaScript cargado correctamente');

    let carrito = [];

    function cargarCarrito() {
        const guardado = localStorage.getItem('carrito');
        if (guardado) carrito = JSON.parse(guardado);
    }

    function guardarCarrito() {
        localStorage.setItem('carrito', JSON.stringify(carrito));
    }

    window.agregarAlCarrito = function(id, titulo, precio) {   // <-- "window." es importante
        if (!id || !titulo) {
            alert("Error: Datos del libro incompletos");
            return;
        }

        precio = parseFloat(precio) || 0;

        const existe = carrito.find(item => item.id === id);

        if (existe) {
            existe.cantidad += 1;
        } else {
            carrito.push({
                id: id,
                titulo: titulo,
                precio: precio,
                cantidad: 1
            });
        }

        guardarCarrito();
        actualizarContadorCarrito();
        mostrarMensaje(`✅ "${titulo}" añadido al carrito`, 'success');
    };

    function mostrarMensaje(texto, tipo = 'success') {
        const div = document.createElement('div');
        div.style.position = 'fixed';
        div.style.top = '20px';
        div.style.right = '20px';
        div.style.padding = '15px 25px';
        div.style.borderRadius = '12px';
        div.style.color = 'white';
        div.style.fontWeight = '600';
        div.style.zIndex = '10000';
        div.style.boxShadow = '0 10px 30px rgba(0,0,0,0.3)';
        div.style.minWidth = '280px';

        if (tipo === 'success') {
            div.style.backgroundColor = '#27ae60';
        } else {
            div.style.backgroundColor = '#e74c3c';
        }

        div.textContent = texto;
        document.body.appendChild(div);

        setTimeout(() => {
            div.style.transition = 'opacity 0.4s';
            div.style.opacity = '0';
            setTimeout(() => div.remove(), 500);
        }, 2800);
    }

    function actualizarContadorCarrito() {
        const contador = document.getElementById('carrito-contador');
        if (contador) {
            let total = 0;
            carrito.forEach(item => total += item.cantidad);
            contador.textContent = total;
        }
    }

    cargarCarrito();
    actualizarContadorCarrito();

});