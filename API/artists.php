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
        crearArtist();
        break;
    case 'PUT':
        actualizarArtist(); 
        break;
    case 'GET':
        isset($_GET["id"]) ? obtenerArtist(intval($_GET["id"])) : obtenerArtists();
        break;
    case 'DELETE':
        borrarArtist();
        break;    
    case 'OPTIONS':
        http_response_code(200);
        break;
    default:
        http_response_code(400);
        echo json_encode(array("mensaje"=> "Método inválido"));
        break; 
}

function obtenerArtists() {
    global $db;
    $query = "SELECT id, idArtist, name, biography, creationDate FROM Avenger_artist";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($items);
}

function obtenerArtist($id) {
    global $db;
    $query = "SELECT id, idArtist, name, biography, creationDate FROM Avenger_artist WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($item);
}

function crearArtist() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    $query = "INSERT INTO Avenger_artist (idArtist, name, biography, creationDate) VALUES (:idArtist, :name, :biography, :creationDate)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":idArtist", $data->Avenger_artist);
    $stmt->bindParam(":name", $data->name);
    $stmt->bindParam(":biography", $data->biography);
    $stmt->bindParam(":creationDate", $data->creationDate);

    if($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("mensaje" => "Artista creado con éxito"));
    } else {
        http_response_code(500);
        echo json_encode(array("mensaje" => "No se pudo crear el artista"));
    }
}

function actualizarArtist() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    $query = "UPDATE Avenger_artist SET idArtist = :idArtist, name = :name, biography = :biography, creationDate = :creationDate WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":idArtist", $data->idArtist);
    $stmt->bindParam(":name", $data->name);
    $stmt->bindParam(":biography", $data->biography);
    $stmt->bindParam(":creationDate", $data->creationDate);
    $stmt->bindParam(":id", $data->id);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("mensaje" => "Artista actualizado con éxito"));
    } else {
        http_response_code(500);
        echo json_encode(array("mensaje" => "No se pudo actualizar el artista"));
    }
}

function borrarArtist() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    $query = "DELETE FROM Avenger_artist WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $data->id);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("mensaje" => "Artista borrado con éxito"));
    } else {
        http_response_code(500);
        echo json_encode(array("mensaje" => "No se pudo borrar el artista"));
    }
}
echo ('mamapichass');
?>