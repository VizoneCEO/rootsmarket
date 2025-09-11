
<style>
    /* Estilos para el nuevo carrusel */
    #heroCarousel {
        border-radius: 20px;
        overflow: hidden;
        max-height: 450px;
    }

    #heroCarousel .carousel-item img {
        width: 100%;
        height: 450px;
        object-fit: cover; /* Asegura que la imagen cubra el espacio sin deformarse */
    }

    /* Estilos para los indicadores (los puntos de abajo) */
    #heroCarousel .carousel-indicators button {
        width: 10px;
        height: 10px;
        border-radius: 100%;
        background-color: #d8d8d8; /* Color para los inactivos */
        border: none;
        margin: 0 5px;
    }

    #heroCarousel .carousel-indicators .active {
        background-color: #4EAE3E; /* Color para el activo */
    }


    /* Estilos existentes */
    .bg-green {
        background-color: #4EAE3E;
        border-radius: 10px 0 0 10px;
    }

    .promo-box {
        height: 200px;
        display: flex;
        padding: 20px;
    }

    .bg-pink { background-color: #ffe4e4; }
    .bg-yellow { background-color: #fff5d7; }
    .bg-lightgreen { background-color: #eaffd6; }
    .bg-lightblue { background-color: #d6f0ff; }

    .promo-box img.promo-img {
        max-width: 150px;
        max-height: 150px;
        object-fit: contain;
    }
</style>

<div class="container my-5">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="front/multimedia/Header.svg" class="d-block w-100" alt="Productos frescos y orgánicos">
            </div>
            <div class="carousel-item">
                <img src="front/multimedia/nosotros1.png" class="d-block w-100" alt="Nuestra misión contigo">
            </div>
            <div class="carousel-item">
                <img src="front/multimedia/tienda.png" class="d-block w-100" alt="Verduras en la tienda">
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>


<div class="container text-center mb-5">
    <h2 class="display-5 fw-bold mb-3">
        Los mejores productos orgánicos de raíz en un solo lugar.
    </h2>
    <p class="lead mb-4">
        ¡Bienvenido a Roots! Explora nuestra variedad de alimentos naturales y orgánicos cuidadosamente seleccionados para ofrecerte lo mejor de la naturaleza.
    </p>
    <a href="tienda.php" class="btn btn-success btn-lg px-4 rounded-pill">
        Comprar Aquí
    </a>
</div>

<div class="container my-5 bg-green">
</div>

<div class="container my-5">
</div>

<div class="container my-5 text-center">
</div>

<div class="container my-5">
</div>




























<style>


    /* --- NUEVOS ESTILOS PARA DEPARTAMENTOS --- */
    .departments-section {
        padding: 2rem 0;
    }

    .departments-title {
        color: #4EAE3E;
        font-weight: bold;
        margin-bottom: 2rem;
    }

    .department-item img {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        transition: transform 0.3s ease;
        border: 3px solid #f2ece9;
    }

    .department-item:hover img {
        transform: scale(1.05);
    }

    .department-item p {
        color: #4EAE3E;
        font-weight: 600;
        margin-top: 1rem;
        font-size: 1.1rem;
    }

    /* Estilos existentes */
    .bg-green {
        background-color: #4EAE3E;
        border-radius: 10px 0 0 10px;
    }

    .promo-box {
        height: 200px;
        display: flex;
        padding: 20px;
    }

    .bg-pink { background-color: #ffe4e4; }
    .bg-yellow { background-color: #fff5d7; }
    .bg-lightgreen { background-color: #eaffd6; }
    .bg-lightblue { background-color: #d6f0ff; }

    .promo-box img.promo-img {
        max-width: 150px;
        max-height: 150px;
        object-fit: contain;
    }
</style>




<div class="container text-center departments-section">
    <h2 class="departments-title">Departamentos</h2>
    <div class="row gy-4 justify-content-center">
        <div class="col-6 col-md-4 col-lg-2">
            <a href="#" class="text-decoration-none department-item">
                <img src="front/multimedia/frutas.png" alt="Frutas y verduras">
                <p>Frutas y verduras</p>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="#" class="text-decoration-none department-item">
                <img src="front/multimedia/nosotros2.png" alt="Despensa">
                <p>Despensa</p>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="#" class="text-decoration-none department-item">
                <img src="front/multimedia/receta1.jpg" alt="Carnes y pescados">
                <p>Carnes y pescados</p>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="#" class="text-decoration-none department-item">
                <img src="front/multimedia/nosotros3.png" alt="100% organic">
                <p>100% organic</p>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="#" class="text-decoration-none department-item">
                <img src="front/multimedia/foto.jpg" alt="Saludables">
                <p>Saludables</p>
            </a>
        </div>
        <div class="col-6 col-md-4 col-lg-2">
            <a href="#" class="text-decoration-none department-item">
                <img src="front/multimedia/tienda.png" alt="Gourmet">
                <p>Gourmet</p>
            </a>
        </div>
    </div>
</div>

<div class="container my-5 bg-green">
</div>

<div class="container my-5">
</div>

<div class="container my-5 text-center">
</div>

<div class="container my-5">
</div>




<style>


    /* --- NUEVOS ESTILOS PARA PRODUCTOS TOP --- */
    .top-products-section {
        padding: 2rem 0;
    }
    .product-card-top {
        background-color: #f8f9fa;
        border-radius: 20px;
        padding: 1.5rem 1rem;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: all 0.3s ease;
    }
    .product-card-top:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }
    .product-card-top img {
        max-height: 150px;
        object-fit: contain;
        margin-bottom: 1rem;
    }
    .product-card-top h5 {
        font-weight: 600;
        color: #333;
        margin-bottom: 1rem;
    }
    .quantity-selector {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 1rem;
    }
    .quantity-selector button {
        border: 1px solid #ddd;
        background-color: #fff;
        color: #555;
        width: 30px;
        height: 30px;
        font-weight: bold;
        cursor: pointer;
    }
    .quantity-selector input {
        width: 40px;
        text-align: center;
        border: 1px solid #ddd;
        height: 30px;
        margin: 0 5px;
    }
    .btn-add-cart {
        background-color: #4EAE3E;
        color: white;
        border: none;
        border-radius: 50px;
        padding: 10px 20px;
        width: 100%;
        font-weight: 600;
    }
    .shop-cta {
        margin-top: 3rem;
    }
    .shop-cta h4 {
        color: #4EAE3E;
        font-weight: bold;
        margin-bottom: 1.5rem;
    }
    .btn-shop-now {
        background-color: #4EAE3E;
        color: white;
        border: none;
        border-radius: 50px;
        padding: 15px 40px;
        font-weight: bold;
        font-size: 1.2rem;
    }
</style>



<div class="container text-center top-products-section">
    <h2 class="section-title">Productos top</h2>
    <div class="row gy-4">
        <div class="col-6 col-md-3">
            <div class="product-card-top">
                <img src="front/multimedia/kiwi.png" alt="Plátano"> <h5>Plátano</h5>
                <div class="quantity-selector">
                    <button class="quantity-btn" data-action="decrement">-</button>
                    <input type="text" class="quantity-input" value="1" readonly>
                    <button class="quantity-btn" data-action="increment">+</button>
                </div>
                <button class="btn-add-cart">Agregar <i class="fas fa-shopping-cart ms-1"></i></button>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="product-card-top">
                <img src="front/multimedia/papaya.png" alt="Fresa"> <h5>Fresa</h5>
                <div class="quantity-selector">
                    <button class="quantity-btn" data-action="decrement">-</button>
                    <input type="text" class="quantity-input" value="1" readonly>
                    <button class="quantity-btn" data-action="increment">+</button>
                </div>
                <button class="btn-add-cart">Agregar <i class="fas fa-shopping-cart ms-1"></i></button>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="product-card-top">
                <img src="front/multimedia/pera.png" alt="Naranja"> <h5>Naranja</h5>
                <div class="quantity-selector">
                    <button class="quantity-btn" data-action="decrement">-</button>
                    <input type="text" class="quantity-input" value="1" readonly>
                    <button class="quantity-btn" data-action="increment">+</button>
                </div>
                <button class="btn-add-cart">Agregar <i class="fas fa-shopping-cart ms-1"></i></button>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="product-card-top">
                <img src="front/multimedia/chile.png" alt="Pepino"> <h5>Pepino</h5>
                <div class="quantity-selector">
                    <button class="quantity-btn" data-action="decrement">-</button>
                    <input type="text" class="quantity-input" value="1" readonly>
                    <button class="quantity-btn" data-action="increment">+</button>
                </div>
                <button class="btn-add-cart">Agregar <i class="fas fa-shopping-cart ms-1"></i></button>
            </div>
        </div>
    </div>

    <div class="shop-cta">
        <h4>COMPRA TUS PRODUCTOS FAVORITOS EN NUESTRA TIENDA</h4>
        <a href="tienda.php" class="btn btn-shop-now">COMPRAR AHORA</a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const quantityButtons = document.querySelectorAll('.quantity-btn');

        quantityButtons.forEach(button => {
            button.addEventListener('click', function () {
                const action = this.getAttribute('data-action');
                const input = this.parentElement.querySelector('.quantity-input');
                let value = parseInt(input.value);

                if (action === 'increment') {
                    value++;
                } else if (action === 'decrement' && value > 1) {
                    value--;
                }
                input.value = value;
            });
        });
    });
</script>
















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











