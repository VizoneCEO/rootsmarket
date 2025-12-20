<style>
    .login-container {
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

    .btn-login {
        border-radius: 15px;
        padding: 12px;
        font-weight: bold;
        background-color: #2d4c48;
        color: white;
        width: 100%;
        border: none;
    }

    .btn-login:hover {
        background-color: #1d3331;
    }

    .btn-google {
        border-radius: 15px;
        padding: 12px;
        font-weight: 500;
        background-color: #f2f2f2;
        color: #333;
        width: 100%;
        border: 1px solid #e0e0e0;
        text-decoration: none;
        /* Añadido para que el enlace no se vea subrayado */
    }

    .btn-google img {
        width: 20px;
        margin-right: 10px;
    }

    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        color: #aaa;
        margin: 1.5rem 0;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e0e0e0;
    }

    .divider:not(:empty)::before {
        margin-right: .25em;
    }

    .divider:not(:empty)::after {
        margin-left: .25em;
    }

    .text-green {
        color: #2d4c48;
    }

    a.text-green:hover {
        text-decoration: underline;
    }
</style>

<div class="container">
    <div class="login-container">
        <h2 class="text-center fw-bold mb-4 text-green">Bienvenido de Vuelta</h2>

        <?php
        if (isset($_GET['error'])) {
            $error = $_GET['error'];
            $alertClass = "alert-danger";
            $message = "";

            switch ($error) {
                case 'credentials':
                    $message = "Correo o contraseña incorrectos.";
                    break;
                case 'invalid_data':
                    $message = "Por favor, completa todos los campos correctamente.";
                    break;
                case 'email_exists':
                    $message = "Este correo ya está registrado. Intenta iniciar sesión.";
                    break;
                case 'google_not_configured':
                    $message = "El inicio de sesión con Google no está disponible en este momento. Por favor usa tu correo y contraseña.";
                    break;
                case 'db_error':
                    $message = "Error en el sistema. Por favor intenta más tarde.";
                    break;
                default:
                    $message = "Ocurrió un error inesperado.";
            }

            if ($message) {
                echo '<div class="alert ' . $alertClass . ' alert-dismissible fade show" role="alert">
                        ' . $message . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
        }

        if (isset($_GET['success'])) {
            $success = $_GET['success'];
            $message = "";

            switch ($success) {
                case 'registered':
                    $message = "¡Cuenta creada con éxito! Ahora puedes iniciar sesión.";
                    break;
            }

            if ($message) {
                echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                        ' . $message . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }
        }
        ?>

        <form action="back/login/aut.php?action=login" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="tu.correo@ejemplo.com"
                    required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="********"
                    required>
            </div>
            <div class="text-end mb-3">
                <a href="#" class="text-green small">¿Olvidaste tu contraseña?</a>
            </div>
            <button type="submit" class="btn btn-login">Iniciar Sesión</button>
        </form>

        <div class="divider">o</div>

        <a href="back/login/aut.php?action=login_google"
            class="btn btn-google d-flex align-items-center justify-content-center">
            <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="Google logo">
            Continuar con Google
        </a>

        <p class="text-center mt-4">
            ¿No tienes una cuenta? <a href="registro.php" class="fw-bold text-green">Regístrate</a>
        </p>
    </div>
</div>