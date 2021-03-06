<?php

session_start();
if($_SESSION['rol'] !=1)
{
    header("location: ./");
}
    include "../conn.php";

    if(!empty($_POST))
    {
        $alert='';
        if(empty($_POST['nombre']) || empty($_POST['correo']) || empty($_POST['usuario']) ||empty($_POST['rol']))
        {
            $alert='<p class="msg_error">Todos los campos son obligatorios.</p>';
        }else{

            $idUsuario = $_POST['id'];
            $nombre = $_POST['nombre'];
            $email = $_POST['correo'];
            $user = $_POST['usuario'];
            $clave = md5($_POST['clave']);
            $rol = $_POST['rol'];


            $query = mysqli_query($conn,"SELECT * FROM usuario 
                                                                WHERE (usuario = '$user' AND idusuario != $idUsuario) 
                                                                OR (correo = '$email' AND idusuario != $idUsuario)");
            $result = mysqli_fetch_array($query);
            

            if($result > 0){
                $alert='<p class="msg_error">El correo o el usuario ya existe.</p>';
            }else{

                if(empty($_POST['clave']))
                {
                    $sql_update = mysqli_query($conn, "UPDATE usuario
                                                            SET nombre = '$nombre', correo = '$email', usuario = '$user', rol = '$rol'
                                                            WHERE idusuario= $idUsuario");
                }else{
                    $sql_update = mysqli_query($conn, "UPDATE usuario
                                                            SET nombre = '$nombre', correo = '$email', usuario = '$user', clave= '$clave', rol = '$rol'
                                                            WHERE idusuario= $idUsuario");
                }

                if($sql_update){
                    $alert='<p class="msg_save">Usuario actualizado correctamente.</p>';
                }else{
                    $alert='<p class="msg_error">Error al actualizar el usuario.</p>';
                }
            }
    }
}

//Mostrar datos
    if(empty($_REQUEST['id']))
    {
        header('Location: lista_usuario.php');
        mysqli_close($conn);
    }
    $iduser = $_REQUEST['id'];

    $sql = mysqli_query($conn,"SELECT u.idusuario, u.nombre,u.correo,u.usuario, (u.rol) as idrol, (r.rol) as rol 
                                    FROM usuario u 
                                    INNER JOIN rol r 
                                    on u.rol = r.idrol 
                                    WHERE idusuario= $iduser  and estatus = 1 ");

    mysqli_close($conn);
    $result_sql = mysqli_num_rows($sql);

    if($result_sql == 0){
            header('Location: lista_usuario.php');
    }else{
        $option = '';
        while($data = mysqli_fetch_array($sql)){

            $iduser = $data['idusuario'];
            $nombre = $data['nombre'];
            $email = $data['correo'];
            $usuario = $data['usuario'];
            $idrol = $data['idrol'];
            $rol = $data['rol'];
        
            if($idrol == 1){
                $option = '<option value="'.$idrol.'"select>'.$rol.'</option>';
            }else if($idrol == 2){
                $option = '<option value="'.$idrol.'"select>'.$rol.'</option>';
            }else if($idrol == 3){
                $option = '<option value="'.$idrol.'"select>'.$rol.'</option>';
        }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Actualizar Usuario</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">

		<div class="form_register">
        <h1>Actualizar Usuario</h1>
        <hr> 
        <div class="alert"><?php echo isset($alert) ? $alert : ''; ?></div>

        <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo $iduser; ?>">
            <label for="nombre">Nome</label>
            <input type="text" name="nombre" id="nombre" placeholder="Nombre completo" value= "<?php echo $nombre; ?>">
            <label for="correo">Email</label>
            <input type="email" name="correo" id="correo" placeholder="Correo electronico" value= "<?php echo $email; ?>">
            <label for="usuario">Usuario</label>
            <input type="text" name="usuario" id="usuario" placeholder="Usuario" value= "<?php echo $usuario; ?>">
            <label for="clave">Senha</label>
            <input type="password" name="clave" id="clave" placeholder="Clave de acceso">
            <label for="rol">Tipo Usuario</label>

            <?php
                     include "../conn.php";
                    $query_rol= mysqli_query($conn, "SELECT * FROM rol");
                    mysqli_close($conn);
                    $result_rol = mysqli_num_rows($query_rol);

            ?>

            <select name="rol" id="rol" class="notItemOne">
            <?php
                echo $option;
            if($result_rol > 0)
                    {
                    while ($rol = mysqli_fetch_array($query_rol)){
                ?>
                    <option value="<?php echo $rol["idrol"]; ?>"><?php  echo $rol["rol"] ?></option>
            <?php
                        }
                    }
            ?>
            </select>
            <input type="submit" value="Actualizar Usuario" class="btn_save">
            
        </form>

    	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>