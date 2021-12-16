<?php
    class Usersmodel {
        public function __construct() {
            require_once '../dbConnection.php';
        }
        
        // Metodo para registrar un nuevo usurio
        public function createUser($userData){
            $pdo = new Conexion();
            $cmd = '
                INSERT INTO user
                    (name, last_name, email, password, register_date, active)
                VALUES
                    (:name, :last_name, :email, :password, now(), 1)
            ';

            $parametros = array(
                ':name'         => $userData['inputName'],
                ':last_name'    => $userData['inputLastName'],
                ':email'        => $userData['inputMail'],
                ':password'     => $userData['inputPassword']            
            );
            
            try {
                $sql = $pdo->prepare($cmd);
                $sql->execute($parametros);

                return [TRUE, $pdo->lastInsertId()];
            } catch (PDOException $e) {
                return [FALSE, $e->getCode()];
            }
        }
    }