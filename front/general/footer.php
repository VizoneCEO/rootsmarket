<style>
    /* --- NUEVO FOOTER ESTILO FIGMA (VERDE) --- */
    .footer-green-section {
        background-color: #599332; /* Verde similar al del diseño */
        color: #ffffff;
        font-family: sans-serif;
        position: relative;
    }

    /* Franja Naranja Superior */
    .footer-top-strip {
        background-color: #E67E22; /* Naranja de la marca */
        height: 10px;
        width: 100%;
    }

    .footer-content {
        padding: 3rem 0 1rem 0;
    }

    /* Sección Suscripción */
    .subscribe-area {
        text-align: center;
        margin-bottom: 4rem;
    }

    .subscribe-title {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        line-height: 1.2;
    }

    .subscribe-form-container {
        position: relative;
        max-width: 400px;
        margin: 0 auto;
    }

    .subscribe-input {
        width: 100%;
        background-color: rgba(36, 66, 36, 0.3); /* Verde oscuro semi-transparente */
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 50px;
        padding: 12px 50px 12px 25px;
        color: white;
        outline: none;
        font-size: 0.9rem;
    }

    .subscribe-input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    .subscribe-btn {
        position: absolute;
        right: 5px;
        top: 5px;
        bottom: 5px;
        width: 36px;
        background-color: rgba(0,0,0,0.2);
        border: none;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.3s;
    }
    .subscribe-btn:hover { background-color: #E67E22; }

    /* Columnas de Información */
    .footer-cols-row {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-bottom: 3rem;
    }

    /* Logo en Footer */
    .footer-logo img {
        height: 45px;
        /* Filtro para volver el logo totalmente BLANCO */
        filter: brightness(0) invert(1);
        margin-bottom: 1rem;
    }

    .footer-col-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 1.2rem;
        opacity: 0.9;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .footer-links li { margin-bottom: 0.6rem; }
    .footer-links a {
        color: white;
        text-decoration: none;
        font-size: 0.9rem;
        opacity: 0.9;
        transition: opacity 0.2s;
    }
    .footer-links a:hover { opacity: 1; text-decoration: underline; }

    /* Iconos Redes Sociales */
    .social-icon {
        font-size: 1.5rem;
        color: white;
        margin-right: 15px;
        text-decoration: none;
    }

    /* Línea y Copyright */
    .footer-divider {
        border-top: 1px solid rgba(255,255,255,0.3);
        margin-bottom: 1.5rem;
    }

    .footer-legal {
        text-align: center;
        font-size: 0.8rem;
        opacity: 0.8;
    }
    .footer-legal a { color: white; text-decoration: none; margin: 0 5px; }
</style>

<footer class="footer-green-section" id="contacto">
    <div class="footer-top-strip"></div>

    <div class="container footer-content">

        <div class="subscribe-area">
            <h3 class="subscribe-title">
                Suscríbete Para Recibir<br>
                Nuestras Promociones
            </h3>
            <div class="subscribe-form-container">
                <form action="#">
                    <input type="email" class="subscribe-input" placeholder="person@email.com" required>
                    <button type="submit" class="subscribe-btn">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="row footer-cols-row">

            <div class="col-12 col-md-4 mb-4">
                <div class="footer-logo">
                    <img src="front/multimedia/logo.svg" alt="Roots Logo">
                </div>
            </div>

            <div class="col-6 col-md-2 mb-4">
                <h5 class="footer-col-title">ACERCA DE ROOTS</h5>
                <ul class="footer-links">
                    <li><a href="tienda.php">Tienda</a></li>
                    <li><a href="nosotros.php">Nosotros</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-3 mb-4">
                <h5 class="footer-col-title">¿NECESITAS AYUDA?</h5>
                <ul class="footer-links">
                    <li><a href="mailto:ayuda@roots.com">ayuda@roots.com</a></li>
                </ul>
            </div>

            <div class="col-12 col-md-3 mb-4">
                <h5 class="footer-col-title">SÍGUENOS</h5>
                <div>
                    <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                    </div>
            </div>
        </div>

        <div class="footer-divider"></div>

        <div class="footer-legal">
            <p class="mb-2">Copyright &copy; <?php echo date("Y"); ?> Roots Mx, Inc. Derechos Reservados</p>
            <p>
                <a href="aviso.php">Políticas de privacidad</a> |
                <a href="terminos.php">Términos y condiciones</a>
            </p>
        </div>

    </div>
</footer>