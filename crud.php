<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'create') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')";
        $conn->query($sql);
    } elseif ($action == 'read') {
        $result = $conn->query("SELECT * FROM users");
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode($users);
    } elseif ($action == 'update') {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $sql = "UPDATE users SET name='$name', email='$email' WHERE id=$id";
        $conn->query($sql);
    } elseif ($action == 'delete') {
        $id = $_POST['id'];
        $sql = "DELETE FROM users WHERE id=$id";
        $conn->query($sql);
    }
}

$conn->close();
?>
