<style>
    /* --- HERO SECTION --- */
    .cero-hero {
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

    .hero-badge-cero {
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

    .quote-text {
        font-style: italic;
        color: #4EAE3E;
        font-weight: 700;
        font-size: 1.1rem;
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

<div class="container cero-hero">

    <!-- Hero Image -->
    <div class="hero-img-wrapper mb-5">
        <img src="front/multimedia/inc2.jpg" alt="Cero Basura Hero">
        <div class="hero-badge-cero">Cero Basura</div>
    </div>

    <!-- Intro Text -->
    <div class="mb-5 px-3 px-md-0">
        <h2 class="intro-title">Cero Basura</h2>
        <h3 class="intro-subtitle">Consumir mejor también es cerrar el ciclo.</h3>
        <p class="intro-text">
            Creemos que el verdadero cambio no termina cuando recibes tu compra.
            Empieza cuando nos hacemos responsables de lo que dejamos atrás.
        </p>
        <p class="intro-text">
            En Roots impulsamos una iniciativa Cero Basura para reducir residuos y transformar la forma en que
            consumimos productos de uso diario.
            Cada compra es una oportunidad para cerrar el ciclo.
        </p>
    </div>

    <!-- How It Works -->
    <div class="mb-5 px-3 px-md-0">
        <h3 class="how-title">¿Cómo Funciona?</h3>

        <div class="mb-4">
            <h4 class="step-title">Recolección Responsable</h4>
            <p class="step-text">
                En tu siguiente compra, recogemos los empaques vacíos de los productos que consumiste. No importa si son
                frascos, bolsas o envases: nos hacemos cargo de ellos para que no terminen en la basura común ni en
                rellenos sanitarios.
                Consumiste. Disfrutaste. Ahora, los regresamos al ciclo correcto.
            </p>
        </div>

        <div class="mb-4">
            <h4 class="step-title">Separación y Reciclaje</h4>
            <p class="step-text">
                Una vez recolectados, clasificamos los empaques por material y los canalizamos a procesos de reciclaje
                responsables. Trabajamos para que cada residuo tenga un destino adecuado y una nueva vida, reduciendo el
                impacto ambiental de cada compra.
                Menos desechos. Más consciencia.
            </p>
        </div>
    </div>

    <!-- Impact Section -->
    <div class="impact-section px-3">
        <h3 class="impact-title">Impacto Real</h3>
        <p class="impact-text">
            Con cada pedido, disminuyes tu huella ambiental, evitas residuos innecesarios y formas parte de una
            comunidad que entiende que consumir también implica responsabilidad.
        </p>
        <p class="quote-text">
            No se trata de ser perfectos.
            Se trata de hacerlo mejor, paso a paso.
        </p>
    </div>

    <!-- CTA Buttons -->
    <div class="text-center d-flex justify-content-center gap-3 flex-wrap mb-5">
        <a href="tienda.php" class="btn-green-pill">Ir a la Tienda</a>
        <a href="iniciativas.php" class="btn-green-pill"
            style="background-color: transparent; color: #4EAE3E; border: 2px solid #4EAE3E;">Volver a Iniciativas</a>
    </div>

</div>