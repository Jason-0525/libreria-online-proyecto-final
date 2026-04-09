document.addEventListener('DOMContentLoaded', function() {
   
    const bookCards = document.querySelectorAll('.card');
    bookCards.forEach(card => {
        card.addEventListener('mouseover', () => {
            card.style.transform = 'translateY(-5px)';
            card.style.boxShadow = '0 8px 16px rgba(0,0,0,0.1)';
        });
        card.addEventListener('mouseout', () => {
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = '0 4px 8px rgba(0,0,0,0.05)';
        });
    });

    const authorListItems = document.querySelectorAll('.list-group-item');
    authorListItems.forEach(item => {
        item.addEventListener('mouseover', () => {
            item.style.backgroundColor = '#e9ecef';
            item.style.color = '#333';
        });
        item.addEventListener('mouseout', () => {
            item.style.backgroundColor = '#fff';
            item.style.color = '#333'; 
        });
    });

    const contactForm = document.querySelector('form[action="contacto.php"]'); 
    if (contactForm) {
        contactForm.addEventListener('submit', function(event) {
            const nombre = document.getElementById('nombre').value;
            const correo = document.getElementById('correo').value;
            const asunto = document.getElementById('asunto').value;
            const comentario = document.getElementById('comentario').value;

            let errors = [];

            if (!nombre.trim()) {
                errors.push('El nombre es obligatorio.');
            }
            if (!correo.trim()) {
                errors.push('El correo es obligatorio.');
            } else if (!/\S+@\S+\.\S+/.test(correo)) { 
                errors.push('Formato de correo inválido.');
            }
            if (!asunto.trim()) {
                errors.push('El asunto es obligatorio.');
            }
            if (!comentario.trim()) {
                errors.push('El comentario es obligatorio.');
            }

            if (errors.length > 0) {
                event.preventDefault(); 
                alert('Por favor, revisa los siguientes errores:\n\n' + errors.join('\n'));
            }
        });
    }
});



