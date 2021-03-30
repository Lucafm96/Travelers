<?php 
//Codigo para mostrar la foto de perfil de cada usuario en las paginas.

if (isset($_POST['submit'])) {
    $name_image = $_FILES['file']['name'];
    $size_image = $_FILES['file']['size'];
    //Ruta de la carpeta donde subir la imagen
    $url_image = $_SERVER['DOCUMENT_ROOT'] . '/Travelers/uploads/';
    move_uploaded_file($_FILES['file']['tmp_name'], $url_image . $name_image);
    $file = fopen($url_image . $name_image, "r");
    $file_byte = fread($file, $size_image);
    $file_byte = addslashes($file_byte);
    fclose($file);
    $query = $connection->prepare("UPDATE users SET pic='$file_byte' WHERE Id_user=:Id_user");
    $query->bindParam("Id_user", $Id_user, PDO::PARAM_INT);
    $query->execute();

}

?>