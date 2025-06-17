<?php

require_once ROOT_DIR . 'modelo/ClienteModelo.php';
require_once ROOT_DIR . 'lib/loan/ScheduleGenerator.php';
class Prestamo
{
    public int $id;
    public int $id_cliente;
    public float $monto;
    public DateTime $fecha_inicio;
    public int $plazos;
    public string $estado;
    public float $interes_anual;

    public function __construct(
        Cliente $cliente,
        float $monto,
        int $plazos,
        float $interes_anual,
        string $estado = 'vigente',
        ?string $fecha_inicio = null
    ) {
        $this->id = -1;
        $this->monto = $monto;
        $this->id_cliente = $cliente->id;

        if ($fecha_inicio !== null) {
            $this->fecha_inicio = DateTime::createFromFormat('Y-m-d', $fecha_inicio);
        } else {
            $this->fecha_inicio = new DateTime(); // fecha actual
        }
        $this->interes_anual = $interes_anual;
        $this->plazos = $plazos;
        $this->estado = $estado;
    }

    public function str_fecha_inicio(): string
    {
        return $this->fecha_inicio->format("d-m-Y");
    }
}



class PrestamoRepositorio
{
    private $conexion;
    private ClienteRepositorio $clienteRepositorio;
    private string $tabla_prestamos = 'prestamos';

    public function __construct($conexion, ClienteRepositorio $clienteRepositorio)
    {
        $this->conexion = $conexion;
        $this->clienteRepositorio = $clienteRepositorio;
    }

    public function obtener_todos(): array
    {
        $sql = "SELECT * FROM {$this->tabla_prestamos}";
        $stmt = $this->conexion->query($sql);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $prestamos = [];
        foreach ($resultados as $fila) {
            $cliente = $this->clienteRepositorio->obtenerPorId((int) $fila['id_cliente']);
            if (!$cliente) {
                continue; // o lanzar una excepción si el cliente no se encuentra
            }

            $prestamo = new Prestamo(
                $cliente,
                (float) $fila['monto'],
                (int) $fila['plazos'],
                (float) $fila['interes_anual'],
                $fila['estado'],
                $fila['fecha_inicio']
            );
            $prestamo->id = (int) $fila['id'];
            $prestamos[] = $prestamo;
        }
        return $prestamos;
    }

    public function obtener_por_id(int $id): ?Prestamo
    {
        $sql = "SELECT * FROM {$this->tabla_prestamos} WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['id' => $id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            $cliente = $this->clienteRepositorio->obtenerPorId((int) $fila['id_cliente']);
            if (!$cliente) {
                return null;
            }

            $prestamo = new Prestamo(
                $cliente,
                (float) $fila['monto'],
                (int) $fila['plazos'],
                (float) $fila['interes_anual'],
                $fila['estado'],
                $fila['fecha_inicio']
            );
            $prestamo->id = (int) $fila['id'];
            return $prestamo;
        }

        return null;
    }

    public function obtener_por_cliente(Cliente $cliente): array
    {
        $sql = "SELECT * FROM {$this->tabla_prestamos} WHERE id_cliente = :id_cliente";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['id_cliente' => $cliente->id]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $prestamos = [];
        foreach ($resultados as $fila) {
            $prestamo = new Prestamo(
                $cliente,
                (float) $fila['monto'],
                (int) $fila['plazos'],
                (float) $fila['interes_anual'],
                $fila['estado'],
                $fila['fecha_inicio']
            );
            $prestamo->id = (int) $fila['id'];
            $prestamos[] = $prestamo;
        }
        return $prestamos;
    }

    public function crear(Prestamo $prestamo): int
    {
        if ($prestamo->id !== -1) {
            die("El préstamo ya tiene un ID asignado.");
        }

        $stmt = $this->conexion->prepare("
        INSERT INTO {$this->tabla_prestamos} 
        (id_cliente, monto, fecha_inicio, plazos, interes_anual, estado)
        VALUES 
        (:id_cliente, :monto, :fecha_inicio, :plazos, :interes_anual, :estado)
    ");

        $stmt->execute([
            'id_cliente' => $prestamo->id_cliente,
            'monto' => $prestamo->monto,
            'fecha_inicio' => $prestamo->fecha_inicio->format('Y-m-d'),
            'plazos' => $prestamo->plazos,
            'interes_anual' => $prestamo->interes_anual,
            'estado' => $prestamo->estado
        ]);

        $prestamo->id = (int) $this->conexion->lastInsertId();
        return $prestamo->id;
    }

    public function get_cronograma()
    {

    }

}
