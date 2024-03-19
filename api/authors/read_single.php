<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate Author post object
  $auth = new DBAuthor($db);

  // Get ID
  $auth->id = isset($_GET['id']) ? $_GET['id'] : die();

  // Get post
  $auth->read_single();

  // Create array
  $auth_arr = array(
    'id' => $auth->id,
    'author' => $auth->author
  );


  if($auth_arr['author']!=null){
  // Make JSON
  echo json_encode($auth_arr);
}else {
  // No Author
  echo json_encode(
    array('message' => 'author_id Not Found')
  );
}
  
