<?php
require_once ("modelo/Bancos.php");

class Moeda implements JsonSerializable
{
    private $codigoISO;
    private $taxaConv;
    private $nome;
    
    public function jsonSerialize()
    {
        $objetoResposta = new stdClass();
        $objetoResposta->codigoISO = $this->codigoISO;
        $objetoResposta->taxaConv = $this->taxaConv;
        $objetoResposta->nome = $this->nome;

        return $objetoResposta;
    }
    
    // Método para criar um novo moeda no banco de dados
    public function create()
    {
        // Obtém a conexão com o banco de dados
        $connect = Banco::getConnect();
        $SQL = "INSERT INTO moedas (codigoISO, taxaConv, nome) VALUES (?, ?, ?);";
        // Prepara a consulta
        $prepareSQL = $connect->prepare($SQL);
        // Define os parâmetros da consulta
        $prepareSQL->bind_param("sds", $this->codigoISO, $this->taxaConv, $this->nome);
        // Executa a consulta
        $executou = $prepareSQL->execute();

        $prepararSQL->close();
        return $executou;
    }
    
    // Método para excluir uma moeda do banco de dados
    public function delete()
    {
        // Obtém a conexão com o banco de dados
        $connect = Banco::getConnect();
        // Define a consulta SQL para excluir uma moeda pelo ISO
        $SQL = "delete from moedas where codigoISO=?;";
        // Prepara a consulta
        $prepareSQL = $connect->prepare($SQL);
        // Define o parâmetro da consulta com o ISO da moeda
        $prepareSQL->bind_param("s", $this->codigoISO);
        // Executa a consulta
        return $prepareSQL->execute();
    }

    // Método para atualizar os dados de uma moeda no banco de dados
    public function update()
    {
        // Obtém a conexão com o banco de dados
        $connect = Banco::getConnect();
        // Define a consulta SQL para atualizar a taxa de conversão da moeda pelo ISO
        $SQL = "update moedas set taxaConv = ? and set nome = ? where codigoISO=?";
        // Prepara a consulta
        $prepareSQL = $connect->prepare($SQL);
        // Define os parâmetros da consulta com o nova taxa, nome e o ISO da moeda
        $prepareSQL->bind_param("dss", $this->taxaConv, $this->nome, $this->codigoISO);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Retorna se a operação foi executada com sucesso
        return $executou;
    }
    
    // Método para verificar se uma moeda já existe no banco de dados
    public function isISO()
    {
        // Obtém a conexão com o banco de dados
        $connect = Banco::getConnect();
        // Define a consulta SQL para contar quantas Moedas possuem o mesmo ISO
        $SQL = "SELECT COUNT(*) AS qtd FROM moedas WHERE codigoISO =?;";
        // Prepara a consulta
        $prepareSQL = $connect->prepare($SQL);
        // Define o parâmetro da consulta com o ISO da moeda
        $prepareSQL->bind_param("s", $this->codigoISO);
        // Executa a consulta
        $executou = $prepareSQL->execute();

        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();

        // Extrai o objeto da tupla
        $objTupla = $matrizTuplas->fetch_object();
        // Retorna se a quantidade de moedas encontradas é maior que zero
        return $objTupla->qtd > 0;

    }
    
    // Método para ler todos as Moedas do banco de dados
    public function readAll()
    {
        // Obtém a conexão com o banco de dados
        $connect = Banco::getConnect();
        // Define a consulta SQL para selecionar todos as Moedas ordenados por ISO
        $SQL = "Select * from moedas order by codigoISO";
        // Prepara a consulta
        $prepareSQL = $connect->prepare($SQL);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Inicializa um vetor para armazenar as Moedas
        $vetorMoedas = array();
        $i = 0;
        // Itera sobre as tuplas do resultado
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria uma nova instância de moeda para cada tupla encontrada
            $vetorMoedas[$i] = new Moeda();
            // Define o ISO e o nome da moeda na instância
            $vetorMoedas[$i]->setIsoMoeda($tupla->codigoISO);
            $vetorMoedas[$i]->setTaxaConv($tupla->taxaConv);
            $vetorMoedas[$i]->setNome($tupla->nome);
            $i++;
        }
        // Retorna o vetor com as Moedas encontrados
        return $vetorMoedas;
    }
    
    // Método para ler uma moeda do banco de dados com base no ISO
    public function readByISO()
    {
        // Obtém a conexão com o banco de dados
        $connect = Banco::getConnect();
        // Define a consulta SQL para selecionar uma moeda pelo ISO
        $SQL = "SELECT * FROM moedas WHERE codigoISO=?;";
        // Prepara a consulta
        $prepareSQL = $connect->prepare($SQL);
        // Define o parâmetro da consulta com o ISO da moeda
        $prepareSQL->bind_param("s", $this->codigoISO);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Inicializa um vetor para armazenar as Moedas
        $vetorMoedas = array();
        $i = 0;
        // Itera sobre as tuplas do resultado
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria uma nova instância de Moeda para cada tupla encontrada
            $vetorMoedas[$i] = new Moeda();
            // Define o ISO e o nome da moeda na instância
            $vetorMoedas[$i]->setIsoMoeda($tupla->codigoISO);
            $vetorMoedas[$i]->setTaxaConv($tupla->taxaConv);
            $vetorMoedas[$i]->setNome($tupla->nome);
            $i++;
        }
        // Retorna o vetor com as Moedas encontrados
        return $vetorMoedas;
    }

    public function getIsoMoeda()
    {
        return $this->codigoISO;
    }

    // Método setter para IsoMoedas
    public function setIsoMoeda($IsoMoeda)
    {
        $this->codigoISO = $IsoMoeda;

        return $this;
    }

    // Método getter para taxa de conversão
    public function getTaxaConv()
    {
        return $this->taxaConv;
    }

    // Método setter para taxa de conversão
    public function setTaxaConv($taxaConv)
    {
        $this->taxaConv = $taxaConv;

        return $this;
    }

    // Método getter para nome
    public function getNome()
    {
        return $this->nome;
    }

    // Método setter para taxa de conversão
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }
}

?>
