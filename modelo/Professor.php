<?php
// Inclui o arquivo Banco.php, que contém funcionalidades relacionadas ao banco de dados
require_once ("modelo/Banco.php");

// Definição da classe Professor, que implementa a interface JsonSerializable
class Professor implements JsonSerializable
{
    // Propriedades privadas da classe
    private $idProfessor;
    private $nomeProfessor;
    private $telProfessor;
    private $cpfProfessor;
    private $especialProfessor;
    private $Escola_idEscola;
    
    // Método necessário pela interface JsonSerializable para serialização do objeto para JSON
    public function jsonSerialize()
    {
        // Cria um objeto stdClass para armazenar os dados do Professor
        $objetoResposta = new stdClass();
        // Define as propriedades do objeto com os valores das propriedades da classe
        $objetoResposta->idProfessor = $this->idProfessor;
        $objetoResposta->nomeProfessor = $this->nomeProfessor;
        $objetoResposta->telProfessor = $this->telProfessor;
        $objetoResposta->cpfProfessor = $this->cpfProfessor;
        $objetoResposta->especialProfessor = $this->especialProfessor;
        $objetoResposta->Escola_idEscola = $this->Escola_idEscola;

        // Retorna o objeto para serialização
        return $objetoResposta;
    }
    
    // Método para criar um novo professor no banco de dados
    public function create()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para inserir um novo Professor  
        $SQL = "INSERT INTO Professor (nomeProfessor, telProfessor, cpfProfessor, especialProfessor) VALUES (?, ?, ?, ?);";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o nome do Professor  
        $prepareSQL->bind_param("siis", $this->nomeProfessor, $this->telProfessor, $this->cpfProfessor, $this->especialProfessor);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o ID do Professor inserido
        $idCadastrado = $conexao->insert_id;
        // Define o ID do Professor na instância atual da classe
        $this->setidProfessor($idCadastrado);
        // Retorna se a operação foi executada com sucesso
        return $executou;
    }

    // Método para excluir todas as associações de turmas de um Professor no banco de dados
    private function deleteAssociations()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // SQL para excluir todas as turmas associadas ao professor
        $SQL = "DELETE FROM turmas WHERE Professor_idProf = ?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do Professor
        $prepareSQL->bind_param("i", $this->idProfessor);
        // Executa a consulta
        return $prepareSQL->execute();
    }
    
    // Método para excluir um Professor do banco de dados
    public function delete()
    {
        // Exclui todas as associações de turmas antes de deletar o professor
        $this->deleteAssociations();
        
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para excluir um Professor pelo ID
        $SQL = "DELETE FROM Professor WHERE idProfessor = ?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do Professor
        $prepareSQL->bind_param("i", $this->idProfessor);
        // Executa a consulta
        return $prepareSQL->execute();
    }

    // Método para atualizar os dados de um Professor no banco de dados
    public function update()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para atualizar o nome do Professor pelo ID
        $SQL = "UPDATE Professor SET nomeProfessor = ?, telProfessor = ?, cpfProfessor = ?, especialProfessor = ? WHERE idProfessor = ?";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define os parâmetros da consulta com os novos dados do Professor e o ID do Professor
        $prepareSQL->bind_param("siisi", $this->nomeProfessor, $this->telProfessor, $this->cpfProfessor, $this->especialProfessor, $this->idProfessor);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Retorna se a operação foi executada com sucesso
        return $executou;
    }

    // Método para verificar se um Professor já existe no banco de dados
    public function isProfessor()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para contar quantos Professor possuem o mesmo nome
        $SQL = "SELECT COUNT(*) AS qtd FROM Professor WHERE nomeProfessor = ?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o nome do Professor
        $prepareSQL->bind_param("s", $this->nomeProfessor);
        // Executa a consulta
        $executou = $prepareSQL->execute();

        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();

        // Extrai o objeto da tupla
        $objTupla = $matrizTuplas->fetch_object();
        // Retorna se a quantidade de Professor encontrados é maior que zero
        return $objTupla->qtd > 0;
    }

    // Método para ler todos os Professor do banco de dados
    public function readAll()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para selecionar todos os Professor ordenados pelo nome
        $SQL = "SELECT * FROM Professor";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Inicializa um vetor para armazenar os Professor
        $vetorProfessor = array();
        $i = 0;
        // Itera sobre as tuplas do resultado
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria uma nova instância de Professor para cada tupla encontrada
            $vetorProfessor[$i] = new Professor();
            // Define o ID, nome, telProfessor, cpfProfessor, especialProfessor e escola na instância
            $vetorProfessor[$i]->setidProfessor($tupla->idProfessor);
            $vetorProfessor[$i]->setnomeProfessor($tupla->nomeProfessor);
            $vetorProfessor[$i]->settelProfessor($tupla->telProfessor);
            $vetorProfessor[$i]->setcpfProfessor($tupla->cpfProfessor);
            $vetorProfessor[$i]->setespecialProfessor($tupla->especialProfessor);
            $i++;
        }
        // Retorna o vetor com os Professor encontrados
        return $vetorProfessor;
    }

    // Método para ler um Professor do banco de dados com base no ID
    public function readByID()
    {
        // Obtém a conexão com o banco de dados
        $conexao = Banco::getConexao();
        // Define a consulta SQL para selecionar um Professor pelo ID
        $SQL = "SELECT * FROM Professor WHERE idProfessor = ?;";
        // Prepara a consulta
        $prepareSQL = $conexao->prepare($SQL);
        // Define o parâmetro da consulta com o ID do Professor
        $prepareSQL->bind_param("i", $this->idProfessor);
        // Executa a consulta
        $executou = $prepareSQL->execute();
        // Obtém o resultado da consulta
        $matrizTuplas = $prepareSQL->get_result();
        // Inicializa um vetor para armazenar os Professor
        $vetorProfessor = array();
        $i = 0;
        // Itera sobre as tuplas do resultado
        while ($tupla = $matrizTuplas->fetch_object()) {
            // Cria uma nova instância de Professor para cada tupla encontrada
            $vetorProfessor[$i] = new Professor();
            // Define o ID, nome, telProfessor, cpfProfessor, especialProfessor e escola na instância
            $vetorProfessor[$i]->setidProfessor($tupla->idProfessor);
            $vetorProfessor[$i]->setnomeProfessor($tupla->nomeProfessor);
            $vetorProfessor[$i]->settelProfessor($tupla->telProfessor);
            $vetorProfessor[$i]->setcpfProfessor($tupla->cpfProfessor);
            $vetorProfessor[$i]->setespecialProfessor($tupla->especialProfessor);
            $i++;
        }
        // Retorna o vetor com os Professor encontrados
        return $vetorProfessor;
    }

    // Getters e Setters para as propriedades da classe
    public function getidProfessor()
    {
        return $this->idProfessor;
    }

    public function setidProfessor($idProfessor)
    {
        $this->idProfessor = $idProfessor;
    }

    public function getnomeProfessor()
    {
        return $this->nomeProfessor;
    }

    public function setnomeProfessor($nomeProfessor)
    {
        $this->nomeProfessor = $nomeProfessor;
    }

    public function gettelProfessor()
    {
        return $this->telProfessor;
    }

    public function settelProfessor($telProfessor)
    {
        $this->telProfessor = $telProfessor;
    }

    public function getcpfProfessor()
    {
        return $this->cpfProfessor;
    }

    public function setcpfProfessor($cpfProfessor)
    {
        $this->cpfProfessor = $cpfProfessor;
    }

    public function getespecialProfessor()
    {
        return $this->especialProfessor;
    }

    public function setespecialProfessor($especialProfessor)
    {
        $this->especialProfessor = $especialProfessor;
    }

    public function getEscola_idEscola()
    {
        return $this->Escola_idEscola;
    }

    public function setEscola_idEscola($Escola_idEscola)
    {
        $this->Escola_idEscola = $Escola_idEscola;
    }
}
?>
