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

        // Mostrar todos los topicos de un grupo
        public function getTopics($groupId){
            $pdo = new Conexion();

            $cmd = '
                SELECT 
                    p.id, 
                    p.titulo, 
                    concat(u.name, " ", u.last_name) AS username,
                    u.id AS userId
                FROM post p 
                INNER JOIN user u ON u.id = p.user_id 
                WHERE p.grupo_id =:groupId
            ';

            $parametros = array(
                ':groupId'     => $groupId
            );

            $sql = $pdo->prepare($cmd);
            $sql->execute($parametros);

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        // Mostrar todos los topicos de la tabla
        public function getAllTopics(){
            $pdo = new Conexion();

            $cmd = '
                SELECT 
                    p.id, 
                    p.titulo, 
                    concat(u.name, " ", u.last_name) AS username,
                    u.id AS userId
                FROM post p 
                INNER JOIN user u ON u.id = p.user_id
                LIMIT 0, 20
            ';

            $sql = $pdo->prepare($cmd);
            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        // Mostrar todos los topicos de un grupo
        public function getUnique($topicId){
            $pdo = new Conexion();

            $cmd = '
                SELECT 
                    p.id, 
                    p.titulo, 
                    p.contenido,
                    p.fecha_registro,
                    concat(u.name, " ", u.last_name) AS owner
                FROM post p 
                INNER JOIN user u ON u.id = p.user_id 
                WHERE p.grupo_id =:topicId
            ';

            $parametros = array(
                ':topicId'     => $topicId
            );

            $sql = $pdo->prepare($cmd);
            $sql->execute($parametros);

            return $sql->fetch(PDO::FETCH_ASSOC);
        }

        // Registrar un comentario de un tema
        public function setComments($data){
            $pdo = new Conexion();
            $cmd = '
                INSERT INTO comentario
                    (user_id, post_id, fecha_registro, comentario, estatus)
                VALUES
                    (:user_id, :post_id, now(), :comentario, 1);
            ';

            $parametros = array(
                ':user_id'      => $data['userId'],
                ':post_id'      => $data['topicId'],
                ':comentario'   => $data['comentario']
            );
            
            try {
                $sql = $pdo->prepare($cmd);
                $sql->execute($parametros);

                return TRUE;
            } catch (PDOException $e) {
                return FALSE;
            }
        }

        // Mostrar todos los comentarios de un post
        public function getComments($topicId){
            $pdo = new Conexion();

            $cmd = '
                SELECT
                    c.id, c.user_id, c.post_id, c.fecha_registro, c.comentario, c.estatus,
                    concat(u.name, " ", u.last_name) AS username,
                    concat("assets/img/user/", u.id, ".jpg") AS userFoto
                FROM comentario c 
                INNER JOIN user u ON u.id = c.user_id
            ';

            $sql = $pdo->prepare($cmd);
            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }
    }