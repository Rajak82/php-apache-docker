<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $quo = new DBQuote($db);


  // Get ID
  $quo->id = isset($_GET['id']) ? $_GET['id'] : die();


  // Get post
  $quo->read_single();

  // Create array
  $quo_arr = array(
    'id' => $quo->id,
    'quote' => $quo->quote,
    'author_name' => $quo->author_name,
    'category_name' => $quo->category_name,
  );

  if($quo_arr['quote']!=null){
    // Make JSON
    print_r(json_encode($quo_arr));
    }else{
      echo json_encode(
        array('message' => 'No Quotes Found')
      );
    }