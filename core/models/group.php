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

        // Metodo para registrar un usuario a un grupo
        public function joinGroup($groupId, $userId){
            $pdo = new Conexion();
            $cmd = '
                INSERT INTO usergroup
                    (group_id, user_id, tipo, register_date, estatus)
                VALUES
                    (:group_id, :user_id, 2, now(), 1);
            ';

            $parametros = array(
                ':group_id' => $groupId,
                ':user_id'  => $userId
            );
            
            try {
                $sql = $pdo->prepare($cmd);
                $sql->execute($parametros);

                return $pdo->lastInsertId();
            } catch (PDOException $e) {
                return 0;
            }
        }

        // Metodo para buscar todos los grupos a los que pertenece el usuario
        public function getJoinGroups($userId){
            $pdo = new Conexion();

            $cmd = '
                SELECT group_concat(group_id) FROM usergroup where user_id =:userId
            ';

            $parametros = array(
                ':user_id'  => $userId
            );

            $sql = $pdo->prepare($cmd);
            $sql->execute($parametros);

            return $sql->fetch(PDO::FETCH_ASSOC);
        }
    }