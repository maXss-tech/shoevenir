/* =====================================================
   E-COMMERCE WEBSITE JAVASCRIPT
   Beginner-friendly with educational comments
   ===================================================== */

// Wait for the page to fully load before running scripts
document.addEventListener('DOMContentLoaded', function() {
    console.log('Website loaded!');
    
    // Initialize all features
    initMobileMenu();
    initDropdowns();
    initModal();
    initQuantityControls();
    initCategoryFilters();
    initSmoothScroll();
});

/* =====================================================
   SECTION: Mobile Menu Toggle
   Controls the hamburger menu on mobile devices
   ===================================================== */
function initMobileMenu() {
    const menuBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');
    
    if (menuBtn && navLinks) {
        menuBtn.addEventListener('click', function() {
            // Toggle the 'active' class to show/hide menu
            navLinks.classList.toggle('active');
            
            // Change hamburger icon to X and back
            if (navLinks.classList.contains('active')) {
                menuBtn.innerHTML = '✕';
            } else {
                menuBtn.innerHTML = '☰';
            }
        });
        
        // Close menu when clicking a link
        navLinks.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                navLinks.classList.remove('active');
                menuBtn.innerHTML = '☰';
            });
        });
    }
}

/* =====================================================
   SECTION: Dropdown Menus
   Controls dropdown behavior on click (for mobile)
   ===================================================== */
function initDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown');
    
    dropdowns.forEach(function(dropdown) {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        
        if (toggle) {
            toggle.addEventListener('click', function(e) {
                // Prevent link navigation if it's just a toggle
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    dropdown.classList.toggle('active');
                }
            });
        }
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            dropdowns.forEach(function(dropdown) {
                dropdown.classList.remove('active');
            });
        }
    });
}

/* =====================================================
   SECTION: Modal/Popup Functions
   Controls modal open/close behavior
   ===================================================== */
function initModal() {
    const modalOverlay = document.querySelector('.modal-overlay');
    const modalClose = document.querySelector('.modal-close');
    const modalTriggers = document.querySelectorAll('[data-modal]');
    
    // Open modal when trigger is clicked
    modalTriggers.forEach(function(trigger) {
        trigger.addEventListener('click', function() {
            if (modalOverlay) {
                modalOverlay.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            }
        });
    });
    
    // Close modal when X is clicked
    if (modalClose) {
        modalClose.addEventListener('click', closeModal);
    }
    
    // Close modal when clicking outside
    if (modalOverlay) {
        modalOverlay.addEventListener('click', function(e) {
            if (e.target === modalOverlay) {
                closeModal();
            }
        });
    }
    
    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
}

function closeModal() {
    const modalOverlay = document.querySelector('.modal-overlay');
    if (modalOverlay) {
        modalOverlay.classList.remove('active');
        document.body.style.overflow = ''; // Re-enable scrolling
    }
}

/* =====================================================
   SECTION: Quantity Controls (Cart Page)
   Handles +/- buttons for product quantities
   ===================================================== */
function initQuantityControls() {
    const quantityContainers = document.querySelectorAll('.quantity-controls');
    
    quantityContainers.forEach(function(container) {
        const minusBtn = container.querySelector('.quantity-minus');
        const plusBtn = container.querySelector('.quantity-plus');
        const display = container.querySelector('.quantity-display');
        const input = container.querySelector('input[name="quantity"]');
        
        if (minusBtn && plusBtn && display) {
            minusBtn.addEventListener('click', function() {
                let value = parseInt(display.textContent);
                if (value > 1) {
                    value--;
                    display.textContent = value;
                    if (input) input.value = value;
                }
            });
            
            plusBtn.addEventListener('click', function() {
                let value = parseInt(display.textContent);
                value++;
                display.textContent = value;
                if (input) input.value = value;
            });
        }
    });
}

/* =====================================================
   SECTION: Category Filter Buttons
   Filters products by category on products page
   ===================================================== */
