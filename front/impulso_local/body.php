<style>
    /* --- HERO SECTION --- */
    .impulso-hero {
        padding: 4rem 0 2rem 0;
    }

    .hero-img-wrapper {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
    }

    .hero-img-wrapper img {
        width: 100%;
        height: auto;
    }

    .hero-badge-green {
        position: absolute;
        top: 50%;
        right: 0;
        transform: translateY(-50%);
        background-color: #4EAE3E;
        /* Verde Roots */
        color: white;
        padding: 15px 40px 15px 30px;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 2rem;
        border-top-left-radius: 50px;
        border-bottom-left-radius: 50px;
        box-shadow: -5px 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* --- INTRO SECTION --- */
    .intro-title {
        font-weight: 800;
        text-transform: uppercase;
        color: #333;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .intro-subtitle {
        font-weight: 700;
        color: #333;
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }

    .intro-text {
        color: #666;
        line-height: 1.6;
        font-size: 0.95rem;
        margin-bottom: 1rem;
    }

    .intro-images img {
        border-radius: 20px;
        width: 100%;
        height: 250px;
        object-fit: cover;
    }

    /* --- HOW IT WORKS --- */
    .how-title {
        font-weight: 800;
        color: #333;
        font-size: 1.3rem;
        margin-bottom: 1.5rem;
    }

    .step-title {
        font-weight: 700;
        color: #333;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .step-text {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1.5rem;
    }

    /* --- STATS BANNER --- */
    .stats-banner {
        background-color: #E67E22;
        /* Naranja Roots */
        padding: 3rem 0;
        color: white;
        text-align: center;
    }

    .stat-number {
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    /* --- ALIADOS SECTION --- */
    .aliados-section {
        padding: 4rem 0;
    }

    .aliado-card {
        margin-bottom: 1.5rem;
    }

    .aliado-img {
        border-radius: 20px;
        width: 100%;
        height: 200px;
        object-fit: cover;
        margin-bottom: 1rem;
    }

    .aliado-name {
        font-weight: 700;
        color: #333;
        font-size: 1rem;
    }

    /* --- CTA BUTTONS --- */
    .btn-green-pill {
        background-color: #4EAE3E;
        color: white;
        border-radius: 50px;
        padding: 12px 40px;
        font-weight: 700;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
        border: none;
    }

    .btn-green-pill:hover {
        background-color: #3d8b31;
        color: white;
    }
</style>

<div class="container impulso-hero">

    <!-- Hero Image -->
    <div class="hero-img-wrapper mb-5">
        <!-- Placeholder image -->
        <img src="front/multimedia/il1.jpg" alt="Impulso Local Hero">

    </div>

    <!-- Intro Text -->
    <div class="mb-5">
        <h2 class="intro-title">Impulso Local</h2>
        <h3 class="intro-subtitle">Apoya lo que nace aquí.</h3>
        <p class="intro-text">
            Creemos que el cambio empieza cerca.<br>
            En Roots impulsamos el consumo responsable y la economía local apoyando a productores que trabajan con
            respeto por la tierra y las personas.
        </p>
        <p class="intro-text">
            Cada vez que eliges productos locales, reduces tu huella ambiental, fortaleces a tu comunidad y contribuyes
            a un futuro más justo y sostenible.
        </p>
    </div>

    <!-- Intro Images -->
    <div class="row g-4 mb-5 intro-images">
        <div class="col-md-6">
            <img src="front/multimedia/il2.png" alt="Productos Locales 1">
        </div>
        <div class="col-md-6">
            <img src="front/multimedia/il3.png" alt="Productos Locales 2">
        </div>
    </div>

    <!-- How It Works -->
    <div class="mb-5">
        <h3 class="how-title">¿Cómo Funciona?</h3>

        <div class="mb-4">
            <h4 class="step-title">Verificación</h4>
            <p class="step-text">
                Antes de agregar cualquier producto a Roots, realizamos un proceso de verificación profundo y honesto.
                Conocemos a los productores, revisamos sus prácticas, la calidad de sus ingredientes, el origen de sus
                materias primas y su compromiso con el medio ambiente. Evaluamos que sus métodos sean responsables,
                transparentes y alineados con nuestros valores. Solo así podemos asegurar que lo que llega a tu hogar es
                auténtico, seguro y elaborado con respeto por la tierra y la comunidad.
            </p>
        </div>

        <div class="mb-4">
            <h4 class="step-title">Integración al Catálogo</h4>
            <p class="step-text">
                Cuando un productor cumple con nuestros estándares, integramos sus productos a nuestro catálogo
                cuidadosamente curado. Les damos un espacio que cuenta su historia: quiénes son, cómo trabajan, qué los
                hace diferentes y por qué su propuesta aporta valor al consumo local. Presentamos sus productos de
                manera clara y accesible, para que puedas explorar nuevas opciones y elegir con la confianza de saber
                que estás apoyando proyectos responsables y de calidad.
            </p>
        </div>

        <div class="mb-4">
            <h4 class="step-title">Promoción</h4>
            <p class="step-text">
                Ayudamos a que los productores locales lleguen a más personas. Desde contenido en redes y
                recomendaciones en la plataforma, hasta campañas dentro de nuestras iniciativas, impulsamos su trabajo
                para que más clientes conozcan y apoyen proyectos con propósito.
            </p>
        </div>
    </div>

</div>

<!-- Stats Banner -->
<div class="stats-banner mb-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="stat-number">+60 aliados</div>
                <div class="stat-label">Productores locales que comparten nuestros valores.</div>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="stat-number">+150 semillas</div>
                <div class="stat-label">Cada compra ayuda a sembrar más vida.</div>
            </div>
            <div class="col-md-4">
                <div class="stat-number">45% de reciclaje</div>
                <div class="stat-label">Consumo más circular y consciente.</div>
            </div>
        </div>
    </div>
</div>

<!-- Aliados Section -->
<div class="container aliados-section">
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="aliado-card">
                <img src="front/multimedia/il4.png" alt="Natural Go" class="aliado-img">
                <p class="aliado-name">Natural Go</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="aliado-card">
                <img src="front/multimedia/il5.png" alt="Miga Madre" class="aliado-img">
                <p class="aliado-name">Miga Madre</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="aliado-card">
                <img src="front/multimedia/il6.png" alt="La Huerta Clara" class="aliado-img">
                <p class="aliado-name">La Huerta Clara</p>
            </div>
        </div>
    </div>

    <!-- CTA Buttons -->
    <div class="text-center d-flex justify-content-center gap-3 flex-wrap">
        <a href="tienda.php?origen=local" class="btn-green-pill">Compra con propósito</a>
        <a href="registro.php" class="btn-green-pill">Regístrate en Roots</a>
    </div>
</div>

<!-- Newsletter Section (Visual match) -->
<div style="background-color: #E67E22; padding: 4rem 0; margin-top: 4rem;">
    <div class="container text-center text-white">
        <h2 class="fw-bold mb-3">Suscríbete Para Recibir</h2>
        <p class="mb-4">Novedades, promociones y tips para una vida más saludable.</p>
    </div>
</div>