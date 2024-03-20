<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: PUT');
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

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  //data is not set
  if(!isset($data->id) || !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)){
    echo json_encode(array('message' => 'Missing Required Parameters'));
    exit();
}

  // Set ID to update
  $quo->id = $data->id;
  $quo->quote = $data->quote;
  $quo->author_id = $data->author_id;
  $quo->category_id = $data->category_id;


  $auth = new DBAuthor($db);
    $cat = new DBCategory($db);
    $auth->id = $quo->author_id;
    $cat->id = $quo->category_id;

    //conduct checks on category and author ids
    $cat->read_single();
    if(!$cat->category){
        echo json_encode(array(
            'message' => 'category_id Not Found'
        ));
        exit();
    }

    $auth->read_single();
    if(!$auth->author){
        echo json_encode(array(
            'message' => 'author_id Not Found'
        ));
        exit();
    }

    $test = curl_init('http://localhost/api/quotes/?id=' . $quo->id);
    curl_setopt($test, CURLOPT_RETURNTRANSFER, true); // Set option to return the response
    $response = curl_exec($test); // Execute the request and store the response
    curl_close($test); // Close the cURL session
    $test2 = array_values(json_decode($response,true));

    if($test2[0] != $quo->id){

      echo json_encode(array(
          'message' => 'No Quotes Found'
      ));
      exit();
    }

  //Update quote
  if($quo->update()) {

    $quo_arr = array(
      'id' => $quo->id,
      'quote' => $quo->quote,
      'author_id' => $quo->author_id,
      'category_id' => $quo->category_id,
    );
    echo json_encode($quo_arr);
  } 

