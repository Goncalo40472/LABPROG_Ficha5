<?php

    session_start();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login</title>
    </head>
    <center>
    <body>

        <form method="post">

            <input type="text" name="email" placeholder="Email"/>
            <br/><br/>

            <input type="password" name="password" placeholder="Password"/>
            <br/><br/>

            <input type="submit" value="Login"/>

        </form>

    </body>
    </center>
</html>

<?php

    if (isset($_POST['email']) && isset($_POST['password'])) {

        $email = $_POST['email'];
        $password = $_POST['password'];

        if ($email === 'admin' && $password === 'admin') {

            $_SESSION['userId'] = 'admin';
            header("Location: http://localhost:8000/AdminPage.php");
            exit();

        } else {

            $user = "myuser";
            $pass = "0811";

            $dbh = new PDO('pgsql:host=localhost;dbname=curriculos', $user, $pass);

            $stmt = $dbh->prepare('SELECT * FROM users WHERE email = ? AND password = ?');

            $password = crypt($password, 'static_salt');

            $stmt->execute(array($email, $password));

            $result = $stmt->fetchAll();

            if (!empty($result)) {

                $_SESSION['userId'] = $result[0]['id'];
        
                header("Location: http://localhost:8000/UserPage.php");
                exit();

            } else {

                ?>

                <center>Utilizador ou password errados!</center>

                <?php

            }

        }

    }

?>
