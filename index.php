<?php

include('config.php');
$message='';
if(isset($_POST['register'])) {
    
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_BCRYPT);//encriptamos la contraseña
    $birth = $_POST['birth'];

    $query = $connection->prepare("SELECT * FROM users WHERE email=:email");
    $query->bindParam("email", $email, PDO::PARAM_STR);
    $query->execute();

    if($query->rowCount() > 0) {
        $message='Email ya registrado';
    }
    
    if($query->rowCount() == 0) {
        $query = $connection->prepare("INSERT INTO users(name, surname, email, password, birthday) VALUES (:name, :surname, :email, :password_hash, :birth)");
        $query->bindParam("name", $name, PDO::PARAM_STR);
        $query->bindParam("surname", $surname, PDO::PARAM_STR);
        $query->bindParam("email", $email, PDO::PARAM_STR);
        $query->bindParam("password_hash", $password_hash, PDO::PARAM_STR);
        $query->bindParam("birth", $birth, PDO::PARAM_STR);
        $result = $query->execute();

        if ($result) {
            $message='Registrado correctamente';
        } else {
            $message='Ha ocurrido un error';
        }
    }
}

if (isset($_POST['login'])) {
    $message = '';
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = $connection->prepare("SELECT * FROM users WHERE email=:email");
    $query->bindParam("email", $email, PDO::PARAM_STR);
    $query->execute();

    $result = $query->fetch(PDO::FETCH_ASSOC);


    if (!$result) {
        $message='Usuario o la contraseña son incorrectos'; 
    } else {
        if (password_verify($password, $result['password'])) {
            $_SESSION['Id_user'] = $result['Id_user'];
            $message='Logueado correctamente';

        } else {
            $message='El usuario o la contraseña son incorrectos';


        }
    }
    //comprobamos si esta definido el id
    if(!isset($_SESSION['Id_user'])) {
        header('Location: index.php');
        exit;
    } else {
        $id_user = $_SESSION['Id_user'];
        header('Location: body.php');
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" type="text/css" href="style/style.css">

</head>
<body>
    <section class="section-login">  
        <div class="login">
            <h1 class="h1-travelers">TRAVELERS</h1>
            <form method="post" action="" name="signin">
                <input class="user-input" name="email" type="email" placeholder="Correo" autocomplete="off" required><br>
                <input class="user-input" name="password" type="password" placeholder="Contraseña" autocomplete="off" required><br>
                <a href=""><button class="user-button" type="submit" name="login" value="login">Entrar</button></a>
                <button class="open-modal" type="button">Registrarse</button>
            </form>
        </div>
        <div class="modal-register hidden">
            <button class="close-modal">X</button>
            <form action="" method="post" name="signup">
                <input class="user-input" name="name" type="text" placeholder="Nombre" autocomplete="off" required><br>
                <input class="user-input" name="surname" type="text" placeholder="Apellido" autocomplete="off" required><br>
                <input class="user-input" name="email" type="email" placeholder="Correo" autocomplete="off" required><br>
                <input class="user-input" name="password" type="password" placeholder="Contraseña" autocomplete="off" required><br>
                <input class="user-input" name="birth" type="date" placeholder="Fecha Nacimiento" autocomplete="off" required><br>
                <button class="user-button" type="submit" name="register" value="register">Enviar</button>
            </form>
        </div>
        <div class="overlay hidden"></div>
    </section>
    <script src="js/script.js"></script>
</body>
</html>