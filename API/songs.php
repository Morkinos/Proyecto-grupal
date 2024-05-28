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
        crearCancion();
        break;
    case 'PUT':
        actualizarCancion(); 
        break;
    case 'GET':
        isset($_GET["id"]) ? obtenerCancion(intval($_GET["id"])) : obtenerCanciones();
        break;
    case 'DELETE':
        borrarCancion();
        break;    
    case 'OPTIONS':
        http_response_code(200);
        break;
    default:
        http_response_code(400);
        echo json_encode(array("mensaje"=> "Método inválido"));
        break; 
}

function obtenerCanciones() {
    global $db;
    $query = "SELECT id, album_id, titulo, duracion, demo_path, full_path, precio FROM songs";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($items);
}

function obtenerCancion($id) {
    global $db;
    $query = "SELECT id, album_id, titulo, duracion, demo_path, full_path, precio FROM songs WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($item);
}

function crearCancion() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    $query = "INSERT INTO songs (album_id, titulo, duracion, demo_path, full_path, precio) VALUES (:album_id, :titulo, :duracion, :demo_path, :full_path, :precio)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":album_id", $data->album_id);
    $stmt->bindParam(":titulo", $data->titulo);
    $stmt->bindParam(":duracion", $data->duracion);
    $stmt->bindParam(":demo_path", $data->demo_path);
    $stmt->bindParam(":full_path", $data->full_path);
    $stmt->bindParam(":precio", $data->precio);

    if($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("mensaje" => "Canción creada con éxito"));
    } else {
        http_response_code(500);
        echo json_encode(array("mensaje" => "No se pudo crear la canción"));
    }
}

function actualizarCancion() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    $query = "UPDATE songs SET album_id = :album_id, titulo = :titulo, duracion = :duracion, demo_path = :demo_path, full_path = :full_path, precio = :precio WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":album_id", $data->album_id);
    $stmt->bindParam(":titulo", $data->titulo);
    $stmt->bindParam(":duracion", $data->duracion);
    $stmt->bindParam(":demo_path", $data->demo_path);
    $stmt->bindParam(":full_path", $data->full_path);
    $stmt->bindParam(":precio", $data->precio);
    $stmt->bindParam(":id", $data->id);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("mensaje" => "Canción actualizada con éxito"));
    } else {
        http_response_code(500);
        echo json_encode(array("mensaje" => "No se pudo actualizar la canción"));
    }
}

function borrarCancion() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    $query = "DELETE FROM songs WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $data->id);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("mensaje" => "Canción borrada con éxito"));
    } else {
        http_response_code(500);
        echo json_encode(array("mensaje" => "No se pudo borrar la canción"));
    }
}

?>
