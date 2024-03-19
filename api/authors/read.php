<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once '../../config/Database.php';
  include_once '../../models/Author.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $auth = new DBAuthor($db);

  // Blog post query
  $result = $auth->read();

  // Get row count
  $num = $result->rowCount();

  // Check if any posts
  if($num > 0) {
    // Post array
    $auth_arr = array();


    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $auth_item = array(
        'id' => $id,
        'author' => $author
      );

      // Push to "data"
      array_push($auth_arr, $auth_item);
      // array_push($posts_arr['data'], $post_item);
    }

    // Turn to JSON & output
    echo json_encode($auth_arr);

  } else {
    // No Posts
    echo json_encode(
      array('message' => 'No Authors Found')
    );
  }
