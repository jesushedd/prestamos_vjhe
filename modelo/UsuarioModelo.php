

<?php
class TipoUsuario {
    public int $id;
    public string $nombre_tipo;

    public function  __construct(string $nombre){
        $this->nombre_tipo=$nombre;
        $this->id=-1;
    }
}



class Usuario {
    public int $id;
    public string $nombre;
    public string $apellido;
    public string $dni;

    public string $nombre_usuario;

    public string $password;

    public TipoUsuario $tipo_usuario;

    public function __construct(string $nombre, string $apellido, string $dni, TipoUsuario $tipo_usuario) {
        //datos base
        $this->nombre= $nombre;
        $this->apellido= $apellido;
        $this->dni=$dni;
        //acceso a dato
        $this->id = -1;  // id sin asignar
        $this->tipo_usuario = $tipo_usuario;
    }
}

class UsuarioRepositorio {
    private $conexion;
    private $tabla_usuario = 'usuarios';
    private $tabla_personas = 'personas';
    private $tabla_tipo_usuarios = 'tipos_usuarios';

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function obtener_tipo_por_id(int $id){
        $stmt = $this->conexion->prepare("SELECT * FROM $this->tabla_tipo_usuarios WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $tipo = new TipoUsuario($resultado['nombre_tipo']);
            $tipo->id = $resultado['id'];
            return $tipo;
        }
        die('No se encontro el id del tipo de usuario');//TODO
    }

