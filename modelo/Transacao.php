<?php
require_once ("modelo/Bancos.php");

class Transacao implements JsonSerializable
{
    private $idTransacao;
    private $moedaOrig;
    private $qtdMoeda;
    private $qtdConv;
   
    public function jsonSerialize()
    {
        $objetoResposta = new stdClass();
        $objetoResposta->idTransacao = $this->idTransacao;
        $objetoResposta->moedaOrig = $this->moedaOrig;
        $objetoResposta->qtdMoeda = $this->qtdMoeda;
        $objetoResposta->qtdConv = $this->qtdConv;

        return $objetoResposta;
    }
    
    // Método para criar um nova transação no banco de dados
    public function create()
    {
        // Obtém a conexão com o banco de dados
        $connect = Banco::getConnect();
        $SQL = "INSERT INTO transações (moedaOrig, qtdmoedaOrig, qtdConv) VALUES (?, ?, ?, ?);";
        // Prepara a consulta
        $prepareSQL = $connect->prepare($SQL);
        // Define os parâmetros da consulta
        $prepareSQL->bind_param("ddd", $this->moedaOrig, $this->qtdMoeda, $this->qtdConv);
        // Executa a consulta
        $executou = $prepareSQL->execute();

        $idTransacao = $conexao->insert_id;
        // Define o ID do funcionário na instância atual da classe
        $this->setIdTransacao($idTransacao);
        // Fecha a consulta
        $prepararSQL->close();
        return $executou;
    }
    
    // Método para excluir uma transação do banco de dados
    public function delete()
    {
        // Obtém a conexão com o banco de dados
        $connect = Banco::getConnect();
        // Define a consulta SQL para excluir uma transação pelo id
        $SQL = "delete from transações where idTransação=?;";
        // Prepara a consulta
        $prepareSQL = $connect->prepare($SQL);
        // Define o parâmetro da consulta com o id da transação
        $prepareSQL->bind_param("i", $this->idTransacao);
        // Executa a consulta
        return $prepareSQL->execute();
    }

    // Método para atualizar os dados de uma transação no banco de dados
    public function update()
    {
        // Obtém a conexão com o banco de dados
        $connect = Banco::getConnect();
        // Define a consulta SQL para atualizar a transação pelo id
        $SQL = "update moedas set moedaOrig = ?, qtdmoedaOrig = ?, qtdConv = ? where idTransação=?";
        // Prepara a consulta
        $prepareSQL = $connect->prepare($SQL);
        // Define os parâmetros da consulta com a transação
        $prepareSQL->bind_param("dddi", $this->moedaOrig, $this->qtdMoeda, $this->qtdConv, $this->idTransacao);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Retorna se a operação foi executada com sucesso
        return $executou;
    }
        
    // Método para ler todos as transações do banco de dados
    public function readAll()
    {
        // Obtém a conexão com o banco de dados
        $connect = Banco::getConnect();
        // Define a consulta SQL para selecionar todos as Transacoes ordenados por id
        $SQL = "Select * from transações order by idTransações";
        // Prepara a consulta
        $prepareSQL = $connect->prepare($SQL);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Inicializa um vetor para armazenar as Transacoes
        $vetorTransacoes = array();
        $i = 0;
        // Itera sobre as tuplas do resultado
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria uma nova instância de transação para cada tupla encontrada
            $vetorTransacoes[$i] = new Transacao();
            // Define propriedades da transação na instância
            $vetorTransacoes[$i]->setIdTransacao($tupla->idTransacoes);
            $vetorTransacoes[$i]->setMoedaOrig($tupla->moedaOrig);
            $vetorTransacoes[$i]->setQtdMoedaOrig($tupla->qtdMoedaOrig);
            $vetorTransacoes[$i]->setQtdConv($tupla->qtdConv);
            $i++;
        }
        // Retorna o vetor com as Transacoes encontrados
        return $vetorTransacoes;
    }
    
    // Método para ler uma transação do banco de dados com base no Id
    public function readById()
    {
        // Obtém a conexão com o banco de dados
        $connect = Banco::getConnect();
        // Define a consulta SQL para selecionar uma moeda pelo Id
        $SQL = "SELECT * FROM transações WHERE idTransação=?;";
        // Prepara a consulta
        $prepareSQL = $connect->prepare($SQL);
        // Define o parâmetro da consulta com o Id da moeda
        $prepareSQL->bind_param("i", $this->idTransacao);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Inicializa um vetor para armazenar as Transações
        $vetorTransacoes = array();
        $i = 0;
        // Itera sobre as tuplas do resultado
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria uma nova instância de Transação para cada tupla encontrada
            $vetorTransacoes[$i] = new Transacao();
            // Define o Id e o nome da moeda na instância
            $vetorTransacoes[$i]->setIdTransacao($tupla->idTransacoes);
            $vetorTransacoes[$i]->setMoedaOrig($tupla->moedaOrig);
            $vetorTransacoes[$i]->setQtdMoedaOrig($tupla->qtdMoedaOrig);
            $vetorTransacoes[$i]->setQtdConv($tupla->qtdConv);
            $i++;
        }
        // Retorna o vetor com as Transacoes encontradas
        return $vetorTransacoes;
    }

    public function getIdTransacao()
    {
        return $this->idTransacao;
    }

    // Método setter para IdTransacao
    public function setIdTransacao($idTransacao)
    {
        $this->idTransacao = $idTransacao;

        return $this;
    }

    // Método getter para taxa de conversão
    public function getMoedaOrig()
    {
        return $this->moedaOrig;
    }

    // Método setter para taxa de conversão
    public function setMoedaOrig($moedaOrig)
    {
        $this->moedaOrig = $moedaOrig;

        return $this;
    }

    // Método getter para taxa de conversão
    public function getQtdMoedaOrig()
    {
        return $this->qtdMoedaOrig;
    }

    // Método setter para taxa de conversão
    public function setQtdMoedaOrig($qtdMoedaOrig)
    {
        $this->qtdMoedaOrig = $qtdMoedaOrig;

        return $this;
    }

    // Método getter para taxa de conversão
    public function getQtdConv()
    {
        return $this->qtdConv;
    }

    // Método setter para taxa de conversão
    public function setQtdConv($qtdConv)
    {
        $this->qtdConv = $qtdConv;

        return $this;
    }
}

?>
