<style>
    /* --- ESTILOS GENERALES INICIATIVAS --- */
    .initiatives-hero {
        padding: 4rem 0;
    }

    .hero-image-container {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        height: 400px;
    }

    .hero-image-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-badge {
        position: absolute;
        bottom: 30px;
        left: 0;
        background-color: #E67E22;
        /* Color naranja Roots */
        color: white;
        padding: 15px 40px 15px 30px;
        font-weight: 800;
        text-transform: uppercase;
        font-size: 1.5rem;
        border-top-right-radius: 50px;
        border-bottom-right-radius: 50px;
        box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
    }

    .hero-content {
        padding-left: 2rem;
    }

    .hero-title {
        font-weight: 800;
        text-transform: uppercase;
        color: #333;
        margin-bottom: 1.5rem;
        font-size: 1.2rem;
        letter-spacing: 1px;
    }

    .hero-desc {
        color: #666;
        line-height: 1.8;
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }

    /* --- CARDS SECTION --- */
    .initiative-card {
        border: none;
        background: transparent;
    }

    .card-image-wrapper {
        position: relative;
        border-radius: 20px;
        overflow: hidden;
        height: 250px;
        margin-bottom: 1.5rem;
    }

    .card-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .initiative-card:hover .card-image-wrapper img {
        transform: scale(1.05);
    }

    .card-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background-color: #4EAE3E;
        /* Verde Roots */
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.8rem;
    }

    .card-title {
        font-weight: 700;
        color: #333;
        font-size: 1.2rem;
        margin-bottom: 0.5rem;
    }

    .card-text {
        color: #666;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
        line-height: 1.5;
        min-height: 3rem;
        /* Para alinear botones */
    }

    .btn-orange-pill {
        background-color: #E67E22;
        color: white;
        border-radius: 50px;
        padding: 8px 25px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .btn-orange-pill:hover {
        background-color: #D35400;
        color: white;
    }
</style>

<div class="container initiatives-hero">

    <div class="mb-5">
        <h1 class="fw-bold text-uppercase text-secondary mb-4" style="font-size: 1rem; letter-spacing: 2px;">Iniciativas
            Roots</h1>
    </div>

    <div class="row align-items-center mb-5">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="hero-image-container">
                <!-- Usando una imagen de 'nosotros' como placeholder para el hero -->
                <img src="front/multimedia/in1.jpg" alt="Iniciativas Roots">

            </div>
        </div>
        <div class="col-lg-6">
            <div class="hero-content">
                <h3 class="fw-bold mb-3" style="color: #333;">Nuestro Objetivo</h3>
                <p class="hero-desc">
                    En Roots, creemos que cada elección tiene poder. Por eso impulsamos iniciativas que conectan el
                    bienestar personal con el bienestar del planeta: desde plantar árboles y reducir los residuos, hasta
                    apoyar a quienes producen con respeto por la tierra.
                </p>
                <p class="hero-desc">
                    Queremos inspirarte a consumir con conciencia y demostrar que juntos podemos generar un cambio
                    positivo, paso a paso, compra a compra.
                </p>
            </div>
        </div>
    </div>

    <div class="row g-4 mt-5">

        <!-- Raíces Verdes -->
        <div class="col-md-4">
            <div class="initiative-card">
                <div class="card-image-wrapper">
                    <span class="card-badge">Raíces Verdes</span>
                    <img src="front/multimedia/r1.png" alt="Raíces Verdes">
                </div>
                <h4 class="card-title">Raíces Verdes</h4>
                <p class="card-text">Tus compras ayudan a plantar árboles y regenerar el planeta.</p>
                <a href="#" class="btn-orange-pill">Conoce más <i class="fas fa-chevron-right ms-1"></i></a>
            </div>
        </div>

        <!-- Cero Basura -->
        <div class="col-md-4">
            <div class="initiative-card">
                <div class="card-image-wrapper">
                    <span class="card-badge" style="background-color: #E67E22;">Cero Basura</span>
                    <img src="front/multimedia/r2.png" alt="Cero Basura">
                </div>
                <h4 class="card-title">Cero Basura</h4>
                <p class="card-text">Damos nueva vida a tus empaques y reducimos los residuos juntos.</p>
                <a href="#" class="btn-orange-pill">Conoce más <i class="fas fa-chevron-right ms-1"></i></a>
            </div>
        </div>

        <!-- Impulso Local -->
        <div class="col-md-4">
            <div class="initiative-card">
                <div class="card-image-wrapper">
                    <span class="card-badge">Impulso Local</span>
                    <img src="front/multimedia/r3.png" alt="Impulso Local">
                </div>
                <h4 class="card-title">Impulso Local</h4>
                <p class="card-text">Apoya a productores cercanos y consume con conciencia.</p>
                <a href="impulso_local.php" class="btn-orange-pill">Conoce más <i
                        class="fas fa-chevron-right ms-1"></i></a>
            </div>
        </div>

    </div>

</div>