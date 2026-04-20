-- Tabla Encargado
CREATE TABLE encargado (
    id          SERIAL       PRIMARY KEY,
    run         VARCHAR(10)  NOT NULL UNIQUE,
    nombre      VARCHAR(100) NOT NULL,
    apellido1   VARCHAR(100) NOT NULL,
    apellido2   VARCHAR(100),
    direccion   VARCHAR(200),
    telefono    VARCHAR(20)
);

-- Tabla Bodega
CREATE TABLE bodega (
    id          SERIAL       PRIMARY KEY,
    codigo      VARCHAR(5)   NOT NULL UNIQUE,
    nombre      VARCHAR(100) NOT NULL,
    direccion   VARCHAR(200),
    dotacion    INT          NOT NULL DEFAULT 0,
    estado      BOOLEAN  NOT NULL DEFAULT TRUE,
    fecha_crea  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Tabla intermedia
CREATE TABLE bodega_encargado (
    id_bodega       INT NOT NULL REFERENCES bodega(id),
    id_encargado    INT NOT NULL REFERENCES encargado(id),
    PRIMARY KEY (id_bodega, id_encargado)
);
