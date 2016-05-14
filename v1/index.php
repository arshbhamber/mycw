<?php
    
    require '../vendor/autoload.php';
    require_once '../include/DbHandler.php';

    $app = new \Slim\Slim();

    
    function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }
 
    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoRespnse(400, $response);
        $app->stop();
    }
}


function echoResponse($status_code, $response) {
    
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);
 
    // setting response content type to json
    $app->contentType('application/json');
 
    echo json_encode($response);
}
 
 

    
$app->get('/hello/:name', function ($name) {
                
    echo "Hello Mr , $name";
    
});


$app->post('/newfood',function() use($app){
    
    verifyRequiredParams(array('name','price'));
    
    $response = array();
    
    //to read post parameters
    $name = $app->request->post('name');
    $price = $app->request->post('price');
    
    $db = new DbHandler();
    
    $result = $db->addItem($name, $price);
    
    if($result == 1){
        $response["error"] = false;
        $response["message"] = "Item added successfully";
        echoResponse(200, $response);
    }else if($result == 0){
        $response["error"] = true;
        $response["message"] = "Item not added";
        echoResponse(200, $response);
        
    }
    
    
});


$app->get('/foods',function(){
    
    $response = array();
    
    echo "I'm in yippeeee";
    
    $db = new DbHandler();
    
    
    $result = $db->getItems();
    $response["error"] = false;
    $response["food_items"] = array();
    
    echo "wtf echopa<br>";
 
    
     while ($foodItems = $result->fetch_assoc()) {
                $tmp = array();
                $tmp["id"] = $foodItems["id"];
                $tmp["name"] = $foodItems["name"];
                $tmp["status"] = $foodItems["price"];
                array_push($response["food_items"], $tmp);
            }
            
            echo "wtf echopa";
    
    echoResponse(200, $response);
    
    
    
});

$app->run();

?>
