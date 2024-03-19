<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';
  
  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $cat = new DBCategory($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  if(!isset($data->id) || !isset($data->category)){
    echo json_encode(array('message' => 'Missing Required Parameters'));
    exit();
}

  // Set ID to UPDATE
  $cat->id = $data->id;

  $cat->category = $data->category;

  $category_arr = array(
    'id' => $cat->id,
    'category' => $cat->category
  );

  // Update post
  if($cat->update()) {
    echo json_encode($category_arr);
  } else {
    echo json_encode(
      array('message' => 'Category not updated')
    );
  }
