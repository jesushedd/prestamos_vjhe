

USE prestamos_vjhe;
-- Eliminar tablas existentes
DROP TABLE IF EXISTS reportes;
DROP TABLE IF EXISTS intereses;
DROP TABLE IF EXISTS pagos;
DROP TABLE IF EXISTS prestamos;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS tipos_usuarios;
DROP TABLE IF EXISTS clientes;
DROP TABLE IF EXISTS personas;
-- Crear tablas con esquema actualizado
source esquema.sql;

-- Crear tipos por defecto
INSERT INTO tipos_usuarios (id,nombre_tipo) VALUES (1, 'administrador'),
                                                (2, 'asistente');
--Crear Usuario root por defecto
INSERT INTO personas (id, nombre, apellido, dni) 
VALUES (1, 'root_nombre', 'root_apellido','root_dni');

INSERT INTO usuarios (id, id_tipo_usuario, nombre_usuario, password) VALUES
(1,1, 'root','$2y$10$SJWsMkntegr4Ax/G9lDroOl9nr93xQwp63wRyklgamOHCX0K9Q8xi');


--Crear Usuario asistente por defecto
INSERT INTO personas (id, nombre, apellido, dni) 
VALUES (2, 'asist_nombre', 'asist_apellido','asist_dni');

INSERT INTO usuarios (id, id_tipo_usuario, nombre_usuario, password) VALUES
(2,2, 'asist','$2y$10$TKmLddFkCv5Sh3QlrnOoE.CswYnBVwYNNy7mIZbz7S8W2cENMNGGu');

--Crear Plazos y tasas por defecto
INSERT INTO plazos(numero_plazos, tasa)
VALUES 
(6, 10),
(12, 15),
(18,20);
