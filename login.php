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

$info_msg = "";

if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'booking') {
        $info_msg = "You need to do login, you cannot book without logging in.";
    } elseif ($_GET['msg'] == 'contact') {
        $info_msg = "You need to do login to contact us.";
    } elseif ($_GET['msg'] == 'password_reset') {
        $info_msg = "Password reset successfully! Please log in with your new password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Atlas Tour - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-dark">
<?php include "includes/header.php"; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="bg-white shadow p-4 rounded text-dark">
        <div>
          <h2 class="text-center mb-4">Login</h2>
          
          <div id="alertContainer">
            <?php if($info_msg != "") { echo "<div class='alert alert-warning text-center fw-bold'>$info_msg</div>"; } ?>
          </div>

          <form id="loginForm">
            <div class="mb-3">
              <label>Email</label>
              <input type="email" name="email" class="form-control" required placeholder="Enter Email">
            </div>
            <div class="mb-3">
              <label>Password</label>
              <input type="password" name="password" class="form-control" required placeholder="Enter Password">
            </div>
            <button type="submit" class="btn btn-info rounded-pill fw-bold w-100 mt-3 text-dark">Login</button>
            <div class="text-end mt-2">
              <a href="modules/auth/forgot_password.php" class="text-warning text-decoration-none small">Forgot Password?</a>
            </div>
          </form>

          <p class="mt-4 text-center">Don't have an account? <a href="register.php" class="text-primary">Register Here</a></p>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include "includes/footer.php"; ?>
<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = this.email.value;
    const password = this.password.value;
    const alertContainer = document.getElementById('alertContainer');
    const submitBtn = this.querySelector('button[type="submit"]');
    
    // Clear previous alerts
    alertContainer.innerHTML = '';
    
    // Disable button during loading
    submitBtn.disabled = true;
    submitBtn.textContent = 'Logging in...';
    
    fetch('api/login.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ email, password })
    })
    .then(response => response.json().then(data => ({ ok: response.ok, body: data })))
    .then(res => {
        if (res.ok && res.body.success) {
            alertContainer.innerHTML = `<div class='alert alert-success text-center fw-bold'>${res.body.message} Redirecting...</div>`;
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 1000);
        } else {
            alertContainer.innerHTML = `<div class='alert alert-danger'>${res.body.message || 'Invalid Email or Password!'}</div>`;
            submitBtn.disabled = false;
            submitBtn.textContent = 'Login';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alertContainer.innerHTML = `<div class='alert alert-danger'>An error occurred. Please try again later.</div>`;
        submitBtn.disabled = false;
        submitBtn.textContent = 'Login';
    });
});
</script>
</body>
</html>
