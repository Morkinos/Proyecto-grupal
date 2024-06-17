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
        crearCancion();
        break;
    case 'PUT':
        actualizarCancion(); 
        break;
    case 'GET':
        isset($_GET["idSong"]) ? obtenerCancion(intval($_GET["idSong"])) : obtenerCanciones();
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
<<<<<<< HEAD
    $query = "SELECT idSong, idAlbum, title	, duration, demo_path, full_path, price FROM Avenger_song";
=======
    $query = "SELECT idSong, idAlbum, title, duration, demo_path, full_path, price FROM Avenger_song";
>>>>>>> DylanRama
    $stmt = $db->prepare($query);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($items);
}

function obtenerCancion($idSong) {
    global $db;
<<<<<<< HEAD
    $query = "SELECT idSong, idAlbum, title, duration, demo_path, full_path, price FROM Avenger_songs WHERE idSong = ?";
=======
    $query = "SELECT idSong, idAlbum, title, duration, demo_path, full_path, price FROM Avenger_song WHERE idSong = ?";
>>>>>>> DylanRama
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $idSong);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($item);
}

function crearCancion() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

<<<<<<< HEAD
    $query = "INSERT INTO Avenger_song (idAlbum, title, duration, demo_path, full_path, price) VALUES (:idAlbum, :title, :duration, :demo_path, :full_path, :price)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":idAlbum", $data->idAlbum);
    $stmt->bindParam(":title", $data->title);
    $stmt->bindParam(":duration", $data->duration);
    $stmt->bindParam(":demo_path", $data->demo_path);
    $stmt->bindParam(":full_path", $data->full_path);
    $stmt->bindParam(":price", $data->price);
=======
    if (!empty($data->idAlbum) && !empty(trim($data->title)) && !empty(trim($data->duration)) && !empty(trim($data->demo_path)) && !empty(trim($data->full_path)) && !empty($data->price)) {
       
        $price = $data->price;
        $priceConImpuesto = $price + ($price * 0.13);
>>>>>>> DylanRama

        $query = "INSERT INTO Avenger_song (idAlbum, title, duration, demo_path, full_path, price) VALUES (:idAlbum, :title, :duration, :demo_path, :full_path, :price)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":idAlbum", $data->idAlbum);
        $stmt->bindParam(":title", $data->title);
        $stmt->bindParam(":duration", $data->duration);
        $stmt->bindParam(":demo_path", $data->demo_path);
        $stmt->bindParam(":full_path", $data->full_path);
        $stmt->bindParam(":price", $priceConImpuesto);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array("mensaje" => "Canción creada con éxito"));
        } else {
            http_response_code(500);
            echo json_encode(array("mensaje" => "No se pudo crear la canción"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos. Todos los campos son obligatorios y no deben estar vacíos."));
    }
}

function actualizarCancion() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

<<<<<<< HEAD
    $query = "UPDATE Avenger_song SET idAlbum = :idAlbum, title = :title, duration = :duration, demo_path = :demo_path, full_path = :full_path, price = :price WHERE idSong = :idSong";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":idAlbum", $data->idAlbum);
    $stmt->bindParam(":title", $data->title);
    $stmt->bindParam(":duration", $data->duration);
    $stmt->bindParam(":demo_path", $data->demo_path);
    $stmt->bindParam(":full_path", $data->full_path);
    $stmt->bindParam(":price", $data->price);
    $stmt->bindParam(":idSong", $data->idSong);
    
=======
    if (!empty($data->idSong) && !empty($data->idAlbum) && !empty(trim($data->title)) && !empty(trim($data->duration)) && !empty(trim($data->demo_path)) && !empty(trim($data->full_path)) && !empty($data->price)) {
        $query = "UPDATE Avenger_song SET idAlbum = :idAlbum, title = :title, duration = :duration, demo_path = :demo_path, full_path = :full_path, price = :price WHERE idSong = :idSong";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":idAlbum", $data->idAlbum);
        $stmt->bindParam(":title", $data->title);
        $stmt->bindParam(":duration", $data->duration);
        $stmt->bindParam(":demo_path", $data->demo_path);
        $stmt->bindParam(":full_path", $data->full_path);
        $stmt->bindParam(":price", $data->price);
        $stmt->bindParam(":idSong", $data->idSong);
>>>>>>> DylanRama

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                http_response_code(200);
                echo json_encode(array("mensaje" => "Canción actualizada con éxito"));
            } else {
                http_response_code(404);
                echo json_encode(array("mensaje" => "No se pudo actualizar la canción: ID no encontrado"));
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

function borrarCancion() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

<<<<<<< HEAD
    $query = "DELETE FROM Avenger_song WHERE idSong = :idSong";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":idSong", $data->idSong);
=======
    if (!empty($data->idSong)) {
        $query = "DELETE FROM Avenger_song WHERE idSong = :idSong";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":idSong", $data->idSong);
>>>>>>> DylanRama

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                http_response_code(200);
                echo json_encode(array("mensaje" => "Canción borrada con éxito"));
            } else {
                http_response_code(404);
                echo json_encode(array("mensaje" => "No se pudo borrar la canción: ID no encontrado"));
            }
        } else {
            http_response_code(500);
            echo json_encode(array("mensaje" => "Error al ejecutar la consulta de eliminación"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos. El campo idSong es obligatorio."));
    }
}
?>