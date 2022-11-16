<?php

    session_start();

?>

<center>

<?php

    if(isset($_SESSION['userId']) && $_SESSION['userId'] === 'admin') {

        if (isset($_GET['page'])) {

            $page  = $_GET['page'];
    
        } else {
    
          $page = 1;
    
        }
    
        $limit = 10;
        $offset = ($page - 1) * $limit;
    
        $user = "myuser";
        $password = "0811";
    
        $dbh = new PDO('pgsql:host=localhost;dbname=curriculos', $user, $password);
    
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        try {
    
            $dbh->beginTransaction();
            
            if (isset($_GET['sortBy'])) {
    
                $sortBy = $_GET['sortBy'];
    
                if ($sortBy === 'firstName') {
    
                    $stmt = $dbh->prepare("SELECT * FROM users ORDER BY first_name LIMIT 10 OFFSET ?");
                    $stmt->execute(array((($page - 1) * 10)));
                    $result = $stmt->fetchAll();
    
                } elseif ($sortBy === 'lastName') {
    
                    $stmt = $dbh->prepare("SELECT * FROM users ORDER BY last_name LIMIT 10 OFFSET ?");
                    $stmt->execute(array((($page - 1) * 10)));
                    $result = $stmt->fetchAll();
    
                } elseif ($sortBy === 'email') {
    
    
                    $stmt = $dbh->prepare("SELECT * FROM users ORDER BY email LIMIT 10 OFFSET ?");
                    $stmt->execute(array((($page - 1) * 10)));
                    $result = $stmt->fetchAll();
    
                } else {
    
                    $stmt = $dbh->prepare("SELECT * FROM users LIMIT 10 OFFSET ?");
                    $stmt->execute(array(($page - 1) * 10));
                    $result = $stmt->fetchAll();
    
                }
                
            } else {
    
                $sortBy = 'default';
    
                $stmt = $dbh->prepare("SELECT * FROM users LIMIT 10 OFFSET ?");
                $stmt->execute(array(($page - 1) * 10));
                $result = $stmt->fetchAll();
    
            }
    
            ?>
    
            <table border="1">
    
                <tr>
                    <th>
                        <a href=<?= "http://localhost:8000/AdminPage.php?sortBy=firstName&page=" . $page ?>>
                        First Name</a>
                    </th>
                    <th>
                        <a href=<?= "http://localhost:8000/AdminPage.php?sortBy=lastName&page=" . $page ?>>Last Name</a>
                    </th>
                    <th>
                        <a href=<?= "http://localhost:8000/AdminPage.php?sortBy=email&page=" . $page ?>>
                        Email</a>
                    </th>
                    <th>CV</th>
                    <th>Remove</th>
                </tr>
    
            <?php
            
            foreach ($result as $users => $user) {
    
                $path = "./Curriculos/" . $user["id"] . ".pdf";
    
                ?>
    
                <tr>
                    <td> <?= $user['first_name'] ?> </td>
                    <td> <?= $user['last_name'] ?> </td>
                    <td> <?= $user['email'] ?> </td>
                    <td>
                        <a href=<?= $path ?> download> Download </a>
                    </td>
                    <td>
                        <a href=<?= "http://localhost:8000/RemoveUser.php?id=" . $user['id'] ?>>Delete</a>
                    </td>
                </tr>
    
                <?php
    
            }
    
            ?>
    
            </table>
    
            <br/>
    
            <?php
    
            if ($page == 1) {
    
                echo $page;
    
                ?>
    
                <a href=<?= "http://localhost:8000/AdminPage.php?sortBy=" . $sortBy . "&page=2" ?>> > </a>
    
                <?php
    
            } else {
    
                ?>
    
                <a href=<?= "http://localhost:8000/AdminPage.php?sortBy=" . $sortBy . "&page=" . $page-1 ?>> < </a>
    
                <?php
    
                echo $page;
    
                ?>
    
                <a href=<?= "http://localhost:8000/AdminPage.php?sortBy=" . $sortBy . "&page=" . $page+1 ?>> > </a>
    
                <?php
    
            }
            
            $dbh->commit();
    
        } catch (PDOException $e) {
    
            $dbh->rollBack(); echo $e->getMessage();
    
        }

    } else {

        ?>

        <strong>Erro! Precisa de se autenticar para aceder a esta página.</strong>

        <?php

    }

?>

</center>