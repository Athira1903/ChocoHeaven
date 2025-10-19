// Simple Cart Logic
let cart = JSON.parse(localStorage.getItem('chocoheaven_cart')) || [];

document.addEventListener('DOMContentLoaded', function() {
    updateCartCount();
    
    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', (e) => {
            const productId = e.target.getAttribute('data-id');
            const productName = e.target.getAttribute('data-name');
            const productPrice = e.target.getAttribute('data-price');

            // Check if product already in cart
            const existingItem = cart.find(item => item.id === productId);
            
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.push({ 
                    id: productId, 
                    name: productName, 
                    price: productPrice,
                    quantity: 1 
                });
            }
            
            // Save to localStorage
            localStorage.setItem('chocoheaven_cart', JSON.stringify(cart));
            
            // Update UI
            updateCartCount();
            
            // Show notification
            showNotification(`${productName} added to cart!`);
        });
    });
});

function updateCartCount() {
    const cartCount = document.getElementById('cart-count');
    if(cartCount) {
        const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
        cartCount.innerText = totalItems;
    }
}

function showNotification(message) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'alert alert-success position-fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '1050';
    notification.innerHTML = message;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Function to get cart items (for cart.php)
function getCartItems() {
    return cart;
}

// Function to clear cart (for after purchase)
function clearCart() {
    cart = [];
    localStorage.removeItem('chocoheaven_cart');
    updateCartCount();
}