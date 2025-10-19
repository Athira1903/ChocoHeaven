<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - ChocoHeaven</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-5">
        <h2 class="mb-4">Your Shopping Cart</h2>
        
        <div id="cart-items">
            <!-- Cart items will be loaded by JavaScript -->
        </div>
        
        <div class="row mt-4">
            <div class="col-md-8">
                <a href="index.php" class="btn btn-outline-primary">Continue Shopping</a>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5>Cart Summary</h5>
                        <div id="cart-summary">
                            <!-- Summary will be loaded by JavaScript -->
                        </div>
                        <button class="btn btn-success w-100 mt-3" onclick="checkout()">Proceed to Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script>
        function loadCart() {
            const cart = JSON.parse(localStorage.getItem('chocoheaven_cart')) || [];
            const cartItems = document.getElementById('cart-items');
            const cartSummary = document.getElementById('cart-summary');
            
            if (cart.length === 0) {
                cartItems.innerHTML = `
                    <div class="alert alert-info text-center">
                        <h4>Your cart is empty</h4>
                        <p>Add some delicious chocolates to your cart!</p>
                        <a href="index.php" class="btn btn-primary">Start Shopping</a>
                    </div>
                `;
                cartSummary.innerHTML = '<p>Total: ₹0.00</p>';
                return;
            }
            
            let itemsHTML = '';
            let total = 0;
            
            cart.forEach(item => {
                const itemTotal = parseFloat(item.price) * item.quantity;
                total += itemTotal;
                
                itemsHTML += `
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5>${item.name}</h5>
                                    <p class="text-muted">Price: ₹${parseFloat(item.price).toFixed(2)}</p>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" class="form-control" value="${item.quantity}" 
                                           min="1" onchange="updateQuantity(${item.id}, this.value)">
                                </div>
                                <div class="col-md-2">
                                    <p class="fw-bold">₹${itemTotal.toFixed(2)}</p>
                                    <button class="btn btn-danger btn-sm" onclick="removeFromCart(${item.id})">Remove</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            cartItems.innerHTML = itemsHTML;
            cartSummary.innerHTML = `
                <p>Items: ${cart.reduce((sum, item) => sum + item.quantity, 0)}</p>
                <p><strong>Total: ₹${total.toFixed(2)}</strong></p>
            `;
        }
        
        function updateQuantity(productId, newQuantity) {
            let cart = JSON.parse(localStorage.getItem('chocoheaven_cart')) || [];
            const item = cart.find(item => item.id == productId);
            
            if (item && newQuantity > 0) {
                item.quantity = parseInt(newQuantity);
                localStorage.setItem('chocoheaven_cart', JSON.stringify(cart));
                loadCart();
                updateCartCount();
            }
        }
        
        function removeFromCart(productId) {
            let cart = JSON.parse(localStorage.getItem('chocoheaven_cart')) || [];
            cart = cart.filter(item => item.id != productId);
            localStorage.setItem('chocoheaven_cart', JSON.stringify(cart));
            loadCart();
            updateCartCount();
        }
        
        function checkout() {
    const cart = JSON.parse(localStorage.getItem('chocoheaven_cart')) || [];
    if (cart.length === 0) {
        alert('Your cart is empty!');
        return;
    }
    
    <?php if(isset($_SESSION['user_id'])): ?>
        // Redirect to checkout page with cart data
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'checkout.php';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'cart_data';
        input.value = JSON.stringify(cart);
        form.appendChild(input);
        
        document.body.appendChild(form);
        form.submit();
    <?php else: ?>
        alert('Please login to proceed with checkout');
        window.location.href = 'login.php';
    <?php endif; ?>
}       
        // Load cart when page loads
        document.addEventListener('DOMContentLoaded', loadCart);
    </script>
</body>
</html>