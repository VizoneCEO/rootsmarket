INSERT INTO productos (
    id, 
    catalogo_id, 
    nombre, 
    sku, 
    descripcion_corta, 
    descripcion_larga, 
    precio_compra, 
    precio_venta, 
    precio_oferta, 
    origen, 
    es_organico, 
    es_vegano, 
    es_vegetariano, 
    es_sin_gluten, 
    porcion_info, 
    calorias, 
    proteinas_g, 
    carbohidratos_g, 
    grasas_g, 
    azucares_g, 
    fibra_g, 
    sodio_mg, 
    calificacion, 
    estatus, 
    es_novedad, 
    es_promocion, 
    fondo_detalle, 
    es_temporada, 
    es_mejor, 
    stock, 
    stock_minimo, 
    tiene_azucar
) VALUES (
    10000,                                -- id
    6,                                    -- catalogo_id (Asignado a un catálogo válido para evitar error de NOT NULL)
    'Bolsa Reutilizable',                 -- nombre
    'BOLSA-REUT',                         -- sku
    'Bolsa ecológica reutilizable',       -- descripcion_corta
    'Bolsa ecológica reutilizable de alta calidad.', -- descripcion_larga
    NULL,                                 -- precio_compra
    13.00,                                -- precio_venta
    NULL,                                 -- precio_oferta
    NULL,                                 -- origen
    0,                                    -- es_organico
    0,                                    -- es_vegano
    0,                                    -- es_vegetariano
    0,                                    -- es_sin_gluten
    '1 pieza',                            -- porcion_info
    NULL,                                 -- calorias
    NULL,                                 -- proteinas_g
    NULL,                                 -- carbohidratos_g
    NULL,                                 -- grasas_g
    NULL,                                 -- azucares_g
    NULL,                                 -- fibra_g
    NULL,                                 -- sodio_mg
    NULL,                                 -- calificacion
    'activo',                             -- estatus
    0,                                    -- es_novedad
    0,                                    -- es_promocion
    NULL,                                 -- fondo_detalle
    0,                                    -- es_temporada
    0,                                    -- es_mejor
    10000,                                -- stock (alto para que no se agote)
    5,                                    -- stock_minimo
    0                                     -- tiene_azucar
);

-- Agregar la imagen al producto
INSERT INTO producto_imagenes (
    producto_id,
    imagen_url,
    alt_text,
    orden
) VALUES (
    10000,
    'front/multimedia/productos.png',
    'Bolsa Reutilizable',
    0
);
