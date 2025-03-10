<?php
class Cuenta {
    private $numCuenta;
    private $nombreCuenta;
    private $tipo;

    public function __construct($numCuenta, $nombreCuenta, $tipo) {
        $this->numCuenta = $numCuenta;
        $this->nombreCuenta = $nombreCuenta;
        $this->tipo = $tipo;
    }

    public function getNumCuenta() {
        return $this->numCuenta;
    }

    public function getNombreCuenta() {
        return $this->nombreCuenta;
    }

    public function getTipo() {
        return $this->tipo;
    }

    public function setNumCuenta($numCuenta) {
        $this->numCuenta = $numCuenta;
    }

    public function setNombreCuenta($nombreCuenta) {
        $this->nombreCuenta = $nombreCuenta;
    }

    public function setTipo($tipo) {
        if (in_array($tipo, ['A', 'P', 'C', 'I', 'G'])) {
            $this->tipo = $tipo;
        } else {
            throw new Exception("Tipo inválido");
        }
    }
}
?>