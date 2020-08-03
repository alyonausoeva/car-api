<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// получаем соединение с базой данных 
include_once '../config/database.php';
// создание объекта товара 
include_once '../objects/request.php';

$database = new Database();
$db = $database->getConnection();

$request = new Request($db);
 
// получаем отправленные данные 
$data = json_decode(file_get_contents("php://input"));
 
// убеждаемся, что данные не пусты 
if (
    !empty($data->request_id) &&
	!empty($data->customer_id) &&
	!empty($data->car_id) &&
	  
    !empty($data->rent_time) &&
    !empty($data->rent_type) &&
    !empty($data->insurance)&&
	!empty($data->payment_id)
) {

    // устанавливаем значения свойств товара 
    $request->request_id = $data->request_id;
	$request->customer_id = $data->customer_id;
	$request->car_id = $data->car_id;
	
    $request->rent_time = $data->rent_time;
    $request->rent_type = $data->rent_type;
    $request->insurance = $data->insurance;
	$request->payment_id = $data->payment_id;

    // создание товара 
    if($request->create()){

        // установим код ответа - 201 создано 
        http_response_code(201);

        // сообщим пользователю 
        echo json_encode(array("message" => "Заявка была создана."), JSON_UNESCAPED_UNICODE);
		
		$result = [
'data' => 'Товар был создан',
'date'=>date('Y-m-d H:i:s'),
'type'=>$_SERVER["REQUEST_METHOD"],
'status'=>http_response_code(201),
'body'=> json_encode($data),
'source'=>$_SERVER['REMOTE_ADDR']
];

    }

    // если не удается создать товар, сообщим пользователю 
    else {

        // установим код ответа - 503 сервис недоступен 
        http_response_code(503);

        // сообщим пользователю 
        echo json_encode(array("message" => "Невозможно создать заявку."), JSON_UNESCAPED_UNICODE);
			$result = [
'data' => 'Невозможно создать заявку.',
'date'=>date('Y-m-d H:i:s'),
'type'=>$_SERVER["REQUEST_METHOD"],
'status'=>http_response_code(503),
'body'=> json_encode($data),
'source'=>$_SERVER['REMOTE_ADDR']
];
    }
}

// сообщим пользователю что данные неполные 
else {

    // установим код ответа - 400 неверный запрос 
    http_response_code(400);

    // сообщим пользователю 
    echo json_encode(array("message" => "Невозможно создать заявку. Данные неполные."), JSON_UNESCAPED_UNICODE);
$result = [
'data' => 'Невозможно создать заявку. Данные неполные.',
'date'=>date('Y-m-d H:i:s'),
'type'=>$_SERVER["REQUEST_METHOD"],
'status'=>http_response_code(400),
'body'=> json_encode($data),
'source'=>$_SERVER['REMOTE_ADDR']
];
	}

	$servername = "std-mysql";
$username = "std_237";
$password = "Qaa123321@";
$dbname = "std_237";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO log (data, date, type, status, body, sourсe)
VALUES ('". $result['data'] ."', '". $result['date'] ."', '". $result['type'] ."','". $result['status'] ."', '". $result['body'] ."', '". $result['source'] ."')";


if ($conn->query($sql) === TRUE) {
    echo "Логирование произведено"  ; 
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();

?>