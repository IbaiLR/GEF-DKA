
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS Seguimiento;
DROP TABLE IF EXISTS Horario;
DROP TABLE IF EXISTS Estancia_alumno;

DROP TABLE IF EXISTS Nota_Transversal;
DROP TABLE IF EXISTS Transversales;

DROP TABLE IF EXISTS Nota_Egibide;

DROP TABLE IF EXISTS Nota_Competencia;
DROP TABLE IF EXISTS Comp_Ra;
DROP TABLE IF EXISTS Ra;
DROP TABLE IF EXISTS Competencia;

DROP TABLE IF EXISTS Notas_Cuaderno;
DROP TABLE IF EXISTS Alumno_Entrega;
DROP TABLE IF EXISTS Entrega_Cuaderno;

DROP TABLE IF EXISTS Asignatura;

DROP TABLE IF EXISTS Tutor_Grado;
DROP TABLE IF EXISTS Tutor_Instructor;

DROP TABLE IF EXISTS Alumno;
DROP TABLE IF EXISTS Grado;
DROP TABLE IF EXISTS Instructor;
DROP TABLE IF EXISTS Tutor;

DROP TABLE IF EXISTS Empresa;
DROP TABLE IF EXISTS Usuarios;

SET FOREIGN_KEY_CHECKS = 1;

-- =========================
-- TABLAS BASE
-- =========================

