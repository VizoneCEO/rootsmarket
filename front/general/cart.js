// Inicializar carrito
let cart = JSON.parse(localStorage.getItem('roots_cart')) || [];

function addToCart(id, name, price, image, quantity = 1) {
    const existingItem = cart.find(item => item.id === id);

    if (existingItem) {
        existingItem.quantity += parseInt(quantity);
    } else {
        cart.push({
            id: id,
            name: name,
            price: parseFloat(price),
            image: image,
            quantity: parseInt(quantity)
        });
    }

    saveCart();
    updateCartCounter();
    triggerCartAnimation(); // <--- AQUÍ ESTÁ LA MAGIA

    // Opcional: Feedback en consola
    // console.log('Agregado:', name);
}

function saveCart() {
    localStorage.setItem('roots_cart', JSON.stringify(cart));
}

function updateCartCounter() {
    // Seleccionamos TODOS los badges (móvil y desktop)
    const counters = document.querySelectorAll('.cart-badge');
    const totalItems = cart.reduce((total, item) => total + item.quantity, 0);

    counters.forEach(counter => {
        counter.innerText = totalItems;
        counter.style.display = totalItems > 0 ? 'flex' : 'none';
    });
}

// Función para la animación del icono
function triggerCartAnimation() {
    // Buscamos los iconos del carrito por sus IDs
    const iconDesktop = document.getElementById('cart-icon-desktop');
    const iconMobile = document.getElementById('cart-icon-mobile');

    // Función helper para añadir/quitar clase
    const animate = (el) => {
        if(el) {
            el.classList.remove('cart-animating'); // Reiniciar si ya estaba animando
            void el.offsetWidth; // Forzar reflow (truco para reiniciar animación CSS)
            el.classList.add('cart-animating');

            // Quitar clase al terminar la animación (0.5s = 500ms)
            setTimeout(() => {
                el.classList.remove('cart-animating');
            }, 500);
        }
    };

    animate(iconDesktop);
    animate(iconMobile);
}

document.addEventListener('DOMContentLoaded', updateCartCounter);