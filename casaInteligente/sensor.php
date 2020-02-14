<?php
class Sensor {

    var $id;
    var $estado;
    var $nome;


    function Sensor ($id,$estado,$nome){
        $this->id = $id;
        $this->estado = $estado;
        $this->nome = $nome;
    }
    function getId(){
        return $this->id;
    }
    function getEstado(){
        return $this->estado;
    }
    function setEstado($novoEstado){
        $this->estado = $novoEstado;
    }
    function getNome(){
        return $this->nome;
    }
    function setNome($novoNome){
        $this->novo = $novoNome;
    }    
}
?>	