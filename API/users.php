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
        crearUsuario();
        break;
    case 'PUT':
        actualizarUsuario(); 
        break;
    case 'GET':
        isset($_GET["idUsers"]) ? obtenerUsuario(intval($_GET["idUsers"])) : obtenerUsuarios();
        break;
    case 'DELETE':
        borrarUsuario();
        break;    
    case 'OPTIONS':
        http_response_code(200);
        break;
    default:
        http_response_code(400);
        echo json_encode(array("mensaje"=> "Método inválidUserso"));
        break; 
}

function obtenerUsuarios() {
    global $db;
    $query = "SELECT idUsers, name, email, password, releaseDate FROM Avenger_users";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($items);
}

function obtenerUsuario($idUsers) {
    global $db;
    $query = "SELECT idUsers, nombre, email, password, releaseDate FROM Avenger_users WHERE idUsers = ?";
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $idUsers);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($item);
}

function crearUsuario() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    $query = "INSERT INTO Avenger_users (nombre, email, password, releaseDate) VALUES (:nombre, :email, :password, :releaseDate)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":nombre", $data->nombre);
    $stmt->bindParam(":email", $data->email);
    $stmt->bindParam(":password", $data->password);
    $stmt->bindParam(":releaseDate", $data->releaseDate);

    if($stmt->execute()) {
        http_response_code(201);
        echo json_encode(array("mensaje" => "Usuario creado con éxito"));
    } else {
        http_response_code(500);
        echo json_encode(array("mensaje" => "No se pudo crear el usuario"));
    }
}

function actualizarUsuario() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    $query = "UPDATE Avenger_users SET nombre = :nombre, email = :email, password = :password, releaseDate = :releaseDate WHERE idUsers = :idUsers";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":nombre", $data->nombre);
    $stmt->bindParam(":email", $data->email);
    $stmt->bindParam(":password", $data->password);
    $stmt->bindParam(":fecha_registro", $data->releaseDate);
    $stmt->bindParam(":idUsers", $data->idUsers);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("mensaje" => "Usuario actualizado con éxito"));
    } else {
        http_response_code(500);
        echo json_encode(array("mensaje" => "No se pudo actualizar el usuario"));
    }
}

function borrarUsuario() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

    $query = "DELETE FROM Avenger_users WHERE idUsers = :idUsers";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":idUsers", $data->idUsers);

    if($stmt->execute()) {
        http_response_code(200);
        echo json_encode(array("mensaje" => "Usuario borrado con éxito"));
    } else {
        http_response_code(500);
        echo json_encode(array("mensaje" => "No se pudo borrar el usuario"));
    }
}

?>
