<?php

if ( ! defined('BASEPATH')) {
    exit('<h1 style="text-transform: uppercase; font-weight: 300; color: red; text-align:center; margin-top: 20%;">Acesso nao permitido</h1>');
}

class Etiqueta {

    protected $ticket;
    protected $dv;
    private $digito;
    private $etiqueta;
    private $sigla;

    public function __construct($ticket = null) {
        $this->ticket = $ticket;
    }

    //valida etiqueta
    public function validaEtiqueta():bool {
        if(!$this->is_sigla()){
            return false;
        }elseif (!$this->is_numero()){
            return false;
        }elseif ($this->digitoVerificador($this->ticket) === false){
            return false;
        }
        return true;
    }

    //digito verificador
    public function digitoVerificador($ticket) {
        $numero = mb_substr($ticket, 2, 8);
        $fatoresDePonderacao = [8, 6, 4, 2, 3, 5, 9, 7];
        $soma = 0;
        for ($i = 0; $i < 8; $i++) {
            $soma += ($numero[$i] * $fatoresDePonderacao[$i]);
        }
        $modulo = $soma % 11;
        if ($modulo == 0) {
            $this->dv = 5;
        } else {
            if ($modulo == 1) {
                $this->dv = 0;
            } else {
                $this->dv = 11 - $modulo;
            }
        }
        $this->setEtiqueta("{$this->getSigla()}{$numero}{$this->dv}");
        $this->setDigito($this->dv);
    }

    //valida digito atual se e verdadeiro
    public function validaDigitoAtual() {
        $etiquetaAtual = mb_substr($this->ticket, 2, 8);
        $digitoAtual = (int) mb_substr($this->ticket, 10, 1);
        $this->digitoVerificador($this->ticket);
        if ($digitoAtual === $this->getDigito()) {
            return true;
        }
        return false;

    }


    public function is_sigla():bool {
        $sigla = mb_substr($this->ticket, 0, 2);
        $this->setSigla($sigla);
        return ctype_alpha($sigla);
    }

    public function is_numero():bool {
        $num = mb_substr($this->ticket, 2, 9);
        if(mb_strlen($num) == 9){
            return is_numeric($num);
        }else{
            return false;
        }

    }

    public function is_etiqueta() {
        $num = mb_substr($this->ticket, 0, 11);
        if(mb_strlen($num) == 11){
            $this->setEtiqueta($num);
        }else{
            return false;
        }

    }

    /**
     * @return mixed
     */
    public function getSigla() {
        return $this->sigla;
    }

    /**
     * @param mixed $sigla
     */
    public function setSigla($sigla): void {
        $this->sigla = $sigla;
    }



    /**
     * @return mixed
     */
    public function getEtiqueta() {
        $this->is_etiqueta();
        return $this->etiqueta;
    }

    /**
     * @param mixed $etiqueta
     */
    public function setEtiqueta($etiqueta): void {
        $this->etiqueta = $etiqueta;
    }


    /**
     * @return int
     */
    private function getDigito(): int {
        return $this->digito;
    }

    /**
     * @param mixed $digito
     */
    private function setDigito($digito): void {
        $this->digito = $digito;
    }
}
