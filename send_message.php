<!-- send_message.php -->
<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "chatcom_db";

    $conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obsługa wysyłania wiadomości
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_POST["message"]; 

    // Zapisanie wiadomości do bazy danych
    $sender = 1; 
    $receiver = 2; 
    $sql = "INSERT INTO messages (sender, receiver, content) VALUES ($sender, $receiver, '$message')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Wiadomość została wysłana.";
    } else {
        echo "Błąd podczas wysyłania wiadomości: " . $conn->error;
    }
}

$conn->close();
?>
