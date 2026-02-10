-- Add columns for personal data to usuarios table
ALTER TABLE usuarios
ADD COLUMN apellido_paterno VARCHAR(100) DEFAULT NULL AFTER nombre,
ADD COLUMN apellido_materno VARCHAR(100) DEFAULT NULL AFTER apellido_paterno,
ADD COLUMN telefono VARCHAR(20) DEFAULT NULL AFTER apellido_materno,
ADD COLUMN direccion VARCHAR(255) DEFAULT NULL AFTER telefono,
ADD COLUMN ciudad VARCHAR(100) DEFAULT NULL AFTER direccion,
ADD COLUMN estado VARCHAR(100) DEFAULT NULL AFTER ciudad;
