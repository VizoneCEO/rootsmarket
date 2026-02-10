<style>
    /* --- HERO SECTION --- */
    .raices-hero {
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

    .hero-badge-raices {
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

    /* --- IMPACT SECTION --- */
    .impact-section {
        background-color: #f8f9fa;
        padding: 3rem 0;
        text-align: center;
        border-radius: 20px;
        margin-bottom: 3rem;
    }

    .impact-title {
        font-weight: 800;
        color: #333;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .impact-text {
        color: #555;
        font-size: 1rem;
        line-height: 1.8;
        max-width: 800px;
        margin: 0 auto 1.5rem auto;
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

<div class="container raices-hero">

    <!-- Hero Image -->
    <div class="hero-img-wrapper mb-5">
        <img src="front/multimedia/inc1.jpg" alt="Raíces Verdes Hero">
        <div class="hero-badge-raices">Raíces Verdes</div>
    </div>

    <!-- Intro Text -->
    <div class="mb-5 px-3 px-md-0">
        <h2 class="intro-title">Raíces Verdes</h2>
        <h3 class="intro-subtitle">Tus compras ayudan a plantar árboles y regenerar el planeta.</h3>
        <p class="intro-text">
            En Roots, creemos que el consumo puede ser una fuerza regenerativa. A través de la iniciativa Raíces Verdes,
            transformamos tus decisiones diarias en acciones concretas para el planeta.
        </p>
        <p class="intro-text">
            No solo llevamos alimentos frescos a tu mesa, sino que también nos comprometemos a devolver vida a la
            tierra.
        </p>
    </div>

    <!-- How It Works -->
    <div class="mb-5 px-3 px-md-0">
        <h3 class="how-title">¿Cómo Funciona?</h3>

        <div class="mb-4">
            <h4 class="step-title">Compra con Propósito</h4>
            <p class="step-text">
                Cada vez que eliges Roots, un porcentaje de tu compra se destina a nuestros fondos de reforestación.
                Seleccionamos productos que apoyan prácticas agrícolas sostenibles y respetuosas con el medio ambiente.
            </p>
        </div>

        <div class="mb-4">
            <h4 class="step-title">Plantamos Vida</h4>
            <p class="step-text">
                Colaboramos con organizaciones locales y comunidades para sembrar árboles nativos en zonas deforestadas.
                Aseguramos el cuidado y crecimiento de cada árbol para garantizar su supervivencia a largo plazo.
            </p>
        </div>

        <div class="mb-4">
            <h4 class="step-title">Regeneración</h4>
            <p class="step-text">
                Más allá de plantar, buscamos regenerar ecosistemas completos. Fomentamos la biodiversidad, protegemos
                los suelos y ayudamos a capturar carbono para combatir el cambio climático.
            </p>
        </div>
    </div>

    <!-- Impact Section -->
    <div class="impact-section px-3">
        <h3 class="impact-title">Nuestro Impacto</h3> <!-- Placeholder Title -->
        <p class="impact-text">
            Juntos estamos sembrando el futuro. Con cada árbol plantado, purificamos el aire, protegemos el agua y
            creamos hogar para la vida silvestre.
        </p>
    </div>

    <!-- CTA Buttons -->
    <div class="text-center d-flex justify-content-center gap-3 flex-wrap mb-5">
        <a href="tienda.php" class="btn-green-pill">Compra y Planta</a>
        <a href="iniciativas.php" class="btn-green-pill"
            style="background-color: transparent; color: #4EAE3E; border: 2px solid #4EAE3E;">Volver a Iniciativas</a>
    </div>

</div>