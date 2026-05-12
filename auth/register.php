<?php
$pageTitle = 'Ecosphere | Register';
$page_css = 'register.css';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?></title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- Page-specific CSS -->
<link rel="stylesheet" href="../assets/css/<?= $page_css ?>">
</head>
<body>

<div class="register-card">
    <h1>Create Account</h1>
    <p>Sign up to submit reports and track your activity</p>

    <?php if(isset($_GET['error'])): ?>
        <p class="register-error"><?= htmlspecialchars($_GET['error']) ?></p>
    <?php endif; ?>

    <form action="register_process.php" method="POST">
        <div class="form-group">
            <input type="text" name="name" placeholder=" " required>
            <label><i class="fas fa-user"></i> Full Name</label>
        </div>

        <div class="form-group">
            <input type="email" name="email" placeholder=" " required>
            <label><i class="fas fa-envelope"></i> Email</label>
        </div>

        <div class="form-group password-wrapper">
            <input type="password" name="password" id="password" placeholder=" " required>
            <label><i class="fas fa-lock"></i> Password</label>
            <button type="button" class="peek-btn"><i class="fas fa-eye"></i></button>
        </div>

        <button type="submit" class="btn-register">Register</button>

        <div class="register-footer">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </form>
</div>

<script>
const peekBtn = document.querySelector('.peek-btn');
const password = document.querySelector('#password');
peekBtn.addEventListener('click', ()=>{
    if(password.type==='password'){
        password.type='text';
        peekBtn.innerHTML='<i class="fas fa-eye-slash"></i>';
    }else{
        password.type='password';
        peekBtn.innerHTML='<i class="fas fa-eye"></i>';
    }
});
</script>

</body>
</html>
