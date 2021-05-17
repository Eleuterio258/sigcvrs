<?php

session_start();
if($_SESSION['rol'] !=1)
{
    header("location: ./");
}

    include "../conn.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Lista de Usuarios</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">
        <?php

            $busqueda = strtolower ($_REQUEST['busqueda']);
            if(empty($busqueda))
            {
                header("location: lista_usuario.php");
                mysqli_close($conn);
            }
        ?>
		<h1><i class="fa fa-user"></i> Lista de usuarios</h1>
        <a href="registro_usuario.php" class="btn_new"><i class="fas fa-plus-circle"></i> Registrar Usuario</a>
        
        <form action="buscar_usuario.php" method="get" class="form_search">
            <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?php echo $busqueda; ?>">
            <button type="submit" class="btn_view"><i class="fas fa-search"></i></button>
        </form>
        <table>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Ações</th>
            </tr>
        <?php
        //Paginador 
        $rol = '';
        if($busqueda == 'administrador')
        {
            $rol = " OR rol LIKE '%1' ";

        }else if($busqueda == 'supervisor'){

            $rol = "OR rol LIKE '%2' ";

        }else if($busqueda == 'vendedor'){

            $rol = "OR rol LIKE '%3' ";
        }

        $sql_registe = mysqli_query($conn, "SELECT COUNT(*) as total_registro FROM usuario 
                                                            WHERE ( idusuario LIKE '%$busqueda%' OR 
                                                                    nombre LIKE '%$busqueda%' OR 
                                                                    correo LIKE '%$busqueda%' OR 
                                                                    usuario LIKE '%$busqueda%' 
                                                                    $rol ) 
                                                                AND estatus = 1 ");
        $result_register = mysqli_fetch_array($sql_registe);
        $total_registro = $result_register['total_registro'];
 
        $por_pagina = 5;

        if(empty($_GET['pagina']))
        {
            $pagina =1;
        }else{
            $pagina = $_GET['pagina'];
        }

        $desde = ($pagina-1) * $por_pagina;
        $total_paginas = ceil($total_registro / $por_pagina);

            $query = mysqli_query($conn, "SELECT u.idusuario, u.nombre, u.correo, u.usuario, r.rol 
                                                FROM usuario u INNER JOIN rol r ON u.rol = r.idrol 
                                                WHERE 
                                                ( u.idusuario LIKE '%$busqueda%' OR 
                                                u.nombre LIKE '%$busqueda%' OR 
                                                u.correo LIKE '%$busqueda%' OR 
                                                u.usuario LIKE '%$busqueda%' OR
                                                r.rol LIKE '%$busqueda%' ) 
                                            AND 
                                            estatus = 1 ORDER BY u.idusuario ASC LIMIT $desde,$por_pagina");

            mysqli_close($conn);
            $result = mysqli_num_rows($query);
            if($result > 0){

                    while($data = mysqli_fetch_array($query)){

        ?>
                <tr>
                <td><?php echo $data["idusuario"] ?></td>
                <td><?php echo $data["nombre"] ?></td>
                <td><?php echo $data["correo"] ?></td>
                <td><?php echo $data["usuario"] ?></td>
                <td><?php echo $data["rol"] ?></td>
                <td>
                    <a class="link_edit" href="editar_usuario.php?id=<?php echo $data["idusuario"] ?>"><i class="fas fa-edit"></i>Editar</a>
                    
                    <?php if ($data["idusuario"] !=1){?>
                    |
                        <a class="link_delete" href="eliminar_confirmar_usuario.php?id=<?php echo $data["idusuario"] ?>"><i class="fas fa-trash-alt"></i> Eliminar</a>
                    <?php }?>
                </td>
            </tr>
            <?php
            }

        }
        ?>
        </table>
    <?php

        if($total_registro != 0)
        {

        
    ?>
        <div class="paginador"> 
            <ul>
                <?php
                    if($pagina != 1)
                    {
                ?>
                <li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>">|<</a></li>
                <li><a href="?pagina=<?php echo $pagina-1; ?>&busqueda=<?php echo $busqueda; ?>"><<</a></li>
            <?php
                    }
                for ($i=1; $i <= $total_paginas; $i++){
                    if($i == $pagina)
                    {
                        echo '<li class="pageSelected">'.$i.'</li>';
                    }else{
                        echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
                    }
                    
                }
                if($pagina != $total_paginas)
                {
                ?>
                <li><a href="?pagina=<?php echo $pagina + 1; ?>&busqueda=<?php echo $busqueda; ?>">>></a></li>
                <li><a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda; ?>">>|</a></li>
                <?php } ?>
    </div>
                <?php } ?>
	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>