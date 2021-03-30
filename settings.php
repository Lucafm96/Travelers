<?php 
include('config.php');
if (isset($_SESSION['Id_user'])){
    $Id_user = $_SESSION['Id_user'];
    $query_users = $connection->prepare("SELECT * FROM users WHERE Id_user=:Id_user");
    $query_users->bindParam("Id_user", $Id_user, PDO::PARAM_INT);
    $query_users->execute();
    $result_users = $query_users->fetch(PDO::FETCH_ASSOC);
    
}else {
    header('Location: index.php');
    die();
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
                <p><strong>Cuenta:</strong> <br><?php echo $result_users['email']; ?></p>
                <a href="">Eliminar Cuenta</a>
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