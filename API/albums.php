<?php   
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once "../API/config/db.php";

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
        isset($_GET["idAlbums"]) ? obtenerAlbum(intval($_GET["idAlbums"])) : obtenerAlbums();
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
    $query = "SELECT idAlbums, idArtist, title, releaseDate, gender FROM Avenger_album";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($items);
}

function obtenerAlbum($idAlbums) {
    global $db;
    $query = "SELECT idAlbums, idArtist, title, releaseDate, gender FROM Avenger_album WHERE idAlbums = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $idAlbums);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($item);
}

function crearAlbum() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->idArtist) && !empty(trim($data->title)) && !empty(trim($data->gender))) {
        $query = "INSERT INTO Avenger_album (idArtist, title, releaseDate, gender) VALUES (:idArtist, :title, NOW(), :gender)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":idArtist", $data->idArtist);
        $stmt->bindParam(":title", $data->title);
        $stmt->bindParam(":gender", $data->gender);

        if($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array("mensaje" => "Álbum creado con éxito"));
        } else {
            http_response_code(500);
            echo json_encode(array("mensaje" => "No se pudo crear el álbum"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos. Todos los campos son obligatorios y no deben estar vacíos."));
    }
}

function actualizarAlbum() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->idAlbums) && !empty($data->idArtist) && !empty(trim($data->title)) && !empty(trim($data->releaseDate)) && !empty(trim($data->gender))) {
        $query = "UPDATE Avenger_album SET idArtist = :idArtist, title = :title, releaseDate = :releaseDate, gender = :gender WHERE idAlbums = :idAlbums";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":idArtist", $data->idArtist);
        $stmt->bindParam(":title", $data->title);
        $stmt->bindParam(":releaseDate", $data->releaseDate);
        $stmt->bindParam(":gender", $data->gender);
        $stmt->bindParam(":idAlbums", $data->idAlbums);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                http_response_code(200);
                echo json_encode(array("mensaje" => "Álbum actualizado con éxito"));
            } else {
                http_response_code(404);
                echo json_encode(array("mensaje" => "No se pudo actualizar el Álbum: ID no encontrado"));
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

function borrarAlbum() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    if (!empty($data->idAlbums)) {
        $query = "DELETE FROM Avenger_album WHERE idAlbums = :idAlbums";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":idAlbums", $data->idAlbums);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                http_response_code(200);
                echo json_encode(array("mensaje" => "Álbum borrado con éxito"));
            } else {
                http_response_code(404);
                echo json_encode(array("mensaje" => "No se pudo borrar el Álbum: ID no encontrado"));
            }
        } else {
            http_response_code(500);
            echo json_encode(array("mensaje" => "Error al ejecutar la consulta de eliminación"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos. El campo idAlbums es obligatorio."));
    }
}
?>