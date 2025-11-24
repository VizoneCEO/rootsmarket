<style>
    /* --- ESTILOS DEL FOOTER (RESKINNING) --- */
    .footer-dark {
        background-color: #333333; /* Gris oscuro/Negro suave */
        color: #ffffff;
        padding-top: 4rem;
        padding-bottom: 2rem;
        font-family: sans-serif;
    }

    /* Logo Verde Centrado */
    .footer-logo {
        height: 50px;
        margin-bottom: 2rem;
        filter: brightness(0) saturate(100%) invert(57%) sepia(78%) saturate(466%) hue-rotate(85deg) brightness(93%) contrast(95%);
        /* El filtro intenta igualar el verde #4EAE3E si el SVG es negro. Si tu SVG ya es verde, quita el filtro */
    }

    /* Sección de Suscripción */
    .subscribe-title {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 2rem;
        line-height: 1.3;
    }

    .subscribe-form-container {
        position: relative;
        max-width: 500px;
        margin: 0 auto 4rem auto; /* Margen inferior grande para separar de los enlaces */
    }

    .subscribe-input {
        width: 100%;
        background-color: rgba(255, 255, 255, 0.1); /* Fondo transparente */
        border: 1px solid #555;
        border-radius: 50px;
        padding: 15px 60px 15px 25px;
        color: white;
        outline: none;
        transition: all 0.3s;
    }

    .subscribe-input:focus {
        background-color: rgba(255, 255, 255, 0.2);
        border-color: #4EAE3E;
    }

    .subscribe-input::placeholder {
        color: #aaa;
    }

    .subscribe-btn {
        position: absolute;
        right: 5px;
        top: 5px;
        bottom: 5px;
        width: 45px;
        background-color: #444;
        border: none;
        border-radius: 50%;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.3s;
    }

    .subscribe-btn:hover {
        background-color: #4EAE3E;
    }

    /* Columnas de Enlaces */
    .footer-col-title {
        color: #888;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1.5rem;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 0.8rem;
    }

    .footer-links a {
        color: #fff;
        text-decoration: none;
        font-size: 1rem;
        transition: color 0.3s;
    }

    .footer-links a:hover {
        color: #4EAE3E;
    }

    /* Barra Inferior */
    .footer-bottom {
        margin-top: 4rem;
        padding-top: 2rem;
        border-top: 1px solid #444;
        text-align: center;
        color: #888;
        font-size: 0.9rem;
    }
</style>

<footer class="footer-dark" id="contacto">
    <div class="container text-center">

        <img src="front/multimedia/logo.svg" alt="Roots Logo" class="footer-logo">

        <h3 class="subscribe-title">
            Suscríbete Para Que Te Lleguen<br>
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

        <div class="row text-start justify-content-center">
            <div class="col-6 col-md-3 mb-4">
                <h5 class="footer-col-title">Productos</h5>
                <ul class="footer-links">
                    <li><a href="tienda.php">Frutas y Verduras</a></li>
                    <li><a href="tienda.php">Despensa</a></li>
                    <li><a href="tienda.php">Lácteos y Huevos</a></li>
                    <li><a href="tienda.php">Bebidas</a></li>
                    <li><a href="tienda.php">Nuevos Productos</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-3 mb-4">
                <h5 class="footer-col-title">Nosotros</h5>
                <ul class="footer-links">
                    <li><a href="nosotros.php">Nuestra Historia</a></li>
                    <li><a href="index.php#iniciativas">Iniciativas Roots</a></li>
                    <li><a href="index.php#iniciativas">Impulso Local</a></li>
                    <li><a href="#">Sostenibilidad</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-3 mb-4">
                <h5 class="footer-col-title">Ayuda</h5>
                <ul class="footer-links">
                    <li><a href="#">Centro de Ayuda</a></li>
                    <li><a href="#">Envíos y Devoluciones</a></li>
                    <li><a href="#">Preguntas Frecuentes</a></li>
                    <li><a href="index.php#contacto">Contáctanos</a></li>
                </ul>
            </div>

            <div class="col-6 col-md-3 mb-4">
                <h5 class="footer-col-title">Legales</h5>
                <ul class="footer-links">
                    <li><a href="terminos.php">Términos y Condiciones</a></li>
                    <li><a href="aviso.php">Aviso de Privacidad</a></li>
                    <li><a href="#">Política de Cookies</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date("Y"); ?> Roots Market. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>