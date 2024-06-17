<?php   header("Access-Control-Allow-Origin: *");
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
<<<<<<< HEAD
<<<<<<< Updated upstream
        isset($_GET["id"]) ? obtenerUsuario(intval($_GET["id"])) : obtenerUsuarios();
=======
        isset($_GET["idUser"]) ? obtenerUsuario(intval($_GET["idUser"])) : obtenerUsuarios();
>>>>>>> Stashed changes
=======
        isset($_GET["idUser"]) ? obtenerUsuario(intval($_GET["idUser"])) : obtenerUsuarios();
>>>>>>> DylanRama
        break;
    case 'DELETE':
        borrarUsuario();
        break;    
    case 'OPTIONS':
        http_response_code(200);
        break;
    default:
        http_response_code(400);
        echo json_encode(array("mensaje"=> "Método inválido"));
        break; 
}

function obtenerUsuarios() {
    global $db;
<<<<<<< HEAD
<<<<<<< Updated upstream
    $query = "SELECT id, nombre, email, password, fecha_registro FROM users";
=======
    $query = "SELECT idUser, name, email, password, releaseDate FROM Avenger_user";
>>>>>>> Stashed changes
=======
    $query = "SELECT idUser, name, email, password, releaseDate FROM Avenger_user";
>>>>>>> DylanRama
    $stmt = $db->prepare($query);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($items);
}

function obtenerUsuario($idUser) {
    global $db;
<<<<<<< HEAD
<<<<<<< Updated upstream
    $query = "SELECT id, nombre, email, password, fecha_registro FROM users WHERE id = ?";
=======
    $query = "SELECT idUser, nombre, email, password, releaseDate FROM Avenger_users WHERE idUsers = ?";
>>>>>>> Stashed changes
=======
    $query = "SELECT idUser, name, email, password, releaseDate FROM Avenger_user WHERE idUser = ?";
>>>>>>> DylanRama
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $idUser);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($item);
}

function crearUsuario() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

<<<<<<< HEAD
<<<<<<< Updated upstream
    $query = "INSERT INTO users (nombre, email, password, fecha_registro) VALUES (:nombre, :email, :password, :fecha_registro)";
=======
    $query = "INSERT INTO Avenger_user (name, email, password, releaseDate) VALUES (:name, :email, :password, :releaseDate)";
>>>>>>> Stashed changes
    $stmt = $db->prepare($query);
    $stmt->bindParam(":name", $data->name);
    $stmt->bindParam(":email", $data->email);
    $stmt->bindParam(":password", $data->password);
    $stmt->bindParam(":fecha_registro", $data->fecha_registro);
=======
    if (!empty(trim($data->name)) && !empty(trim($data->email)) && !empty(trim($data->password))) {
       
        $query = "INSERT INTO Avenger_user (name, email, password, releaseDate) VALUES (:name, :email, :password, NOW())";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":name", $data->name);
        $stmt->bindParam(":email", $data->email);
        $stmt->bindParam(":password", $data->password);
>>>>>>> DylanRama


        if($stmt->execute()) {
            http_response_code(201);
            echo json_encode(array("mensaje" => "Usuario creado con éxito"));
        } else {
            http_response_code(500);
            echo json_encode(array("mensaje" => "No se pudo crear el usuario"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos. Todos los campos son obligatorios y no deben estar vacíos."));
    }
}

function actualizarUsuario() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

<<<<<<< HEAD
<<<<<<< Updated upstream
    $query = "UPDATE users SET nombre = :nombre, email = :email, password = :password, fecha_registro = :fecha_registro WHERE id = :id";
=======
    $query = "UPDATE Avenger_user SET nombre = :nombre, email = :email, password = :password, releaseDate = :releaseDate WHERE idUsers = :idUser";
>>>>>>> Stashed changes
    $stmt = $db->prepare($query);
    $stmt->bindParam(":name", $data->name);
    $stmt->bindParam(":email", $data->email);
    $stmt->bindParam(":password", $data->password);
<<<<<<< Updated upstream
    $stmt->bindParam(":fecha_registro", $data->fecha_registro);
    $stmt->bindParam(":id", $data->id);
=======
    $stmt->bindParam(":releaseDate", $data->releaseDate);
    $stmt->bindParam(":idUser", $data->idUser);
>>>>>>> Stashed changes
=======
    if (!empty(trim($data->idUser)) && !empty(trim($data->name)) && !empty(trim($data->email)) && !empty(trim($data->password)) && !empty(trim($data->releaseDate))) {
        $query = "UPDATE Avenger_user SET name = :name, email = :email, password = :password, releaseDate = :releaseDate WHERE idUser = :idUser";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":name", $data->name);
        $stmt->bindParam(":email", $data->email);
        $stmt->bindParam(":password", $data->password);
        $stmt->bindParam(":releaseDate", $data->releaseDate);
        $stmt->bindParam(":idUser", $data->idUser);
>>>>>>> DylanRama

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                http_response_code(200);
                echo json_encode(array("mensaje" => "Usuario actualizado con éxito"));
            } else {
                http_response_code(404);
                echo json_encode(array("mensaje" => "No se pudo actualizar el usuario: ID no encontrado"));
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

function borrarUsuario() {
    global $db;
    $data = json_decode(file_get_contents("php://input"));

<<<<<<< HEAD
<<<<<<< Updated upstream
    $query = "DELETE FROM users WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $data->id);
=======
    $query = "DELETE FROM Avenger_user WHERE idUser = :idUser";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":idUser", $data->idUser);
>>>>>>> Stashed changes
=======
    if (!empty($data->idUser)) {
        $query = "DELETE FROM Avenger_user WHERE idUser = :idUser";
        $stmt = $db->prepare($query);
        $stmt->bindParam(":idUser", $data->idUser);
>>>>>>> DylanRama

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                http_response_code(200);
                echo json_encode(array("mensaje" => "Usuario borrado con éxito"));
            } else {
                http_response_code(404);
                echo json_encode(array("mensaje" => "No se pudo borrar el usuario: ID no encontrado"));
            }
        } else {
            http_response_code(500);
            echo json_encode(array("mensaje" => "Error al ejecutar la consulta de eliminación"));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("mensaje" => "Datos incompletos. El campo idUser es obligatorio."));
    }
}
?>