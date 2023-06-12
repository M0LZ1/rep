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
        .icon{
            width: 50px;
            height: 50px;
        }

        .icon_small{
            width: 25px;
            height: 25px;
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
        .reverse{
            font-size:20px;
            position: absolute;
            left: 10px;
            align-self: center;
        }
        .rectangle{
            height: 100px;
            display: flex;
            background: linear-gradient(to right, #8e2de2, #4a00e0);
            color: #fff;
            font-size: 15px;
            font-family: 'Inter';
        }
        .right{
            position: absolute;
            right: 10px;
            align-self: center;
        }
        .left{
            align-self: center;
        }
        .left p{
            margin-left: 10px;
        }

        .height{
            height: 100px;
            background-color: #404040;
        }

        .contact{
        font-family: 'Inter';
        border-collapse: collapse;
        width: 100%;
        }

        .contact td {
        padding: 10px;
        text-align: left;
        }

        .contact td {
        background-color: #404040; /* Fioletowy kolor tła dla komórek */
        }
        .contact tr:nth-child(even) td {
        background-color: #d8bfd8; /* Inny odcień fioletowego dla parzystych wierszy */
        }
        .contact tr:hover td {
        background-color: #dda0dd; /* Efekt podświetlenia dla najechania na wiersz */
        }

        #add_friends{
            position: absolute;
            right: 10px;
            align-self: center;
            width: 50px;
            height: 50px;
        }

        input{
            background: linear-gradient(to right, #8e2de2, #4a00e0);
            color: #fff;
            font-size: 15px;
            font-family: 'Inter';
            border-radius: 20px;
            border: none;
            padding: 10px;
            justify-content: center;
            top: 30px;
        }

        footer{
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
        div>a{
            color: floralwhite;
            text-decoration: none;
        }
        p{
            color: floralwhite;
        }
    </style>
</head>
<body>
<?php

session_start();
$host = "localhost";
$username = "root";
$password = "";
$database = "chatcom_db";
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

if (isset($_SESSION['id_user'])) {
    echo '<p>';
    echo 'Zostałeś zalogowany jako' . ' ' . $_SESSION['user_name'] . ' ' . $_SESSION['user_surname'];
    echo '</p>';
}

echo '
<nav>
    <h1>Chat.com</h1>
</nav>';

echo '<main>';

if (isset($_POST['add_friends'])) {
    $sql = "SELECT id_user, user_name, user_surname FROM `users`;";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo '
        <table class="contact">
        <tr>
            <td>Wybierz znajomego
            <img src="./src/close.png" class="icon_small right" id="close"></td>
        </tr>
        </table>';

        while ($row = $result->fetch_assoc()) {
            $id_user = $row["id_user"];
            $user_1 = $row["user_name"] . ' ' . $row["user_surname"];

            echo '
            <form method="post" action="">
                <table class="contact">
                    <tr>
                        <td>' . $user_1 . '</td>
                        <td>
                            <input type="hidden" name="id_user" value="' . $id_user . '">
                            <input type="submit" name="add_friend" value="Dodaj">
                        </td>
                    </tr>
                </table>
            </form>
            ';
        }
    }
}

echo '<div class="height"></div>';
echo '</main>';

if (isset($_POST['add_friend'])) {
    $id_user = $_POST['id_user'];
    $current_user_id = $_SESSION['id_user'];

    // Sprawdzanie czy znajomy nie istnieje już w bazie
    $check_friend_query = "SELECT * FROM friends WHERE UserID = '$current_user_id' AND FriendID = '$id_user'";
    $check_friend_result = $conn->query($check_friend_query);

    if ($check_friend_result->num_rows > 0) {
        echo 'Ten użytkownik jest już Twoim znajomym.';
    } else {
        // Dodawanie znajomego do bazy danych
        $add_friend_query = "INSERT INTO friends (UserID, FriendID) VALUES ('$current_user_id', '$id_user')";
        if ($conn->query($add_friend_query) === true) {
            // Pobieranie informacji o dodanym znajomym
            $get_friend_query = "SELECT id_user, user_name, user_surname FROM users WHERE id_user = '$id_user'";
            $get_friend_result = $conn->query($get_friend_query);

            if ($get_friend_result->num_rows > 0) {
                $friend = $get_friend_result->fetch_assoc();
                $added_friends[] = $friend;
                $_SESSION['added_friends'] = $added_friends;
            }
        } else {
            echo 'Błąd podczas dodawania przyjaciela: ' . $conn->error;
        }
    }
}

// Wyświetlanie dodanych przyjaciół
if (isset($_SESSION['added_friends'])) {
    $added_friends = $_SESSION['added_friends'];
    echo '<div class="added-friends">';
    foreach ($added_friends as $friend) {
        $friend_name = $friend['user_name'] . ' ' . $friend['user_surname'];
        $friend_id = $friend['id_user'];
        echo '<a href="third_page.php?friend_id=' . $friend_id . '">' . $friend_name . '</a><br>';
    }
    echo '</div>';
}

?>
      <footer>
        <h3>COPYRIGHT ©</h3>
        <form method="post" action="">
            <input type="submit" name="add_friends" value="Dodaj przyjaciół" class="right">
        </form>
    </footer>

</body>
</html>