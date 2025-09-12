<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <title>Roots - Registro</title>
    <style>
        .register-container {
            max-width: 450px;
            margin: 5rem auto;
            padding: 2.5rem;
            background-color: #ffffff;
            border-radius: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 15px;
            padding: 12px 20px;
        }
        .btn-register {
            border-radius: 15px;
            padding: 12px;
            font-weight: bold;
            background-color: #2d4c48;
            color: white;
            width: 100%;
            border: none;
        }
        .btn-register:hover {
            background-color: #1d3331;
        }
        .text-green {
            color: #2d4c48;
        }
        a.text-green:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Encabezado general -->
<?php include 'front/general/header.php'; ?>

<!-- Contenedor del formulario de registro -->
<div class="container">
    <div class="register-container">
        <h2 class="text-center fw-bold mb-4 text-green">Crea tu cuenta</h2>

        <!-- Formulario de Registro -->
        <form action="back/login/aut.php?action=register" method="POST">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre Completo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu nombre" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="tu.correo@ejemplo.com" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Mínimo 8 caracteres" required>
            </div>
            <button type="submit" class="btn btn-register">Registrarse</button>
        </form>

        <p class="text-center mt-4">
            ¿Ya tienes una cuenta? <a href="login.php" class="fw-bold text-green">Inicia sesión</a>
        </p>
    </div>
</div>

<!-- Pie de página general -->
<?php include 'front/general/footer.php'; ?>

<!-- Scripts de Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>
</html>
