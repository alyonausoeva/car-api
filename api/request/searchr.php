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
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";

// запрос товаров 
$stmt = $request->searchr($keywords);
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

    // код ответа - 200 OK 
    http_response_code(200);

    // покажем товары 
    echo json_encode($request_arr);
}

else {
    // код ответа - 404 Ничего не найдено 
    http_response_code(404);

    // скажем пользователю, что товары не найдены 
    echo json_encode(array("message" => "Товары не найдены."), JSON_UNESCAPED_UNICODE);
}
?>