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
                WHERE p.id =:topicId
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
        public function getComments($topicId, $parent = 0){
            $pdo = new Conexion();

            $cmd = '
                SELECT
                    c.id, c.user_id, c.post_id, c.fecha_registro, c.comentario, c.estatus,
                    concat(u.name, " ", u.last_name) AS username,
                    concat("assets/img/user/", u.id, ".jpg") AS userFoto,
                    c.parent
                FROM comentario c 
                INNER JOIN user u ON u.id = c.user_id
                WHERE c.post_id =:topicId AND c.estatus = 1 AND c.parent =:parent
            ';

            $parametros = array(
                ':topicId'  => $topicId,
                ':parent'   => $parent
            );

            $sql = $pdo->prepare($cmd);
            $sql->execute($parametros);

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        // Registrar una respuesta a un comentario de un tema
        public function setResponesComments($data){
            $pdo = new Conexion();
            $cmd = '
                INSERT INTO comentario
                    (user_id, post_id, fecha_registro, comentario, parent, estatus)
                VALUES
                    (:user_id, :post_id, now(), :comentario, :commentId, 1);
            ';

            $parametros = array(
                ':user_id'      => $data['userId'],
                ':post_id'      => $data['topicId'],
                ':comentario'   => $data['comentario'],
                ':commentId'    => $data['commentId']
            );
            
            try {
                $sql = $pdo->prepare($cmd);
                $sql->execute($parametros);

                return TRUE;
            } catch (PDOException $e) {
                return FALSE;
            }
        }

        // Metodo para setear un like al post
        public function setLike($data){
            $pdo = new Conexion();
            $cmd = '
                INSERT INTO postlike
                    (user_id, post_id, date_registered)
                VALUES
                    (:user_id, :post_id, now());
            ';

            $parametros = array(
                ':user_id'      => $data['userId'],
                ':post_id'     => $data['topicId']
            );
            
            try {
                $sql = $pdo->prepare($cmd);
                $sql->execute($parametros);

                return TRUE;
            } catch (PDOException $e) {
                return FALSE;
            }
        }

        // Metodo para consultar si ya indico un like al post
        public function getLikes($data){
            $pdo = new Conexion();
            $cmd = '
                SELECT COUNT(id) AS existe FROM postlike WHERE user_id =:user_id AND post_id =:post_id
            ';

            $parametros = array(
                ':user_id'      => $data['userId'],
                ':post_id'     => $data['topicId']
            );
            
            $sql = $pdo->prepare($cmd);
            $sql->execute($parametros);

            return $sql->fetch(PDO::FETCH_ASSOC);
        }

        // Metodo para mostrar los 10 temas mas votados
        public function getVotacion($groupId){
            $pdo = new Conexion();
            $subWhere = '';

            if($groupId > 0)
                $subWhere = ' WHERE post_id in (select id from post where grupo_id = '. $groupId . ') ';

            $cmd = '
                SELECT 
                    universo.votacion, p.titulo, g.nombre, universo.post_id, CONCAT(u.name, " ", u.last_name) as owner, u.id
                FROM 
                    (SELECT COUNT(id) AS votacion, post_id FROM postlike '.$subWhere.' GROUP by post_id ORDER BY 1 DESC LIMIT 0,10) universo
                INNER JOIN post p ON p.id = universo.post_id
                INNER JOIN grupo g ON g.id = p.grupo_id
                INNER JOIN user u ON u.id = p.user_id
            ';

            $sql = $pdo->prepare($cmd);
            $sql->execute();

            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }
    }