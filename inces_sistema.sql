-- Crear base de datos
CREATE DATABASE IF NOT EXISTS inces_sistema;

-- Usar la base de datos creada
USE inces_sistema;

-- Tabla usuario
CREATE TABLE IF NOT EXISTS usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único del usuario',
    nombre_usuario VARCHAR(50) NOT NULL UNIQUE COMMENT 'Nombre de usuario único',
    tipo_usuario ENUM('Administrador', 'Auxiliar') NOT NULL COMMENT 'Tipo de usuario',
    contrasena VARCHAR(255) NOT NULL COMMENT 'Contraseña del usuario',
    correo_electronico VARCHAR(100) NOT NULL UNIQUE COMMENT 'Correo electrónico único del usuario',
    activo BOOLEAN DEFAULT 1 COMMENT 'Estado del usuario (activo/inactivo)',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro del usuario',
    token_recuperacion VARCHAR(64) DEFAULT NULL COMMENT 'Token de recuperación',
    token_expira DATETIME DEFAULT NULL COMMENT 'Fecha de expiración del token'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla de usuarios del sistema';

-- Agregar índice para búsquedas por token
CREATE INDEX idx_token_recuperacion ON usuario(token_recuperacion);

-- Tabla participante
CREATE TABLE IF NOT EXISTS participante (
    id_participante INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único del participante',
    
    -- Datos de identificación
    tipo_identificacion ENUM('Cédula', 'Pasaporte') NOT NULL COMMENT 'Tipo de identificación del participante',
    identificacion VARCHAR(20) NOT NULL UNIQUE COMMENT 'Número de identificación único del participante',
    
    -- Datos personales
    nombres VARCHAR(100) NOT NULL COMMENT 'Nombres del participante',
    apellidos VARCHAR(100) NOT NULL COMMENT 'Apellidos del participante',
    fecha_nacimiento DATE NOT NULL COMMENT 'Fecha de nacimiento del participante',
    edad INT COMMENT 'Edad del participante',
    genero ENUM('Masculino', 'Femenino') NOT NULL COMMENT 'Género del participante',
    nacionalidad ENUM('Venezolano', 'Extranjero') NOT NULL COMMENT 'Nacionalidad del participante',
    
    -- Datos de contacto
    telefono VARCHAR(15) NOT NULL COMMENT 'Teléfono del participante',
    correo VARCHAR(100) UNIQUE COMMENT 'Correo electrónico único del participante',
    direccion VARCHAR(100) NOT NULL COMMENT 'Dirección del participante',
    
    -- Ubicación
    estado VARCHAR(50) NOT NULL COMMENT 'Estado de residencia del participante',
    municipio VARCHAR(50) NOT NULL COMMENT 'Municipio de residencia del participante',
    parroquia VARCHAR(50) NOT NULL COMMENT 'Parroquia de residencia del participante',
    comuna VARCHAR(50) COMMENT 'Comuna de residencia del participante',
    ciudad VARCHAR(50) COMMENT 'Ciudad de residencia del participante',
    sector VARCHAR(50) COMMENT 'Sector de residencia del participante',
    
    -- Información adicional
    posee_discapacidad BOOLEAN DEFAULT 0 COMMENT 'Indica si el participante posee alguna discapacidad',
    tipo_discapacidad VARCHAR(100) COMMENT 'Tipo de discapacidad del participante',
    grado_instruccion VARCHAR(50) COMMENT 'Grado de instrucción del participante',
    estudia BOOLEAN DEFAULT 0 COMMENT 'Indica si el participante estudia (1: Sí, 0: No)',
    tipo_economia VARCHAR(50) COMMENT 'Tipo de economía del participante',
    trabaja BOOLEAN DEFAULT 0 COMMENT 'Indica si el participante trabaja (1: Sí, 0: No)',
    entidad_trabajo VARCHAR(100) COMMENT 'Nombre de la entidad de trabajo',
    
    -- Registro
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro del participante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla de participantes';

-- Tabla instructor
CREATE TABLE IF NOT EXISTS instructor (
    id_instructor INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único del instructor',
    nombres VARCHAR(100) NOT NULL COMMENT 'Nombres del instructor',
    apellidos VARCHAR(100) NOT NULL COMMENT 'Apellidos del instructor',
    correo_electronico VARCHAR(100) NOT NULL UNIQUE COMMENT 'Correo electrónico único del instructor',
    telefono VARCHAR(15) NOT NULL COMMENT 'Teléfono del instructor',
    especialidad VARCHAR(100) NOT NULL COMMENT 'Especialidad del instructor',
    experiencia_anios INT DEFAULT 0 COMMENT 'Años de experiencia del instructor',
    certificado BOOLEAN NOT NULL DEFAULT 0 COMMENT 'Indica si el instructor está certificado',
    direccion TEXT COMMENT 'Dirección del instructor',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro del instructor',
    activo BOOLEAN DEFAULT 1 COMMENT 'Estado del instructor (activo/inactivo)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla de instructores';

-- Tabla tipo_formacion (antes tipo_curso)
CREATE TABLE IF NOT EXISTS tipo_formacion (
    id_tipo_formacion INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único del tipo de formación',
    nombre_tipo_formacion VARCHAR(255) NOT NULL COMMENT 'Nombre del tipo de formación'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla de tipos de formación';

-- Datos de ejemplo para la tabla tipo_formacion
INSERT INTO tipo_formacion (nombre_tipo_formacion) VALUES ('Presencial'), ('En línea'), ('Híbrido');

-- Tabla formacion (modificada para permitir inserciones estáticas)
DROP TABLE IF EXISTS formacion;
CREATE TABLE IF NOT EXISTS formacion (
    id_formacion INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único de la formación',
    nombre_formacion VARCHAR(255) NOT NULL COMMENT 'Nombre de la formación',
    descripcion TEXT DEFAULT NULL COMMENT 'Descripción de la formación',
    id_tipo_formacion INT DEFAULT NULL COMMENT 'Identificador del tipo de formación',
    fecha_inicio DATE DEFAULT NULL COMMENT 'Fecha de inicio de la formación',
    fecha_fin DATE DEFAULT NULL COMMENT 'Fecha de fin de la formación',
    -- Se omite la clave foránea para permitir registros sin información adicional
    INDEX idx_tipo_formacion (id_tipo_formacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla de formaciones';

-- Inserción de formaciones predefinidas (se insertan únicamente el nombre; los demás campos quedan en null)
INSERT INTO formacion (nombre_formacion) VALUES 
('CRIADOR DE GANADO OVINO Y CAPRINO'),
('ARTESANO CONSTRUCTOR DE EDIFICACIONES DE TIERRA'),
('ASISTENTE ADMINISTRATIVO'),
('AVICULTORES'),
('ASISTENTE DE COMUNICACIÓN SOCIAL DIGITAL'),
('MESONERO SERVICIO DE VINOS'),
('CERRAJERO'),
('ASISTENTE DE RECREACIÓN'),
('AUXILIAR DE RECREACIÓN'),
('CARPINTERO'),
('AUXILIAR DE PROTOCOLO'),
('ASISTENTE DE CONTABILIDAD'),
('ANALISTA DE SISTEMA'),
('AYUDANTE DE ALBAÑIL'),
('AYUDANTE DE ARTESANO CONSTRUCTOR DE EDIFICACIONES DE TIERRA'),
('CONSERVADOR DE HORTALIZAS PARA USO GASTRONÓMICO'),
('BARBERO(A)'),
('AYUDANTE DE COCINA'),
('REFORESTADOR DE BOSQUES'),
('CONFECCIONISTA DE ROPA INFANTIL'),
('CONFECCIONISTA DE UNIFORMES'),
('CRIADORES DE GANADO BOVINO'),
('BARTENDER'),
('COCINERO (A)'),
('CRIADORES DE GANADO PORCINO'),
('CULTIVADOR DE CACAO'),
('DESARROLLADOR DE SITIO WEB'),
('DIAGRAMADOR(A)'),
('ELABORADOR DE FRUTAS DESHIDRATADAS Y CONFITADAS'),
('DISEÑADOR DE CALZADO'),
('DISEÑADOR DE SITIO WEB'),
('DISTRIBUIDOR DE GAS LICUADO DE PETRÓLEO'),
('EBANISTA'),
('EDITORIALISTA'),
('ELABORADOR DE BOLSOS TEJIDOS'),
('ELABORADOR DE CHINCHORROS'),
('ELABORADOR DE COJINES'),
('ELABORADOR DE COMPOTAS Y CREMAS: FRUTAS Y HORTALIZAS'),
('ELABORADOR DE HAMACAS'),
('ELABORADOR DE HARINAS ARTESANALES A BASE DE GRANOS Y CEREALES.'),
('OPERADOR DE CALDERAS'),
('ELABORADOR DE INSTRUMENTOS MUSICALES DE CUERDA'),
('ELABORADOR DE NÉCTAR, JUGOS CONCENTRADOS Y HELADOS A BASE DE FRUTAS.'),
('ELABORADOR DE VINOS A BASE DE FRUTAS'),
('RESTAURADOR, MUEBLES DE MADERA'),
('ENTRENADOR DEPORTIVO ESPECIALIDAD: VOLEIBOL'),
('ORIENTADOR(A) FAMILIAR Y GESTACIÓN'),
('EMPRENDEDOR'),
('EMPRENDEDOR DIGITAL'),
('ENTRENADOR DEPORTIVO'),
('ENTRENADOR DEPORTIVO ESPECIALIDAD: BALONCESTO'),
('ENTRENADOR DEPORTIVO ESPECIALIDAD: FUTBOL SALA'),
('ESTETICISTA'),
('FOTÓGRAFO (A)'),
('MÉTODOS DE DESTRUCCIÓN DE MATERIAL EXPLOSIVO'),
('GUIA CEREMONIAL'),
('PROMOTOR(A) DE SALUD COMUNITARIA'),
('GUIA PROTOCOLO'),
('GUIA DE TURISMO DE AVENTURA EN BAJA Y MEDIA MONTAÑA'),
('INSPECTOR DE SEGURIDAD Y SALUD OCUPACIONAL'),
('INSTALADOR DE CONDUCTOS PARA AIRE ACONDICIONADO'),
('MECÁNICO DE MOTORES A GASOLINA'),
('PANADERO'),
('TÉCNICO EN DEMOLICIÓN Y DESACTIVACIÓN DE BOMBAS Y ARTEFACTOS EXPLOSIVOS'),
('AYUDANTE DE SERVICIOS DE POZOS PETROLEROS'),
('ENCUELLADOR DE SERVICIOS DE POZOS PETROLEROS'),
('MAESTRO ARTESANO DE EDIFICACIONES DE TIERRA'),
('MANICURISTA/PEDICURISTA'),
('INSTALADOR DE REDES Y LÍNEAS DE TELECOMUNICACIONES'),
('MECÁNICO MAQUINARIA TEXTIL'),
('OPERADOR DE CARGADOR FRONTAL'),
('INSTALADOR DE REVESTIMIENTOS: PISOS, PAREDES Y TECHOS'),
('MECÁNICO OPERADOR DE MONTACARGAS'),
('MESONERO/AZAFATA'),
('MONTADOR PREFABRICADOS TABIQUERÍA LIGERA'),
('OBRERO DE MANTENIMIENTO DE ESPACIOS PÚBLICOS'),
('PANTRISTA'),
('MONTADOR DE CALZADO'),
('OPERADOR DE GRANJAS INTEGRALES'),
('OPERADOR DE MÁQUINA MOLIENDA/CEREALES'),
('OPERADOR DE MAQUINARIAS FIJAS'),
('OPERADOR DE MÁQUINAS DE CONFECCIÓN'),
('OPERADOR DE PLANTA DE GAS LICUADO DE PETRÓLEO'),
('PRODUCTOR AGRÍCOLA'),
('PASTELERO'),
('PATRONISTA PRENDAS DE VESTIR'),
('PELUQUERO(A)'),
('PLOMERO'),
('PROCESADOR DE CACAO'),
('PARRILLERO (A)'),
('PRODUCTOR AGROPECUARIO'),
('PRODUCTOR(A) AGROECOLÓGICO'),
('PROMOTOR CULTURAL'),
('PROMOTOR DE RECREACIÓN DEPORTIVA'),
('PROMOTOR DE RECREACIÓN LABORAL'),
('PROMOTOR RECREATIVO'),
('ELABORADOR DE DULCES A BASE DE COCO'),
('PROMOTOR(A) DE SALUD COMUNITARIA: PARTO HUMANIZADO'),
('RECEPCIONISTA DE HOTEL'),
('REDACTOR (A)'),
('REPARADOR DE MOTORES DIÉSEL'),
('REPORTERO (A) GRÁFICO'),
('TÉCNICO WEB'),
('REPORTERO (A) GRÁFICO DIGITAL'),
('REPOSTERO'),
('RESTAURADOR, Y MANTENIMIENTO DE INSTRUMENTOS MUSICALES DE CUERDA'),
('SECRETARIO(A) ADMINISTRATIVO (A)'),
('SOLDADOR (A)'),
('AYUDANTE DE CALZADO'),
('SOLDADOR (A) ARCO ELÉCTRICO'),
('SOLDADOR (A) DE SOLDADURAS ESPECIALES'),
('SUPERVISOR DE CONTROL DE CALIDAD EN LA PRODUCCIÓN'),
('TÉCNICO EN ATENCIÓN AL USUARIO EN TECNOLOGÍA  DE LA INFORMACIÓN Y LA COMUNICACIÓN'),
('TÉCNICO EN FABRICACIÓN DE MUEBLES'),
('TÉCNICO EN SOPORTE Y MANTENIMIENTO DE TECNOLOGÍAS DE INFORMACIÓN'),
('TÉCNICO EN REDES Y SISTEMAS INFORMÁTICOS'),
('ALBAÑIL'),
('ENSAMBLADOR DE PRODUCTOS ELECTRÓNICOS'),
('DIMENSIÓN FORMACIÓN DIDÁCTICA Y PEDAGÓGICA'),
('CULTOR DE CACAO'),
('JARDINERO'),
('ARMADOR DE CORTE'),
('MECÁNICO DE REFRIGERACIÓN Y CLIMATIZACIÓN'),
('INSTALADOR DE EQUIPOS DE REFRIGERACIÓN Y AIRE ACONDICionado'),
('GESTOR EMPRESARIAL DE TALENTO HUMANO'),
('INTÉRPRETE DE CANTO POPULAR'),
('INSTALADOR DE FIBRA ÓPTICA'),
('FABRICANTE DE CALZADO'),
('OPERADOR (A) DE SERVICIOS DE ALOJAMIENTO'),
('SUPERVISOR (A) DE AMBIENTES EN INSTALACIONES DE ALOJAMIENTO'),
('SUPERVISOR (A) DE DEPARTAMENTO EN INSTALACIONES DE ALOJAMIENTOS'),
('PROCESADOR DE CAFÉ'),
('ASISTENTE SOCIAL PARA LA ATENCIÓN INTEGRAL DE PERSONAS CON DISCAPACIDAD'),
('CORTADOR DE CALZADO'),
('ELABORADOR DE DULCES A BASE DE AUYAMA'),
('ELABORADOR DE DULCES A BASE DE LECHE'),
('ELABORADOR DE DULCES A BASE DE GUAYABA'),
('ELABORADOR DE DULCES A BASE DE LECHOZA'),
('ELABORADOR DE DULCES A BASE DE MANGO'),
('ELABORADOR DE DULCES A BASE DE NARANJA'),
('ELABORADOR DE DULCES A BASE DE PARCHITA'),
('ELABORADOR DE DULCES A BASE DE YUCA'),
('ELABORADOR DE DULCES A BASE DE CACAO'),
('CHOCOLATERO'),
('APICULTOR'),
('FRESADOR'),
('CONFECCIONISTA DE ROPA INTIMA'),
('TÉCNICOS EN ADMINISTRACIÓN DE UNIDADES MILITARES BÁSICAS'),
('PINTOR DE CONSTRUCCIÓN'),
('MODELISTA DE CALZADO'),
('ASISTENTE DE NUTRICIÓN Y DIETÉTICA'),
('AYUDANTE DE MECÁNICA AUTOMOTRIZ'),
('ELECTRICISTA AUTOMOTRIZ'),
('ANALISTA DE INFORMACIÓN DIGITAL'),
('ELECTRICISTA EN REDES DE DISTRIBUCIÓN'),
('MECÁNICO DE TRANSMISIÓN AUTOMOTRIZ'),
('MECÁNICO DIRECCIÓN Y FRENOS DE AUTOMÓVILES'),
('REPARADOR Y MANTENIMIENTO DE MOTOCICLETA DE BAJA CILINDRADA'),
('INSTALADOR DE MATERIALES DE IMPERMEABILIZACIÓN EN EDIFICACIONES'),
('HERRERO'),
('ELECTRICISTA DE MANTENIMIENTO DEL SISTEMA DE ALUMBRADO PÚBLICO'),
('INSTALADOR DE LÍNEAS DE ENERGÍA ELÉCTRICA AÉREAS EN REDES DE DISTRIBución'),
('ASISTENTE DE GUIA DE TURISMO DE AVENTURA DE BAJA Y MEDIA MONTAÑA'),
('COMUNICADOR DIGITAL'),
('CORRECTOR DE CONTENIDO DIGITAL'),
('PRODUCTOR DE CONTENIDO DIGITAL'),
('CUÑERO DE SERVICIOS DE POZOS PETROLEROS'),
('OPERADOR DE MÁQUINAS HERRAMIENTAS'),
('TORNERO');

-- Eliminar la tabla formacion_apertura ya que todas las formaciones están aperturadas
DROP TABLE IF EXISTS formacion_apertura;

-- Actualizar la tabla inscripcion para que haga referencia a formacion
DROP TABLE IF EXISTS inscripcion;
CREATE TABLE IF NOT EXISTS inscripcion (
    id_inscripcion INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único de la inscripción',
    id_participante INT NOT NULL COMMENT 'Identificador del participante',
    id_formacion INT NOT NULL COMMENT 'Identificador de la formación',
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de inscripción',
    FOREIGN KEY (id_participante) REFERENCES participante(id_participante) ON DELETE CASCADE,
    FOREIGN KEY (id_formacion) REFERENCES formacion(id_formacion) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla de inscripciones';

-- Actualizar la tabla participante_formacion para que haga referencia a formacion
DROP TABLE IF EXISTS participante_formacion;
CREATE TABLE IF NOT EXISTS participante_formacion (
    id_participante_formacion INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único de la inscripción del participante en la formación',
    id_participante INT NOT NULL COMMENT 'Identificador del participante',
    id_formacion INT NOT NULL COMMENT 'Identificador de la formación',
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de inscripción',
    estado_inscripcion ENUM('Inscrito', 'Retirado', 'Finalizado') DEFAULT 'Inscrito' COMMENT 'Estado de la inscripción',
    calificacion DECIMAL(5,2) COMMENT 'Calificación del participante en la formación',
    comentarios TEXT COMMENT 'Comentarios sobre la inscripción',
    FOREIGN KEY (id_participante) REFERENCES participante(id_participante) ON DELETE CASCADE,
    FOREIGN KEY (id_formacion) REFERENCES formacion(id_formacion) ON DELETE CASCADE,
    INDEX idx_participante (id_participante),
    INDEX idx_formacion (id_formacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla de inscripciones de participantes en formaciones';

-- Actualizar la tabla instructor_formacion para que haga referencia a formacion
DROP TABLE IF EXISTS instructor_formacion;
CREATE TABLE IF NOT EXISTS instructor_formacion (
    id_instructor_formacion INT AUTO_INCREMENT PRIMARY KEY COMMENT 'Identificador único de la asignación del instructor a la formación',
    id_instructor INT NOT NULL COMMENT 'Identificador del instructor',
    id_formacion INT NOT NULL COMMENT 'Identificador de la formación',
    fecha_asignacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de asignación del instructor a la formación',
    rol ENUM('Principal', 'Asistente') DEFAULT 'Principal' COMMENT 'Rol del instructor en la formación',
    FOREIGN KEY (id_instructor) REFERENCES instructor(id_instructor) ON DELETE CASCADE,
    FOREIGN KEY (id_formacion) REFERENCES formacion(id_formacion) ON DELETE CASCADE,
    INDEX idx_instructor (id_instructor),
    INDEX idx_formacion (id_formacion)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tabla de asignaciones de instructores a formaciones';
