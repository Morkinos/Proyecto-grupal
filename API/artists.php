<?php   
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../API/config/db.php"; // Asegúrate de que esta ruta es correcta

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
        isset($_GET["idArtist"]) ? obtenerArtist(intval($_GET["idArtist"])) : obtenerArtists();
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
    $query = "SELECT idArtist, name, biography, creationDate FROM Avenger_artist";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($items);
}

function obtenerArtist($idArtist) {
    global $db;
    $query = "SELECT idArtist, name, biography, creationDate FROM Avenger_artist WHERE idArtist = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $idArtist);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($item);
}

function crearArtist() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    if (!empty(trim($data->name)) && !empty(trim($data->biography)) && !empty(trim($data->creationDate))) {
        $query = "INSERT INTO Avenger_artist (name, biography, creationDate) VALUES (:name, :biography, :creationDate)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":name", $data->name);
        $stmt->bindParam(":biography", $data->biography);
        $stmt->bindParam(":creationDate", $data->creationDate);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array("mensaje" => "Artista creado con éxito"));
        } else {
            http_response_code(500);
            echo json_encode(array("mensaje" => "No se pudo crear el artista"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos. Todos los campos son obligatorios y no deben estar vacíos."));
    }
}

function actualizarArtist() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    if (!empty(trim($data->idArtist)) && !empty(trim($data->name)) && !empty(trim($data->biography)) && !empty(trim($data->creationDate))) {
        $query = "UPDATE Avenger_artist SET name = :name, biography = :biography, creationDate = :creationDate WHERE idArtist = :idArtist";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":name", $data->name);
        $stmt->bindParam(":biography", $data->biography);
        $stmt->bindParam(":creationDate", $data->creationDate);
        $stmt->bindParam(":idArtist", $data->idArtist);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                http_response_code(200);
                echo json_encode(array("mensaje" => "Artista actualizado con éxito"));
            } else {
                http_response_code(404);
                echo json_encode(array("mensaje" => "No se pudo actualizar el artista: ID no encontrado"));
            }
        } else {
            http_response_code(500);
            echo json_encode(array("mensaje" => "Error al ejecutar la consulta de actualización"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos. Todos los campos son obligatorios y no deben estar vacíos."));
    }
}

function borrarArtist() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->idArtist)) {
        $query = "DELETE FROM Avenger_artist WHERE idArtist = :idArtist";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":idArtist", $data->idArtist);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                http_response_code(200);
                echo json_encode(array("mensaje" => "Artista borrado con éxito"));
            } else {
                http_response_code(404);
                echo json_encode(array("mensaje" => "No se pudo borrar el artista: ID no encontrado"));
            }
        } else {
            http_response_code(500);
            echo json_encode(array("mensaje" => "Error al ejecutar la consulta de eliminación"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos. El campo idArtist es obligatorio."));
    }
}
?>
