<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Category.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate category post object
  $cat = new DBCategory($db);

  // Get ID
  $cat->id = isset($_GET['id']) ? $_GET['id'] : die();

  // Get post
  $cat->read_single();

  // Create array
  $category_arr = array(
    'id' => $cat->id,
    'category' => $cat->category
  );


  if($category_arr['category']!=null){
  // Make JSON
  echo json_encode($category_arr);
  }else{
    echo json_encode(
      array('message' => 'category_id Not Found')
    );
  }
