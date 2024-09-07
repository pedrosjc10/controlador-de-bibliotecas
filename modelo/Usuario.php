<?php
require_once ("modelo/Bancos.php");

class Moeda implements JsonSerializable
{
    private $idUsuario;
    private $nome;
    private $moedaPref;
    private $codigoISO;
    private $idTransacao;



    
    public function jsonSerialize()
    {
        $objetoResposta = new stdClass();
        $objetoResposta->idUsuario = $this->idUsuario;
        $objetoResposta->moedaPref = $this->moedaPref;
        $objetoResposta->isoMoeda = $this->isoMoeda;
        $objetoResposta->nome = $this->nome;
        $objetoResposta->idTansacao = $this->idTansacao;


        return $objetoResposta;
    }
    
    // Método para criar um novo moeda no banco de dados
    public function create()
    {
        // Obtém a conexão com o banco de dados
        $connect = Banco::getConnect();
        $SQL = "INSERT INTO usuarios ( moedaPref, nome, moedas_codigoISO, transações_idtransação) VALUES (?, ?, ?, ?, ?);";
        // Prepara a consulta
        $prepareSQL = $connect->prepare($SQL);
        // Define os parâmetros da consulta
        $prepareSQL->bind_param("sssi", $this->moedaPref, $this->nome, $this->isoMoeda, $this->idTransacao);
        // Executa a consulta
        $executou = $prepareSQL->execute();

        $idUsuario = $conexao->insert_id;
        // Define o ID do funcionário na instância atual da classe
        $this->setIdUsuario($idUsuario)        // Fecha a consulta
        $prepararSQL->close();
        return $executou;
    }
    
    // Método para excluir uma moeda do banco de dados
    public function delete()
    {
        // Obtém a conexão com o banco de dados
        $connect = Banco::getConnect();
        // Define a consulta SQL para excluir uma moeda pelo ISO
        $SQL = "delete from usuarios where idusuario=?;";
        // Prepara a consulta
        $prepareSQL = $connect->prepare($SQL);
        // Define o parâmetro da consulta com o ISO da moeda
        $prepareSQL->bind_param("i", $this->idUsuario);
        // Executa a consulta
        return $prepareSQL->execute();
    }

    // Método para atualizar os dados de uma moeda no banco de dados
    public function update()
    {
        // Obtém a conexão com o banco de dados
        $connect = Banco::getConnect();
        // Define a consulta SQL para atualizar o ISO da moeda pelo ISO
        $SQL = "update usuarios set moedaPref = ?, nome = ?, moedas_codigoISO = ?, transações_idtransação = ? where idusuario=?";
        // Prepara a consulta
        $prepareSQL = $connect->prepare($SQL);
        // Define os parâmetros da consulta com o novo Iso da moeda e o ISO da moeda
        $prepareSQL->bind_param("sssii", $this->moedaPref, $this->nome, $this->isoMoeda, $this->idTransacao, $this->isUsuario);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Retorna se a operação foi executada com sucesso
        return $executou;
    }
    
    
    // Método para ler todos as Moedas do banco de dados
    public function readAll()
    {
        // Obtém a conexão com o banco de dados
        $connect = Banco::getConnect();
        // Define a consulta SQL para selecionar todos as Moedas ordenados por ISO
        $SQL = "Select * from usuarios order by idusuario";
        // Prepara a consulta
        $prepareSQL = $connect->prepare($SQL);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Inicializa um vetor para armazenar as Moedas
        $vetorUsuarios = array();
        $i = 0;
        // Itera sobre as tuplas do resultado
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria uma nova instância de moeda para cada tupla encontrada
            $vetorUsuarios[$i] = new Usuario();
            // Define o ISO e o nome da moeda na instância
            $vetorUsuarios[$i]->setIdUsuario($tupla->idUsuario);
            $vetorUsuarios[$i]->setMoedaPref($tupla->moedaPref);
            $vetorUsuarios[$i]->setNome($tupla->nome);
            $vetorUsuarios[$i]->setIsoMoeda($tupla->isoMoeda);
            $vetorUsuarios[$i]->setIdTransacao($tupla->idTransacao);
            $i++;
        }
        // Retorna o vetor com as Usuarios$vetorUsuarios encontrados
        return $vetorUsuarios;
    }
    
    // Método para ler uma moeda do banco de dados com base no ISO
    public function readById()
    {
        // Obtém a conexão com o banco de dados
        $connect = Banco::getConnect();
        // Define a consulta SQL para selecionar uma moeda pelo ISO
        $SQL = "SELECT * FROM usuarios WHERE idusuario=?;";
        // Prepara a consulta
        $prepareSQL = $connect->prepare($SQL);
        // Define o parâmetro da consulta com o ISO da moeda
        $prepareSQL->bind_param("i", $this->idUsuario);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Inicializa um vetor para armazenar as Moedas
        $vetorUsuarios = array();
        $i = 0;
        // Itera sobre as tuplas do resultado
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria uma nova instância de moeda para cada tupla encontrada
            $vetorUsuarios[$i] = new Usuario();
            // Define o ISO e o nome da moeda na instância
            $vetorUsuarios[$i]->setIdUsuario($tupla->idUsuario);
            $vetorUsuarios[$i]->setMoedaPref($tupla->moedaPref);
            $vetorUsuarios[$i]->setNome($tupla->nome);
            $vetorUsuarios[$i]->setIsoMoeda($tupla->isoMoeda);
            $vetorUsuarios[$i]->setIdTransacao($tupla->idTransacao);
            $i++;
        }
        // Retorna o vetor com as Usuarios$vetorUsuarios encontrados
        return $vetorUsuarios;
    }

    public function getIsoMoeda()
    {
        return $this->isoMoeda;
    }

    // Método setter para IsoMoedas
    public function setIsoMoeda($codigoISO)
    {
        $this->isoMoeda = $codigoISO;

        return $this;
    }

    // Método getter para taxa de conversão
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    // Método setter para taxa de conversão
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    public function getMoedaPref()
    {
        return $this->moedaPref;
    }

    // Método setter para taxa de conversão
    public function setMoedaPref($moedaPref)
    {
        $this->moedaPref = $moedaPref;

        return $this;
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
}

?>
