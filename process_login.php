<?php
session_start();

// Credenciales de usuario (puedes reemplazar esto con una base de datos)
$validUsername = 'admin';
$validPassword = 'admin';

// Verifica si se enviaron los datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Verifica las credenciales
    if ($username === $validUsername && $password === $validPassword) {
        // Credenciales válidas, inicia sesión
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = $username;

        // Redirige al usuario a la página principal
        header('Location: index.php');
        exit;
    } else {
        // Credenciales inválidas, redirige al login con un mensaje de error
        $_SESSION['error'] = 'Usuario o contraseña incorrectos.';
        header('Location: login.php');
        exit;
    }
} else {
    // Si se accede directamente al archivo, redirige al login
    header('Location: login.php');
    exit;
}