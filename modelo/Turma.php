<?php
    // Inclui o arquivo Banco.php, que contém funcionalidades relacionadas ao banco de dados
    require_once ("modelo/Banco.php");

    // Definição da classe Turma, que implementa a interface JsonSerializable
    class Turma implements JsonSerializable
    {
        // Propriedades privadas da classe
        private $idTurma;
        private $serieTurma;
        private $qtdAlunos;
        private $curso;
        private $Professor_idProfessor;
        
        // Método necessário pela interface JsonSerializable para serialização do objeto para JSON
        public function jsonSerialize()
        {
            // Cria um objeto stdClass para armazenar os dados da turma
            $objetoResposta = new stdClass();
            // Define as propriedades do objeto com os valores das propriedades da classe
            $objetoResposta->idTurma = $this->idTurma;
            $objetoResposta->serieTurma = $this->serieTurma;
            $objetoResposta->qtdAlunos = $this->qtdAlunos;
            $objetoResposta->curso = $this->curso;
            $objetoResposta->Professor_idProfessor = $this->Professor_idProfessor;

            // Retorna o objeto para serialização
            return $objetoResposta;
        }
        
        // Método para criar uma nova turma no banco de dados
        public function create()
        {
            // Obtém a conexão com o banco de dados
            $conexao = Banco::getConexao();
            // Define a consulta SQL para inserir uma nova turma  
            $SQL = "INSERT INTO Turma (serieTurma, qtdAlunos, curso)VALUES(?, ?, ?);";
            // Prepara a consulta
            $prepareSQL = $conexao->prepare($SQL);
            // Define o parâmetro da consulta com a serieTurma da turma  
            $prepareSQL->bind_param("sis", $this->serieTurma, $this->qtdAlunos, $this->curso);
            // Executa a consulta
            $executou = $prepareSQL->execute();
            // Obtém o ID da turma inserida
            $idCadastrado = $conexao->insert_id;
            // Define o ID do turma na instância atual da classe
            $this->setidTurma($idCadastrado);
            // Retorna se a operação foi executada com sucesso
            return $executou;
        }
        
        // Método para excluir uma turma do banco de dados
        public function delete()
        {
            // Obtém a conexão com o banco de dados
            $conexao = Banco::getConexao();
            // Define a consulta SQL para excluir uma turma pelo ID
            $SQL = "delete from Turma where idTurma=?;";
            // Prepara a consulta
            $prepareSQL = $conexao->prepare($SQL);
            // Define o parâmetro da consulta com o ID da turma
            $prepareSQL->bind_param("i", $this->idTurma);
            // Executa a consulta
            return $prepareSQL->execute();
        }

        // Método para atualizar os dados de uma turma no banco de dados
        public function update()
        {
            // Obtém a conexão com o banco de dados
            $conexao = Banco::getConexao();
            // Define a consulta SQL para atualizar o nome da turma pelo ID
            $SQL = "update Turma set serieTurma=?,qtdAlunos=?,curso=? where idTurma=?";
            // Prepara a consulta
            $prepareSQL = $conexao->prepare($SQL);
            // Define os parâmetros da consulta com os novos dados da turma e o ID da turma
            $prepareSQL->bind_param("sisi", $this->serieTurma, $this->qtdAlunos, $this->curso, $this->idTurma,);
            // Executa a consulta
            $executou = $prepareSQL->execute();
            // Retorna se a operação foi executada com sucesso
            return $executou;
        }
        
        // Método para verificar se uma turma já existe no banco de dados
        public function isTurma()
        {
            // Obtém a conexão com o banco de dados
            $conexao = Banco::getConexao();
            // Define a consulta SQL para contar quantas Turma possuem o mesmo nome
            $SQL = "SELECT COUNT(*) AS qtd FROM Turma WHERE serieTurma =?;";
            // Prepara a consulta
            $prepareSQL = $conexao->prepare($SQL);
            // Define o parâmetro da consulta com a serieTurma da turma
            $prepareSQL->bind_param("s", $this->serieTurma);
            // Executa a consulta
            $executou = $prepareSQL->execute();

            // Obtém o resultado da consulta
            $matrizTuplas = $prepareSQL->get_result();

            // Extrai o objeto da tupla
            $objTupla = $matrizTuplas->fetch_object();
            // Retorna se a quantidade de Turma encontradas é maior que zero
            return $objTupla->qtd > 0;

        }
        
        // Método para ler todos as Turma do banco de dados
        public function readAll()
        {
            // Obtém a conexão com o banco de dados
            $conexao = Banco::getConexao();
            // Define a consulta SQL para selecionar todos as Turma ordenados pela serieTurma
            $SQL = "Select * from Turma order by serieTurma";
            // Prepara a consulta
            $prepareSQL = $conexao->prepare($SQL);
            // Executa a consulta
            $executou = $prepareSQL->execute();
            // Obtém o resultado da consulta
            $matrizTuplas = $prepareSQL->get_result();
            // Inicializa um vetor para armazenar as Turma
            $vetorTurma = array();
            $i = 0;
            // Itera sobre as tuplas do resultado
            while ($tupla = $matrizTuplas->fetch_object()) {
                // Cria uma nova instância de turma para cada tupla encontrada
                $vetorTurma[$i] = new Turma();
                // Define o ID, serieTurma, qtdAlunos, curso e professor na instância
                $vetorTurma[$i]->setidTurma($tupla->idTurma);
                $vetorTurma[$i]->setserieTurma($tupla->serieTurma);
                $vetorTurma[$i]->setqtdAlunos($tupla->qtdAlunos);
                $vetorTurma[$i]->setcurso($tupla->curso);

                $i++;
            }
            // Retorna o vetor com as Turma encontrados
            return $vetorTurma;
        }
        
        // Método para ler uma turma do banco de dados com base no ID
        public function readByID()
        {
            // Obtém a conexão com o banco de dados
            $conexao = Banco::getConexao();
            // Define a consulta SQL para selecionar uma turma pelo ID
            $SQL = "SELECT * FROM Turma WHERE idTurma=?;";
            // Prepara a consulta
            $prepareSQL = $conexao->prepare($SQL);
            // Define o parâmetro da consulta com o ID da turma
            $prepareSQL->bind_param("i", $this->idTurma);
            // Executa a consulta
            $executou = $prepareSQL->execute();
            // Obtém o resultado da consulta
            $matrizTuplas = $prepareSQL->get_result();
            // Inicializa um vetor para armazenar as Turma
            $vetorTurma = array();
            $i = 0;
            // Itera sobre as tuplas do resultado
            while ($tupla = $matrizTuplas->fetch_object()) {
                // Cria uma nova instância de turma para cada tupla encontrada
                $vetorTurma[$i] = new Turma();
                // Define o ID,serieTurma,quantia de alunos,curso e professor na instância
                $vetorTurma[$i]->setidTurma($tupla->idTurma);
                $vetorTurma[$i]->setserieTurma($tupla->serieTurma);
                $vetorTurma[$i]->setqtdAlunos($tupla->qtdAlunos);
                $vetorTurma[$i]->setcurso($tupla->curso);
                $vetorTurma[$i]->setProfessor_idProfessor($tupla->Professor_idProfessor);

                $i++;
            }
            // Retorna o vetor com as Turma encontradas
            return $vetorTurma;
        }

        // Método getter para idTurma
        public function getidTurma()
        {
            return $this->idTurma;
        }

        // Método setter para idTurma
        public function setidTurma($idTurma)
        {
            $this->idTurma = $idTurma;

            return $this;
        }

        //Método getter para Professor_idProfessor
        public function getProfessor_idProfessor()
        {
            return $this->Professor_idProfessor;
        }

        //Método setter para Professor_idProfessor
        public function setProfessor_idProfessor($Professor_idProfessor)
        {
            $this->Professor_idProfessor = $Professor_idProfessor;

            return $this;
        }

        //Método getter para qtdAlunos
        public function getqtdAlunos()
        {
            return $this->qtdAlunos;
        }

        //Método setter para qtdAlunos
        public function setqtdAlunos($qtdAlunos)
        {
            $this->qtdAlunos = $qtdAlunos;

            return $this;
        }

        //Método getter para curso
        public function getcurso()
        {
            return $this->curso;
        }

        //Método setter para curso
        public function setcurso($curso)
        {
            $this->curso = $curso;

            return $this;
        }
        

        // Método getter para serieTurma
        public function getserieTurma()
        {
            return $this->serieTurma;
        }

        // Método setter para serieTurma
        public function setserieTurma($x)
        {
            $this->serieTurma = $x;

            return $this;
        }
    }

?>
