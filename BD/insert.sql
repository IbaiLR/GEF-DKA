-- =========================
-- INSERTS DE PRUEBA
-- =========================

-- Datos de ejemplo (MySQL 8 / InnoDB)
-- Inserciones en orden para respetar FKs.
-- He puesto IDs explícitos para que sea reproducible al programar.

START TRANSACTION;

-- =========================
-- USUARIOS
-- =========================
INSERT INTO Usuarios (ID, Nombre, Apellidos, `Nº Tel`, Email, Contrasenna, Tipo) VALUES
(1,  'Ainhoa',   'Admin Etxeberria',     '600111222', 'admin@centro.local',    '$2y$fakehash_admin',     'Admin'),
(10, 'Maite',    'García Lasa',          '600222333', 'maite.tutor@centro.local','$2y$fakehash_tutor1',   'Tutor'),
(11, 'Iñigo',    'Sáez Arriola',         '600333444', 'inigo.tutor@centro.local','$2y$fakehash_tutor2',   'Tutor'),
(20, 'Nerea',    'López Mendieta',       '610111222', 'nerea.instructor@empresa.local','$2y$fakehash_inst1','Instructor'),
(21, 'Jon',      'Martín Urrutia',       '610222333', 'jon.instructor@empresa.local',  '$2y$fakehash_inst2','Instructor'),
(100,'Ane',      'Zubizarreta Ochoa',    '620111222', 'ane.alumno@centro.local', '$2y$fakehash_a1', 'Alumno'),
(101,'Unai',     'Alonso Ibarrola',      '620222333', 'unai.alumno@centro.local','$2y$fakehash_a2', 'Alumno'),
(102,'Irati',    'Serrano Garmendia',    '620333444', 'irati.alumno@centro.local','$2y$fakehash_a3', 'Alumno'),
(103,'Mikel',    'Ruiz Echevarría',      '620444555', 'mikel.alumno@centro.local','$2y$fakehash_a4', 'Alumno'),
(104,'Leire',    'Navarro Etxaniz',      '620555666', 'leire.alumno@centro.local','$2y$fakehash_a5', 'Alumno');

-- =========================
-- EMPRESAS
-- =========================
INSERT INTO Empresa (CIF, Nombre, Dirección, Email, `Nº Tel`) VALUES
('B12345678', 'TechBizi SL',     'C/ Gran Vía 12, Bilbao', 'rrhh@techbizi.local', '944000111'),
('A87654321', 'IndusGoi SA',     'Pol. Ugaldeguren I, Nave 7', 'contacto@indusgoi.local', '944000222');

-- =========================
-- ROLES
-- =========================
INSERT INTO Tutor (ID_Usuario) VALUES
(10), (11);

INSERT INTO Instructor (ID_Usuario, CIF_Empresa) VALUES
(20, 'B12345678'),
(21, 'A87654321');

-- =========================
-- GRADOS
-- =========================
INSERT INTO Grado (ID, Nombre, Curso, ID_Tutor) VALUES
(1, 'Desarrollo de Aplicaciones Web (DAW)',  '2º', 10),
(2, 'Administración de Sistemas (ASIR)',     '2º', 11);

-- =========================
-- ALUMNOS
-- =========================
INSERT INTO Alumno (ID_Usuario, ID_Grado, ID_Tutor, ID_Instructor) VALUES
(100, 1, 10, 20),
(101, 1, 10, 20),
(102, 1, 10, 21),
(103, 2, 11, 21),
(104, 2, 11, 20);

-- =========================
-- RELACIONES TUTOR <-> INSTRUCTOR / TUTOR <-> GRADO
-- =========================
INSERT INTO Tutor_Instructor (ID, ID_Tutor, ID_Instructor) VALUES
(1, 10, 20),
(2, 10, 21),
(3, 11, 21),
(4, 11, 20);

INSERT INTO Tutor_Grado (ID, ID_Tutor, ID_Grado) VALUES
(1, 10, 1),
(2, 11, 2);

-- =========================
-- ASIGNATURAS
-- =========================
INSERT INTO Asignatura (ID, Nombre, ID_Grado) VALUES
(1, 'Despliegue de Aplicaciones Web', 1),
(2, 'Desarrollo Web en Entorno Servidor', 1),
(3, 'Administración de Sistemas Operativos', 2),
(4, 'Seguridad y Alta Disponibilidad', 2);

-- =========================
-- ENTREGAS CUADERNO / ALUMNO_ENTREGA / NOTAS_CUADERNO
-- =========================
INSERT INTO Entrega_Cuaderno (ID, Fecha_creacion, Fecha_Limite, ID_Grado) VALUES
(1, '2026-01-02', '2026-01-09', 1),
(2, '2026-01-02', '2026-01-09', 2),
(3, '2026-01-06', '2026-01-13', 1);