CREATE TABLE Usuarios (
  ID            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  Nombre        VARCHAR(100) NOT NULL,
  Apellidos     VARCHAR(150) NOT NULL,
  Num_tel      VARCHAR(25)  NULL,
  Email         VARCHAR(255) NOT NULL,
  Contrasenna      VARCHAR(255) NOT NULL,
  Tipo          ENUM('Admin','Tutor','Instructor','Alumno') NOT NULL,
  PRIMARY KEY (ID),
  UNIQUE KEY uq_usuarios_email (Email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Empresa (
  CIF        VARCHAR(20)  NOT NULL,
  Nombre     VARCHAR(150) NOT NULL,
  Dirección  VARCHAR(255) NULL,
  Email      VARCHAR(255) NULL,
  Num_tel   VARCHAR(25)  NULL,
  PRIMARY KEY (CIF)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- ROLES (1-1 con Usuarios)
-- =========================

CREATE TABLE Tutor (
  ID_Usuario INT UNSIGNED NOT NULL,
  PRIMARY KEY (ID_Usuario),
  CONSTRAINT fk_tutor_id_usuario
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Instructor (
  ID_Usuario   INT UNSIGNED NOT NULL,
  CIF_Empresa  VARCHAR(20)  NOT NULL,
  PRIMARY KEY (ID_Usuario),
  KEY ix_instructor_cif_empresa (CIF_Empresa),
  CONSTRAINT fk_instructor_id_usuario
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_instructor_cif_empresa
    FOREIGN KEY (CIF_Empresa) REFERENCES Empresa(CIF)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- ACADÉMICO
-- =========================

CREATE TABLE Grado (
  ID        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  Nombre    VARCHAR(150) NOT NULL,
  Curso     VARCHAR(50)  NULL,
  ID_Tutor  INT UNSIGNED NULL,
  PRIMARY KEY (ID),
  KEY ix_grado_id_tutor (ID_Tutor),
  CONSTRAINT fk_grado_id_tutor
    FOREIGN KEY (ID_Tutor) REFERENCES Tutor(ID_Usuario)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Alumno (
  ID_Usuario     INT UNSIGNED NOT NULL,  -- PK-FK (según imagen)
  ID_Grado       INT UNSIGNED NULL,
  ID_Tutor       INT UNSIGNED NULL,
  ID_Instructor  INT UNSIGNED NULL,
  PRIMARY KEY (ID_Usuario),
  KEY ix_alumno_id_grado (ID_Grado),
  KEY ix_alumno_id_tutor (ID_Tutor),
  KEY ix_alumno_id_instructor (ID_Instructor),
  CONSTRAINT fk_alumno_id_usuario
    FOREIGN KEY (ID_Usuario) REFERENCES Usuarios(ID)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_alumno_id_grado
    FOREIGN KEY (ID_Grado) REFERENCES Grado(ID)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_alumno_id_tutor
    FOREIGN KEY (ID_Tutor) REFERENCES Tutor(ID_Usuario)
    ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT fk_alumno_id_instructor
    FOREIGN KEY (ID_Instructor) REFERENCES Instructor(ID_Usuario)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Tutor_Instructor (
  ID            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ID_Tutor      INT UNSIGNED NOT NULL,
  ID_Instructor INT UNSIGNED NOT NULL,
  PRIMARY KEY (ID),
  KEY ix_tutor_instructor_id_tutor (ID_Tutor),
  KEY ix_tutor_instructor_id_instructor (ID_Instructor),
  CONSTRAINT fk_tutor_instructor_id_tutor
    FOREIGN KEY (ID_Tutor) REFERENCES Tutor(ID_Usuario)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_tutor_instructor_id_instructor
    FOREIGN KEY (ID_Instructor) REFERENCES Instructor(ID_Usuario)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Tutor_Grado (
  ID        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ID_Tutor  INT UNSIGNED NOT NULL,
  ID_Grado  INT UNSIGNED NOT NULL,
  PRIMARY KEY (ID),
  KEY ix_tutor_grado_id_tutor (ID_Tutor),
  KEY ix_tutor_grado_id_grado (ID_Grado),
  CONSTRAINT fk_tutor_grado_id_tutor
    FOREIGN KEY (ID_Tutor) REFERENCES Tutor(ID_Usuario)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_tutor_grado_id_grado
    FOREIGN KEY (ID_Grado) REFERENCES Grado(ID)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Asignatura (
  ID       INT UNSIGNED NOT NULL AUTO_INCREMENT,
  Nombre   VARCHAR(150) NOT NULL,
  ID_Grado INT UNSIGNED NOT NULL,
  PRIMARY KEY (ID),
  KEY ix_asignatura_id_grado (ID_Grado),
  CONSTRAINT fk_asignatura_id_grado
    FOREIGN KEY (ID_Grado) REFERENCES Grado(ID)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- ENTREGAS / CUADERNO
-- =========================

CREATE TABLE Entrega_Cuaderno (
  ID             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  Fecha_creacion DATE         NOT NULL,
  Fecha_Limite   DATE         NOT NULL,
  ID_Grado       INT UNSIGNED NOT NULL,
  PRIMARY KEY (ID),
  KEY ix_entrega_cuaderno_id_grado (ID_Grado),
  CONSTRAINT fk_entrega_cuaderno_id_grado
    FOREIGN KEY (ID_Grado) REFERENCES Grado(ID)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Alumno_Entrega (
  ID            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  URL_Cuaderno  VARCHAR(2048) NULL,
  Fecha_Entrega DATE          NULL,
  ID_Alumno     INT UNSIGNED  NOT NULL,
  ID_Entrega    INT UNSIGNED  NOT NULL,
  PRIMARY KEY (ID),
  KEY ix_alumno_entrega_id_alumno (ID_Alumno),
  KEY ix_alumno_entrega_id_entrega (ID_Entrega),
  CONSTRAINT fk_alumno_entrega_id_alumno
    FOREIGN KEY (ID_Alumno) REFERENCES Alumno(ID_Usuario)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_alumno_entrega_id_entrega
    FOREIGN KEY (ID_Entrega) REFERENCES Entrega_Cuaderno(ID)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Notas_Cuaderno (
  ID          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ID_Cuaderno INT UNSIGNED NOT NULL,
  ID_Tutor    INT UNSIGNED NOT NULL,
  Fecha       DATE         NOT NULL,
  Nota        DECIMAL(5,2) NULL,
  PRIMARY KEY (ID),
  KEY ix_notas_cuaderno_id_cuaderno (ID_Cuaderno),
  KEY ix_notas_cuaderno_id_tutor (ID_Tutor),
  CONSTRAINT fk_notas_cuaderno_id_cuaderno
    FOREIGN KEY (ID_Cuaderno) REFERENCES Entrega_Cuaderno(ID)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_notas_cuaderno_id_tutor
    FOREIGN KEY (ID_Tutor) REFERENCES Tutor(ID_Usuario)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- COMPETENCIAS / RA
-- =========================

CREATE TABLE Competencia (
  ID          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  Descripcion VARCHAR(255) NOT NULL,
  PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Ra (
  ID          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  Descripcion VARCHAR(255) NOT NULL,
  PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Comp_Ra (
  ID      INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ID_Comp INT UNSIGNED NOT NULL,
  ID_Ra   INT UNSIGNED NOT NULL,
  PRIMARY KEY (ID),
  KEY ix_comp_ra_id_comp (ID_Comp),
  KEY ix_comp_ra_id_ra (ID_Ra),
  CONSTRAINT fk_comp_ra_id_comp
    FOREIGN KEY (ID_Comp) REFERENCES Competencia(ID)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_comp_ra_id_ra
    FOREIGN KEY (ID_Ra) REFERENCES Ra(ID)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Nota_Competencia (
  ID             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ID_Competencia INT UNSIGNED NOT NULL,
  ID_Alumno      INT UNSIGNED NOT NULL,
  Nota           DECIMAL(5,2) NULL,
  PRIMARY KEY (ID),
  KEY ix_nota_competencia_id_competencia (ID_Competencia),
  KEY ix_nota_competencia_id_alumno (ID_Alumno),
  CONSTRAINT fk_nota_competencia_id_competencia
    FOREIGN KEY (ID_Competencia) REFERENCES Competencia(ID)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_nota_competencia_id_alumno
    FOREIGN KEY (ID_Alumno) REFERENCES Alumno(ID_Usuario)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- NOTAS EGIBIDE
-- =========================

CREATE TABLE Nota_Egibide (
  ID            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ID_Asignatura INT UNSIGNED NOT NULL,
  ID_Alumno     INT UNSIGNED NOT NULL,   -- (tal cual aparece en la imagen)
  PRIMARY KEY (ID),
  KEY ix_nota_egibide_id_asignatura (ID_Asignatura),
  KEY ix_nota_egibide_id_alumno (ID_Alumno),
  CONSTRAINT fk_nota_egibide_id_asignatura
    FOREIGN KEY (ID_Asignatura) REFERENCES Asignatura(ID)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_nota_egibide_id_alumno
    FOREIGN KEY (ID_Alumno) REFERENCES Alumno(ID_Usuario)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- TRANSVERSALES
-- =========================

CREATE TABLE Transversales (
  ID          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  Descripcion VARCHAR(255) NOT NULL,
  PRIMARY KEY (ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Nota_Transversal (
  ID             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ID_Transversal INT UNSIGNED NOT NULL,
  ID_Alumno      INT UNSIGNED NOT NULL,  -- en la imagen sale "Field", pero la columna existe
  Nota           DECIMAL(5,2) NULL,
  PRIMARY KEY (ID),
  KEY ix_nota_transversal_id_transversal (ID_Transversal),
  KEY ix_nota_transversal_id_alumno (ID_Alumno),
  CONSTRAINT fk_nota_transversal_id_transversal
    FOREIGN KEY (ID_Transversal) REFERENCES Transversales(ID)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_nota_transversal_id_alumno
    FOREIGN KEY (ID_Alumno) REFERENCES Alumno(ID_Usuario)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================
-- ESTANCIAS / HORARIO / SEGUIMIENTO
-- =========================

CREATE TABLE Estancia_alumno (
  ID           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ID_Alumno    INT UNSIGNED NOT NULL,
  CIF_Empresa  VARCHAR(20)  NOT NULL,
  Fecha_inicio DATE         NOT NULL,
  Fecha_fin    DATE         NULL,
  PRIMARY KEY (ID),
  KEY ix_estancia_alumno_id_alumno (ID_Alumno),
  KEY ix_estancia_alumno_cif_empresa (CIF_Empresa),
  CONSTRAINT fk_estancia_alumno_id_alumno
    FOREIGN KEY (ID_Alumno) REFERENCES Alumno(ID_Usuario)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_estancia_alumno_cif_empresa
    FOREIGN KEY (CIF_Empresa) REFERENCES Empresa(CIF)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Horario (
  ID          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ID_Estancia INT UNSIGNED NOT NULL,
  Dias        VARCHAR(50)  NOT NULL,
  Horario1    VARCHAR(50)  NULL,
  Horario2    VARCHAR(50)  NULL,
  PRIMARY KEY (ID),
  KEY ix_horario_id_estancia (ID_Estancia),
  CONSTRAINT fk_horario_id_estancia
    FOREIGN KEY (ID_Estancia) REFERENCES Estancia_alumno(ID)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE Seguimiento (
  ID                   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ID_Estancia          INT UNSIGNED NOT NULL,
  Fecha                DATE         NOT NULL,
  Hora                 TIME         NULL,
  Accion_seguimiento   TEXT         NULL,
  Seguimiento_actividad TEXT        NULL,
  PRIMARY KEY (ID),
  KEY ix_seguimiento_id_estancia (ID_Estancia),
  CONSTRAINT fk_seguimiento_id_estancia
    FOREIGN KEY (ID_Estancia) REFERENCES Estancia_alumno(ID)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



