<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $auth = new DBAuthor($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  if(!isset($data->id) || !isset($data->author)){
    echo json_encode(array('message' => 'Missing Required Parameters'));
    exit();
}

  // Set ID to update
  $auth->id = $data->id;

  $auth->author = $data->author;

  $auth_arr = array(
    'id' => $auth->id,
    'author' => $auth->author
  );
  // Update post
  if($auth->update()) {
    echo json_encode($auth_arr);
  } else {
    echo json_encode(
      array('message' => 'Author Not Updated')
    );
  }

