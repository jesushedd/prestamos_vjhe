-- 1. Personas (base de herencia)
CREATE TABLE IF NOT EXISTS personas (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100),
    apellido VARCHAR(100),
    dni VARCHAR(8) UNIQUE NOT NULL
);

-- 2. Clientes (hereda de personas)
CREATE TABLE IF NOT EXISTS  clientes (
    id INT PRIMARY KEY REFERENCES personas(id),
    email VARCHAR(100) NOT NULL, 
    fecha_creacion DATE NOT NULL, 
    direccion VARCHAR(100) nOT NULL,
    telefono VARCHAR(10) nOT NULL
);

-- 3. Tipos de Usuarios
CREATE TABLE IF NOT EXISTS  tipos_usuarios (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre_tipo VARCHAR(50) UNIQUE NOT NULL
);

-- 4. Usuarios (hereda de personas)
CREATE TABLE IF NOT EXISTS  usuarios (
    id INT PRIMARY KEY REFERENCES personas(id),
    id_tipo_usuario INT NOT NULL REFERENCES tipos_usuarios(id),
    nombre_usuario VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- 5. Prestamos
CREATE TABLE prestamos (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT NOT NULL REFERENCES clientes(id),
    monto DECIMAL(10,2) NOT NULL,
    fecha_inicio DATE NOT NULL,
    plazos INT UNSIGNED NOT NULL,
    interes_anual DECIMAL(4,2) NOT NULL,
    estado ENUM('vigente', 'pagado', 'atrasado') NOT NULL
);



-- 6. Pagos
CREATE TABLE pagos (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_prestamo INT NOT NULL REFERENCES prestamos(id),
    pago_principal DECIMAL(10,2) NOT NULL,
    pago_interes DECIMAL(10,2) NOT NULL,
    pago_total DECIMAL(10,2) NOT NULL,
    restante_pago_principal DECIMAL(10,2) NOT NULL,
    fecha_pago DATE NOT NULL, 
    estado ENUM('pendiente', 'pagado') NOT NULL

);

-- 7. Intereses
CREATE TABLE intereses (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY  KEY,
    id_prestamo INT NOT NULL REFERENCES prestamos(id),
    tasa DECIMAL(5,2),
    fecha_aplicacion DATE
);

-- 8. Reportes (si aplican)
CREATE TABLE reportes (
    id_reporte BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT REFERENCES usuarios(id_usuario),
    descripcion TEXT,
    fecha_reporte TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);