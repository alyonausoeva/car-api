<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// подключим файл для соединения с базой и объектом Product 
include_once '../config/database.php';
include_once '../objects/request.php';

// получаем соединение с БД 
$database = new Database();
$db = $database->getConnection();

// подготовка объекта 
$request = new Request($db);

// получаем id товара 
$data = json_decode(file_get_contents("php://input"));

// установим id товара для удаления 
$request->request_id = $data->request_id;

// удаление товара 
if ($request->delete()) {

    // код ответа - 200 ok 
    http_response_code(200);

    // сообщение пользователю 
    echo json_encode(array("message" => "Товар был удалён."), JSON_UNESCAPED_UNICODE);
	$result = [
'data' => 'Товар был удален.',
'date'=>date('Y-m-d H:i:s'),
'type'=>$_SERVER["REQUEST_METHOD"],
'status'=>http_response_code(200),
'body'=> json_encode($data),
'source'=>$_SERVER['REMOTE_ADDR']
];
}

// если не удается удалить товар 
else {

    // код ответа - 503 Сервис не доступен 
    http_response_code(503);

    // сообщим об этом пользователю 
    echo json_encode(array("message" => "Не удалось удалить товар."));
	$result = [
'data' => 'Не удалось удалить товар.',
'date'=>date('Y-m-d H:i:s'),
'type'=>$_SERVER["REQUEST_METHOD"],
'status'=>http_response_code(503),
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
    echo "Логирование произведено";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>