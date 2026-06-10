// ========== PASSWORD STRENGTH CHECKER ==========
function checkPasswordStrength(password) {
    let strength = 0;
    let message = "";
    let color = "";
    
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    if (strength <= 2) {
        message = "Weak";
        color = "red";
    } else if (strength <= 4) {
        message = "Medium";
        color = "orange";
    } else {
        message = "Strong";
        color = "green";
    }
    
    return { message, color, score: strength };
}

function showPasswordStrength() {
    let password = document.getElementById('password');
    let strengthDiv = document.getElementById('password-strength');
    
    if (password && strengthDiv) {
        password.addEventListener('keyup', function() {
            let result = checkPasswordStrength(this.value);
            strengthDiv.innerHTML = '<span style="color:' + result.color + ';">' + result.message + '</span>';
        });
    }
}

// ========== LOGIN VALIDATION ==========
function validateLogin() {
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    
    if (email == "") {
        alert("Email address required");
        return false;
    }
    if (!email.includes("@") || !email.includes(".")) {
        alert("Enter valid email address");
        return false;
    }
    if (password == "") {
        alert("Password required");
        return false;
    }
    if (password.length < 4) {
        alert("Password must be at least 4 characters");
        return false;
    }
    return true;
}

// ========== PRODUCT VALIDATION ==========
function validateProduct() {
    let name = document.getElementById('prod_name').value;
    let brand = document.getElementById('prod_brand').value;
    let size = document.getElementById('prod_size').value;
    let price = document.getElementById('prod_price').value;
    let stock = document.getElementById('prod_stock').value;
    
    if (name == "") {
        alert("Product name required");
        return false;
    }
    if (brand == "") {
        alert("Brand required");
        return false;
    }
    if (size == "" || size <= 0) {
        alert("Valid size required");
        return false;
    }
    if (price == "" || price <= 0) {
        alert("Price must be greater than 0");
        return false;
    }
    if (stock == "" || stock < 0) {
        alert("Stock cannot be negative");
        return false;
    }
    return true;
}

// ========== EMPLOYEE VALIDATION ==========
function validateEmployee() {
    let name = document.getElementById('emp_name').value;
    let email = document.getElementById('emp_email').value;
    let password = document.getElementById('emp_password').value;
    let role = document.getElementById('emp_role').value;
    
    if (name == "") {
        alert("Employee name required");
        return false;
    }
    if (email == "" || !email.includes("@")) {
        alert("Valid email required");
        return false;
    }
    if (password == "") {
        alert("Password required");
        return false;
    }
    if (password.length < 8) {
        alert("Password must be at least 8 characters");
        return false;
    }
    let strength = checkPasswordStrength(password);
    if (strength.score < 3) {
        alert("Password too weak. Use uppercase, lowercase, numbers");
        return false;
    }
    if (role == "") {
        alert("Select a role");
        return false;
    }
    return true;
}

// ========== SALE VALIDATION ==========
function validateSale() {
    let product = document.getElementById('sale_product').value;
    let quantity = document.getElementById('sale_qty').value;
    
    if (product == "") {
        alert("Select a product");
        return false;
    }
    if (quantity == "" || quantity <= 0) {
        alert("Quantity must be greater than 0");
        return false;
    }
    return confirm("Record this sale? This will reduce stock.");
}

// ========== STOCK VALIDATION ==========
function validateStock() {
    let product = document.getElementById('stock_product').value;
    let quantity = document.getElementById('stock_qty').value;
    
    if (product == "") {
        alert("Select a product");
        return false;
    }
    if (quantity == "" || quantity <= 0) {
        alert("Quantity must be greater than 0");
        return false;
    }
    return confirm("Add " + quantity + " units to stock?");
}

// ========== DELETE CONFIRMATION ==========
function confirmDelete(itemName) {
    return confirm("Delete " + itemName + "? This cannot be undone.");
}

// ========== RUN ON PAGE LOAD ==========
document.addEventListener('DOMContentLoaded', function() {
    showPasswordStrength();
});