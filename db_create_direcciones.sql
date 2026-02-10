-- Create table for shipping addresses
CREATE TABLE IF NOT EXISTS direcciones_envio (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nombre_contacto VARCHAR(255) NOT NULL, -- Who receives it
    telefono_contacto VARCHAR(20) NOT NULL,
    calle_numero VARCHAR(255) NOT NULL,
    codigo_postal VARCHAR(10) NOT NULL,
    colonia VARCHAR(100) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    estado VARCHAR(100) NOT NULL,
    alias VARCHAR(50) DEFAULT 'Casa', -- e.g. Casa, Oficina
    es_principal BOOLEAN DEFAULT FALSE,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
