// Carrito de compras
let cart = [];

// Agregar producto al carrito
function addToCart(button) {
    const product = {
        id: parseInt(button.dataset.id),
        name: button.dataset.name,
        price: parseFloat(button.dataset.price),
        stock: parseInt(button.dataset.stock),
        quantity: 1
    };
    
    // Verificar si el producto ya está en el carrito
    const existingItem = cart.find(item => item.id === product.id);
    
    if (existingItem) {
        // Si existe, incrementar cantidad
        existingItem.quantity += 1;
    } else {
        // Si no existe, agregar al carrito
        cart.push(product);
    }
    
    updateCartDisplay();
    showNotification(`${product.name} agregado al carrito`);
}

// Remover producto del carrito
function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    updateCartDisplay();
}

// Actualizar cantidad en el carrito
function updateQuantity(productId, newQuantity) {
    const item = cart.find(item => item.id === productId);
    if (item && newQuantity > 0 && newQuantity <= item.stock) {
        item.quantity = newQuantity;
        updateCartDisplay();
    }
}

// Limpiar carrito
function clearCart() {
    cart = [];
    updateCartDisplay();
    showNotification('Carrito limpiado');
}

// Calcular total del carrito
function getCartTotal() {
    return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
}

// Actualizar visualización del carrito
function updateCartDisplay() {
    const cartContainer = document.getElementById('cart-container');
    const cartCount = document.getElementById('cart-count');
    const cartTotal = document.getElementById('cart-total');
    
    // Actualizar contador
    if (cartCount) {
        cartCount.textContent = cart.length;
    }
    
    // Actualizar total
    if (cartTotal) {
        cartTotal.textContent = `$${getCartTotal().toFixed(2)}`;
    }
    
    // Actualizar contenido del carrito
    if (cartContainer) {
        if (cart.length === 0) {
            cartContainer.innerHTML = `
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-1.1 5.6M7 13h10M17 21a2 2 0 100-4 2 2 0 000 4zM9 21a2 2 0 100-4 2 2 0 000 4z"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">El carrito está vacío</p>
                    <p class="text-gray-400 text-xs">Selecciona productos para comenzar</p>
                </div>
            `;
        } else {
            let cartHTML = '<div class="space-y-3">';
            cart.forEach(item => {
                cartHTML += `
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900 text-sm">${item.name}</h4>
                            <p class="text-xs text-gray-500">$${item.price.toFixed(2)} x ${item.quantity}</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <input type="number" value="${item.quantity}" min="1" max="${item.stock}" 
                                   onchange="updateQuantity(${item.id}, this.value)"
                                   class="w-16 px-2 py-1 border border-gray-300 rounded text-sm">
                            <button onclick="removeFromCart(${item.id})" 
                                    class="text-red-600 hover:text-red-800 text-xs">
                                Quitar
                            </button>
                        </div>
                    </div>
                `;
            });
            cartHTML += '</div>';
            cartContainer.innerHTML = cartHTML;
        }
    }
}

// Filtrar productos por categoría
function filterByCategory(category) {
    const products = document.querySelectorAll('.product-card');
    const buttons = document.querySelectorAll('.category-btn');
    
    // Actualizar botones activos
    buttons.forEach(btn => {
        btn.classList.remove('bg-gradient-to-r', 'from-blue-600', 'to-blue-700', 'text-white');
        btn.classList.add('bg-white', 'border-2', 'border-gray-200', 'text-gray-700');
    });
    
    // Marcar botón activo
    event.target.classList.remove('bg-white', 'border-2', 'border-gray-200', 'text-gray-700');
    event.target.classList.add('bg-gradient-to-r', 'from-blue-600', 'to-blue-700', 'text-white');
    
    // Filtrar productos
    products.forEach(product => {
        const productCategory = product.dataset.category;
        if (category === 'all' || productCategory === category) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
}

// Mostrar notificación
function showNotification(message) {
    // Crear notificación temporal
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform transition-transform duration-300';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Procesar venta (placeholder - aquí irían las llamadas al servidor)
function processSale() {
    if (cart.length === 0) {
        alert('El carrito está vacío');
        return;
    }
    
    // Aquí iría la lógica para procesar la venta
    alert(`Venta procesada: $${getCartTotal().toFixed(2)}`);
    clearCart();
}

// Inicializar cuando cargue la página
document.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();
});