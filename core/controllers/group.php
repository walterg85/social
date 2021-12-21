<?php
	session_start();
	
	require_once '../models/group.php';

	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Methods: POST");

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$put_vars 	= ($_POST) ? $_POST : json_decode(file_get_contents("php://input"), TRUE);
		$groupModel	= new Groupmodel();

		if($put_vars['_method'] == 'POST'){
			$tmpResponse = $groupModel->createGroup($put_vars);

			if($tmpResponse > 0){
				$folder = 'assets/img/group';
				if( !is_dir(dirname(__FILE__, 3) . "/{$folder}") )
					mkdir(dirname(__FILE__, 3) . "/{$folder}", 0777, true);

				if (!empty($_FILES['cropImage'])){
					$filename = $tmpResponse . '.jpg';
					$tempname = $_FILES['cropImage']['tmp_name'];
					       
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
				'data' 			=> $groupModel->getGroup($put_vars['limit'])
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == '_GetUnique'){
			$data = $groupModel->getGroupId($put_vars['groupId']);
			$data['image'] = '';
			
			$foto = 'assets/img/group/'. $put_vars['groupId'] .'.jpg';
			if(file_exists('../../' . $foto))
				$data['image'] = $foto;

			$response = array(
				'codeResponse' 	=> 200,
				'data' 			=> $data
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		} else if($put_vars['_method'] == '_JoinGroup'){
			if($groupModel->joinGroup($put_vars['groupId'], $_SESSION['socialAuthData']->id) > 0)
				$_SESSION['socialAuthData']->groupsid = $groupModel->getJoinGroups( $_SESSION['socialAuthData']->id )['groupsid'];

			$response = array(
				'codeResponse' => 200
			);

			header('HTTP/1.1 200 Ok');
			header("Content-Type: application/json; charset=UTF-8");			
			exit(json_encode($response));
		}
	}

	header('HTTP/1.1 400 Bad Request');
	header("Content-Type: application/json; charset=UTF-8");
	$response = array(
		'codeResponse' => 400,
		'message' => 'Bad Request'
	);
	exit( json_encode($response) );