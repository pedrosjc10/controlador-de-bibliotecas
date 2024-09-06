<?php
        public function create(){
            $connect = Banco::getConnect();
            $sql = "insert into Moedas (codigoISO)values("?")";
            $prepareSQL = $connect -> prepare($sql);
            $prepareSQL -> bind_param("s", $this->codigoISOs);
            $executed = $prepareSQL -> execute();
            $IsoCadastrada = $connect -> insert_iso;
            $this -> setcodigoISOs($IsoCadastrada);
            
            return $executed;
        }
?>