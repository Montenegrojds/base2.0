<?php
session_start();

function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

function is_admin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'Administrador';
}

function is_gerente() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'Gerente';
}

function is_recepcionista() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'Recepcionista';
}

function is_cliente() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'Cliente';
}
?>
