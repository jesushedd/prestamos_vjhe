<?php
class Cliente
{
    public int $id;
    public string $nombre;
    public string $apellido;
    public string $dni;
    public string $email;
    public DateTime $fecha_creacion;
    public string $direccion;
    public string $telefono;

    public function __construct(
        string $nombre,
        string $apellido,
        string $dni,
        string $email,
        string $direccion,
        string $telefono,
        ?string $fecha_creacion = null
    ) {
        $this->id = -1;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->dni = $dni;
        $this->email = $email;
        $this->direccion = $direccion;
        $this->telefono = $telefono;

        if ($fecha_creacion !== null) {
            $this->fecha_creacion = DateTime::createFromFormat('Y-m-d', $fecha_creacion);
        } else {
            $this->fecha_creacion = new DateTime(); // Fecha actual
        }
    }

    public function str_fecha(): string {
        return $this->fecha_creacion->format("d-m-Y");
    }
}

class ClienteRepositorio
{
    private $conexion;

    private string $tabla_personas = 'personas';
    private string $tabla_clientes = 'clientes';

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtener_clientes(): array
    {
        $sql = "SELECT * 
                FROM {$this->tabla_personas} p
                JOIN {$this->tabla_clientes} c ON p.id = c.id";
        $stmt = $this->conexion->query($sql);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $clientes = [];
        foreach ($resultados as $fila) {
            $cliente = new Cliente(
                $fila['nombre'],
                $fila['apellido'],
                $fila['dni'],
                $fila['email'],
                $fila['direccion'],
                $fila['telefono'],
                $fila['fecha_creacion']
            );
            $cliente->id = $fila['id'];
            $clientes[] = $cliente;
        }
        return $clientes;
    }

    public function obtenerPorId(int $id): ?Cliente
    {
        $sql = "SELECT * 
                FROM {$this->tabla_personas} p
                JOIN {$this->tabla_clientes} c ON p.id = c.id
                WHERE p.id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['id' => $id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            $cliente = new Cliente(
                $fila['nombre'],
                $fila['apellido'],
                $fila['dni'],
                $fila['email'],
                $fila['direccion'],
                $fila['telefono'],
                $fila['fecha_creacion']
            );
            $cliente->id = $fila['id'];
            return $cliente;
        }
        return null;
    }

    public function crear(Cliente $cliente): int
    {
        if ($cliente->id != -1) {
            die("Cliente ya existente.");
        }

        // Insertar en personas
        $stmt1 = $this->conexion->prepare("INSERT INTO {$this->tabla_personas} (nombre, apellido, dni) VALUES (:nombre, :apellido, :dni)");
        $stmt1->execute([
            'nombre' => $cliente->nombre,
            'apellido' => $cliente->apellido,
            'dni' => $cliente->dni
        ]);

        $cliente->id = (int) $this->conexion->lastInsertId();

        // Insertar en clientes
        $stmt2 = $this->conexion->prepare("INSERT INTO {$this->tabla_clientes} (id, email, fecha_creacion, direccion, telefono) 
                                           VALUES (:id, :email, :fecha_creacion, :direccion, :telefono)");
        $stmt2->execute([
            'id' => $cliente->id,
            'email' => $cliente->email,
            'fecha_creacion' => $cliente->fecha_creacion->format('Y-m-d'),
            'direccion' => $cliente->direccion,
            'telefono' => $cliente->telefono
        ]);

        return $cliente->id;
    }

    public function actualizar(Cliente $cliente): bool
    {
        if ($cliente->id === -1) {
            die("Cliente no existente.");
        }

        // Actualizar en personas
        $stmt1 = $this->conexion->prepare("UPDATE {$this->tabla_personas} 
                                           SET nombre = :nombre, apellido = :apellido, dni = :dni 
                                           WHERE id = :id");
        $ok1 = $stmt1->execute([
            'nombre' => $cliente->nombre,
            'apellido' => $cliente->apellido,
            'dni' => $cliente->dni,
            'id' => $cliente->id
        ]);

        // Actualizar en clientes
        $stmt2 = $this->conexion->prepare("UPDATE {$this->tabla_clientes} 
                                           SET email = :email, fecha_creacion = :fecha_creacion, direccion = :direccion, telefono = :telefono 
                                           WHERE id = :id");
        $ok2 = $stmt2->execute([
            'email' => $cliente->email,
            'fecha_creacion' => $cliente->fecha_creacion->format('Y-m-d'),
            'direccion' => $cliente->direccion,
            'telefono' => $cliente->telefono,
            'id' => $cliente->id
        ]);

        return $ok1 && $ok2;
    }

    public function eliminar(Cliente $cliente): void
    {
        if ($cliente->id === -1) {
            die("Cliente no existente.");
        }

        // Eliminar de clientes
        $stmt1 = $this->conexion->prepare("DELETE FROM {$this->tabla_clientes} WHERE id = :id");
        $stmt1->execute(['id' => $cliente->id]);

        // Eliminar de personas
        $stmt2 = $this->conexion->prepare("DELETE FROM {$this->tabla_personas} WHERE id = :id");
        $stmt2->execute(['id' => $cliente->id]);

        $cliente->id = -1;
    }
}
?>
