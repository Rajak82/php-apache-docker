<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: POST');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';
  include_once '../../models/Author.php';
  include_once '../../models/Category.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $quo = new DBQuote($db);

  //create author and category object
  $auth = new DBAuthor($db);
  $cat = new DBCategory($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  //if data is not all set, send error message and exit
  if ( !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id))
  {
      echo json_encode(array('message' => 'Missing Required Parameters'));
      exit();
  }

  $quo->quote = $data->quote;
  $quo->author_id = $data->author_id;
  $quo->category_id = $data->category_id;

  $auth->id = $data->author_id;
  $cat->id = $data->category_id;


    //Check category
    $cat->read_single();
    if(!$cat->category){
        echo json_encode(array('message' => 'category_id Not Found'));
        exit ();
    }
    //check author
    $auth->read_single();
    if(!$auth->author){
        echo json_encode(array('message' => 'author_id Not Found'));
        exit();
    }

  // Create quote
  if($quo->create()) {

    $quo->id = $db->lastInsertId();

    $test = curl_init('http://localhost/api/quotes/?id=' . $quo->id);
  curl_setopt($test, CURLOPT_RETURNTRANSFER, true); // Set option to return the response
  $response = curl_exec($test); // Execute the request and store the response
  curl_close($test); // Close the cURL session
  $test2 = json_decode($response,true);
  echo json_encode($test2);

  } else {
    echo json_encode(
      array('message' => 'Quote Not Created')
    );
  }