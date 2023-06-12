<!-- index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href='https://fonts.googleapis.com/css?family=Inter' rel='stylesheet'>
    <style>
        body{
            margin: 0px;
            overflow-y: auto;
            background-color: #404040;
        }

        nav{
            background-color: #141414;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family:'Inter';
            font-size: 40px;
            color: #fff;
            height: 100px;
        }

        nav h1{
            margin: 0px;
            padding: 0px;
        }

        .right{
            position: absolute;
            right: 10px;
        }

        .left{
            position: absolute;
            left: 10px;
        }

        #container{
            position: fixed;
            width: 100%;
            height: 100px;
            bottom: 0px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #141414;
            color: #fff;
        }

        #chat{
            background: linear-gradient(to right, #8e2de2, #4a00e0);
            border:none;
            border-radius: 20px;
            padding: 10px;
            height:30px;
            width: 55%;
        }

        #send{
            background: linear-gradient(to right, #8e2de2, #4a00e0);
            color: #fff;
            font-size: 15px;
            font-family: 'Inter';
            border-radius: 20px;
            border: none;
            padding: 10px 70px;
            justify-content: center;
            top: 30px;
        }
        .message {
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <nav>
        <!-- ZMIEŃ HREFA-->
        <a href="second_page.php" class="left">
            <img src="./src/back-icon.png" alt="back to menu">
        </a>
        <h1>Chat.com</h1>
    </nav>
    <main>
        <div id="container">
            <input id="chat" class="left" type="text" placeholder="Text here">
            <div id="send" class="right">Send</div>
        </div>

        <div id="chat-messages">
            <?php
                // Wyświetlanie wiadomości z bazy danych
                $db = mysqli_connect("localhost", "root", "", "chatcom_db");
                $query = "SELECT * FROM messages ORDER BY date_of_send";
                $result = mysqli_query($db, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    $sender = $row['sender'];
                    $receiver = $row['receiver'];
                    $content = $row['content'];

                    echo "<p class='message'> $content</p>";
                }
            ?>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const sendButton = document.getElementById("send");
        const chatInput = document.getElementById("chat");
        const chatbox = document.getElementById("chat-messages");

        // Funkcja obsługująca kliknięcie przycisku "Send"
        sendButton.addEventListener("click", () => {
            const message = chatInput.value.trim();

            if (message !== "") {
                sendMessage(message);
                chatInput.value = "";
            }
        });

        // Funkcja wysyłająca wiadomość do serwera
        function sendMessage(message) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "send_message.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = () => {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = xhr.responseText;
                    console.log(response);
                    displayMessage(message);
                }
            };
            xhr.send("message=" + encodeURIComponent(message));
        }

        // Funkcja wyświetlająca wiadomość na czacie
        function displayMessage(message) {
            const messageElement = document.createElement("p");
            messageElement.textContent = message;
            messageElement.classList.add('message');
            chatbox.appendChild(messageElement);

            // Przewiń do ostatniej wiadomości
            chatbox.scrollTop = chatbox.scrollHeight;
        }
    </script>
</body>
</html>
