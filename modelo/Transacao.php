<?php
require_once("modelo/Banco.php");

class Transacao implements JsonSerializable
{
    private $idtransacoes;
    private $moedaOrig;
    private $qtdMoeda;
    private $qtdConv;

    public function jsonSerialize()
    {
        $objetoResposta = new stdClass();
        $objetoResposta->idtransacoes = $this->idtransacoes;
        $objetoResposta->moedaOrig = $this->moedaOrig;
        $objetoResposta->qtdMoeda = $this->qtdMoeda;
        $objetoResposta->qtdConv = $this->qtdConv;
        return $objetoResposta;
    }

    public function create()
    {
        $connect = Banco::getConnect();
        $SQL = "INSERT INTO transacoes (moedaOrig, qtdMoeda, qtdConv) VALUES (?, ?, ?);";
        $prepareSQL = $connect->prepare($SQL);
        $prepareSQL->bind_param("sdd", $this->moedaOrig, $this->qtdMoeda, $this->qtdConv);
        $executou = $prepareSQL->execute();
        $idTransacao = $connect->insert_id;
        $this->setIdtransacoes($idTransacao);
        $prepareSQL->close();
        return $executou;
    }

    public function delete()
    {
        $connect = Banco::getConnect();
        $SQL = "DELETE FROM transacoes WHERE idtransacoes = ?;";
        $prepareSQL = $connect->prepare($SQL);
        $prepareSQL->bind_param("i", $this->idtransacoes);
        $executou = $prepareSQL->execute();
        $prepareSQL->close();
        return $executou;
    }

    public function update()
    {
        $connect = Banco::getConnect();
        $SQL = "UPDATE transacoes SET moedaOrig = ?, qtdMoeda = ?, qtdConv = ? WHERE idtransacoes = ?";
        $prepareSQL = $connect->prepare($SQL);
        $prepareSQL->bind_param("sddi", $this->moedaOrig, $this->qtdMoeda, $this->qtdConv, $this->idtransacoes);
        $executou = $prepareSQL->execute();
        $prepareSQL->close();
        return $executou;
    }

    public function readAll()
    {
        $connect = Banco::getConnect();
        $SQL = "SELECT * FROM transacoes ORDER BY idtransacoes";
        $prepareSQL = $connect->prepare($SQL);
        $prepareSQL->execute();
        $matrizTuplas = $prepareSQL->get_result();
        $transacoes = [];
        while ($tupla = $matrizTuplas->fetch_object()) {
            $transacao = new Transacao();
            $transacao->setIdtransacoes($tupla->idtransacoes);
            $transacao->setMoedaOrig($tupla->moedaOrig);
            $transacao->setQtdMoeda($tupla->qtdMoeda);
            $transacao->setQtdConv($tupla->qtdConv);
            $transacoes[] = $transacao;
        }
        return $transacoes;
    }

    public function readById()
    {
        $connect = Banco::getConnect();
        $SQL = "SELECT * FROM transacoes WHERE idtransacoes = ?;";
        $prepareSQL = $connect->prepare($SQL);
        $prepareSQL->bind_param("i", $this->idtransacoes);
        $prepareSQL->execute();
        $matrizTuplas = $prepareSQL->get_result();
        if ($tupla = $matrizTuplas->fetch_object()) {
            $this->setIdtransacoes($tupla->idtransacoes);
            $this->setMoedaOrig($tupla->moedaOrig);
            $this->setQtdMoeda($tupla->qtdMoeda);
            $this->setQtdConv($tupla->qtdConv);
        }
        return $this;
    }

    // Getters e Setters
    // ...

    /**
     * Get the value of idtransacoes
     */ 
    public function getIdtransacoes()
    {
        return $this->idtransacoes;
    }

    /**
     * Set the value of idtransacoes
     *
     * @return  self
     */ 
    public function setIdtransacoes($idtransacoes)
    {
        $this->idtransacoes = $idtransacoes;

        return $this;
    }

    /**
     * Get the value of moedaOrig
     */ 
    public function getMoedaOrig()
    {
        return $this->moedaOrig;
    }

    /**
     * Set the value of moedaOrig
     *
     * @return  self
     */ 
    public function setMoedaOrig($moedaOrig)
    {
        $this->moedaOrig = $moedaOrig;

        return $this;
    }

    /**
     * Get the value of qtdMoeda
     */ 
    public function getQtdMoeda()
    {
        return $this->qtdMoeda;
    }

    /**
     * Set the value of qtdMoeda
     *
     * @return  self
     */ 
    public function setQtdMoeda($qtdMoeda)
    {
        $this->qtdMoeda = $qtdMoeda;

        return $this;
    }

    /**
     * Get the value of qtdConv
     */ 
    public function getQtdConv()
    {
        return $this->qtdConv;
    }

    /**
     * Set the value of qtdConv
     *
     * @return  self
     */ 
    public function setQtdConv($qtdConv)
    {
        $this->qtdConv = $qtdConv;

        return $this;
    }
}
