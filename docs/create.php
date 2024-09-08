<?php
class Moeda {
    private $idMoedas;

    // Método para definir o código ISO
    public function setidMoedas($idMoedas) {
        $this->idMoedas = $idMoedas;
    }

    // Método para criar uma nova moeda
    public function create() {
        $connect = Banco::getConnect();
        $sql = "INSERT INTO Moedas (idMoeda) VALUES (?)";
        $prepareSQL = $connect->prepare($sql);
        $prepareSQL->bind_param("s", $this->idMoedas);
        $executed = $prepareSQL->execute();
        
        if ($executed) {
            $IsoCadastrada = $connect->insert_id;
            $this->setidMoedas($IsoCadastrada);
        }
        
        return $executed;
    }
}
