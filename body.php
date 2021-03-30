<?php 
    include('config.php');
    if (isset($_SESSION['Id_user'])){
        $Id_user = $_SESSION['Id_user'];
        $query_users = $connection->prepare("SELECT * FROM users WHERE Id_user=:Id_user");
        $query_users->bindParam("Id_user", $Id_user, PDO::PARAM_INT);
        $query_users->execute();
        $result_users = $query_users->fetch(PDO::FETCH_ASSOC);

        //sentencia para extraer los datos de la tabla post
        $query_post = $connection->prepare("SELECT * FROM post order by date_post desc");
        $query_post->bindParam("Id_user", $Id_user, PDO::PARAM_INT);
        $query_post->execute();
        $result_post = $query_post->fetchAll();
        //extraer los datos de los comentarios
        $query_comment = $connection->prepare("SELECT pic_user, email_user, comment_text FROM comment where id_postorder by date_comment desc");
        $query_comment->execute();
        $result_comment = $query_comment->fetchAll();
    }else {
        header('Location: index.php');
        die();
    }

    if (isset($_POST['send-comment'])) {
        $id_post = $_POST['id_post_comment'];
        $comment = $_POST['comment'];
        $sql_comment = $connection->prepare("INSERT INTO comment (id_post, id_user, pic_user, email_user, comment_text) 
                                    VALUES ('$id_post', '$Id_user', (SELECT pic FROM users WHERE Id_user='$Id_user'),
                                    (SELECT email FROM users WHERE Id_user='$Id_user'), '$comment')");
        $result_sql_comment = $sql_comment->execute();
        if (!$result_sql_comment) {
            echo "error";
        }
    }

    include ('profile-pic.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css">
    <script src="jquery/jquery-3.5.1.min.js" charset="utf-8"></script>
    <title>Document</title>
</head>
<body>

    <div class="wrapper collapse">
        <!--header menu-->
        <div class="header">
            <div class="header-menu">
                <a href="body.php"><div class="title">TRAVELERS</div></a>
                <div class="sidebar-btn">
                    <i class="fas fa-bars"></i>
                </div>
                <ul>
                    <li><a href="#"><i class="fas fa-search"></i></a></li>
                    <li><a href="createpost.php"><i class="fas fa-plus-circle"></i></a></li>
                    <li><a href="../Travelers/logout.php"><i class="fas fa-sign-out-alt"></i></i></a></li>
                </ul>
            </div>
        </div>
        <!--header menu end-->
        <!--sidebar menu-->
        <div class="sidebar">
            <div class="sidebar-menu">
                <div class="profile">
                    <?php echo "<img src='data:image/jpg; base64," . base64_encode($result_users['pic'])."'>" ?>
                    <p><?php echo $result_users['email']  ?></p>
                </div>
                <li class="item">
                    <a href="profile.php" class="menu-btn">
                        <i class="far fa-user"></i><span>Perfil</span>
                    </a>
                </li>
                <li class="item">
                    <a href="settings.php" class="menu-btn">
                        <i class="fas fa-cog"></i><span>Ajustes</span>
                    </a>
                </li>
                <li class="item">
                    <a href="../Travelers/logout.php" class="menu-btn">
                        <i class="fas fa-sign-out-alt"></i><span>Salir</span>
                    </a>
                </li>
            </div>
        </div>
        <!--sidebar menu end-->
        <!--Main container-->
        <div class="main-container">
            <div class="main-container-header">
                <h1>Hello <?php echo $result_users['name']; ?></h1>
            </div>
            <div class="main-container-header-post">
                <h3>Publicaciones</h3>
                    <?php foreach ($result_post as $results) { ?>
                        <div class="main-container-header-post-content">
                            <p> <?php echo "<img class='img-post-user' src='data:image/jpg; base64," . base64_encode($results['pic_user'])."'>" ?>
                                <?php echo $results['email_user'];?>
                            </p>
                            <h1><?php echo $results['title']; ?></h1>
                            <h3><?php echo $results['category']; ?></h3>
                            <p><?php echo $results['content'];?></p>
                            <?php echo "<img class='img-post' src='data:image/jpg; base64," . base64_encode($results['pic'])."'>" ?>
                            <p><?php echo $results['date_post'];?></p>
                            <p><?php echo $results['id_user'];?></p>
                            <div class="main-container-header-post-comment">
                                <form action="" method="POST">
                                    <input class="post-input-id" type="text" name="id_user_comment" value="<?php echo $results['id_user']?>">
                                    <input class="post-input-id" type="text" name="id_post_comment" value="<?php echo $results['id_post']?>">
                                    <textarea class="textarea-comment" type="text" name="comment" placeholder="Escribe tu comentario..."></textarea><br>
                                    <input type="submit" name="send-comment" value="comentar">
                                </form>
                            </div>
                        </div> 
                     <?php } ?>
            </div>
        </div>
        <!--Main container end-->
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $(".sidebar-btn").click(function(){
                $(".wrapper").toggleClass("collapse");
            });
        });
    </script>

</body>
</html>