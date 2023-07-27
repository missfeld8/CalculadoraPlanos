<?php
require 'Proposta.php';

class PlanosAPI {
    private $plans;
    private $prices;

    public function __construct() {
        $plansFile = file_get_contents('api/plans.json');
        $this->plans = json_decode($plansFile, true);

        $pricesFile = file_get_contents('api/price.json');
        $this->prices = json_decode($pricesFile, true);
    }

    public function calcularPreco($dadosProposta) {
        $registroPlano = $dadosProposta['registro_plano'];
        $quantidadeBeneficiarios = $dadosProposta['quantidade_beneficiarios'];
        $beneficiarios = $dadosProposta['beneficiarios'];

        $planoEncontrado = null;
        foreach ($this->plans as $plano) {
            if ($plano['registro'] === $registroPlano) {
                $planoEncontrado = $plano;
                break;
            }
        }

        if (!$planoEncontrado) {
            return json_encode(["error" => "Registro do plano inválido."]);
        }

        $precoTotal = 0;
        foreach ($beneficiarios as $beneficiario) {
            $precoBeneficiario = 0;
            if ($beneficiario['idade'] >= 0 && $beneficiario['idade'] <= 17) {
                $precoBeneficiario = $planoEncontrado['faixa1'];
            } elseif ($beneficiario['idade'] >= 18 && $beneficiario['idade'] <= 40) {
                $precoBeneficiario = $planoEncontrado['faixa2'];
            } elseif ($beneficiario['idade'] > 40) {
                $precoBeneficiario = $planoEncontrado['faixa3'];
            }

            foreach ($this->prices as $price) {
                if ($price['codigo'] === $planoEncontrado['codigo'] && $price['minimo_vidas'] <= $quantidadeBeneficiarios) {
                    $precoBeneficiario = $price['faixa' . $precoBeneficiario];
                    break;
                }
            }

            $precoTotal += $precoBeneficiario;
        }

        return json_encode(["precoTotal" => $precoTotal]);
    }

    public function salvarProposta($dadosProposta, $precoTotal) {
        $propostasFile = 'api/propostas/propostas.json';
        $propostas = [];

        if (file_exists($propostasFile)) {
            $propostas = json_decode(file_get_contents($propostasFile), true);
        }

        $proposta = new Proposta(); // Assumindo que a classe Proposta representa a estrutura de uma proposta individual
        $proposta->registro_plano = $dadosProposta['registro_plano'];
        $proposta->quantidade_beneficiarios = $dadosProposta['quantidade_beneficiarios'];
        $proposta->beneficiarios = $dadosProposta['beneficiarios'];
        $proposta->precoTotal = $precoTotal;

        $propostas[] = $proposta->toArray();

        file_put_contents($propostasFile, json_encode($propostas, JSON_PRETTY_PRINT));

        return true;
    }
}
?>
