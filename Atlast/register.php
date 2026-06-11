<?php
include "config/database.php";

// Redirect if already logged in
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Atlas Tour - Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-dark">

<?php include "includes/header.php"; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="bg-white shadow p-4 rounded text-dark">
        <div>
          <h2 class="text-center mb-4">Register</h2>
          
          <div id="alertContainer"></div>

          <form id="registerForm">
            <div class="mb-3">
              <label>Full Name</label>
              <input type="text" name="full_name" class="form-control" required placeholder="Your Name">
            </div>
            <div class="mb-3">
              <label>Phone Number</label>
              <input type="number" name="phone" class="form-control" required placeholder="Phone">
            </div>
            <div class="mb-3">
              <label>Email</label>
              <input type="email" name="email" class="form-control" required placeholder="Email Address">
            </div>
            <div class="mb-3">
              <label>Password</label>
              <input type="password" name="password" class="form-control" required placeholder="Password">
            </div>
            <div class="mb-3">
              <label>Confirm Password</label>
              <input type="password" name="confirm_password" class="form-control" required placeholder="Confirm Password">
            </div>
            <button type="submit" class="btn btn-info rounded-pill fw-bold w-100 mt-3 text-dark">Register</button>
          </form>

          <p class="mt-4 text-center">Already have an account? <a href="login.php" class="text-primary">Login Here</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include "includes/footer.php"; ?>
<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const fullName = this.full_name.value;
    const phone = this.phone.value;
    const email = this.email.value;
    const password = this.password.value;
    const confirmPassword = this.confirm_password.value;
    const alertContainer = document.getElementById('alertContainer');
    const submitBtn = this.querySelector('button[type="submit"]');
    
    // Clear previous alerts
    alertContainer.innerHTML = '';
    
    // Validate passwords match
    if (password !== confirmPassword) {
        alertContainer.innerHTML = `<div class='alert alert-danger'>Passwords do not match!</div>`;
        return;
    }
    
    // Disable button during loading
    submitBtn.disabled = true;
    submitBtn.textContent = 'Registering...';
    
    fetch('api/register.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            full_name: fullName,
            phone: phone,
            email: email,
            password: password
        })
    })
    .then(response => response.json().then(data => ({ ok: response.ok, body: data })))
    .then(res => {
        if (res.ok && res.body.success) {
            alertContainer.innerHTML = `<div class='alert alert-success'>${res.body.message} <a href='login.php' class='alert-link'>Click here to login</a></div>`;
            this.reset();
        } else {
            alertContainer.innerHTML = `<div class='alert alert-danger'>${res.body.message || 'Registration failed.'}</div>`;
        }
        submitBtn.disabled = false;
        submitBtn.textContent = 'Register';
    })
    .catch(error => {
        console.error('Error:', error);
        alertContainer.innerHTML = `<div class='alert alert-danger'>An error occurred. Please try again later.</div>`;
        submitBtn.disabled = false;
        submitBtn.textContent = 'Register';
    });
});
</script>
</body>
</html>
