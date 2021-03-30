<?php 
include('config.php');
if (isset($_SESSION['Id_user'])){
    $Id_user = $_SESSION['Id_user'];
    $query_users = $connection->prepare("SELECT * FROM users WHERE Id_user=:Id_user");
    $query_users->bindParam("Id_user", $Id_user, PDO::PARAM_INT);
    $query_users->execute();
    $result_users = $query_users->fetch(PDO::FETCH_ASSOC);

    $query_post = $connection->prepare("SELECT * FROM post WHERE id_user = (SELECT Id_User from users WHERE Id_user=:Id_user) order by date_post desc");
    $query_post->bindParam("Id_user", $Id_user, PDO::PARAM_INT);
    $query_post->execute();
    $result_post = $query_post->fetchAll();
    
}else {
    header('Location: index.php');
    die();
}

if (isset($_POST['deletepost'])) {
    $id_post = $_POST['id_post'];
    $query_delete = $connection->prepare("DELETE FROM post WHERE id_post = '$id_post'");
    $query_delete->execute();
    header('Location: profile.php');
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
                    <p><?php echo $result_users['name']  ?></p>
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
            <div class="main-container-header-profile">
                <div class="main-container-header-data">
                    <!--
                    En la etiqueta img le indicamos en el src el tipo de dato utilizado que sera image/jpg
                    Seguidamente le indicamos el sistema de codificacion que sera en este caso base64
                    Utilizamos la funcion base64_encode para convertir la cadena de $result_users['pic'] a su representacion en base64
                    -->
                    <?php echo "<img class='img-profile' src='data:image/jpg; base64," . base64_encode($result_users['pic'])."'>" ?> 
                </div>
                <div class="main-container-header-data">
                    <h1><?php echo $result_users['name']; ?> <?php echo $result_users['surname'];?> </h1>
                    <h3><?php echo $result_users['email']; ?></h3>
                    <h3><?php echo $result_users['birthday']; ?></h3>
                    <h3>Editar foto de perfil:</h3> 
                    <form action="" method="POST" enctype="multipart/form-data">
                    <input type="file" name="file">
                    <button type="submit" name="submit">UPLOAD</button>
                    </form>
                </div>
                
            </div>
            <div class="main-container-header-post">
                <h3>Publicaciones</h3>
                    <?php foreach ($result_post as $results) { ?>
                        <div class="main-container-header-post-content">
                            <h1><?php echo $results['title']; ?></h1>
                            <h3><?php echo $results['category']; ?></h3>
                            <h4><?php echo $results['place']; ?></h4>
                            <p><?php echo $results['content'];?></p>
                            <?php echo "<img class='img-post' src='data:image/jpg; base64," . base64_encode($results['pic'])."'>" ?>
                            <p><?php echo $results['date_post'];?></p>
                            <form action="" method="POST">
                                <input class="post-input-id" type="text" name="id_post" value="<?php echo $results['id_post']?>">
                                <input class="post-input" type="submit" name="deletepost" value="ELIMINAR">
                            </form>

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