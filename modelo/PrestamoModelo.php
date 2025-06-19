<?php

use Phelix\LoanAmortization\ScheduleGenerator;

require_once ROOT_DIR . 'modelo/ClienteModelo.php';

require_once ROOT_DIR . 'modelo/PagoModelo.php';
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

    public function obtener_cronograma(): array
    {
        $interestCalculator = new ScheduleGenerator();
        $interestCalculator
            ->setPrincipal($this->monto)
            ->setInterestRate($this->interes_anual, "yearly", ScheduleGenerator::INTEREST_ON_REDUCING_BALANCE) // note the interest type
            ->setLoanDuration($this->plazos, "months")
            ->setRepayment(1, 1, "months")
            ->setAmortization(ScheduleGenerator::EVEN_PRINCIPAL_REPAYMENT) // note the amortization type
            ->generate();
        $schedule = $interestCalculator->amortization_schedule;
        $cronograma = [];
        foreach ($schedule as $letra) {
            $cronograma[] = [
                'pago_principal' => $letra['principal_repayment'],
                'pago_interes' => $letra['interest_repayment'],
                'pago_total' => $letra['total_amount_repayment'],
                'restante_pago_principal' => $letra['principal_repayment_balance'],
                'fecha_pago' => $letra['repayment_date']
            ];

        }

        return $cronograma;
    }

}



class PrestamoRepositorio
{
    private $conexion;
    private ClienteRepositorio $clienteRepositorio;
    private string $tabla_prestamos = 'prestamos';
    private string $tabla_pagos = 'pagos';

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

        try {
            $this->conexion->beginTransaction();
            //guardar en tabla prestamos

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
            //guardar el cronograma de pagos en tabla pagos

            $cronograma = $prestamo->obtener_cronograma();
            $pagos_repo = new PagoRepositorio($this->conexion);

            foreach ($cronograma as $letra) {
                $pago = new Pago(
                    $prestamo->id,
                    $letra['pago_principal'],
                    $letra['pago_interes'],
                    $letra['pago_total'],
                    $letra['restante_pago_principal'],
                    'pendiente',
                    $letra['fecha_pago']
                );

                $pagos_repo->crear($pago);
            }


            $this->conexion->commit();
            return $prestamo->id;

        } catch (PDOException $e) {
            $this->conexion->rollBack();
            $prestamo->id = -1;
            throw new Exception("Error al crear el préstamo: " . $e->getMessage());
        }
    }




}
