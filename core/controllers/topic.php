<?php
	session_start();
	
	require_once '../models/topic.php';

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$put_vars 	= ($_POST) ? $_POST : json_decode(file_get_contents("php://input"), TRUE);
		$topicModel	= new Topicmodel();
		$respuestas = [];

		if($put_vars['_method'] == 'POST'){
			$put_vars['userId'] = $_SESSION['socialAuthData']->id;

			$tmpResponse = $topicModel->createTopic($put_vars);

			if($tmpResponse[0]){
				$folder = 'assets/img/topic';
				if( !is_dir(dirname(__FILE__, 3) . "/{$folder}") )
					mkdir(dirname(__FILE__, 3) . "/{$folder}", 0777, true);

				if (!empty($_FILES['inputPhotoTopic'])){
					$filename = $tmpResponse[1] . ".jpg";
					$tempname = $_FILES['inputPhotoTopic']['tmp_name'];
					       
					move_uploaded_file($tempname, "../../{$folder}/{$filename}");
				}

				$response = array(
					'codeResponse' 	=> 200
				);
			}else{
				$response = array(
					'codeResponse' 	=> 0
				);
			}

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == 'GET'){
			$response = array(
				'codeResponse' 	=> 200,
				'data' 			=> $topicModel->getTopics( $put_vars['groupId'] )
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == '_GetAll'){
			$response = array(
				'codeResponse' 	=> 200,
				'data' 			=> $topicModel->getAllTopics()
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == '_Getunique'){
			$data = $topicModel->getUnique( $put_vars['topicId'] );
			$data['image'] = '';
			
			$foto = 'assets/img/topic/'. $put_vars['topicId'] .'.jpg';
			if(file_exists('../../' . $foto))
				$data['image'] = $foto;

			$response = array(
				'codeResponse' 	=> 200,
				'data' 			=> $data
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == '_Setcoments'){
			$data = array(
				'comentario'	=> $put_vars['comentario'],
				'topicId' 		=> $put_vars['topicId'],
				'userId'		=> $_SESSION['socialAuthData']->id
			);
			
			$topicModel->setComments( $data );

			$response = array(
				'codeResponse' 	=> 200
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == '_GetComments'){
			$tmpData = $topicModel->getComments( $put_vars['topicId'] );
			// $data = array();
			// foreach ($tmpData as $key => $value) {
			// 	$value['respuestas'] = $topicModel->getComments($put_vars['topicId'], $value['id']);
			// 	$data[] = $value;
			// }

			$response = array(
				'codeResponse' 	=> 200,
				'data' 			=> $tmpData
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == '_SetLikes'){
			$data = array(
				'topicId'	=> $put_vars['topicId'],
				'userId'	=> $_SESSION['socialAuthData']->id
			);

			$response = array(
				'codeResponse' 	=> 200,
				'data' 			=> $topicModel->setLike( $data )
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == '_GetLikes'){
			$data = array(
				'topicId'	=> $put_vars['topicId'],
				'userId'	=> $_SESSION['socialAuthData']->id
			);

			$response = array(
				'codeResponse' 	=> 200,
				'data' 			=> $topicModel->getLikes( $data )
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == '_GetVotacion'){
			$response = array(
				'codeResponse' 	=> 200,
				'data' 			=> $topicModel->getVotacion( $put_vars['groupId'] )
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == '_Respondercomentarios'){
			$data = array(
				'comentario'	=> $put_vars['respuesta'],
				'topicId' 		=> $put_vars['topicId'],
				'commentId' 	=> $put_vars['commentId'],
				'userId'		=> $_SESSION['socialAuthData']->id
			);
			
			$topicModel->setResponesComments( $data );

			$response = array(
				'codeResponse' 	=> 200
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == '_DeleteComment'){

			$response = array(
				'codeResponse' 	=> 200,
				'data' 			=> $topicModel->deleteComment( $put_vars['commentId'] )
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == '_EditarComentario'){
			$data = array(
				'comentario'	=> $put_vars['respuesta'],
				'commentId' 	=> $put_vars['commentId']
			);
			
			$topicModel->editComments( $data );

			$response = array(
				'codeResponse' 	=> 200
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		}
	}

	function rcvResponses($topicId, $parent){
		$tmpResponse = $topicModel->getComments( $topicId, $parent );

		foreach ($tmpResponse as $key => $value) {
			$value['respuestas'] = rcvResponses($topicId, $value['id']);
			$tmpResponse[$key] = $value;
		}

		return TRUE;
	}

	header('HTTP/1.1 400 Bad Request');
	header("Content-Type: application/json; charset=UTF-8");
	$response = array(
		'codeResponse' => 400,
		'message' => 'Bad Request'
	);
	exit( json_encode($response) );