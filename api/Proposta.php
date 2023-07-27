<?php
class Proposta {
    public $registro_plano;
    public $quantidade_beneficiarios;
    public $beneficiarios;
    public $precoTotal;

    public function __construct() {
        $this->beneficiarios = array();
    }

    public function setRegistroPlano($registro_plano) {
        $this->registro_plano = $registro_plano;
    }

    public function setQuantidadeBeneficiarios($quantidade_beneficiarios) {
        $this->quantidade_beneficiarios = $quantidade_beneficiarios;
    }

    public function setBeneficiarios($beneficiarios) {
        $this->beneficiarios = $beneficiarios;
    }

    public function setPrecoTotal($precoTotal) {
        $this->precoTotal = $precoTotal;
    }

    public function toArray() {
        return array(
            "registro_plano" => $this->registro_plano,
            "quantidade_beneficiarios" => $this->quantidade_beneficiarios,
            "beneficiarios" => $this->beneficiarios,
            "precoTotal" => $this->precoTotal
        );
    }

    public function toJson() {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }
}
?>
