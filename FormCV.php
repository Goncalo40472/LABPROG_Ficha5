<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Formulário CV</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' type='text/css' media='screen' href='FormCV.css'>
</head>
<body>

    <center>

    <form method="post" enctype="multipart/form-data">

        <input type="password" name="password" placeholder="Password"/>
        <br/><br/>

        <input type="text" name="email" placeholder="Email"/>
        <br/><br/>

        <input type="text" name="firstName" placeholder="First name"/>
        <input type="text" name="lastName" placeholder="Last name"/>
        <br/><br/>

        <input type="file" name="cv" accept=".pdf"/>
        <br/><br/>

        <input type="submit" value="Submeter CV"/>

    </form>

    </center>

</body>
</html>

<center>

<?php

    $user = "myuser";
    $password = "0811";

    $dbh = new PDO('pgsql:host=localhost;dbname=curriculos', $user, $password);

    if (!empty($_POST["password"]) && !empty($_POST["firstName"])
       && !empty($_POST["lastName"]) && !empty($_POST["email"])
       && is_uploaded_file($_FILES["cv"]["tmp_name"]) && $_FILES["cv"]["type"] === "application/pdf") {

        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $email = $_POST["email"];
        $pass = crypt($_POST["password"], 'static_salt');

        $stmt = $dbh->prepare('SELECT * FROM users WHERE first_name = ? AND last_name = ?
                               AND email = ? AND password = ?');
        $stmt->execute(array($firstName, $lastName, $email, $pass));
        $result = $stmt->fetchAll();

        if (sizeof($result) > 0) {

            ?>

            <br/>
            <br/>
            Submissão negada! Já existe outra submissão feita pelo utilizador!

            <?php

        } else {

            $stmt = $dbh->prepare('INSERT INTO Users (first_name, last_name, email, password) VALUES (?,?,?,?)');
            $stmt->execute(array($firstName, $lastName, $email, $pass));

            $stmt = $dbh->prepare('SELECT id FROM users WHERE first_name = ? AND last_name = ?
                                   AND email = ? AND password = ?');
            $stmt->execute(array($firstName, $lastName, $email, $pass));
            $id = $stmt->fetch();

            $id = $id["id"];
            move_uploaded_file($_FILES["cv"]["tmp_name"], "./Curriculos/$id.pdf");

            ?>
            
            <br/>
            <br/>
            Submissão feita com sucesso!

            <?php

        }

    }

?>

</center>