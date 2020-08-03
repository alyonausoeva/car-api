<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
// подключение базы данных и файл, содержащий объекты 
include_once '../config/database.php';
include_once '../objects/request.php';

// получаем соединение с базой данных 
$database = new Database();
$db = $database->getConnection();

// инициализируем объект 
$request = new Request($db);
 
// запрашиваем товары 
$stmt = $request->read();
$num = $stmt->rowCount();
$table="log";
// проверка, найдено ли больше 0 записей 
if ($num>0) {

    // массив товаров 
    $request_arr=array();
    $request_arr["records"]=array();

    // получаем содержимое нашей таблицы 
    // fetch() быстрее, чем fetchAll() 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){

        // извлекаем строку 
        extract($row);

        $request_item=array(
         "request_id" => $request_id,
			"customer_id" => $customer_id,
			"car_id" => $car_id,
            "rent_time" => $rent_time,
            "rent_type" => $rent_type,
            "insurance" => $insurance,
            "payment_id" => $payment_id
        );

        array_push($request_arr["records"], $request_item);
    }

    // устанавливаем код ответа - 200 OK 
    http_response_code(200);

    // выводим данные о товаре в формате JSON 
   
	$result = [
'data' => $request_arr,
'date'=>date('Y-m-d H:i:s'),
'type'=>$_SERVER["REQUEST_METHOD"],
'status'=>http_response_code(200),
'body'=>'http://api.std-237.ist.mospolytech.ru/request/read.php',
'source'=>$_SERVER['REMOTE_ADDR']
];
echo json_encode($request_arr);
}

else {

    // установим код ответа - 404 Не найдено 
    http_response_code(404);
    // сообщаем пользователю, что товары не найдены 
    
	$result = [
'data' => 'Товары не найдены',
'date'=>date('Y-m-d H:i:s'),
'type'=>$_SERVER["REQUEST_METHOD"],
'status'=>http_response_code(200),
'body'=>'http://api.std-237.ist.mospolytech.ru/request/read.php',
'source'=>$_SERVER['REMOTE_ADDR']
];
echo json_encode(array("message" => "Товары не найдены."), JSON_UNESCAPED_UNICODE);
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
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();

