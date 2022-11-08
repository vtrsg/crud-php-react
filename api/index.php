<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

include 'DbConnect.php';
$objDb = new DbConnect;
$conn = $objDb->connect();

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case "GET":
        $sql = "SELECT * FROM books";
        $path = explode('/', $_SERVER['REQUEST_URI']);
        if (isset($path[3]) && is_numeric($path[3])) {
            $sql .= " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $path[3]);
            $stmt->execute();
            $books = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode($books);
        break;
    case "POST":
        $book = json_decode(file_get_contents('php://input'));
        $sql = "INSERT INTO books(id, name, autor, quantidpages, price, flag, data) VALUES(null, :name, :autor, :quantidpages, :price, :flag, :data)";
        $stmt = $conn->prepare($sql);
        $data = date('Y-m-d');
        $stmt->bindParam(':name', $book->name);
        $stmt->bindParam(':autor', $book->autor);
        $stmt->bindParam(':quantidpages', $book->quantidpages);
        $stmt->bindParam(':price', $book->price);
        $stmt->bindParam(':flag', $book->flag);
        $stmt->bindParam(':data', $data);

        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record created successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to create record.'];
        }
        echo json_encode($response);
        break;

    case "PUT":
        $book = json_decode(file_get_contents('php://input'));
        $sql = "UPDATE books SET name= :name, autor= :autor, quantidpages= :quantidpages, price= :price, flag= :flag ,atualizado= :atualizado WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $atualizado = date('Y-m-d');
        $stmt->bindParam(':id', $book->id);
        $stmt->bindParam(':name', $book->name);
        $stmt->bindParam(':autor', $book->autor);
        $stmt->bindParam(':quantidpages', $book->quantidpages);
        $stmt->bindParam(':price', $book->price);
        $stmt->bindParam(':flag', $book->flag);
        $stmt->bindParam(':atualizado', $atualizado);

        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record updated successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to update record.'];
        }
        echo json_encode($response);
        break;

    case "DELETE":
        $sql = "DELETE FROM books WHERE id = :id";
        $path = explode('/', $_SERVER['REQUEST_URI']);

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $path[3]);

        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record deleted successfully.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to delete record.'];
        }
        echo json_encode($response);
        break;
}