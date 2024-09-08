<?php
require_once ("modelo/Banco.php");

class Moeda implements JsonSerializable
{
    private $idmoedas;
    private $taxaConv;
    private $codigoISO;


    public function jsonSerialize()
    {
        $objetoResposta = new stdClass();
        $objetoResposta->idmoedas = $this->idmoedas;
        $objetoResposta->taxaConv = $this->taxaConv;
        $objetoResposta->codigoISO = $this->codigoISO;
        return $objetoResposta;
    }

    public function create()
    {
        $connect = Banco::getConnect();
        $SQL = "INSERT INTO moedas (taxaConv, codigoISO) VALUES (?, ?);";
        $prepareSQL = $connect->prepare($SQL);
        $prepareSQL->bind_param("fs", $this->taxaConv, $this->codigoISO);
        $executou = $prepareSQL->execute();

        $idmoedas = $connect->insert_id;
        $this->setIdmoedas($idmoedas);

        return $executou;
    }

    public function isMoeda()
    {
        $connect = Banco::getConnect();
        $SQL = "SELECT COUNT(*) AS qtd FROM moedas WHERE codigoISO = ?;";
        $prepareSQL = $connect->prepare($SQL);
        $prepareSQL->bind_param("s", $this->codigoISO);
        $executou = $prepareSQL->execute();
        $matrizTuplas = $prepareSQL->get_result();
        $objTupla = $matrizTuplas->fetch_object();
        return $objTupla->qtd > 0;
    }

    public function readAll()
    {
        $connect = Banco::getConnect();
        $SQL = "Select * from moeda order by codigoISO";
        $prepareSQL = $connect->prepare($SQL);
        $executou = $prepareSQL->execute();
        $matrizTuplas = $prepareSQL->get_result();
        $vetorMoedas = array();
        $i = 0;
        while ($tupla = $matrizTuplas->fetch_object()) {
            $vetorMoedas[$i] = new Moeda();
            $vetorMoedas[$i]->setIdmoedas($tupla->idmoedas);
            $vetorMoedas[$i]->setTaxaConv($tupla->taxaConv);
            $vetorMoedas[$i]->setCodigoISO($tupla->codigoISO);
            $i++;
        }
        return $vetorMoedas;
    }

    public function readByID()
    {
        $connect = Banco::getConnect();
        $SQL = "SELECT * FROM moedas WHERE idmoedas=?;";
        $prepareSQL = $connect->prepare($SQL);
        $prepareSQL->bind_param("i", $this->idmoedas);
        $executou = $prepareSQL->execute();
        $matrizTuplas = $prepareSQL->get_result();
        $vetorMoedas = array();
        $i = 0;
        while ($tupla = $matrizTuplas->fetch_object()) {
            $vetorMoedas[$i] = new Moeda();
            $vetorMoedas[$i]->setIdmoedas($tupla->idCargo);
            $vetorMoedas[$i]->setTaxaConv($tupla->nomeCargo);
            $vetorMoedas[$i]->setCodigoISO($tupla->codigoISO);
            $i++;
        }
        return $vetorMoedas;
    }

    

    /**
     * Get the value of idmoedas
     */ 
    public function getIdmoedas()
    {
        return $this->idmoedas;
    }

    /**
     * Set the value of idmoedas
     *
     * @return  self
     */ 
    public function setIdmoedas($idmoedas)
    {
        $this->idmoedas = $idmoedas;

        return $this;
    }

    /**
     * Get the value of taxaConv
     */ 
    public function getTaxaConv()
    {
        return $this->taxaConv;
    }

    /**
     * Set the value of taxaConv
     *
     * @return  self
     */ 
    public function setTaxaConv($taxaConv)
    {
        $this->taxaConv = $taxaConv;

        return $this;
    }

    /**
     * Get the value of codigoISO
     */ 
    public function getCodigoISO()
    {
        return $this->codigoISO;
    }

    /**
     * Set the value of codigoISO
     *
     * @return  self
     */ 
    public function setCodigoISO($codigoISO)
    {
        $this->codigoISO = $codigoISO;

        return $this;
    }
}