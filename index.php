<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Hotel - Video</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style_inicio.css"> 
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Gestión Hotel</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a target="_blank" class="nav-link" href="login.php">Iniciar Sesión</a></li>
                <li class="nav-item"><a target="_blank" class="nav-link" href="register.php">Registrarse</a></li>
            </ul>
        </div>
    </nav>

    <!-- Video -->
    <header class="hero">
        <video autoplay loop muted poster="fallback.jpg">
            <source src="videos_adi/video_intro.mp4" type="video/mp4">
            <source src="videos_adi/video_intro.webm" type="video/webm">
            <source src="videos_adi/video_intro.ogv" type="video/ogv">
            ERROR - No reproduce el video :,,(
        </video>
    </header>

</body>
</html>

