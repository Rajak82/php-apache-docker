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
    'author' => $quo->author_name,
    'category' => $quo->category_name,
  );

  if($quo_arr['quote']!=null){
    // Make JSON
    $json_data = json_encode($quo_arr);

    // Decode HTML entities before echoing
    echo htmlspecialchars_decode($json_data);
    }else{
      echo json_encode(
        array('message' => 'No Quotes Found')
      );
    }