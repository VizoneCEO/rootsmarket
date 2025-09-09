
<style>
    .bg-green {
        background-color: #4CAF50; /* Ajusta el tono del verde según la imagen de referencia */
        border-radius: 10px 0 0 10px; /* Redondeo solo en la izquierda */
    }

    h1 {
        font-size: 2rem; /* Tamaño ajustable */
        line-height: 1.4;
    }

    p {
        font-size: 0.875rem; /* Tamaño del texto más pequeño */
    }

    .img-fluid {
        object-fit: cover; /* Asegura que la imagen no se deforme */
        border-radius: 0 10px 10px 0; /* Redondeo solo en la derecha */
    }

    @media (max-width: 768px) {
        .row {
            flex-direction: column;
        }

        .bg-green {
            border-radius: 10px 10px 0 0; /* Redondeo para móviles */
        }

        .img-fluid {
            border-radius: 0 0 10px 10px; /* Redondeo para móviles */
        }
    }

</style>


<!-- front/index/body.php -->

<div class="container my-5 text-center">
    <!-- Imagen Principal -->
    <div class="mb-4">
        <img src="front/multimedia/Header.svg" class="img-fluid rounded" alt="Productos Orgánicos">
    </div>

    <!-- Texto Principal -->
    <h2 class="display-5 fw-bold mb-3">
        Los mejores productos orgánicos de raíz en un solo lugar.
    </h2>
    <p class="lead mb-4">
        ¡Bienvenido a Roots! Explora nuestra variedad de alimentos naturales y orgánicos cuidadosamente seleccionados para ofrecerte lo mejor de la naturaleza.
    </p>

    <!-- Botón de Acción -->
    <a href="#" class="btn btn-success btn-lg px-4 rounded-pill">
        Comprar Aquí
    </a>
</div>

<!-- Esquema Div Principal -->
<div class="container my-5 bg-green">
    <div class="row align-items-center">
        <!-- Sección Izquierda -->
        <div class="col-md-6 bg-green text-white p-4 justify-content-center">
            <center><h1 class="fw-bold">Las Mejoras Recetas, <br>PRODUCTOS QUE TRANSFORMAN.</h1></center>
            <center><p class="mt-3 small">TIPS PRÁCTICOS PARA CUIDAR TU SALUD.</p></center>
            <center><a href="recetas.php"><button type="button" class="btn btn-light btn-lg">Recetas</button></a><center>
        </div>
        <!-- Sección Derecha -->
        <div class="col-md-6 p-0">
            <img src="front/multimedia/foto.jpg" alt="Inspiración" class="img-fluid w-100 h-100 rounded">
        </div>
    </div>
</div>



<div class="container my-5">
    <div class="row g-3">



        <!-- Primer Div: Super Healthy -->
        <div class="col-md-6">
            <div class="promo-box p-4 rounded bg-pink d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-3">Super Healthy</h4>
                    <button class="btn btn-success">Shop Now</button>
                </div>
                <img src="front/multimedia/frutas.png" alt="Super Healthy" class="img-fluid promo-img">
            </div>
        </div>

        <!-- Segundo Div: Fresh Fruits -->
        <div class="col-md-6">
            <div class="promo-box p-4 rounded bg-yellow d-flex justify-content-between align-items-center">
                <div class="text-end">
                    <h4 class="fw-bold mb-1">Fresh Fruits</h4>
                    <p class="mb-3">Flats 25% Discount</p>
                    <button class="btn btn-success">Shop Now</button>
                </div>
                <img src="front/multimedia/frutas.png" alt="Fresh Fruits" class="img-fluid promo-img">
            </div>
        </div>

        <!-- Tercer Div: Fresh Vegetables -->
        <div class="col-md-6">
            <div class="promo-box p-4 rounded bg-lightgreen d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="fw-bold mb-1">Fresh Vegetables</h4>
                    <p class="mb-3">Get 30% Off on Your Order</p>
                    <button class="btn btn-success">Shop Now</button>
                </div>
                <img src="front/multimedia/frutas.png" alt="Fresh Vegetables" class="img-fluid promo-img">
            </div>
        </div>

        <!-- Cuarto Div: 100% Organic -->
        <div class="col-md-6">
            <div class="promo-box p-4 rounded bg-lightblue d-flex justify-content-between align-items-center">
                <div class="text-end">
                    <h4 class="fw-bold mb-3">100% Organic</h4>
                    <button class="btn btn-success">Shop Now</button>
                </div>
                <img src="front/multimedia/frutas.png" alt="100% Organic" class="img-fluid promo-img">
            </div>
        </div>
    </div>
</div>

