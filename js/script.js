// Password strength checker
function checkPasswordStrength(password) {
    if (password.length < 4) return "Weak";
    if (password.length < 8) return "Medium";
    return "Strong";
}

function showPasswordStrength() {
    let password = document.getElementById('emp_password');
    let strengthDiv = document.getElementById('password-strength');
    
    if (password && strengthDiv) {
        password.addEventListener('keyup', function() {
            let strength = checkPasswordStrength(this.value);
            let color = strength == "Weak" ? "red" : (strength == "Medium" ? "orange" : "green");
            strengthDiv.innerHTML = '<span style="color:' + color + ';">' + strength + '</span>';
        });
    }
}

// Login validation
function validateLogin() {
    let email = document.getElementById('email').value;
    let password = document.getElementById('password').value;
    if (email == "") { alert("Email required"); return false; }
    if (password == "") { alert("Password required"); return false; }
    return true;
}

// Product validation
function validateProduct() {
    let name = document.getElementById('prod_name').value;
    let price = document.getElementById('prod_price').value;
    if (name == "") { alert("Product name required"); return false; }
    if (price <= 0) { alert("Price must be greater than 0"); return false; }
    return true;
}

// Employee validation with password strength
function validateEmployee() {
    let name = document.getElementById('emp_name').value;
    let email = document.getElementById('emp_email').value;
    let password = document.getElementById('emp_password').value;
    
    if (name == "") { alert("Name required"); return false; }
    if (email == "" || !email.includes("@")) { alert("Valid email required"); return false; }
    if (password == "") { alert("Password required"); return false; }
    if (password.length < 4) { alert("Password must be at least 4 characters"); return false; }
    return true;
}

// Sale validation
function validateSale() {
    let qty = document.getElementById('sale_qty').value;
    if (qty <= 0) { alert("Quantity must be greater than 0"); return false; }
    return confirm("Record this sale?");
}

// Stock validation
function validateStock() {
    let qty = document.getElementById('stock_qty').value;
    if (qty <= 0) { alert("Quantity must be greater than 0"); return false; }
    return confirm("Add stock?");
}

// Delete confirmation
function confirmDelete(item) {
    return confirm("Delete " + item + "?");
}

// Run on page load
document.addEventListener('DOMContentLoaded', function() {
    showPasswordStrength();
});