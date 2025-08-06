<?php
session_start();

// Usuario y contraseña encriptada
$usuario_valido = 'sistemas';
$contrasena_encriptada = '$2y$10$mcDnrMJ.mTGgOOiBxr68z.kICN.f6jqOkTRN5XBeEHbvWF4fc/jsS'; // Asegúrate de usar la contraseña encriptada correcta

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $usuario_valido) {
        if (password_verify($password, $contrasena_encriptada)) {
            // Autenticación exitosa
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit();
        } else {
            echo "<script>alert('Contraseña incorrecta'); window.location.href='inicio.html';</script>";
        }
    } else {
        echo "<script>alert('Usuario incorrecto'); window.location.href='inicio.html';</script>";
    }
} else {
    header('Location: inicio.html');
    exit();
}
?>