<style>
    .bg-pink {
        background-color: #ffe4e4;
    }

    .bg-yellow {
        background-color: #fff5d7;
    }

    .bg-lightgreen {
        background-color: #eaffd6;
    }

    .bg-lightblue {
        background-color: #d6f0ff;
    }

    .promo-box {
        height: 200px;
        display: flex;
        padding: 20px;
    }

    .promo-box img.promo-img {
        max-width: 150px;
        max-height: 150px;
        object-fit: contain;
    }

    .promo-box h4 {
        font-size: 1.5rem;
    }

    .promo-box p {
        font-size: 1rem;
    }

    .btn-success {
        background-color: #198754;
        border-color: #198754;
    }

    /* Ajustes para versión móvil */
    @media (max-width: 768px) {
        .promo-box {
            flex-direction: column;
            height: auto;
            text-align: center;
        }

        .promo-box img.promo-img {
            margin-top: 15px;
            max-width: 100%;
        }
    }
</style>


<!-- Carrusel de Productos Top -->

<div class="container my-5 text-center">
    <h2 class="fw-bold" style="color: #2d4c48;">Productos Top</h2>
</div>

<div class="container my-5">
    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <!-- Primer Slide para Móvil -->
            <div class="carousel-item active d-block d-md-none">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="card text-center border-0">
                            <img src="front/multimedia/kiwi.png" class="card-img-top img-fluid" alt="Kiwi Orgánico" style="height: 200px; object-fit: contain;">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Kiwi Orgánico</h5>
                                <p class="text-muted"><del>$400</del> <span class="text-danger fw-bold">$350</span></p>
                                <p class="text-warning"><i class="fas fa-star"></i> (5)</p>
                                <div class="d-flex justify-content-center align-items-center mb-2">
                                    <button class="btn btn-outline-secondary btn-sm">-</button>
                                    <input type="number" class="form-control mx-2 text-center" value="1" min="1" style="width: 60px;">
                                    <button class="btn btn-outline-secondary btn-sm">+</button>
                                </div>
                                <button class="btn btn-success w-100">Agregar <i class="fas fa-shopping-cart ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Primer Slide para Escritorio -->
            <div class="carousel-item active d-none d-md-block">
                <div class="row justify-content-center g-3">
                    <div class="col-3">
                        <div class="card text-center border-0">
                            <img src="front/multimedia/kiwi.png" class="card-img-top img-fluid" alt="Kiwi Orgánico" style="height: 200px; object-fit: contain;">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Kiwi Orgánico</h5>
                                <p class="text-muted"><del>$400</del> <span class="text-danger fw-bold">$350</span></p>
                                <p class="text-warning"><i class="fas fa-star"></i> (5)</p>
                                <div class="d-flex justify-content-center align-items-center mb-2">
                                    <button class="btn btn-outline-secondary btn-sm">-</button>
                                    <input type="number" class="form-control mx-2 text-center" value="1" min="1" style="width: 60px;">
                                    <button class="btn btn-outline-secondary btn-sm">+</button>
                                </div>
                                <button class="btn btn-success w-100">Agregar <i class="fas fa-shopping-cart ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card text-center border-0">
                            <img src="front/multimedia/papaya.png" class="card-img-top img-fluid" alt="Papaya Orgánica" style="height: 200px; object-fit: contain;">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Papaya Orgánica</h5>
                                <p class="text-muted"><del>$400</del> <span class="text-danger fw-bold">$350</span></p>
                                <p class="text-warning"><i class="fas fa-star"></i> (5)</p>
                                <div class="d-flex justify-content-center align-items-center mb-2">
                                    <button class="btn btn-outline-secondary btn-sm">-</button>
                                    <input type="number" class="form-control mx-2 text-center" value="1" min="1" style="width: 60px;">
                                    <button class="btn btn-outline-secondary btn-sm">+</button>
                                </div>
                                <button class="btn btn-success w-100">Agregar <i class="fas fa-shopping-cart ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card text-center border-0">
                            <img src="front/multimedia/kiwi.png" class="card-img-top img-fluid" alt="Kiwi Orgánico" style="height: 200px; object-fit: contain;">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Kiwi Orgánico</h5>
                                <p class="text-muted"><del>$400</del> <span class="text-danger fw-bold">$350</span></p>
                                <p class="text-warning"><i class="fas fa-star"></i> (5)</p>
                                <div class="d-flex justify-content-center align-items-center mb-2">
                                    <button class="btn btn-outline-secondary btn-sm">-</button>
                                    <input type="number" class="form-control mx-2 text-center" value="1" min="1" style="width: 60px;">
                                    <button class="btn btn-outline-secondary btn-sm">+</button>
                                </div>
                                <button class="btn btn-success w-100">Agregar <i class="fas fa-shopping-cart ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card text-center border-0">
                            <img src="front/multimedia/papaya.png" class="card-img-top img-fluid" alt="Papaya Orgánica" style="height: 200px; object-fit: contain;">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Papaya Orgánica</h5>
                                <p class="text-muted"><del>$400</del> <span class="text-danger fw-bold">$350</span></p>
                                <p class="text-warning"><i class="fas fa-star"></i> (5)</p>
                                <div class="d-flex justify-content-center align-items-center mb-2">
                                    <button class="btn btn-outline-secondary btn-sm">-</button>
                                    <input type="number" class="form-control mx-2 text-center" value="1" min="1" style="width: 60px;">
                                    <button class="btn btn-outline-secondary btn-sm">+</button>
                                </div>
                                <button class="btn btn-success w-100">Agregar <i class="fas fa-shopping-cart ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Más tarjetas se agregan aquí para Escritorio -->
                </div>
            </div>
        </div>

        <!-- Controles del Carrusel -->
        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-dark rounded-circle" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-dark rounded-circle" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
