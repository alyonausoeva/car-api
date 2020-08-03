<?php
// необходимые HTTP-заголовки 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение необходимых файлов 
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/request.php';

// создание подключения к БД 
$database = new Database();
$db = $database->getConnection();

// инициализируем объект 
$request = new Request($db);


// получаем ключевые слова 
$model = isset($_GET['model']) ? $_GET['model'] : '';
$price = isset($_GET['price']) ? $_GET['price'] : ''; 
$param = array ($model, $price);
// запрос товаров 
$stmt = $request->search($model, $price);
$num = $stmt->rowCount();

// проверяем, найдено ли больше 0 записей 
if ($num>0) {

    // массив товаров 
    $request_arr=array();
    $request_arr["records"]=array();

    // получаем содержимое нашей таблицы 
    // fetch() быстрее чем fetchAll() 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        // извлечём строку 
        extract($row);

        $request_item=array(
            "car_id" => $car_id,
			"car_model" => $car_model,
			"seats" => $seats,
            "conditioner" => $conditioner,
            "color" => $color,
            "power_window" => $power_window,
            "car_price" => $car_price
        );

        array_push($request_arr["records"], $request_item);
    }

    // код ответа - 200 OK 
    http_response_code(200);

    // покажем товары 
    echo json_encode($request_arr);
	
	$result = [
'data' =>  json_encode($request_arr),
'date'=>date('Y-m-d H:i:s'),
'type'=>$_SERVER["REQUEST_METHOD"],
'status'=>http_response_code(200),
'body'=> json_encode($param),
'source'=>$_SERVER['REMOTE_ADDR']
];
}

else {
    // код ответа - 404 Ничего не найдено 
    http_response_code(404);

    // скажем пользователю, что товары не найдены 
    echo json_encode(array("message" => "Товары не найдены."), JSON_UNESCAPED_UNICODE);
	$result = [
'data' =>  'Товары не найдены',
'date'=>date('Y-m-d H:i:s'),
'type'=>$_SERVER["REQUEST_METHOD"],
'status'=>http_response_code(404),
'body'=> json_encode($param),
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