<div class="container my-5">
    <div class="row align-items-center bg-light p-4 rounded">
        <!-- Imagen del Producto -->
        <div class="col-md-5 text-center">
            <img src="front/multimedia/kiwi.png" alt="Kiwi Importado" class="img-fluid rounded">

            <!-- Miniaturas de Imágenes -->
            <div class="d-flex justify-content-center mt-3">
                <img src="front/multimedia/kiwi.png" alt="Kiwi 1" class="img-thumbnail mx-1" style="width: 50px;">
                <img src="front/multimedia/kiwi.png" alt="Kiwi 2" class="img-thumbnail mx-1" style="width: 50px;">
                <img src="front/multimedia/kiwi.png" alt="Kiwi 3" class="img-thumbnail mx-1" style="width: 50px;">
            </div>
        </div>

        <!-- Información del Producto -->
        <div class="col-md-7">
            <span class="badge bg-danger mb-2">Promoción</span>
            <h2 class="fw-bold">Kiwi Importado</h2>
            <h3 class="fw-bold text-green mb-3">$99.00 <small>/kg</small></h3>

            <button class="btn btn-success mb-3">Agregar</button>

            <!-- Opciones de Madurez -->
            <div class="mb-3">
                <button class="btn btn-outline-secondary">Verde</button>
                <button class="btn btn-outline-secondary">Medio</button>
                <button class="btn btn-outline-secondary">Maduro</button>
            </div>

            <!-- Tabla Nutrimental -->
            <h5 class="fw-bold">Tabla Nutrimental</h5>
            <div class="d-flex mb-3">
                <div class="p-2 border rounded text-center mx-1">
                    <p class="fw-bold mb-0">100g</p>
                    <small>Porción</small>
                </div>
                <div class="p-2 border rounded text-center mx-1">
                    <p class="fw-bold mb-0">0</p>
                    <small>Grasas por 100g</small>
                </div>
                <div class="p-2 border rounded text-center mx-1">
                    <p class="fw-bold mb-0">52</p>
                    <small>Calorías por 100g</small>
                </div>
                <div class="p-2 border rounded text-center mx-1">
                    <p class="fw-bold mb-0">1.1</p>
                    <small>Proteínas por 100g</small>
                </div>
            </div>

            <!-- Descripción del Producto -->
            <p>
                Nescafé Frappé es un premix ideal para tu negocio. Esta mezcla en polvo te permite preparar bebidas frías
                de manera fácil y rápida. No requiere el uso de base láctea, leches o ingredientes adicionales, ya que es
                un premix completo.
            </p>
        </div>
    </div>
</div>


<div class="container my-5 text-center">
    <h2 class="fw-bold" style="color: #2d4c48;">Productos Top</h2>
</div>

<div class="container my-5">
    <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <!-- Primer Slide -->
            <div class="carousel-item active">
                <div class="row justify-content-center">
                    <!-- Producto 1 -->
                    <div class="col-6 col-md-3 mb-4">
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

                    <!-- Producto 2 -->
                    <div class="col-6 col-md-3 mb-4">
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

                    <!-- Producto 3 -->
                    <div class="col-6 col-md-3 mb-4">
                        <div class="card text-center border-0">
                            <img src="front/multimedia/pera.png" class="card-img-top img-fluid" alt="Pera Orgánica" style="height: 200px; object-fit: contain;">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Pera Orgánica</h5>
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

                    <!-- Producto 4 -->
                    <div class="col-6 col-md-3 mb-4">
                        <div class="card text-center border-0">
                            <img src="front/multimedia/chile.png" class="card-img-top img-fluid" alt="Chile Orgánico" style="height: 200px; object-fit: contain;">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">Chile Orgánico</h5>
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