INSERT INTO Alumno_Entrega (ID, URL_Cuaderno, Fecha_Entrega, ID_Alumno, ID_Entrega) VALUES
(1, 'https://drive.local/cuadernos/ane-week1',   '2026-01-08', 100, 1),
(2, 'https://drive.local/cuadernos/unai-week1',  '2026-01-09', 101, 1),
(3, 'https://drive.local/cuadernos/irati-week1', '2026-01-09', 102, 1),
(4, 'https://drive.local/cuadernos/mikel-week1', '2026-01-08', 103, 2),
(5, 'https://drive.local/cuadernos/leire-week1', '2026-01-09', 104, 2),
(6, 'https://drive.local/cuadernos/ane-week2',   '2026-01-13', 100, 3);

INSERT INTO Notas_Cuaderno (ID, ID_Cuaderno, ID_Tutor, Fecha, Nota) VALUES
(1, 1, 10, '2026-01-10', 8.50),
(2, 1, 10, '2026-01-10', 7.25),
(3, 3, 10, '2026-01-10', 9.00),
(4, 2, 11, '2026-01-10', 7.75);

-- =========================
-- COMPETENCIAS / RA / COMP_RA / NOTA_COMPETENCIA
-- =========================
INSERT INTO Competencia (ID, Descripcion) VALUES
(1, 'Trabajo en equipo'),
(2, 'Comunicación efectiva'),
(3, 'Resolución de problemas');

INSERT INTO Ra (ID, Descripcion) VALUES
(1, 'Documenta correctamente el trabajo realizado'),
(2, 'Aplica buenas prácticas de desarrollo'),
(3, 'Gestiona incidencias de forma autónoma');

INSERT INTO Comp_Ra (ID, ID_Comp, ID_Ra) VALUES
(1, 1, 2),
(2, 2, 1),
(3, 3, 3),
(4, 3, 2);

INSERT INTO Nota_Competencia (ID, ID_Competencia, ID_Alumno, Nota) VALUES
(1, 1, 100, 8.00),
(2, 2, 100, 7.50),
(3, 3, 100, 8.75),
(4, 1, 101, 7.00),
(5, 3, 102, 7.50),
(6, 2, 103, 6.75),
(7, 1, 104, 8.25);

-- =========================
-- TRANSVERSALES / NOTA_TRANSVERSAL
-- =========================
INSERT INTO Transversales (ID, Descripcion) VALUES
(1, 'Puntualidad'),
(2, 'Actitud'),
(3, 'Organización');

INSERT INTO Nota_Transversal (ID, ID_Transversal, ID_Alumno, Nota) VALUES
(1, 1, 100, 9.00),
(2, 2, 100, 8.50),
(3, 3, 100, 8.00),
(4, 1, 101, 7.50),
(5, 2, 102, 7.00),
(6, 3, 103, 7.25),
(7, 2, 104, 8.75);

-- =========================
-- NOTA_EGIBIDE (sin columna "Nota" en el diagrama)
-- =========================
INSERT INTO Nota_Egibide (ID, ID_Asignatura, ID_Alumno) VALUES
(1, 1, 100),
(2, 2, 100),
(3, 1, 101),
(4, 2, 102),
(5, 3, 103),
(6, 4, 104);

-- =========================
-- ESTANCIA / HORARIO / SEGUIMIENTO
-- =========================
INSERT INTO Estancia_alumno (ID, ID_Alumno, CIF_Empresa, Fecha_inicio, Fecha_fin) VALUES
(1, 100, 'B12345678', '2025-11-04', NULL),
(2, 101, 'B12345678', '2025-11-04', NULL),
(3, 102, 'A87654321', '2025-11-04', NULL),
(4, 103, 'A87654321', '2025-11-04', NULL),
(5, 104, 'B12345678', '2025-11-04', NULL);

INSERT INTO Horario (ID, ID_Estancia, Dias, Horario1, Horario2) VALUES
(1, 1, 'L-M-X-J-V', '08:00-12:00', '13:00-15:00'),
(2, 2, 'L-M-X-J-V', '09:00-13:00', '14:00-16:00'),
(3, 3, 'L-M-X-J',   '08:30-12:30', '13:30-15:30'),
(4, 4, 'L-M-X-J-V', '07:00-11:00', '12:00-14:00'),
(5, 5, 'L-M-X-J-V', '08:00-12:00', '13:00-15:00');

INSERT INTO Seguimiento (ID, ID_Estancia, Fecha, Hora, Accion_seguimiento, Seguimiento_actividad) VALUES
(1, 1, '2026-01-07', '10:15:00', 'Revisión de tareas de la semana', 'Implementación de endpoints REST y validaciones'),
(2, 1, '2026-01-09', '12:05:00', 'Feedback sobre documentación', 'Mejora de README y guía de despliegue'),
(3, 2, '2026-01-08', '11:00:00', 'Control de progreso', 'Migraciones y seed de datos'),
(4, 3, '2026-01-08', '09:30:00', 'Incidencia con acceso a repo', 'Se corrige permisos y se configura SSH'),
(5, 4, '2026-01-09', '08:45:00', 'Prueba de backup', 'Verificación de snapshots y restauración parcial'),
(6, 5, '2026-01-10', '13:10:00', 'Planificación semana siguiente', 'Refactor de servicios y tests unitarios');

COMMIT;

