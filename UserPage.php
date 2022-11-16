<?php 

    session_start();
    
?>

<html>

    <head>
        <title>User Page</title>
    </head>

    <form method="post">
        <input type="submit" value="Apagar Submissão"/>
    </form>

</html>

<?php

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $id = $_SESSION['userId'];

        $user = "myuser";
        $password = "0811";

        $dbh = new PDO('pgsql:host=localhost;dbname=curriculos', $user, $password);

        $stmt = $dbh->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute(array($id));

        unlink("./Curriculos/$id.pdf");

        session_destroy();

        header("Location: http://localhost:8000/UserDeleted.php");

    }

?>