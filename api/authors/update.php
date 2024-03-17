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

  // Set ID to update
  $auth->id = $data->id;

  $auth->author = $data->author;

  // Update post
  if($auth->update()) {
    echo json_encode(
      array('message' => 'Author Updated')
    );
  } else {
    echo json_encode(
      array('message' => 'Author Not Updated')
    );
  }

