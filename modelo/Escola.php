<?php
    // Inclui o arquivo Banco.php, que contém funcionalidades relacionadas ao banco de dados
    require_once ("modelo/Banco.php");

    // Definição da classe Escola, que implementa a interface JsonSerializable
    class Escola implements JsonSerializable
    {
        // Propriedades privadas da classe
        private $idEscola;
        private $emailEscola;
        private $nomeEscola;
        private $senhaEscola;
        
        // Método necessário pela interface JsonSerializable para serialização do objeto para JSON
        public function jsonSerialize()
        {
            // Cria um objeto stdClass para armazenar os dados da escola
            $objetoResposta = new stdClass();
            // Define as propriedades do objeto com os valores das propriedades da classe
            $objetoResposta->idEscola = $this->idEscola;
            $objetoResposta->nomeEscola = $this->nomeEscola;
            $objetoResposta->emailEscola = $this->emailEscola;
            $objetoResposta->senhaEscola = $this->senhaEscola;

            // Retorna o objeto para serialização
            return $objetoResposta;
        }
        
        // Método de login para a escola
        public function login() {
            // Consulta SQL corrigida
            $SQL = "SELECT COUNT(*) AS idEscola, emailEscola, nomeEscola, senhaEscola 
                    FROM Escola 
                    JOIN professores ON idProfessor = professores_idProf 
                    WHERE emailEscola = ? AND senhaEscola = MD5(?);";
            // Aqui você continuaria com a lógica de preparação e execução da query
        }

        // Método para criar uma nova escola no banco de dados
        public function create()
        {
            // Obtém a conexão com o banco de dados
            $conexao = Banco::getConexao();
            // Define a consulta SQL para inserir uma nova escola  
            $SQL = "INSERT INTO Escola (emailEscola, nomeEscola, senhaEscola ) VALUES (?, ?, ?);";
            // Prepara a consulta
            $prepareSQL = $conexao->prepare($SQL);
            // Define o parâmetro da consulta com o nomeEscola da escola  
            $prepareSQL->bind_param("sss", $this->emailEscola, $this->nomeEscola, $this->senhaEscola);

            // Executa a consulta
            $executou = $prepareSQL->execute();
            // Obtém o ID da escola inserida
            $idCadastrado = $conexao->insert_id;
            // Define o ID do escola na instância atual da classe
            $this->setidEscola($idCadastrado);
            // Retorna se a operação foi executada com sucesso
            return $executou;
        }
        
        // Método para excluir uma escola do banco de dados
        public function delete()
        {
            // Obtém a conexão com o banco de dados
            $conexao = Banco::getConexao();
            // Define a consulta SQL para excluir uma escola pelo ID
            $SQL = "DELETE FROM Escola WHERE idEscola = ?;";
            // Prepara a consulta
            $prepareSQL = $conexao->prepare($SQL);
            // Define o parâmetro da consulta com o ID da escola
            $prepareSQL->bind_param("i", $this->idEscola);
            // Executa a consulta
            return $prepareSQL->execute();
        }

        // Método para atualizar os dados de uma escola no banco de dados
        public function update()
        {
            // Obtém a conexão com o banco de dados
            $conexao = Banco::getConexao();
            // Define a consulta SQL para atualizar o nome da escola pelo ID
            $SQL = "UPDATE Escola SET emailEscola = ?, nomeEscola = ?, senhaEscola = ? WHERE idEscola = ?;";
            // Prepara a consulta
            $prepareSQL = $conexao->prepare($SQL);
            // Define os parâmetros da consulta com os novos dados da escola e o ID da escola
            $prepareSQL->bind_param("sssi", $this->emailEscola, $this->nomeEscola, $this->senhaEscola, $this->idEscola);
            // Executa a consulta
            $executou = $prepareSQL->execute();
            // Retorna se a operação foi executada com sucesso
            return $executou;
        }
        
        // Método para verificar se uma escola já existe no banco de dados
        public function isEscola()
        {
            // Obtém a conexão com o banco de dados
            $conexao = Banco::getConexao();
            // Define a consulta SQL para contar quantas Escola possuem o mesmo nome
            $SQL = "SELECT COUNT(*) AS qtd FROM Escola WHERE nomeEscola = ?;";
            // Prepara a consulta
            $prepareSQL = $conexao->prepare($SQL);
            // Define o parâmetro da consulta com o nomeEscola da escola
            $prepareSQL->bind_param("s", $this->nomeEscola);
            // Executa a consulta
            $executou = $prepareSQL->execute();

            // Obtém o resultado da consulta
            $matrizTuplas = $prepareSQL->get_result();

            // Extrai o objeto da tupla
            $objTupla = $matrizTuplas->fetch_object();
            // Retorna se a quantidade de Escola encontradas é maior que zero
            return $objTupla->qtd > 0;
        }
        
        // Método para ler todos as Escola do banco de dados
        public function readAll()
        {
            // Obtém a conexão com o banco de dados
            $conexao = Banco::getConexao();
            // Define a consulta SQL para selecionar todos as Escola ordenados pelo nomeEscola
            $SQL = "SELECT * FROM Escola ORDER BY nomeEscola;";
            // Prepara a consulta
            $prepareSQL = $conexao->prepare($SQL);
            // Executa a consulta
            $executou = $prepareSQL->execute();
            // Obtém o resultado da consulta
            $matrizTuplas = $prepareSQL->get_result();
            // Inicializa um vetor para armazenar as Escola
            $vetorEscola = array();
            $i = 0;
            // Itera sobre as tuplas do resultado
            while ($tupla = $matrizTuplas->fetch_object()) {
                // Cria uma nova instância de escola para cada tupla encontrada
                $vetorEscola[$i] = new Escola();
                // Define o ID, nomeEscola e emailEscola
                $vetorEscola[$i]->setidEscola($tupla->idEscola);
                $vetorEscola[$i]->setemailEscola($tupla->emailEscola);
                $vetorEscola[$i]->setnomeEscola($tupla->nomeEscola);
                
                $i++;
            }
            // Retorna o vetor com as Escola encontradas
            return $vetorEscola;
        }
        
        // Método para ler uma escola do banco de dados com base no ID
        public function readByID()
        {
            // Obtém a conexão com o banco de dados
            $conexao = Banco::getConexao();
            // Define a consulta SQL para selecionar uma escola pelo ID
            $SQL = "SELECT * FROM Escola WHERE idEscola = ?;";
            // Prepara a consulta
            $prepareSQL = $conexao->prepare($SQL);
            // Define o parâmetro da consulta com o ID da escola
            $prepareSQL->bind_param("i", $this->idEscola);
            // Executa a consulta
            $executou = $prepareSQL->execute();
            // Obtém o resultado da consulta
            $matrizTuplas = $prepareSQL->get_result();
            // Inicializa um vetor para armazenar as Escola
            $vetorEscola = array();
            $i = 0;
            // Itera sobre as tuplas do resultado
            while ($tupla = $matrizTuplas->fetch_object()) {
                // Cria uma nova instância de escola para cada tupla encontrada
                $vetorEscola[$i] = new Escola();
                // Define o ID,emailEscola e nomeEscola
                $vetorEscola[$i]->setidEscola($tupla->idEscola);
                $vetorEscola[$i]->setemailEscola($tupla->emailEscola);
                $vetorEscola[$i]->setnomeEscola($tupla->nomeEscola);

                $i++;
            }
            // Retorna o vetor com as Escola encontradas
            return $vetorEscola;
        }

        // Método getter para idEscola
        public function getidEscola()
        {
            return $this->idEscola;
        }

        // Método setter para idEscola
        public function setidEscola($idEscola)
        {
            $this->idEscola = $idEscola;
            return $this;
        }

        //Método getter para emailEscola
        public function getemailEscola()
        {
            return $this->emailEscola;
        }

        //Método setter para emailEscola
        public function setemailEscola($emailEscola)
        {
            $this->emailEscola = $emailEscola;
            return $this;
        }

        // Método getter para nomeEscola
        public function getnomeEscola()
        {
            return $this->nomeEscola;
        }

        // Método setter para nomeEscola
        public function setnomeEscola($nomeEscola)
        {
            $this->nomeEscola = $nomeEscola;
            return $this;
        }

        public function getsenhaEscola()
        {
            return $this->senhaEscola;
        }

        // Método setter para senhaEscola
        public function setsenhaEscola($senhaEscola)
        {
            $this->senhaEscola = $senhaEscola;
            return $this;
        }
    }
?>
