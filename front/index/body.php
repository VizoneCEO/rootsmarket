<style>
    /* --- HERO SECTION --- */
    .hero-section {
        background-color: #666666; /* Gris oscuro del diseño */
        height: 500px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: white;
        padding: 20px;
    }
    
    .hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-transform: uppercase;
    }
    
    .hero-subtitle {
        font-size: 1.2rem;
        max-width: 600px;
        color: #E0E0E0;
    }

    /* --- SECCIONES GENERALES --- */
    .section-padding {
        padding: 4rem 0;
    }
    
    .section-title {
        font-weight: 700;
        color: #333;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }
    
    .section-desc {
        color: #666;
        margin-bottom: 2rem;
    }

    /* --- BOTONES --- */
    .btn-dark-pill {
        background-color: #333;
        color: white;
        border-radius: 50px;
        padding: 10px 30px;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }
    .btn-dark-pill:hover {
        background-color: #000;
        color: white;
    }

    /* --- CARDS GRISES (PLACEHOLDERS FIGMA) --- */
    .gray-card {
        background-color: #A0A0A0; /* Gris medio */
        border-radius: 20px;
        width: 100%;
        height: 100%;
        min-height: 250px;
        display: flex;
        align-items: flex-end;
        padding: 20px;
        color: white;
        font-weight: 500;
        font-size: 1.1rem;
        transition: transform 0.3s;
    }
    .gray-card:hover {
        transform: translateY(-5px);
    }
    
    .card-tall { min-height: 520px; }
    .card-medium { min-height: 250px; }
    
    /* --- CATEGORIAS --- */
    .cat-card {
        background-color: #A0A0A0;
        border-radius: 30px; /* Más redondeado */
        height: 350px;
        position: relative;
        margin-bottom: 1rem;
    }
    .cat-label {
        text-align: center;
        margin-top: 15px;
        color: #444;
        font-weight: 500;
    }

    /* --- PRODUCT CARDS (LO MEJOR DE ROOTS) --- */
    .product-card-minimal {
        border: none;
        background: transparent;
    }
    .product-placeholder {
        background-color: #A0A0A0;
        border-radius: 20px;
        height: 300px;
        position: relative;
        margin-bottom: 15px;
    }
    .discount-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background-color: #333;
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
    }
    .add-btn-circle {
        border: 1px solid #333;
        background: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
</style>

<div class="hero-section">
    <h1 class="hero-title">Compra con Propósito</h1>
    <p class="hero-subtitle">
        Todo lo que necesitas para tu día a día, libre de químicos dañinos.<br>
        Saludable, confiable y al alcance de un clic.
    </p>
</div>

<div class="container section-padding">
    <div class="text-center mb-5">
        <h2 class="section-title">Novedades y Promos de la Semana</h2>
        <p class="section-desc">Encuentra descuentos, nuevos productos y ediciones limitadas,<br>todos con la garantía de estar libres de químicos dañinos.</p>
        <a href="tienda.php" class="btn-dark-pill">Empieza tu súper <i class="fas fa-chevron-right ms-2"></i></a>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="gray-card card-tall">
                Temporada
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-flex flex-column h-100 gap-4">
                <div class="gray-card card-medium">
                    Nuevos Productos
                </div>
                <div class="gray-card card-medium">
                    Campañas de impacto
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="gray-card card-tall">
                Descuentos
            </div>
        </div>
    </div>
</div>

<div class="container section-padding">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <h2 class="section-title">Compra por Categoría</h2>
        <p class="text-end text-muted small d-none d-md-block">
            Hicimos la selección por ti:<br>alimentos, bebidas, cuidado personal y más.
        </p>
    </div>

    <div class="row g-4">
        <div class="col-6 col-md-3">
            <div class="cat-card"></div>
            <p class="cat-label">Frutas y verduras</p>
        </div>
        <div class="col-6 col-md-3">
            <div class="cat-card"></div>
            <p class="cat-label">Pan, Cereales y Granos</p>
        </div>
        <div class="col-6 col-md-3">
            <div class="cat-card"></div>
            <p class="cat-label">Limpieza del Hogar</p>
        </div>
        <div class="col-6 col-md-3">
            <div class="cat-card"></div>
            <p class="cat-label">Carnes, Pescados y Huevo</p>
        </div>
    </div>
</div>

<div class="container section-padding">
    <div class="mb-4">
        <h2 class="section-title">Lo Mejor de Roots</h2>
        <p class="section-desc">Desde los más vendidos hasta los favoritos de Roots.</p>
    </div>

    <div class="row g-4">
        <div class="col-6 col-md-3">
            <div class="product-card-minimal">
                <div class="product-placeholder">
                    <span class="discount-badge">-13%</span>
                </div>
                <h5 class="fw-normal mb-1">Leche de almendra</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">$200.00</span>
                    <div class="add-btn-circle"><i class="fas fa-plus"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="product-card-minimal">
                <div class="product-placeholder">
                    <span class="discount-badge">-13%</span>
                </div>
                <h5 class="fw-normal mb-1">Pan integral multigrano</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">$200.00</span>
                    <div class="add-btn-circle"><i class="fas fa-plus"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="product-card-minimal">
                <div class="product-placeholder">
                    <span class="discount-badge">-13%</span>
                </div>
                <h5 class="fw-normal mb-1">Jugo de naranja natural</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">$200.00</span>
                    <div class="add-btn-circle"><i class="fas fa-plus"></i></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="product-card-minimal">
                <div class="product-placeholder">
                    <span class="discount-badge">-13%</span>
                </div>
                <h5 class="fw-normal mb-1">Yogurt natural sin azúcar</h5>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">$200.00</span>
                    <div class="add-btn-circle"><i class="fas fa-plus"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
    /* --- SECCIÓN: HAZ QUE TU COMPRA CUENTE --- */
    .impact-section {
        padding: 4rem 0;
        background-color: #fff;
    }
    .impact-card {
        background-color: #A0A0A0; /* Placeholder gris */
        border-radius: 20px;
        height: 300px;
        width: 100%;
        margin-bottom: 1rem;
    }
    .impact-title {
        font-weight: 500;
        font-size: 1.1rem;
        color: #333;
    }

    /* --- SECCIÓN: IMPULSO LOCAL (FAQ) --- */
    .local-impulse-section {
        padding: 4rem 0;
        background-color: #fff; /* O un gris muy tenue si prefieres */
    }
    .faq-card {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        margin-bottom: 1rem;
        overflow: hidden;
    }
    .faq-header {
        background-color: #fff;
        padding: 1.5rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .faq-title {
        font-size: 1.1rem;
        margin: 0;
        color: #333;
        font-weight: 500;
    }
    .faq-body {
        padding: 0 1.5rem 1.5rem 1.5rem;
        color: #666;
        line-height: 1.6;
    }

    /* Icono de chevron para el acordeón */
    .chevron-icon {
        transition: transform 0.3s ease;
    }
    .collapsed .chevron-icon {
        transform: rotate(180deg); /* Ajustar según icono inicial */
    }
</style>

<div class="container impact-section" id="iniciativas">
    <div class="d-flex justify-content-between align-items-start mb-5 flex-wrap">
        <div class="col-md-7">
            <h2 class="section-title mb-3">HAZ QUE TU COMPRA CUENTE</h2>
            <p class="section-desc">
                En Roots, cada compra tiene un propósito.<br>
                Con nuestros programas, transformar tu súper en acciones que<br>
                cuidan el planeta y apoyan a la comunidad es más fácil de lo que imaginas.
            </p>
        </div>
        <div class="col-md-3 text-md-end mt-3 mt-md-0">
            <a href="#" class="btn-dark-pill">Conoce más <i class="fas fa-chevron-right ms-2"></i></a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="impact-card"></div>
            <p class="impact-title">Raíces Verdes</p>
        </div>
        <div class="col-md-4">
            <div class="impact-card"></div>
            <p class="impact-title">Cero Basura</p>
        </div>
        <div class="col-md-4">
            <div class="impact-card"></div>
            <p class="impact-title">Impulso Local</p>
        </div>
    </div>
</div>

<div class="container local-impulse-section">
    <div class="row gx-5">
        <div class="col-lg-5 mb-5 mb-lg-0">
            <h2 class="section-title mb-4">IMPULSO LOCAL</h2>
            <p class="section-desc mb-4">
                En Roots creemos en el talento y la calidad mexicana. Con Impulso Local,
                cada compra ayuda a pequeñas y medianas marcas del país a crecer y ofrecer
                productos honestos y de confianza para tu día a día.
            </p>
            <a href="#" class="btn-dark-pill">Compra productos mexicanos <i class="fas fa-chevron-right ms-2"></i></a>
        </div>

        <div class="col-lg-7">
            <div class="accordion" id="accordionImpulsoLocal">

                <div class="faq-card">
                    <div class="faq-header" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <h5 class="faq-title">¿Qué es Impulso Local?</h5>
                        <i class="fas fa-chevron-up chevron-icon"></i>
                    </div>
                    <div id="collapseOne" class="collapse show" data-bs-parent="#accordionImpulsoLocal">
                        <div class="faq-body">
                            Es nuestro programa que apoya marcas mexicanas, para que cada compra impulse la economía local y productos de calidad.
                        </div>
                    </div>
                </div>

                <div class="faq-card">
                    <div class="faq-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <h5 class="faq-title">¿Cómo sé que un producto es local?</h5>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </div>
                    <div id="collapseTwo" class="collapse" data-bs-parent="#accordionImpulsoLocal">
                        <div class="faq-body">
                            (Respuesta pendiente...) Buscamos identificar claramente estos productos con un sello distintivo en nuestra tienda.
                        </div>
                    </div>
                </div>

                <div class="faq-card">
                    <div class="faq-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        <h5 class="faq-title">¿Puedo comprar solo productos locales?</h5>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </div>
                    <div id="collapseThree" class="collapse" data-bs-parent="#accordionImpulsoLocal">
                        <div class="faq-body">
                            (Respuesta pendiente...) ¡Claro! Puedes filtrar tu búsqueda para ver exclusivamente productos de nuestros socios locales.
                        </div>
                    </div>
                </div>

                <div class="faq-card">
                    <div class="faq-header collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        <h5 class="faq-title">¿Hay beneficios adicionales por comprar local?</h5>
                        <i class="fas fa-chevron-down chevron-icon"></i>
                    </div>
                    <div id="collapseFour" class="collapse" data-bs-parent="#accordionImpulsoLocal">
                        <div class="faq-body">
                            (Respuesta pendiente...) A menudo tenemos promociones especiales para incentivar el apoyo a marcas nacionales.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

