<?php   

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../API/config/bd.php";

$database = new Database();
$db = $database->getConn();

$request_method = $_SERVER["REQUEST_METHOD"];

switch ($request_method) {
    case 'POST':
        crearCompra();
        break;
    case 'PUT':
        actualizarCompra(); 
        break;
    case 'GET':
        isset($_GET["idPurchase"]) ? obtenerCompra(intval($_GET["idPurchase"])) : obtenerCompras();
        break;
    case 'DELETE':
        borrarCompra();
        break;    
    case 'OPTIONS':
        http_response_code(200);
        break;
    default:
        http_response_code(400);
        echo json_encode(array("mensaje"=> "Método inválido"));
        break; 
}

function obtenerCompras() {
    global $db;
<<<<<<< HEAD
    $query = "SELECT idPurchase, idUser, idSong, fecha_compra, datePurchase FROM Avenger_purchase";
=======
    $query = "SELECT idPurchase, idUser, idSong, datePurchase, price FROM Avenger_purchases";
>>>>>>> main
    $stmt = $db->prepare($query);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($items);
}

function obtenerCompra($idPurchase) {
    global $db;
<<<<<<< HEAD
    $query = "SELECT idPurchase, idUser, idSong, fecha_compra, datePurchase FROM Avenger_purchase WHERE idPurchase = ?";
=======
    $query = "SELECT idPurchase, idUser, idSong, datePurchase, price FROM Avenger_purchases WHERE idPurchase = ?";
>>>>>>> main
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $idPurchase);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($item);
}

function crearCompra() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

<<<<<<< HEAD
    $query = "INSERT INTO Avenger_purchase (idUser, idSong, price, datePurchase) VALUES (:idUser, :cancion_id, :price, :datePurchase)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":idUser", $data->idUser);
    $stmt->bindParam(":idSong", $data->idSong);
    $stmt->bindParam(":price", $data->price);
    $stmt->bindParam(":datePurchase", $data->datePurchase);
=======
    $query = "INSERT INTO Avenger_purchases (idUser, idSong, datePurchase, price) VALUES (:idUser, :idSong, :datePurchase, :price)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":idUser", $data->idUser);
    $stmt->bindParam(":idSong", $data->idSong);
    $stmt->bindParam(":datePurchase", $data->datePurchase);
    $stmt->bindParam(":price", $data->price);
>>>>>>> main

    if($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("mensaje" => "Compra creada con éxito"));
    } else {
        http_response_code(500);
        echo json_encode(array("mensaje" => "No se pudo crear la compra"));
    }
}

function actualizarCompra() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

<<<<<<< HEAD
    $query = "UPDATE Avenger_purchase SET idUser = :idUser, idSong = :idSong, price = :price, datePurchase = :datePurchase WHERE idPurchase = :idPurchase";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":idUser", $data->idUser);
    $stmt->bindParam(":idSong", $data->idSong);
    $stmt->bindParam(":price", $data->price);
    $stmt->bindParam(":datePurchase", $data->datePurchase);
=======
    $query = "UPDATE Avenger_purchases SET idUser = :idUser, idSong = :idSong, datePurchase = :datePurchase, price = :price WHERE idPurchase = :idPurchase";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":idUser", $data->idUser);
    $stmt->bindParam(":idSong", $data->idSong);
    $stmt->bindParam(":datePurchase", $data->datePurchase);
    $stmt->bindParam(":price", $data->price);
>>>>>>> main
    $stmt->bindParam(":idPurchase", $data->idPurchase);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("mensaje" => "Compra actualizada con éxito"));
    } else {
        http_response_code(500);
        echo json_encode(array("mensaje" => "No se pudo actualizar la compra"));
    }
}

function borrarCompra() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

<<<<<<< HEAD
    $query = "DELETE FROM Avenger_purchase WHERE idPurchase = :idPurchase";
=======
    $query = "DELETE FROM Avenger_purchases WHERE idPurchase = :idPurchase";
>>>>>>> main
    $stmt = $db->prepare($query);
    $stmt->bindParam(":idPurchase", $data->idPurchase);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("mensaje" => "Compra borrada con éxito"));
    } else {
        http_response_code(500);
        echo json_encode(array("mensaje" => "No se pudo borrar la compra"));
    }
}

?>
