<?php

class Plazo
{
    public int $numero_plazos;
    public float $tasa;

    public function __construct(int $numero_plazos, float $tasa)
    {
        $this->numero_plazos = $numero_plazos;
        $this->tasa = $tasa;
    }
}

class PlazoRepositorio
{
    private $conexion;
    private string $tabla = 'plazos';

    public function __construct($conexion)
    {
        $this->conexion = $conexion;
    }

    public function obtener_todos(): array
    {
        $sql = "SELECT * FROM {$this->tabla}";
        $stmt = $this->conexion->query($sql);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $plazos = [];
        foreach ($resultados as $fila) {
            $plazos[] = new Plazo((int)$fila['numero_plazos'], (float)$fila['tasa']);
        }

        return $plazos;
    }

    public function obtener_por_numero(int $numero_plazos): ?Plazo
    {
        $sql = "SELECT * FROM {$this->tabla} WHERE numero_plazos = :numero";
        $stmt = $this->conexion->prepare($sql);
        $stmt->execute(['numero' => $numero_plazos]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($fila) {
            return new Plazo((int)$fila['numero_plazos'], (float)$fila['tasa']);
        }

        return null;
    }

    public function crear(Plazo $plazo): bool
    {
        $sql = "INSERT INTO {$this->tabla} (numero_plazos, tasa) VALUES (:numero, :tasa)";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            'numero' => $plazo->numero_plazos,
            'tasa' => $plazo->tasa
        ]);
    }

    public function actualizar(Plazo $plazo): bool
    {
        $sql = "UPDATE {$this->tabla} SET tasa = :tasa WHERE numero_plazos = :numero";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute([
            'tasa' => $plazo->tasa,
            'numero' => $plazo->numero_plazos
        ]);
    }

    public function eliminar(int $numero_plazos): bool
    {
        $sql = "DELETE FROM {$this->tabla} WHERE numero_plazos = :numero";
        $stmt = $this->conexion->prepare($sql);
        return $stmt->execute(['numero' => $numero_plazos]);
    }
}

