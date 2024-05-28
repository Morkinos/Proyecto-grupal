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
        crearAlbum();
        break;
    case 'PUT':
        actualizarAlbum(); 
        break;
    case 'GET':
        isset($_GET["id"]) ? obtenerAlbum(intval($_GET["id"])) : obtenerAlbums();
        break;
    case 'DELETE':
        borrarAlbum();
        break;    
    case 'OPTIONS':
        http_response_code(200);
        break;
    default:
        http_response_code(400);
        echo json_encode(array("mensaje"=> "Método inválido"));
        break; 
}

function obtenerAlbums() {
    global $db;
    $query = "SELECT id, artista_id, titulo, fecha_lanzamiento, genero FROM albums";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($items);
}

function obtenerAlbum($id) {
    global $db;
    $query = "SELECT id, artista_id, titulo, fecha_lanzamiento, genero FROM albums WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($item);
}

function crearAlbum() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    $query = "INSERT INTO albums (artista_id, titulo, fecha_lanzamiento, genero) VALUES (:artista_id, :titulo, :fecha_lanzamiento, :genero)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":artista_id", $data->artista_id);
    $stmt->bindParam(":titulo", $data->titulo);
    $stmt->bindParam(":fecha_lanzamiento", $data->fecha_lanzamiento);
    $stmt->bindParam(":genero", $data->genero);

    if($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("mensaje" => "Álbum creado con éxito"));
    } else {
        http_response_code(500);
        echo json_encode(array("mensaje" => "No se pudo crear el álbum"));
    }
}

function actualizarAlbum() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    $query = "UPDATE albums SET artista_id = :artista_id, titulo = :titulo, fecha_lanzamiento = :fecha_lanzamiento, genero = :genero WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":artista_id", $data->artista_id);
    $stmt->bindParam(":titulo", $data->titulo);
    $stmt->bindParam(":fecha_lanzamiento", $data->fecha_lanzamiento);
    $stmt->bindParam(":genero", $data->genero);
    $stmt->bindParam(":id", $data->id);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("mensaje" => "Álbum actualizado con éxito"));
    } else {
        http_response_code(500);
        echo json_encode(array("mensaje" => "No se pudo actualizar el álbum"));
    }
}

function borrarAlbum() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    $query = "DELETE FROM albums WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $data->id);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("mensaje" => "Álbum borrado con éxito"));
    } else {
        http_response_code(500);
        echo json_encode(array("mensaje" => "No se pudo borrar el álbum"));
    }
}

?>
