<?php
class Request {

    // подключение к базе данных и таблице 'products' 
    private $conn;
    private $table_name = "request";
	private $table_namec = "car";

    // свойства объекта 
    public $request_id;
    public $customer_id;
	public $car_id;
    public $rent_time;
    public $rent_type;
    public $insurance;
    public $payment_id;

    // конструктор для соединения с базой данных 
    public function __construct($db){
        $this->conn = $db;
    }

    function read(){

    // выбираем все записи 
    $query = "SELECT * FROM
                " . $this->table_name;
            

    // подготовка запроса 
    $stmt = $this->conn->prepare($query);

    // выполняем запрос 
    $stmt->execute();

    return $stmt;
}
// метод create - создание товаров 
function create(){

    // запрос для вставки (создания) записей 
    $query = "INSERT INTO
                " . $this->table_name . "
            SET
                request_id=:request_id, customer_id=:customer_id, car_id=:car_id, rent_time=:rent_time, rent_type=:rent_type, insurance=:insurance, payment_id=:payment_id";

    // подготовка запроса 
    $stmt = $this->conn->prepare($query);

    // очистка а
    $this->request_id=htmlspecialchars(strip_tags($this->request_id));
    $this->customer_id=htmlspecialchars(strip_tags($this->customer_id));
	$this->car_id=htmlspecialchars(strip_tags($this->car_id));
	
    $this->rent_time=htmlspecialchars(strip_tags($this->rent_time));
	$this->rent_type=htmlspecialchars(strip_tags($this->rent_type));
    $this->insurance=htmlspecialchars(strip_tags($this->insurance));
	$this->payment_id=htmlspecialchars(strip_tags($this->payment_id));

    // привязка значений 
    $stmt->bindParam(":request_id", $this->request_id);
    $stmt->bindParam(":customer_id", $this->customer_id);
    $stmt->bindParam(":car_id", $this->car_id);
	
    $stmt->bindParam(":rent_time", $this->rent_time);
	 $stmt->bindParam(":rent_type", $this->rent_type);
    $stmt->bindParam(":insurance", $this->insurance);
    $stmt->bindParam(":payment_id", $this->payment_id);
	

    // выполняем запрос 
    if ($stmt->execute()) {
        return true;
    }

    return false;
}


function update(){

    // запрос для обновления записи (товара) 
    $query = "UPDATE
                " . $this->table_name . "
            SET
                customer_id=:customer_id, 
				car_id=:car_id,
				rent_time=:rent_time,
				rent_type=:rent_type,
				insurance=:insurance,
				payment_id=:payment_id
            WHERE
                request_id=:request_id";

    // подготовка запроса 
    $stmt = $this->conn->prepare($query);

    // очистка 
    $this->request_id=htmlspecialchars(strip_tags($this->request_id));
    $this->customer_id=htmlspecialchars(strip_tags($this->customer_id));
	$this->car_id=htmlspecialchars(strip_tags($this->car_id));
	
    $this->rent_time=htmlspecialchars(strip_tags($this->rent_time));
	$this->rent_type=htmlspecialchars(strip_tags($this->rent_type));
    $this->insurance=htmlspecialchars(strip_tags($this->insurance));
	$this->payment_id=htmlspecialchars(strip_tags($this->payment_id));
	
	

    // привязываем значения 
    $stmt->bindParam(":request_id", $this->request_id);
    $stmt->bindParam(":customer_id", $this->customer_id);
    $stmt->bindParam(":car_id", $this->car_id);
	
    $stmt->bindParam(":rent_time", $this->rent_time);
	$stmt->bindParam(":rent_type", $this->rent_type);
    $stmt->bindParam(":insurance", $this->insurance);
    $stmt->bindParam(":payment_id", $this->payment_id);

    // выполняем запрос 
    if ($stmt->execute()) {
        return true;
    }

    return false;
}

function delete(){

   
    $query = "DELETE FROM " . $this->table_name . " WHERE request_id = ?";

   
    $stmt = $this->conn->prepare($query);

    
    $this->request_id=htmlspecialchars(strip_tags($this->request_id));

    
    $stmt->bindParam(1, $this->request_id);

   
    if ($stmt->execute()) {
        return true;
    }

    return false;
}

function searchr($keywords){

    // выборка по всем записям 
    $query = "SELECT
                request.request_id, request.customer_id, request.car_id, request.rent_time, request.rent_type, request.insurance, request.payment_id
            FROM
                " . $this->table_name . " 
            WHERE
                request.request_id LIKE ? OR request.customer_id LIKE ? OR request.rent_time LIKE ? ";

    // подготовка запроса 
    $stmt = $this->conn->prepare($query);

    // очистка 
    $keywords=htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";

    // привязка 
    $stmt->bindParam(1, $keywords);
	 $stmt->bindParam(2, $keywords);
	 $stmt->bindParam(3, $keywords);
    

    // выполняем запрос 
    $stmt->execute();

    return $stmt;
}
function search($model, $price){

    // выборка по всем записям 
    $query = "SELECT
`car_id`, `car_model`, `seats`, `conditioner`, `color`, `power_window`, `car_price`
FROM
`{$this->table_namec}`
WHERE
`car_model` LIKE '{$model}' OR `car_price` LIKE '{$price}'";

    // подготовка запроса 
    $stmt = $this->conn->prepare($query);

    // очистка 
    $model=htmlspecialchars(strip_tags($model));
    $model = "%{$model}%";
	 $price=htmlspecialchars(strip_tags($price));
    $price = "%{$price}%"; 

    // выполняем запрос 
    $stmt->execute();

    return $stmt;
}
function method($car_id){

    // выборка по всем записям 
    $query = "SELECT
`customer_id`, `rent_time`
FROM
`{$this->table_name}`
WHERE
`car_id` = {$car_id}";

    // подготовка запроса 
    $stmt = $this->conn->prepare($query);

    // очистка 
    $car_id=htmlspecialchars(strip_tags($car_id));
    $car_id = "%{$car_id}%"; 

    // выполняем запрос 
    $stmt->execute();

    return $stmt;
}

}
?>