</div>



<div class="container my-5 py-5 bg-white rounded shadow-sm">
    <h2 class="text-center fw-bold mb-3" style="color: #2d4c48;">El valor de nuestros productos reales</h2>
    <p class="text-center text-muted mb-5">
        Generamos una comunidad basada en productos orgánicos puestos a tu alcance en un mismo lugar.
    </p>

    <div class="row g-4 justify-content-center">
        <!-- Tarjeta Productos -->
        <div class="col-12 col-md-5 d-flex align-items-stretch">
            <div class="card border-0 text-center bg-light w-100 py-4" style="background-color: #d9e2de;">
                <div class="card-body">
                    <img src="front/multimedia/productos.png" alt="Productos" class="mb-3" style="max-width: 150px;">
                    <h5 class="fw-bold" style="color: #2d4c48;">Productos</h5>
                    <p class="text-muted">
                        Disfruta de productos 100% orgánicos en un solo lugar.
                    </p>
                </div>
            </div>
        </div>

        <!-- Tarjeta Comunidad -->
        <div class="col-12 col-md-5 d-flex align-items-stretch">
            <div class="card border-0 text-center bg-light w-100 py-4" style="background-color: #d9e2de;">
                <div class="card-body">
                    <img src="front/multimedia/comunidad.png" alt="Comunidad" class="mb-3" style="max-width: 150px;">
                    <h5 class="fw-bold" style="color: #2d4c48;">Comunidad</h5>
                    <p class="text-muted">
                        Crea una comunidad basada en productos 100% naturales para disfrutar de la mejor manera.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container my-5">
    <!-- Sección de Opiniones -->
    <div class="p-5 rounded" style="background-color: #2d4c48;">
        <h2 class="text-center fw-bold mb-4 text-white">Opiniones de nuestra comunidad</h2>
        <div class="row g-4 justify-content-center">
            <!-- Tarjeta de Opinión 1 -->
            <div class="col-12 col-md-4">
                <div class="card h-100 text-center p-3">
                    <img src="front/multimedia/user.png" alt="Profile picture of Elias López" class="mb-2" style="max-width: 40px;">
                    <h5 class="fw-bold">Elias López</h5>
                    <p class="text-warning mb-1">
                        <i class="fas fa-star"></i> (4.5)
                    </p>
                    <p class="fw-bold">"Delicioso"</p>
                    <p class="text-muted">
                        Having never tasted Manukora honey before, I thought I'd try out the MGO 600 first. The taste was nothing like any honey I'd ever had before and I'm very impressed. I truly hope the healing claims are real as I have issues with digestion and produce too much histamine.
                    </p>
                </div>
            </div>

            <!-- Tarjeta de Opinión 2 -->
            <div class="col-12 col-md-4">
                <div class="card h-100 text-center p-3">
                    <img src="front/multimedia/user.png" alt="Profile picture of Elias López" class="mb-2" style="max-width: 40px;">
                    <h5 class="fw-bold">Elias López</h5>
                    <p class="text-warning mb-1">
                        <i class="fas fa-star"></i> (4.5)
                    </p>
                    <p class="fw-bold">"Delicioso"</p>
                    <p class="text-muted">
                        Having never tasted Manukora honey before, I thought I'd try out the MGO 600 first. The taste was nothing like any honey I'd ever had before and I'm very impressed. I truly hope the healing claims are real as I have issues with digestion and produce too much histamine.
                    </p>
                </div>
            </div>

            <!-- Tarjeta de Opinión 3 -->
            <div class="col-12 col-md-4">
                <div class="card h-100 text-center p-3">
                    <img src="front/multimedia/user.png" alt="Profile picture of Elias López" class="mb-2" style="max-width: 40px;">
                    <h5 class="fw-bold">Elias López</h5>
                    <p class="text-warning mb-1">
                        <i class="fas fa-star"></i> (4.5)
                    </p>
                    <p class="fw-bold">"Delicioso"</p>
                    <p class="text-muted">
                        Having never tasted Manukora honey before, I thought I'd try out the MGO 600 first. The taste was nothing like any honey I'd ever had before and I'm very impressed. I truly hope the healing claims are real as I have issues with digestion and produce too much histamine.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="container text-center my-5">
    <h2 class="fw-bold mb-4" style="color: #2d4c48;">Compra tus productos favoritos en nuestra tienda</h2>
    <button class="btn btn-success px-4 py-2" style="background-color: #2d4c48; border-color: #2d4c48; border-radius: 20px;">Comprar Ahora</button>
</div>







