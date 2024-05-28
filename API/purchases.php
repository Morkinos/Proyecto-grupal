<?php   

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../config/db.php";

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
        isset($_GET["id"]) ? obtenerCompra(intval($_GET["id"])) : obtenerCompras();
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
    $query = "SELECT id, usuario_id, cancion_id, fecha_compra, precio FROM purchases";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($items);
}

function obtenerCompra($id) {
    global $db;
    $query = "SELECT id, usuario_id, cancion_id, fecha_compra, precio FROM purchases WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($item);
}

function crearCompra() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    $query = "INSERT INTO purchases (usuario_id, cancion_id, fecha_compra, precio) VALUES (:usuario_id, :cancion_id, :fecha_compra, :precio)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":usuario_id", $data->usuario_id);
    $stmt->bindParam(":cancion_id", $data->cancion_id);
    $stmt->bindParam(":fecha_compra", $data->fecha_compra);
    $stmt->bindParam(":precio", $data->precio);

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

    $query = "UPDATE purchases SET usuario_id = :usuario_id, cancion_id = :cancion_id, fecha_compra = :fecha_compra, precio = :precio WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":usuario_id", $data->usuario_id);
    $stmt->bindParam(":cancion_id", $data->cancion_id);
    $stmt->bindParam(":fecha_compra", $data->fecha_compra);
    $stmt->bindParam(":precio", $data->precio);
    $stmt->bindParam(":id", $data->id);

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

    $query = "DELETE FROM purchases WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $data->id);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("mensaje" => "Compra borrada con éxito"));
    } else {
        http_response_code(500);
        echo json_encode(array("mensaje" => "No se pudo borrar la compra"));
    }
}

?>
