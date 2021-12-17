<?php
    class Groupmodel {
        public function __construct() {
            require_once '../dbConnection.php';
        }
        
        // Metodo para registrar un nuevo grupo
        public function createGroup($data){
            $pdo = new Conexion();
            $cmd = '
                INSERT INTO grupo
                    (nombre, fecha_registro, estatus)
                VALUES
                    (:nombre, now(), 1);
            ';

            $parametros = array(
                ':nombre' => $data['inputNameGroup']          
            );
            
            try {
                $sql = $pdo->prepare($cmd);
                $sql->execute($parametros);

                return $pdo->lastInsertId();
            } catch (PDOException $e) {
                return 0;
            }
        }

        public function getGroup($limit){
            $strLimite = '';

            if($limit > 0)
                $strLimite = 'LIMIT 0, ' . $limit;

            $pdo = new Conexion();
            $cmd = '
                SELECT
                    id, nombre, fecha_registro, estatus
                FROM
                    grupo
                ORDER BY nombre ASC
                '. $strLimite .'
            ';

            $sql = $pdo->prepare($cmd);
            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        // Metodo para mostrar los detalles de un grupo
        public function getGroupId($groupId){
            $pdo = new Conexion();
            $cmd = '
                SELECT
                    id, nombre, fecha_registro, estatus
                FROM
                    grupo
                WHERE id =:id
            ';

            $parametros = array(
                ':id' => $groupId
            );

            $sql = $pdo->prepare($cmd);
            $sql->execute($parametros);

            return $sql->fetch(PDO::FETCH_ASSOC);
        }
    }