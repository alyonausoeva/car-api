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

$car_id=$_POST['car_id'];

// запрашиваем товары 
$stmt = $request->method($car_id);
$num = $stmt->rowCount();

// проверка, найдено ли больше 0 записей 
if ($num>0) {

    // массив товаров 
    $request_arr=array();

    // получаем содержимое нашей таблицы 
    // fetch() быстрее, чем fetchAll() 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        $request_arr=$row;
    }

    // устанавливаем код ответа - 200 OK 
    http_response_code(200);
	
  if( $curl = curl_init() ) {
    curl_setopt($curl, CURLOPT_URL, 'http://persons.std-247.ist.mospolytech.ru/person/' . $request_arr['customer_id']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    $out = (array) json_decode(curl_exec($curl));
    //var_dump($out);
	$dataArray = array();
	$dataArray['customer_id'] = $request_arr['customer_id'];
	$dataArray['rent_time'] = $request_arr['rent_time'];
	$dataArray = array_merge($dataArray, $out);
	
	
    curl_close($curl);
  }
  
  echo json_encode($dataArray);
 $result = [
'data' => json_encode($dataArray) ,
'date'=>date('Y-m-d H:i:s'),
'type'=>$_SERVER["REQUEST_METHOD"],
'status'=>http_response_code(200),
'body'=> json_encode($car_id),
'source'=>$_SERVER['REMOTE_ADDR']
];
}
else {

    // установим код ответа - 404 Не найдено 
    http_response_code(404);

    // сообщаем пользователю, что товары не найдены 
    echo json_encode(array("message" => "Заявка не найдена"), JSON_UNESCAPED_UNICODE);
	$result = [
'data' =>  'заявка не найдена',
'date'=>date('Y-m-d H:i:s'),
'type'=>$_SERVER["REQUEST_METHOD"],
'status'=>http_response_code(404),
'body'=> json_encode($car_id),
'source'=>$_SERVER['REMOTE_ADDR']
];
}

//echo json_encode($request_arr);


  
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