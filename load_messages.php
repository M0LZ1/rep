<!-- load_messages.php -->
<?php
    $db = mysqli_connect("localhost", "root", "", "chatcom_db");
    $query = "SELECT * FROM messages ORDER BY date_of_send DESC";
    $result = mysqli_query($db, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $sender = $row['sender'];
        $receiver = $row['receiver'];
        $content = $row['content'];

        echo "<p>$sender: $content</p>";
    }
?>
