<?php
session_start();
if($_SESSION['rol'] !=1 and $_SESSION['rol'] !=2)
{
    header("location: ./");
}
    include "../conn.php";

    if(!empty($_POST))
    {
        if(empty($_POST['idproveedor']))
        {
            header("location: lista_fornecedor.php");
            mysqli_close($conn);
        }
        $idproveedor = $_POST['idproveedor'];

        //$query_delete = mysqli_query($conn, "DELETE FROM usuario WHERE idusuario =$idusuario ");
        $query_delete = mysqli_query($conn,"UPDATE proveedor SET estatus = 0 WHERE codproveedor = $idproveedor ");
        mysqli_close($conn);
        if($query_delete){
            header("location: lista_fornecedor.php");
        }else{
            echo "Error al eliminar";

        }
    }

    if(empty($_REQUEST['id']))
    {
            header("location: lista_fornecedor.php");
            mysqli_close($conn);
    }else{
        
        $idproveedor = $_REQUEST['id'];

        $query = mysqli_query($conn, "SELECT* FROM proveedor WHERE codproveedor = $idproveedor ");
        mysqli_close($conn);
        $result = mysqli_num_rows($query);

        if($result > 0){
            while ($data = mysqli_fetch_array($query)){

                $proveedor = $data['proveedor'];
            }
        }else{
            header("location:lista_fornecedor.php");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Eliminar Proveedor</title>
</head>
<body>
<?php include "includes/header.php"; ?>
	<section id="container">
            <div class= "data_delete">
            <br><br>
            <h1><i class="fas fa-industry"></i></h1>
            <br><br>
                <h2>¿Esta seguro de eliminar el siguiente registro?</h2>
                <p>Nombre del Proveedor: <span><?php echo $proveedor; ?></span></p>
             
            <form method="post" action="">
                    <input type="hidden"  name="idproveedor" value="<?php echo $idproveedor; ?>">
                    <a href="lista_fornecedor.php" class="btn_cancel">Cancelar</a>
                    <input type= "submit" value="Eliminar" class="btn_ok">
            </form>    
                </div>
	</section>

	<?php include "includes/footer.php"; ?>
</body>
</html>