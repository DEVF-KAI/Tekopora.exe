CREATE DATABASE IF NOT EXISTS tekopora_db;
USE tekopora_db;

CREATE TABLE IF NOT EXISTS departamento (
    idDepartamento INT AUTO_INCREMENT PRIMARY KEY,
    codigoDepartamento VARCHAR(10) NOT NULL UNIQUE,
    nombreDepartamento VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS municipio (
    idMunicipio INT AUTO_INCREMENT PRIMARY KEY,
    codigoMunicipio VARCHAR(10) NOT NULL UNIQUE,
    nombreMunicipio VARCHAR(100) NOT NULL,
    idDepartamento_FK INT NOT NULL,
    FOREIGN KEY (idDepartamento_FK) REFERENCES departamento(idDepartamento)
);

CREATE TABLE IF NOT EXISTS alcalde (
    idAlcalde INT AUTO_INCREMENT PRIMARY KEY,
    codigoAlcalde VARCHAR(10) NOT NULL UNIQUE,
    nombreAlcalde VARCHAR(50) NOT NULL,
    apPaternoAlcalde VARCHAR(50) NOT NULL,
    apMaternoAlcalde VARCHAR(50),
    inicioGestion DATE NOT NULL,
    finGestion DATE
);

CREATE TABLE IF NOT EXISTS alcaldia (
    idAlcaldia INT AUTO_INCREMENT PRIMARY KEY,
    codigoAlcaldia VARCHAR(10) NOT NULL UNIQUE,
    nombreAlcaldia VARCHAR(100) NOT NULL,
    presupuestoAnual DECIMAL(15, 2) DEFAULT 0.00,
    idMunicipio_FK INT NOT NULL,
    idAlcalde_FK INT UNIQUE,
    FOREIGN KEY (idMunicipio_FK) REFERENCES municipio(idMunicipio),
    FOREIGN KEY (idAlcalde_FK) REFERENCES alcalde(idAlcalde)
);

CREATE TABLE IF NOT EXISTS macrodistrito (
    idMacrodistrito INT AUTO_INCREMENT PRIMARY KEY,
    codigoMacrodistrito VARCHAR(10) NOT NULL UNIQUE,
    nombreMacrodistrito VARCHAR(100) NOT NULL,
    presupuestoAnual DECIMAL(15, 2) DEFAULT 0.00,
    idAlcaldia_FK INT NOT NULL,
    FOREIGN KEY (idAlcaldia_FK) REFERENCES alcaldia(idAlcaldia)
);

-- =========================
-- USUARIOS Y SEGURIDAD
-- =========================

CREATE TABLE IF NOT EXISTS usuario (
    idUsuario INT AUTO_INCREMENT PRIMARY KEY,
    codigoUsuario VARCHAR(20) NOT NULL UNIQUE,
    ci VARCHAR(15) NOT NULL UNIQUE,
    nombre VARCHAR(50) NOT NULL,
    appPaterno VARCHAR(50) NOT NULL,
    appMaterno VARCHAR(50),
    fechaNacimiento DATE,
    email VARCHAR(100) NOT NULL UNIQUE,
    telefono VARCHAR(20),
    passwordHash VARCHAR(255) NOT NULL,
    karmaTotal INT DEFAULT 0,
    estado ENUM('Activo', 'Inactivo', 'Suspendido') DEFAULT 'Activo',
    failedLoginAttempts INT NOT NULL DEFAULT 0,
    bloqueado TINYINT(1) NOT NULL DEFAULT 0,
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS rol (
    idRol INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS permiso (
    idPermiso INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS usuario_rol (
    idUsuarioRol INT AUTO_INCREMENT PRIMARY KEY,
    idUsuario_FK INT NOT NULL,
    idRol_FK INT NOT NULL,
    FOREIGN KEY (idUsuario_FK) REFERENCES usuario(idUsuario),
    FOREIGN KEY (idRol_FK) REFERENCES rol(idRol)
);

CREATE TABLE IF NOT EXISTS rol_permiso (
    idRolPermiso INT AUTO_INCREMENT PRIMARY KEY,
    idRol_FK INT NOT NULL,
    idPermiso_FK INT NOT NULL,
    FOREIGN KEY (idRol_FK) REFERENCES rol(idRol),
    FOREIGN KEY (idPermiso_FK) REFERENCES permiso(idPermiso)
);

-- =========================
-- NOTIFICACIONES Y BITACORA
-- =========================

CREATE TABLE IF NOT EXISTS notificacion (
    idNotificacion INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    mensaje TEXT NOT NULL,
    fechaCreacion DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS usuario_notificacion (
    idUsuarioNotificacion INT AUTO_INCREMENT PRIMARY KEY,
    idUsuario_FK INT NOT NULL,
    idNotificacion_FK INT NOT NULL,
    leida BOOLEAN DEFAULT FALSE,
    fechaLectura DATETIME,
    FOREIGN KEY (idUsuario_FK) REFERENCES usuario(idUsuario),
    FOREIGN KEY (idNotificacion_FK) REFERENCES notificacion(idNotificacion)
);

CREATE TABLE IF NOT EXISTS bitacora (
    idBitacora INT AUTO_INCREMENT PRIMARY KEY,
    accion TEXT NOT NULL,
    fechaHora DATETIME DEFAULT CURRENT_TIMESTAMP,
    idUsuario_FK INT NOT NULL,
    FOREIGN KEY (idUsuario_FK) REFERENCES usuario(idUsuario)
);

-- =========================
-- PROYECTOS
-- =========================

CREATE TABLE IF NOT EXISTS empresaConstructora (
    idEmpresa INT AUTO_INCREMENT PRIMARY KEY,
    codigoEmpresa VARCHAR(20) NOT NULL UNIQUE,
    nombreEmpresa VARCHAR(150) NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    valoracionPromedio DECIMAL(3, 2) DEFAULT 0.00
);
CREATE TABLE IF NOT EXISTS evaluacion_empresa (
    idEvaluacion INT AUTO_INCREMENT PRIMARY KEY,
    puntaje TINYINT NOT NULL CHECK(puntaje BETWEEN 1 AND 5),
    idUsuario_FK INT NOT NULL,
    idEmpresa_FK INT NOT NULL,
    UNIQUE KEY (idUsuario_FK, idEmpresa_FK), -- Esto evita que un usuario vote 2 veces a la misma empresa
    FOREIGN KEY (idUsuario_FK) REFERENCES usuario(idUsuario),
    FOREIGN KEY (idEmpresa_FK) REFERENCES empresaConstructora(idEmpresa)
);

CREATE TABLE IF NOT EXISTS proyecto (
    idProyecto INT AUTO_INCREMENT PRIMARY KEY,
    codigoProyecto VARCHAR(20) NOT NULL UNIQUE,
    nombreProyecto VARCHAR(200) NOT NULL,
    descripcion TEXT,
    presupuesto DECIMAL(18, 2) NOT NULL,
    fechaInicio DATE NOT NULL,
    fechaEntregaEstimada DATE,
    avancePorcentaje DECIMAL(5, 2) DEFAULT 0.00,
    estado VARCHAR(50) DEFAULT 'Pendiente',
    latitud DECIMAL(10, 7),
    longitud DECIMAL(10, 7),
    idUsuario_FK INT NOT NULL,
    FOREIGN KEY (idUsuario_FK) REFERENCES usuario(idUsuario)
);

CREATE TABLE IF NOT EXISTS proyecto_empresa (
    idProyectoEmpresa INT AUTO_INCREMENT PRIMARY KEY,
    idProyecto_FK INT NOT NULL,
    idEmpresa_FK INT NOT NULL,
    FOREIGN KEY (idProyecto_FK) REFERENCES proyecto(idProyecto),
    FOREIGN KEY (idEmpresa_FK) REFERENCES empresaConstructora(idEmpresa)
);

CREATE TABLE IF NOT EXISTS reporteProyecto (
    idReporte INT AUTO_INCREMENT PRIMARY KEY,
    fechaReporte DATETIME DEFAULT CURRENT_TIMESTAMP,
    descripcion TEXT NOT NULL,
    porcentajeAvance DECIMAL(5, 2) NOT NULL,
    idProyecto_FK INT NOT NULL,
    idUsuario_FK INT NOT NULL,
    FOREIGN KEY (idProyecto_FK) REFERENCES proyecto(idProyecto),
    FOREIGN KEY (idUsuario_FK) REFERENCES usuario(idUsuario)
);

CREATE TABLE IF NOT EXISTS macrodistrito_proyecto (
    idMacrodistritoProyecto INT AUTO_INCREMENT PRIMARY KEY,
    idMacrodistrito_FK INT NOT NULL,
    idProyecto_FK INT NOT NULL,
    FOREIGN KEY (idMacrodistrito_FK) REFERENCES macrodistrito(idMacrodistrito),
    FOREIGN KEY (idProyecto_FK) REFERENCES proyecto(idProyecto)
);

-- =========================
-- TURISMO Y SOCIAL
-- =========================
-- 1. Creamos la tabla para Sitios Turísticos
CREATE TABLE IF NOT EXISTS sitioTuristico (
    idSitio INT AUTO_INCREMENT PRIMARY KEY,
    codigoSitio VARCHAR(20) NOT NULL UNIQUE,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    latitud DECIMAL(10, 7) NOT NULL,
    longitud DECIMAL(10, 7) NOT NULL,
    estado ENUM('Pendiente', 'Aprobado', 'Rechazado') DEFAULT 'Pendiente',
    idUsuario_FK INT NOT NULL, -- El ciudadano o usuario que lo propone
    fechaRegistro DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idUsuario_FK) REFERENCES usuario(idUsuario)
);

CREATE TABLE IF NOT EXISTS categoriaTuristica (
    idCategoria INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS publicacion (
    idPublicacion INT AUTO_INCREMENT PRIMARY KEY,
    codigoPublicacion VARCHAR(20) NOT NULL UNIQUE,
    titulo VARCHAR(200) NOT NULL,
    contenido TEXT NOT NULL,
    fechaPublicacion DATETIME DEFAULT CURRENT_TIMESTAMP,
    estado VARCHAR(20) DEFAULT 'Pendiente',
    idUsuario_FK INT NOT NULL,
    idMunicipio_FK INT NOT NULL,
    idCategoria_FK INT NOT NULL,
    FOREIGN KEY (idUsuario_FK) REFERENCES usuario(idUsuario),
    FOREIGN KEY (idMunicipio_FK) REFERENCES municipio(idMunicipio),
    FOREIGN KEY (idCategoria_FK) REFERENCES categoriaTuristica(idCategoria)
);

-- TABLA MULTIMEDIA ACTUALIZADA (Soporte Triple)
-- =============================================
CREATE TABLE IF NOT EXISTS multimedia (
    idMultimedia INT AUTO_INCREMENT PRIMARY KEY,
    urlArchivo TEXT NOT NULL,
    tipo VARCHAR(50),
    -- Relaciones (Todas permiten NULL para ser polimórficas)
    idPublicacion_FK INT NULL,
    idProyecto_FK INT NULL,
    idSitio_FK INT NULL,
    -- Llaves Foráneas con eliminación en cascada
    FOREIGN KEY (idPublicacion_FK) REFERENCES publicacion(idPublicacion) ON DELETE CASCADE,
    FOREIGN KEY (idProyecto_FK) REFERENCES proyecto(idProyecto) ON DELETE CASCADE,
    FOREIGN KEY (idSitio_FK) REFERENCES sitioTuristico(idSitio) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS comentario (
    idComentario INT AUTO_INCREMENT PRIMARY KEY,
    contenido TEXT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    idUsuario_FK INT NOT NULL,
    idPublicacion_FK INT NOT NULL,
    FOREIGN KEY (idUsuario_FK) REFERENCES usuario(idUsuario),
    FOREIGN KEY (idPublicacion_FK) REFERENCES publicacion(idPublicacion)
);

CREATE TABLE IF NOT EXISTS voto (
    idVoto INT AUTO_INCREMENT PRIMARY KEY,
    tipoVoto TINYINT NOT NULL,
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    idUsuario_FK INT NOT NULL,
    idPublicacion_FK INT NULL,
    idComentario_FK INT NULL,
    FOREIGN KEY (idUsuario_FK) REFERENCES usuario(idUsuario),
    FOREIGN KEY (idPublicacion_FK) REFERENCES publicacion(idPublicacion),
    FOREIGN KEY (idComentario_FK) REFERENCES comentario(idComentario)
);

-- ==========================================================
-- MODIFICACIONES PARA INCLUIR INICIO DE SESION CON GOOGLE
-- ==========================================================

-- Ajustes para el registro inicial con Google 
ALTER TABLE usuario 
MODIFY ci VARCHAR(15) NULL,
MODIFY appPaterno VARCHAR(50) NULL,
MODIFY passwordHash VARCHAR(255) NULL;

-- Añadir el identificador único de Google
ALTER TABLE usuario 
ADD COLUMN google_id VARCHAR(100) NULL UNIQUE AFTER idUsuario;

-- Añadir campos para verificación en dos pasos (2FA)
ALTER TABLE usuario 
ADD COLUMN codigo_verificacion VARCHAR(10) NULL, 
ADD COLUMN expiracion_codigo DATETIME NULL;

INSERT INTO categoriaturistica (idCategoria, nombre) VALUES
(1, 'General'),
(2, 'Avance de Obras Públicas'),
(3, 'Turismo, Arte y Cultura'),
(4, 'Denuncias y Reportes Ciudadanos'),
(5, 'Movilidad y Tráfico Urbano'),
(6, 'Medio Ambiente y Áreas Verdes');

INSERT IGNORE INTO departamento (codigoDepartamento, nombreDepartamento) 
VALUES ('LPZ-01', 'La Paz');

INSERT IGNORE   INTO municipio (idMunicipio, codigoMunicipio, nombreMunicipio, idDepartamento_FK) VALUES 
(1, 'MUN-LPZ-01', 'La Paz', 1),
(2, 'MUN-EAL-02', 'El Alto', 1),
(3, 'MUN-VIA-03', 'Viacha', 1),
(4, 'MUN-ACH-04', 'Achocalla', 1),
(5, 'MUN-MEC-05', 'Mecapaca', 1),
(6, 'MUN-PAL-06', 'Palca', 1),
(7, 'MUN-LPB', 'La Paz (Nuestra Señora de La Paz)', 1),
(8, 'MUN-EAL', 'El Alto', 1);

INSERT IGNORE INTO alcalde (codigoAlcalde, nombreAlcalde, apPaternoAlcalde, apMaternoAlcalde, inicioGestion, finGestion)
VALUES ('ALC-ARIAS', 'Iván', 'Arias', 'Durán', '2021-05-03', '2026-05-03');

INSERT IGNORE INTO alcaldia (codigoAlcaldia, nombreAlcaldia, presupuestoAnual, idMunicipio_FK, idAlcalde_FK)
VALUES ('GAMLP-LP', 'Gobierno Autónomo Municipal de La Paz', 2100000000.00, 1, 1);

INSERT IGNORE INTO macrodistrito (codigoMacrodistrito, nombreMacrodistrito, presupuestoAnual, idAlcaldia_FK) 
VALUES 
('MAC-COT', 'Cotahuma', 45000000.00, 1),
('MAC-MAX', 'Max Paredes', 48000000.00, 1),
('MAC-PER', 'Periférica', 42000000.00, 1),
('MAC-SAN', 'San Antonio', 40000000.00, 1),
('MAC-SUR', 'Sur', 65000000.00, 1),
('MAC-MAL', 'Mallasa', 30000000.00, 1),
('MAC-CEN', 'Centro', 55000000.00, 1),
('MAC-HAM', 'Hampaturi', 25000000.00, 1),
('MAC-ZON', 'Zongo', 20000000.00, 1);
INSERT INTO empresaConstructora (codigoEmpresa, nombreEmpresa, telefono, direccion) VALUES
('EMP-TAU', 'Constructora Tauro S.A.', '22445566', 'Av. Arce, Edif. Illimani'),
('EMP-ALB', 'Alba Ingeniería & Obras', '22334455', 'Calle 21 de Calacoto'),
('EMP-BOL', 'Boliviana de Carreteras', '22112233', 'Zona Industrial El Alto');