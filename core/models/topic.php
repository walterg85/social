<?php
    class Topicmodel {
        public function __construct() {
            require_once '../dbConnection.php';
        }
        
        // Metodo para registrar un nuevo grupo
        public function createTopic($data){
            $pdo = new Conexion();
            $cmd = '
                INSERT INTO post
                    (titulo, contenido, fecha_registro, user_id, grupo_id, estatus)
                VALUES
                    (:titulo, :contenido, now(), :user_id, :grupo_id, 1);
            ';

            $parametros = array(
                ':titulo'       => $data['inputTitleTopic'],
                ':contenido'    => $data['inputContentTopic'],
                ':user_id'      => $data['userId'],
                ':grupo_id'     => $data['groupId']
            );
            
            try {
                $sql = $pdo->prepare($cmd);
                $sql->execute($parametros);

                return [TRUE, $pdo->lastInsertId()];
            } catch (PDOException $e) {
                return [FALSE];
            }
        }
    }