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

        // Metodo auxiliar para obtener la iunformacion de inicio de session de un usuario
        public function validarUsuario($email) {
            $pdo = new Conexion();
            $cmd = 'SELECT id, name, last_name, email, password, register_date FROM user WHERE email =:email AND active = 1';

            $parametros = array(
                ':email' => $email
            );

            $sql = $pdo->prepare($cmd);
            $sql->execute($parametros);
            $sql->setFetchMode(PDO::FETCH_OBJ);

            return $sql->fetch();
        }

        // Metodo para actualizar perfil de usuario
        public function updateData($data){
            $pdo = new Conexion();
            $cmd = '
                UPDATE user
                SET name =:name, last_name =:last_name
                WHERE id =:id
            ';

            $parametros = array(
                ':name'         => $data['firstName'],
                ':last_name'    => $data['lastName'],
                ':id'           => $data['userId'],
            );

            try {
                $sql = $pdo->prepare($cmd);
                $sql->execute($parametros);

                return TRUE;
            } catch (PDOException $e) {
                return FALSE;
            }
        }
    }