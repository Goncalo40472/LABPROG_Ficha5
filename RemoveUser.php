<?php

    session_start();

    $id = $_GET["id"];
     
    $user = "myuser";
    $password = "0811";

    $dbh = new PDO('pgsql:host=localhost;dbname=curriculos', $user, $password);

    $stmt = $dbh->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute(array($id));

    unlink("./Curriculos/$id.pdf");

    header("Location: http://localhost:8000/AdminPage.php");
    exit();

?>