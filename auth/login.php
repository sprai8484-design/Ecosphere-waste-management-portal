<?php
// login_dynamic.php

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard/user/User_dashboard.php");
    exit;
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ecosphere | Login</title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
:root{
    --primary:#1b7f5c;
    --secondary:#e6f4ef;
    --dark:#0f172a;
    --muted:#475569;
}

/* Full-screen login background */
body {
    margin:0;
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #d1fae5, #ecfdf5);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    overflow:hidden;
}

/* Animated background shapes */
body::before, body::after {
    content:"";
    position:absolute;
    border-radius:50%;
    filter: blur(120px);
}
body::before {
    width:400px; height:400px;
    background:#1b7f5c;
    top:-100px; left:-100px;
}
body::after {
    width:300px; height:300px;
    background:#065f46;
    bottom:-80px; right:-80px;
}

/* Login card */
.login-card {
    position: relative;
    background: #fff;
    width: 100%;
    max-width: 420px;
    padding: 40px;
    border-radius: 24px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.12);
    animation: fadeIn 1s ease forwards;
}

/* Header */
.login-card h1 {
    color: var(--primary);
    text-align: center;
    margin-bottom: 10px;
}
.login-card p {
    text-align: center;
    color: var(--muted);
    margin-bottom: 30px;
}

/* Form group floating labels */
.form-group {
    position: relative;
    margin-bottom: 25px;
}
.form-group input {
    width:100%;
    padding:14px 14px;
    border-radius:12px;
    border:1px solid #cbd5e1;
    font-size:0.95rem;
    transition: all 0.3s ease;
}
.form-group input:focus {
    outline:none;
    border-color: var(--primary);
    box-shadow:0 0 8px rgba(27,127,92,0.25);
}
.form-group label {
    position:absolute;
    top:50%;
    left:14px;
    transform: translateY(-50%);
    pointer-events:none;
    color:#64748b;
    transition:0.3s ease all;
    font-size:0.95rem;
}
.form-group input:focus + label,
.form-group input:not(:placeholder-shown) + label {
    top:-8px;
    left:10px;
    font-size:0.8rem;
    color: var(--primary);
    background:#fff;
    padding:0 4px;
}

/* Password peek */
.password-wrapper {position:relative;}
.peek-btn {
    position:absolute;
    right:10px;
    top:50%;
    transform:translateY(-50%);
    border:none;
    background:none;
    cursor:pointer;
    color:#64748b;
    font-size:1rem;
    transition: color 0.3s;
}
.peek-btn:hover { color: var(--primary); }

/* Login button */
.btn-login {
    width:100%;
    padding:14px;
    border-radius:30px;
    border:none;
    font-weight:600;
    cursor:pointer;
    background: linear-gradient(135deg, #1b7f5c, #065f46);
    color:#fff;
    transition: all 0.3s ease;
}
.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(27,127,92,0.35);
}

/* Footer link */
.login-footer {
    margin-top: 18px;
    text-align:center;
}
.login-footer a {
    color: var(--primary);
    font-weight:600;
}

/* Card fade-in */
@keyframes fadeIn {
    0% {opacity:0; transform: translateY(30px);}
    100% {opacity:1; transform: translateY(0);}
}

/* Responsive */
@media(max-width: 500px){
    .login-card {padding:30px 20px;}
}
</style>
</head>
<body>

<div class="login-card">
    <h1>Welcome Back</h1>
    <p>Login to access your dashboard</p>

    <?php if(isset($_GET['error'])): ?>
        <p style="color:red; text-align:center;">Invalid email or password</p>
    <?php endif; ?>

    <form action="login_process.php" method="POST">
        <div class="form-group">
            <input type="email" name="email" placeholder=" " required>
            <label>Email <i class="fas fa-envelope"></i></label>
        </div>

        <div class="form-group password-wrapper">
            <input type="password" name="password" id="password" placeholder=" " required>
            <label>Password <i class="fas fa-lock"></i></label>
            <button type="button" class="peek-btn"><i class="fas fa-eye"></i></button>
        </div>

        <button type="submit" class="btn-login">Login</button>

        <div class="login-footer">
            Don’t have an account? <a href="register.php">Register here</a>
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
