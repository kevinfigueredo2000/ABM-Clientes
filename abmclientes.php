<?php 
   ini_set('display_errors','1');
   ini_set('display_startup_errors','1');
   ini_set('error_reporting', E_ALL);

    $aClientes=array();

    if(file_exists("archivo.txt")){
        $strJson= file_get_contents("archivo.txt");
        $aClientes= json_decode($strJson, true);
    }else{
        $aClientes=array();
    }

    $id=isset($_GET["id"])?$_GET["id"]:'0';

    if(isset($_GET["id"]) && $_GET["id"] >=0 && isset($_GET["do"]) && $_GET["do"] == "eliminar"){
        //print_r($aClientes); exit;
        if (file_exists("files/".$aClientes[$id]["archivo1"])){
            unlink("files/".$aClientes[$id]["archivo1"]);
        }
        unset($aClientes[$id]);
        $strJson=json_encode($aClientes);
        file_put_contents("archivo.txt", $strJson);
        header("location: abmclientes.php");
    }


    if($_POST){
        //$aClientes[]=array(
        $dni=$_POST["txtDNI"]; 
        $nombre=$_POST["txtNombre"]; 
        $tel=$_POST["txtTel"];
        $correo=$_POST["txtCorreo"];
        $archivo1=["archivo1"];
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $nombreImagen = $nombreArchivo . "." . $extension;

        if($_FILES["archivo1"]["error"] === UPLOAD_ERR_OK){
            $nombreAleatorio=date("Ymdhsi");
            $archivo_tmp = $_FILES["archivo1"]["tmp_name"];
            $nombreArchivo = $_FILES["archivo1"]["name"];
            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
            $nombreImagen = $nombreArchivo . "." . $extension;
            move_uploaded_file($archivo_tmp, "imagenes/$nombreImagen");
        }
        if ($nombreImagen != "" && $aClientes[$id]["archivo1"] !=""){
            unlink("imagenes/".$aClientes[$_GET["id"]]["archivo1"]);
        }
        if($nombreImagen == ""){
            $nombreImagen = $aClientes[$_GET["id"]]["archivo1"];
        }

        if(isset($_GET["id"]) && $_GET["id"] >= 0){
            $aClientes[$id]=array("txtDNI"=>$dni,
                                "txtNombre"=>$nombre,
                                "txtTel"=>$tel,
                                "txtCorreo"=>$correo,
                                "archivo1"=>$nombreImagen);
            //si hay actualización

        }else{
            $aClientes[]=array("txtDNI"=>$dni,
                                "txtNombre"=>$nombre,
                                "txtTel"=>$tel,
                                "txtCorreo"=>$correo,
                                "archivo1"=>$nombreImagen);
                                //si es nuevo
        }
        
        $strJson=json_encode($aClientes);

        file_put_contents("archivo.txt", $strJson);
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="css/fontawesome-free-5.14.0-web/css/all.min.css">
    <link rel="stylesheet" href="css/fontawesome-free-5.14.0-web/css/fontawesome.min.css">
    <title>ABMClientes</title>
</head>
<body>
    <header>
        <h1 class="text-center">Registro de clientes</h1>
    </header>
    <main>
        <div class="container">
            <div class="row">
                <div class="col">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div>
                            <label for="txtDNI">DNI:</label><br>
                            <input type="tel" name="txtDNI" id="txtDNI" class="form-control" required value ="<?php echo isset ($_GET["id"]) && isset($aClientes[$_GET["id"]])? $aClientes[$_GET["id"]]["txtDNI"] : ""; ?>">
                        </div>
                        <div>
                            <label for="txtNombre">Nombre</label><br>
                            <input type="text" name="txtNombre" id="txtNombre" class="form-control" required value ="<?php echo isset ($_GET["id"]) && isset($aClientes[$_GET["id"]])? $aClientes[$_GET["id"]]["txtNombre"] : ""; ?>">
                        </div>
                        <div>
                            <label for="txtTel">Teléfono:</label><br>
                            <input type="tel" name="txtTel" id="txtTel" class="form-control" required value ="<?php echo isset ($_GET["id"]) && isset($aClientes[$_GET["id"]])? $aClientes[$_GET["id"]]["txtTel"] : ""; ?>">
                        </div>
                        <div>
                            <label for="txtCorreo">Correo:</label><br>
                            <input type="email" name="txtCorreo" id="txtCorreo" class="form-control" required value ="<?php echo isset ($_GET["id"]) && isset($aClientes[$_GET["id"]])? $aClientes[$_GET["id"]]["txtCorreo"] : ""; ?>">
                        </div>
                        <div class="mt-2">
                            <input type="file" name="archivo1" id="archivo1" class="mb-2" accept="jpg,jpeg,png"><br>
                        </div>
                        <div>
                            <input type="submit" value="Guardar" class="btn btn-primary mb-2">
                        </div>
                    </form>
                </div>
                <div class="col">
                    <table class="table table-hover border">
                        <thead>
                            <tr>
                                <td class="font-weight-bold">Imagen</td>
                                <td class="font-weight-bold">DNI</td>
                                <td class="font-weight-bold">Nombre</td>
                                <td class="font-weight-bold">Correo</td>
                                <td class="font-weight-bold">Acciones</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($aClientes as $pos => $cliente): ?>
                                <tr>
                                    <td> <img src="imagenes/<?php echo $cliente["archivo1"];?>" class="img-thumbnail imgfluid"></td>
                                    <td> <?php echo $cliente["txtDNI"];?> </td>
                                    <td> <?php echo $cliente["txtNombre"];?> </td>
                                    <td> <?php echo $cliente["txtCorreo"];?> </td>
                                    <td style="widht: 110 px;">  
                                        <a href="abmclientes.php?id=<?php echo $pos?>"><i class="fas fa-edit btn btn-sm btn-info"></i></a>
                                        <a href="abmclientes.php?id=<?php echo $pos?>&do=eliminar"><i class="fas fa-trash-alt btn btn-sm btn-danger"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?> 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="container text-right">
            <a href="abmclientes.php" class="mas"><i class="fas fa-user-plus btn btn-sm btn-primary"></i></a>
        </div>
    </main>
</body>
</html>