    public function obtener_usuarios_por_tipo(TipoUsuario $tipo): array {
        $id_tipo = $tipo->id;
        if ( $tipo->id === -1) {
            die("Tipo no existente");
        } 
        /*      
        echo "SELECT * FROM $this->tabla_usuario
                                            JOIN $this->tabla_personas ON $this->tabla_usuario.id=$this->tabla_personas.id
                                            WHERE id_tipo_usuario = :id"; */
        $stmt = $this->conexion->prepare("SELECT * FROM $this->tabla_usuario
                                            JOIN $this->tabla_personas ON $this->tabla_usuario.id=$this->tabla_personas.id
                                            JOIN $this->tabla_tipo_usuarios ON $this->tabla_tipo_usuarios.id=$this->tabla_usuario.id_tipo_usuario
                                            WHERE id_tipo_usuario = :id");
        $stmt->execute(['id' => $id_tipo]);

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $usuarios = [];

        
        foreach ($resultados as $fila) {
            
            $usuario = new Usuario($fila['nombre'], $fila['apellido'], $fila['dni'], $tipo);
            $usuario->nombre_usuario = $fila['nombre_usuario'];
        
            $usuario->password = $fila['password'];

            $usuario->id = $fila["id"];
            $usuarios[] = $usuario;
        }

        return $usuarios;
    }

    public function obtener_usuario_por_id(int $id) : Usuario | null{
        $stmt = $this->conexion->prepare("SELECT * FROM $this->tabla_usuario
                                            JOIN $this->tabla_personas ON $this->tabla_usuario.id=$this->tabla_personas.id
                                            WHERE $this->tabla_personas.id = :id");
        $stmt->execute(['id' => $id]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $tipo_usuario = $this->obtener_tipo_por_id($resultado['id_tipo_usuario']);
            $usuario = new Usuario($resultado['nombre'], $resultado['apellido'], $resultado['dni'], $tipo_usuario);
            
            $usuario->nombre_usuario = $resultado['nombre_usuario'];
            $usuario->password = $resultado['password'];
            $usuario->id=$resultado['id'];

            return $usuario;
        }
        return null;
        
        
    }

    public function usuario_por_nombre(string $nombre) : Usuario | null {
        $stmt = $this->conexion->prepare("SELECT * FROM $this->tabla_usuario
                                            JOIN $this->tabla_personas ON $this->tabla_usuario.id=$this->tabla_personas.id
                                            WHERE nombre_usuario = :nombre_usuario");
        $stmt->execute(['nombre_usuario' => $nombre]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $id_usuario = $resultado['id'];
            
            $usuario = $this->obtener_usuario_por_id($id_usuario);

            return $usuario;
        }
        return null;
        
        
        
    }

    

    public function crear(Usuario $usuario): int {
        if ($usuario->id != -1) {
            die("Usuario ya existente.");
        }

        if (!isset($usuario->nombre_usuario)) {
            die("No haay nombre de usuario");//TODO
        }

        if (!isset($usuario->password)) {
            die("No haay  contraseña");//TODO
        }

        try {
            //transaccion
            $this->conexion->beginTransaction();
            //crear datos personales
            $stmt = $this->conexion->prepare("INSERT INTO $this->tabla_personas (nombre, apellido, dni) 
                        VALUES (:nombre, :apellido, :dni)");
            $result = $stmt->execute([
                'nombre' => $usuario->nombre,
                'apellido'=> $usuario->apellido,
                'dni'=> $usuario->dni
            ]);
            //crear usuario con el id obtenido al crear la persona
            $id_creado = $this->conexion->lastInsertId();

            $stmt = $this->conexion->prepare("INSERT INTO $this->tabla_usuario (id, id_tipo_usuario, nombre_usuario, password) 
                                                VALUES (:id, :id_tipo_usuario, :nombre_usuario, :password)");
            $result = $stmt->execute([
                'id' => $id_creado,
                'id_tipo_usuario'=> $usuario->tipo_usuario->id,
                'nombre_usuario' => $usuario->nombre_usuario,
                'password' => $usuario->password
            ]);

            $this->conexion->commit();
            //asignar el id al objeto usuario
            $usuario->id = $id_creado;

            return $usuario->id;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            die("Error al crear usuario: " . $e->getMessage());
        }
    }

    public function actualizar(Usuario $usuario): bool {
        if ($usuario->id === -1) {
            die("Usuario no existente .");
        }

        try {
            //transaccion
            $this->conexion->beginTransaction();
            //actualizar datos personales
            $stmt = $this->conexion->prepare(" UPDATE $this->tabla_personas  
                        SET nombre=:nombre, apellido=:apellido, dni=:dni
                        WHERE id=:id");

            $stmt->execute([
                'nombre' => $usuario->nombre,
                'apellido'=> $usuario->apellido,
                'dni'=> $usuario->dni
            ]);
             if ($stmt->rowCount() != 1 ){
                $this->conexion->rollBack();
                echo ("Error al match id del usuario a actualizar");
                return false;
             }
            //actualizar tipo de usuario
           

            $stmt = $this->conexion->prepare("UPDATE $this->tabla_usuario  
                                            SET id_tipo_usuario=:id_tipo
                                            WHERE id=:id_usuario");
            $stmt->execute([
                'id_usuario' => $usuario->id,
                'id_tipo'=> $usuario->tipo_usuario->id
            ]);
            if ($stmt->rowCount() != 1 ){
                $this->conexion->rollBack();
                echo ("Error al match id del usuario a actualizar");
                return false;
             }

            $this->conexion->commit();

            return true;
        } catch (PDOException $e) {
            $this->conexion->rollBack();
            die("Error al actualizar usuario: " . $e->getMessage());
        }
    }

    public function eliminar(Usuario $usuario): bool {
        if ($usuario->id === -1) {
            die("Usuario no existente.");
        }
        try {
            //transaccion
            $this->conexion->beginTransaction();
            //eliminar de usuarios
            $stmt = $this->conexion->prepare("DELETE FROM $this->tabla_usuario WHERE id = :id");
            $stmt->execute([
                'id' => $usuario->id
            ]);
            if ($stmt->rowCount() != 1) {
                $this->conexion->rollBack();
                echo("Error al matchear id al eliminar usuario");
                return false;
            }
            //eliminar de personas
            $stmt = $this->conexion->prepare("DELETE FROM $this->tabla_personas WHERE id = :id");
            $stmt->execute([
                'id' => $usuario->id
            ]);
            if ($stmt->rowCount() != 1) {
                $this->conexion->rollBack();
                echo("Error al matchear id al eliminar usuario");
                return false;
            }

            $usuario->id=-1;
            $this->conexion->commit();
            return true;


        } catch (PDOException $e) {
            $this->conexion->rollBack();
            die("error al eliminar usuario". $e->getMessage());
        }
        
        
    }
}
?>