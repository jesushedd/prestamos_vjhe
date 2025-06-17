<?php

require_once ROOT_DIR . 'modelo/PrestamoModelo.php';
require_once ROOT_DIR . 'modelo/PrestamoModelo.php';

class Pago
{
    public int $id;
    public int $id_prestamo;
    public float $pago_principal;
    public float $pago_interes;
    public float $pago_total;
    public float $restante_pago_principal;
    public DateTime $fecha_pago;
    public string $estado;

    public function __construct(
        int $id_prestamo,
        float $pago_principal,
        float $pago_interes,
        float $pago_total,
        float $restante_pago_principal,
        string $estado = 'pendiente',
        ?string $fecha_pago = null
    ) {
        $this->id = -1;
        $this->id_prestamo = $id_prestamo;
        $this->pago_principal = $pago_principal;
        $this->pago_interes = $pago_interes;
        $this->pago_total = $pago_total;
        $this->restante_pago_principal = $restante_pago_principal;
        $this->estado = $estado;

        $this->fecha_pago = $fecha_pago
            ? DateTime::createFromFormat('Y-m-d', $fecha_pago)
            : new DateTime(); // Fecha actual
    }

    public function str_fecha(): string
    {
        return $this->fecha_pago->format("d-m-Y");
    }




}

class PagoRepositorio
{
    private $conexion;
    private string $tabla_pagos = 'pagos';

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtener_todos(): array
    {
        $sql = "SELECT * FROM {$this->tabla_pagos}";
        $stmt = $this->conexion->query($sql);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pagos = [];
        foreach ($resultados as $fila) {
            $pago = new Pago(
                (float) $fila['pago_principal'],
                (float) $fila['pago_interes'],
                (float) $fila['pago_total'],
                (float) $fila['restante_pago_principal'],
                $fila['fecha_pago']
            );
            $pago->id = (int) $fila['id'];
            $pagos[] = $pago;
        }
        return $pagos;
    }

    public function obtener_por_id(int $id): ?Pago
    {
        $sql = "SELECT * FROM {$this->tabla_pagos} WHERE id = :id";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['id' => $id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            $pago = new Pago(
                (float) $fila['pago_principal'],
                (float) $fila['pago_interes'],
                (float) $fila['pago_total'],
                (float) $fila['restante_pago_principal'],
                $fila['fecha_pago']
            );
            $pago->id = (int) $fila['id'];
            return $pago;
        }
        return null;
    }

    public function crear(Pago $pago): int
    {
        if ($pago->id !== -1) {
            die("Pago ya existente.");
        }

        $stmt = $this->conexion->prepare(
            "INSERT INTO {$this->tabla_pagos} 
    (id_prestamo, pago_principal, pago_interes, pago_total, restante_pago_principal, fecha_pago, estado)
    VALUES 
    (:id_prestamo, :pago_principal, :pago_interes, :pago_total, :restante_pago_principal, :fecha_pago, :estado)"
        );

        $stmt->execute([
            'id_prestamo' => $pago->id_prestamo,
            'pago_principal' => $pago->pago_principal,
            'pago_interes' => $pago->pago_interes,
            'pago_total' => $pago->pago_total,
            'restante_pago_principal' => $pago->restante_pago_principal,
            'fecha_pago' => $pago->fecha_pago->format('Y-m-d'),
            'estado' => $pago->estado
        ]);

        $pago->id = (int) $this->conexion->lastInsertId();
        return $pago->id;
    }

    public function eliminar(Pago $pago): void
    {
        if ($pago->id === -1) {
            die("Pago no existente.");
        }

        $stmt = $this->conexion->prepare("DELETE FROM {$this->tabla_pagos} WHERE id = :id");
        $stmt->execute(['id' => $pago->id]);
        $pago->id = -1;
    }


    public function obtener_por_prestamo(Prestamo $prestamo): array
    {
        if ($prestamo->id === -1) {
            die("Préstamo no válido.");
        }

        $sql = "SELECT * 
            FROM {$this->tabla_pagos} 
            JOIN prestamos  ON pagos.id_prestamo = prestamos.id
            WHERE prestamos.id = :id_prestamo
            ORDER BY fecha_pago ASC";

        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['id_prestamo' => $prestamo->id]);

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $pagos = [];
        foreach ($resultados as $fila) {
            $pago = new Pago(
                (float) $fila['pago_principal'],
                (float) $fila['pago_interes'],
                (float) $fila['pago_total'],
                (float) $fila['restante_pago_principal'],
                $fila['fecha_pago']
            );
            $pago->id = (int) $fila['id'];
            $pagos[] = $pago;
        }

        return $pagos;
    }

}
?>