function initCategoryFilters() {
    const filterButtons = document.querySelectorAll('.category-btn');
    const products = document.querySelectorAll('.product-card');
    
    filterButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const category = this.dataset.category;
            
            // Show/hide products based on category
            products.forEach(function(product) {
                if (category === 'all' || product.dataset.category === category) {
                    product.style.display = 'block';
                    product.classList.add('fade-in');
                } else {
                    product.style.display = 'none';
                }
            });
        });
    });
}

/* =====================================================
   SECTION: Smooth Scrolling
   Enables smooth scroll for anchor links
   ===================================================== */
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            
            if (targetId !== '#') {
                e.preventDefault();
                const target = document.querySelector(targetId);
                
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
}

/* =====================================================
   SECTION: Toast Notifications
   Shows popup messages for user feedback
   ===================================================== */
function showToast(message, type = 'success') {
    // Remove existing toast if any
    const existingToast = document.querySelector('.toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Create new toast element
    const toast = document.createElement('div');
    toast.className = 'toast ' + type;
    toast.textContent = message;
    
    // Add to page
    document.body.appendChild(toast);
    
    // Show toast (trigger CSS animation)
    setTimeout(function() {
        toast.classList.add('show');
    }, 10);
    
    // Hide toast after 3 seconds
    setTimeout(function() {
        toast.classList.remove('show');
        setTimeout(function() {
            toast.remove();
        }, 300);
    }, 3000);
}

/* =====================================================
   SECTION: Add to Cart Function
   Handles adding products to shopping cart
   ===================================================== */
function addToCart(productId) {
    // Create form data
    const formData = new FormData();
    formData.append('product_id', productId);
    formData.append('action', 'add');
    
    // Send request to server
    fetch('php/cart-functions.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Product added to cart!', 'success');
            // Update cart badge count
            updateCartBadge(data.cartCount);
        } else {
            showToast(data.message || 'Failed to add product', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred', 'error');
    });
}

/* =====================================================
   SECTION: Update Cart Badge
   Updates the number shown on cart icon
   ===================================================== */
function updateCartBadge(count) {
    const badge = document.querySelector('.cart-badge');
    if (badge) {
        badge.textContent = count;
        if (count > 0) {
            badge.style.display = 'block';
        } else {
            badge.style.display = 'none';
        }
    }
}

/* =====================================================
   SECTION: Remove from Cart
   Handles removing items from cart
   ===================================================== */
function removeFromCart(cartItemId) {
    if (confirm('Remove this item from cart?')) {
        const formData = new FormData();
        formData.append('cart_id', cartItemId);
        formData.append('action', 'remove');
        
        fetch('php/cart-functions.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Item removed from cart', 'success');
                // Reload page to update cart display
                location.reload();
            } else {
                showToast('Failed to remove item', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred', 'error');
        });
    }
}

/* =====================================================
   SECTION: Form Validation
   Basic client-side form validation
   ===================================================== */
function validateForm(formElement) {
    let isValid = true;
    const inputs = formElement.querySelectorAll('input[required], textarea[required]');
    
    inputs.forEach(function(input) {
        // Remove previous error styling
        input.style.borderColor = '';
        
        if (!input.value.trim()) {
            input.style.borderColor = '#e74c3c';
            isValid = false;
        }
        
        // Email validation
        if (input.type === 'email' && input.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(input.value)) {
                input.style.borderColor = '#e74c3c';
                isValid = false;
            }
        }
    });
    
    return isValid;
}

/* =====================================================
   SECTION: Admin Functions
   Functions used in admin panel
   ===================================================== */
function deleteProduct(productId) {
    if (confirm('Are you sure you want to delete this product?')) {
        window.location.href = 'delete-product.php?id=' + productId;
    }
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        window.location.href = 'delete-user.php?id=' + userId;
    }
}

function updateOrderStatus(orderId, status) {
    const formData = new FormData();
    formData.append('order_id', orderId);
    formData.append('status', status);
    
    fetch('update-order-status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Order status updated', 'success');
        } else {
            showToast('Failed to update status', 'error');
        }
    });
}

/* =====================================================
   SECTION: Image Preview
   Shows preview when uploading product images
   ===================================================== */
function previewImage(input) {
    const preview = document.querySelector('.image-preview');
    
    if (input.files && input.files[0] && preview) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}
