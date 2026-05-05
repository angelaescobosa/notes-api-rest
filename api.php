<?php
header("Content-Type: application/json");
include 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch($method) {

    // 🔍 GET (read all notes)
    case 'GET':
        $result = $conn->query("SELECT * FROM notes");
        $notes = [];

        while($row = $result->fetch_assoc()) {
            $notes[] = $row;
        }

        echo json_encode($notes);
        break;

    // ➕ POST (create note)
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);

        $title = $data['title'];
        $author = $data['author'];
        $body = $data['body'];
        $classification = $data['classification'];

        $sql = "INSERT INTO notes (title, author, body, classification, created_at)
                VALUES ('$title', '$author', '$body', '$classification', NOW())";

        if ($conn->query($sql)) {
            echo json_encode(["message" => "Note created"]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
        break;

    // ✏️ PUT (update note)
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"), true);

        $id = $data['id'];
        $title = $data['title'];
        $author = $data['author'];
        $body = $data['body'];
        $classification = $data['classification'];

        $sql = "UPDATE notes SET 
                title='$title',
                author='$author',
                body='$body',
                classification='$classification'
                WHERE id=$id";

        if ($conn->query($sql)) {
            echo json_encode(["message" => "Note updated"]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
        break;

    // ❌ DELETE
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'];

        $sql = "DELETE FROM notes WHERE id=$id";

        if ($conn->query($sql)) {
            echo json_encode(["message" => "Note deleted"]);
        } else {
            echo json_encode(["error" => $conn->error]);
        }
        break;
}
?>