<?php 
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once '../../config/Database.php';
  include_once '../../models/Quote.php';

  // Instantiate DB & connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate blog post object
  $quo = new DBQuote($db);

  // Get raw posted data
  $data = json_decode(file_get_contents("php://input"));

  // Set ID to update
  $quo->id = $data->id;

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

  // Delete post
  if($quo->delete()) {
    echo json_encode(
      array('id' => $quo->id)
    );
  }
