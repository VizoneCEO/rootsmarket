<?php
// 1. Incluir la conexión a la BD
require_once(__DIR__ . '/../../back/conection/db.php');

// 2. Obtener la categoría de la URL, si existe
$categoria_seleccionada_id = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;

// 3. Obtener todas las categorías para el filtro
try {
    $stmt_cat = $pdo->prepare("SELECT id, nombre FROM catalogos WHERE estatus = 'activo' ORDER BY nombre ASC");
    $stmt_cat->execute();
    $categorias = $stmt_cat->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $categorias = [];
}

// 4. Construir la consulta de productos para la carga inicial
$sql_productos = "SELECT 
                    p.id, p.nombre, p.descripcion_corta, p.precio_venta, p.calificacion,
                    (SELECT imagen_url FROM producto_imagenes WHERE producto_id = p.id ORDER BY orden ASC LIMIT 1) as imagen_principal
                  FROM productos p
                  WHERE p.estatus = 'activo'";
$params = [];

if ($categoria_seleccionada_id > 0) {
    $sql_productos .= " AND p.catalogo_id = ?";
    $params[] = $categoria_seleccionada_id;
}
$sql_productos .= " ORDER BY p.nombre ASC";

try {
    $stmt_prod = $pdo->prepare($sql_productos);
    $stmt_prod->execute($params);
    $productos_iniciales = $stmt_prod->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $productos_iniciales = [];
}
?>

<div class="container my-5">
    <div class="header-image mb-4 position-relative">
        <img src="front/multimedia/tienda.png" alt="Nuestra misión es contigo" class="img-fluid rounded w-100">
        <h2 class="overlay-text position-absolute top-50 start-50 translate-middle text-white text-center fw-bold">Nuestra misión es contigo</h2>
    </div>

    <h2 class="text-center fw-bold mb-4">Bienvenido a tu espacio orgánico</h2>
    <hr>

    <div class="row mb-4 gx-2">
        <div class="col-md-4 mb-2 mb-md-0">
            <select class="form-select" id="filtroCategoria">
                <option value="0">Todas las Categorías</option>
                <?php foreach ($categorias as $categoria): ?>
                    <?php $selected = ($categoria['id'] == $categoria_seleccionada_id) ? 'selected' : ''; ?>
                    <option value="<?php echo htmlspecialchars($categoria['id']); ?>" <?php echo $selected; ?>>
                        <?php echo htmlspecialchars($categoria['nombre']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-8">
            <input type="text" class="form-control" id="buscador" placeholder="Busca tu producto por nombre...">
        </div>
    </div>

    <div class="product-container p-4 rounded">
        <div class="row g-4" id="listaProductos">
            </div>
    </div>
</div>

<style>
    /* Estilos originales del diseño estático */
    .header-image { height: 300px; position: relative; overflow: hidden; border-radius: 10px; }
    .header-image img { height: 100%; width: 100%; object-fit: cover; }
    .overlay-text { font-size: 2rem; padding: 0 10px; }
    .form-select { width: 100%; font-size: 0.9rem; padding: 8px; }
    .product-container { background-color: #f5f5f5; }
    .card img { max-height: 150px; object-fit: contain; }
    .card-body { padding: 1rem; }
    .btn-success, .btn-outline-primary { border-radius: 20px; }
    @media (max-width: 768px) {
        .header-image { height: 200px; }
        .overlay-text { font-size: 1.5rem; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filtroCategoria = document.getElementById('filtroCategoria');
    const buscador = document.getElementById('buscador');
    const listaProductos = document.getElementById('listaProductos');

    // Cargar los productos iniciales que ya obtuvimos con PHP
    let productosActuales = <?php echo json_encode($productos_iniciales); ?>;

    function renderizarProductos(productos) {
        listaProductos.innerHTML = ''; // Limpiar vista
        if (productos.length === 0) {
            listaProductos.innerHTML = '<p class="text-center col-12">No se encontraron productos.</p>';
            return;
        }

        productos.forEach(producto => {
            const imagenUrl = producto.imagen_principal ? encodeURI(ltrim(producto.imagen_principal, '/')) : 'front/multimedia/default.png';

            // --- ESTRUCTURA DE TARJETA RESTAURADA ---
            const productoHTML = `
                <div class="col-6 col-md-4 col-lg-3 producto-item" data-nombre="${producto.nombre.toLowerCase()}">
                    <div class="card text-center border-0 shadow-sm h-100">
                        <img src="${imagenUrl}" class="card-img-top p-3" alt="${producto.nombre}">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>
                                <p class="card-text fw-bold nombre-producto">${producto.nombre}</p>
                                <p class="text-muted small">${producto.descripcion_corta || ''}</p>
                                <p class="fw-bold">$${parseFloat(producto.precio_venta).toFixed(2)}</p>
                                <p class="text-warning"><i class="fas fa-star"></i> (${producto.calificacion || 'N/A'})</p>
                            </div>
                            <div class="d-grid gap-2 mt-3">
                                <button class="btn btn-success">Agregar</button>
                                <a href="producto.php?id=${producto.id}" class="btn btn-outline-primary">Ver Detalle</a>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            listaProductos.innerHTML += productoHTML;
        });
    }

    // Búsqueda en tiempo real (filtra los productos ya cargados)
    buscador.addEventListener('keyup', function() {
        const texto = this.value.toLowerCase();
        document.querySelectorAll('.producto-item').forEach(item => {
            const nombre = item.dataset.nombre;
            if (nombre.includes(texto)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Redirección al cambiar de categoría
    filtroCategoria.addEventListener('change', function() {
        const categoriaId = this.value;
        if (categoriaId === '0') {
            window.location.href = 'tienda.php';
        } else {
            window.location.href = 'tienda.php?categoria=' + categoriaId;
        }
    });

    // Función para quitar la barra inicial de una ruta
    function ltrim(str, chars) {
        return str.replace(new RegExp("^[" + chars + "]+"), "");
    }

    // Carga inicial de los productos
    renderizarProductos(productosActuales);
});
</script>