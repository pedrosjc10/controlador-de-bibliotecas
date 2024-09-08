<?php
require_once ("modelo/Banco.php");
require_once ("modelo/Moeda.php");
require_once ("modelo/Transacao.php");

class Usuario implements JsonSerializable
{
    private $idusuario;
    private $nome;
    private $moedaPref;
    private $senha;
    private $email;
    private $transacoes_idtransacoes;
    private $moedas_idmoedas;

    public function __construct()
    {
        $this->moedaPref = new Moeda();
        $this->transacao = new Transacao();
    }

    public function jsonSerialize()
    {
        $respostaPadrao = new stdClass();
        $respostaPadrao->idusuario = $this->idusuario;
        $respostaPadrao->nome = $this->nome;
        $respostaPadrao->moedaPref = $this->moedaPref;
        $respostaPadrao->senha = $this->senha;
        $respostaPadrao->email = $this->email;
        $respostaPadrao->transacoes_idtransacoes = $this->transacoes_idtransacoes;
        $respostaPadrao->moedas_idmoedas = $this->moedas_idmoedas;
        return $respostaPadrao;
    }

    public function create()
    {
        $conexao = Banco::getConnect();
        $SQL = "INSERT INTO usuario (nome, email, senha, moedas_idmoedas) VALUES (?, ?, ?, ?)";
        $prepararSQL = $conexao->prepare($SQL);
        $prepararSQL->bind_param("sssi", $this->nome, $this->email, $this->senha, $this->moedas_idmoedas);
        $executar = $prepararSQL->execute();
        $idCadastrado = $conexao->insert_id;
        $this->setIdusuario($idCadastrado);
        $prepararSQL->close();
        return $executar;
    }

    public function update()
    {
        $conexao = Banco::getConnect();
        $SQL = "UPDATE usuario SET nome = ?, email = ?, senha = ?, moedas_idmoedas = ? WHERE idusuario = ?";
        $prepararSQL = $conexao->prepare($SQL);
        $prepararSQL->bind_param("sssii", $this->nome, $this->email, $this->senha, $this->moedas_idmoedas, $this->idusuario);
        $executar = $prepararSQL->execute();
        $prepararSQL->close();
        return $executar;
    }

    public function delete()
    {
        $conexao = Banco::getConnect();
        $SQL = "DELETE FROM usuario WHERE idusuario = ?";
        $prepararSQL = $conexao->prepare($SQL);
        $prepararSQL->bind_param("i", $this->idusuario);
        $executar = $prepararSQL->execute();
        $prepararSQL->close();
        return $executar;
    }

    public function readById()
    {
        $conexao = Banco::getConnect();
        $SQL = "SELECT * FROM usuario WHERE idusuario = ?";
        $prepararSQL = $conexao->prepare($SQL);
        $prepararSQL->bind_param("i", $this->idusuario);
        $prepararSQL->execute();
        $matrizTuplas = $prepararSQL->get_result();
        if ($tupla = $matrizTuplas->fetch_object()) {
            $this->setIdusuario($tupla->idusuario);
            $this->setNome($tupla->nome);
            $this->setEmail($tupla->email);
            $this->setSenha($tupla->senha);
            $this->setMoedas_idmoedas($tupla->moedas_idmoedas);
        }
        return $this;
    }

    public function readAll()
    {
        $conexao = Banco::getConnect();
        $SQL = "SELECT * FROM usuario ORDER BY nome";
        $prepararSQL = $conexao->prepare($SQL);
        $prepararSQL->execute();
        $matrizTuplas = $prepararSQL->get_result();
        $usuarios = [];
        while ($tupla = $matrizTuplas->fetch_object()) {
            $usuario = new Usuario();
            $usuario->setIdusuario($tupla->idusuario);
            $usuario->setNome($tupla->nome);
            $usuario->setEmail($tupla->email);
            $usuario->setSenha($tupla->senha);
            $usuario->setMoedas_idmoedas($tupla->moedas_idmoedas);
            $usuarios[] = $usuario;
        }
        return $usuarios;
    }

    // Getters e Setters
    // ...


    /**
     * Get the value of idusuario
     */ 
    public function getIdusuario()
    {
        return $this->idusuario;
    }

    /**
     * Set the value of idusuario
     *
     * @return  self
     */ 
    public function setIdusuario($idusuario)
    {
        $this->idusuario = $idusuario;

        return $this;
    }

    /**
     * Get the value of nome
     */ 
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set the value of nome
     *
     * @return  self
     */ 
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get the value of moedaPref
     */ 
    public function getMoedaPref()
    {
        return $this->moedaPref;
    }

    /**
     * Set the value of moedaPref
     *
     * @return  self
     */ 
    public function setMoedaPref($moedaPref)
    {
        $this->moedaPref = $moedaPref;

        return $this;
    }

    /**
     * Get the value of senha
     */ 
    public function getSenha()
    {
        return $this->senha;
    }

    /**
     * Set the value of senha
     *
     * @return  self
     */ 
    public function setSenha($senha)
    {
        $this->senha = $senha;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of transacoes_idtransacoes
     */ 
    public function getTransacoes_idtransacoes()
    {
        return $this->transacoes_idtransacoes;
    }

    /**
     * Set the value of transacoes_idtransacoes
     *
     * @return  self
     */ 
    public function setTransacoes_idtransacoes($transacoes_idtransacoes)
    {
        $this->transacoes_idtransacoes = $transacoes_idtransacoes;

        return $this;
    }

    /**
     * Get the value of moedas_idmoedas
     */ 
    public function getMoedas_idmoedas()
    {
        return $this->moedas_idmoedas;
    }

    /**
     * Set the value of moedas_idmoedas
     *
     * @return  self
     */ 
    public function setMoedas_idmoedas($moedas_idmoedas)
    {
        $this->moedas_idmoedas = $moedas_idmoedas;

        return $this;
    }
}
