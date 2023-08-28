<?php
header("Content-Type: application/json");
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "todo_app";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create task
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $title = $data['title'];
    $description = $data['description'];

    $sql = "INSERT INTO tasks (title, description, completed) VALUES ('$title', '$description', 0)";
    if ($conn->query($sql) === TRUE) {
        $response = ["message" => "Task created successfully"];
        echo json_encode($response);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
}

// Read tasks
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $tasks = [];
    $sql = "SELECT * FROM tasks ORDER BY id DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
        echo json_encode($tasks);
    } else {
        echo json_encode(["message" => "No tasks found"]);
    }
}



// Update task
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    //parse_str(file_get_contents("php://input"), $data);

    $id = $data['id'];
    $title = $data['title'];
    $description = $data['description'];
    $completed = $data['completed'];

    $sql = "UPDATE tasks SET title='$title', description='$description', completed='$completed' WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        $response = ["message" => "Task updated successfully"];
        echo json_encode($response);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
}


// Delete task
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    parse_str(file_get_contents("php://input"), $data);

    $id = $data['id'];

    $sql = "DELETE FROM tasks WHERE id='$id'";
    if ($conn->query($sql) === TRUE) {
        $response = ["message" => "Task deleted successfully"];
        echo json_encode($response);
    } else {
        echo json_encode(["error" => $conn->error]);
    }
}

$conn->close();
?>
