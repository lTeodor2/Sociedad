<?php
session_start();
require '../conn/acciones_base.php';
require '../conn/constantes.php';
$db=conectaBD();
if (isset($_REQUEST["opcion"])){
    $_SESSION["opcion"]=$_REQUEST["opcion"];
}
$mensaje="";
if (isset($_SESSION["opcion"])){
    $opcion=$_SESSION["opcion"];
    $db=conectaBD();
}
//Al dar a inicio se destruye la sesiÃ³n..
if (isset($_REQUEST["salir"])){
    header("location:../index.php?opcion=login");
}
if (isset($_REQUEST["editar"])){
    header("location:perfil.php?opcion=editar");
}
if (isset($_REQUEST["listar"])){
    header("location:perfil.php?opcion=listar");
}
if (isset($_REQUEST["nuevo"])){
    header("location:perfil.php?opcion=nuevo");
}
if(isset($_SESSION['usuario'])){
    $_REQUEST['usuario']=$_SESSION['usuario'];
}
if (isset($_REQUEST["guardar_editado"])){
    if ($_REQUEST["pass"]==$_REQUEST["pass2"]){
        editar_usuario($db, $_REQUEST["editar"], $_REQUEST["pass"], $_REQUEST["txtnom"], $_REQUEST["txttel"], $_REQUEST["txtdir"], $_REQUEST["mail"]);
        //Después de guardar los cambios vuelvo al listado, se podría poner un mensaje también..
        header("location:perfil.php?opcion=user");
    }
    else {
        if($_REQUEST["pass"]== $_REQUEST["pass"]){
            editar_usuario($db, $_REQUEST["editar"], $_REQUEST["pass"], $_REQUEST["txtnom"], $_REQUEST["txttel"], $_REQUEST["txtdir"], $_REQUEST["mail"]);
            header("location:perfil.php?opcion=user");
    }
}
}
//Agregar usuario nuevo
if (isset($_REQUEST["nuevo"])){
    if (es_unico_user($db, $_REQUEST["dni"])){
        //meter_usuario($db, $dni, $nombre, $telefono, $direccion, $correo, $clave, $foto)
        if (meter_usuario($db, $_REQUEST["dni"],$_REQUEST["nombre"], $_REQUEST["telefono"],
            $_REQUEST["direccion"],$_REQUEST["correo"],$_REQUEST["tipo_perfil"],$_REQUEST["password"],$_REQUEST["estado"])){
                
                $mensaje="<span>Usuario aÃ±adido de forma correcta</span>";
        }
    }else{
        $mensaje="<span>Usuario ya existe </span>";
    }
}
?>
<!DOCTYPE HTML>
<!--
	Twenty by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Sociedad Ametza</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<link rel="stylesheet" href="../assets/css/noscript.css" />
		<link rel="stylesheet" href="../assets/css/almacen.css" />
	</head>
	<body class="index is-preload">
		<div id="page-wrapper">
			<!-- Header -->
				<header id="header" class="alt">
					<h1 id="logo"><a href="<?php session_destroy();echo "../index.php";?>">Sociedad Ametza</a></h1>
					<nav id="nav">
						<ul>					
							<li class="button primary"><a href="<?php session_destroy();echo "../index.php";?>">Volver</a></li>
							<!--  <li <?php if($opcion=="login");?>><a href="<?php echo $_SERVER["PHP_SELF"]?>?opcion=login" class="button primary">Iniciar Sesión</a></li> -->
						</ul>
					</nav>
				</header>

			<!-- Banner -->
				<section id="banner">					
					<div class="inner">
						<?php
						if (!isset($_SESSION["opcion"])){						    
						    echo "<p>Primero tienes que iniciar sesión<p>".$_REQUEST['usuario'];
						    echo "<form action='".$_SERVER["PHP_SELF"]."' method='post'><p><input type='submit' name='salir' value='Volver'></p></form>";
						}else{
						    switch ($_SESSION["opcion"]){
						        default :						       
						            echo "<p>Error</p>";
						            break;
						        case "user":
						            echo $_SESSION['usuario'];
						            echo "<form action='".$_SERVER["PHP_SELF"]."' method='post'";						            						            
						            echo "<p><input type='submit' name='editar' value='Editar usuario'></p>";
						            echo $mensaje;
						            echo "</form>";
						            break;
						        case "administracion":
						            echo "<form action='".$_SERVER["PHP_SELF"]."' method='post'";
						            echo "<p><input type='submit' name='listar' value='Listar Usuarios'></p>";
						            echo "<br>";
						            echo "<p><input type='submit' name='nuevo' value='Nuevo Usuario'></p>";
						            echo "<br>";
						            echo $mensaje;
						            echo "</form>";
						            break;
						        case "listar":
						            echo "<table>";
						            echo "<tr>";
						            echo "<th>ID Usuario</th>";
						            echo "<th>Nombre</th>";
						            echo "<th>Telefono</th>";
						            echo "<th>Direccion</th>";
						            echo "<th>Correo</th>";
						            echo "<th>Tipo perfil</th>";
						            echo "<th>Estado</th>";
						            echo "<th colspan='2' style='text-align: center;'>Acciones</th>";
						            echo "</tr>";
						            listar_datos_usuario($db);
						            echo "</table>";
						            if (isset($_REQUEST["borrar"])){
						                borrar_usuario($db, $_REQUEST['borrar_usuario']);
						                header("Location:perfil.php?opcion=listar");
						            }
						            break;
						        case "nuevo":
						            echo "<form action='".$_SERVER["PHP_SELF"]."' method='get'>";
						            //Es interesante añadir el maxlenth para evitar fallos al meter datos a la base de datos.
						            echo "<p><label for='dni'>DNI</label><input type='text' required name='dni' maxlength='25'></p>";
						            echo "<br>";
						            echo "<p><label for='nombre'>Nombre</label><input type='text' required name='nombre' maxlength='25'></p>";
						            echo "<br>";
						            echo "<p><label for='telefono'>Telefono</label><input type='text' name='telefono' maxlength='50'></p>";
						            echo "<br>";
						            echo "<p><label for='direccion'>Direccion</label><input type='text' name='direccion' maxlength='150'></p>";
						            echo "<br>";
						            echo "<p><label for='correo'>Correo</label><input type='text' name='correo' maxlength='150'></p>";
						            echo "<br>";
						            echo "<p><label for='tipo_perfil'>Tipo Perfil</label><select name='tipo_perfil' required>
                                            <option value='comun' selected>Común</option>
                                            <option value='bodeguero'>Bodeguero</option>
                                            <option value='admin'>Administrador</option>
                                            </select></p>";
						            echo "<br>";
						            echo "<p><label for='estado'>Estado</label><select name='estado' required>
                                            <option value='0' selected>0</option>
                                            <option value='1'>1</option>
                                            </select></p>";
						            echo "<br>";
						            echo "<p><label for='password'>Contraseña</label><input type='password' name='password' maxlength='150'></p>";
						            echo "<br>";
						            echo "<br>";						            
						            echo "<p><input type='submit' name='nuevo' value='Guardar'></p>";
						            echo $mensaje;
						            echo "</form>";
						            break;
						        case "correcto":
						            echo "<p>Entraste</p>";
						            echo "<form action='".$_SERVER["PHP_SELF"]."' method='post'>";
						            echo "<br>";
						            echo "<p><input type='submit' name='user' value='Pefil'></p>";
						            echo "<br>";
						            echo "<p><input type='submit' name='salir' value='Salir'></p>";
						            echo "</form>";
						            break;
						        case "editar":
						            echo "<form action='".$_SERVER["PHP_SELF"]."' method='post'";
						            echo "<label for='txtnom'>Nombre</label><input type='text' name='txtnom' value='".sacar_tipo_user($db, $_REQUEST["usuario"],"nombre")."'><br>";//para que sea solo lectura y no se pueda cambiar
						            echo "<p>Actualizar contraseña</p>";
						            echo "<label for='pass'>Contraseña</label><input type='text' name='pass' value='".sacar_tipo_user($db, $_REQUEST["usuario"],"password")."'><br>";
						            echo "<label for='pass2'>Contraseña</label><input type='text' name='pass2' value='".sacar_tipo_user($db, $_REQUEST["usuario"],"password")."'><br>";
						            echo "<p>Actualizar datos</p>";
						            echo "<label for='txttel'>Telefono</label><input type='text' name='txttel' value='".sacar_tipo_user($db, $_REQUEST["usuario"],"telefono")."'><br>";
						            echo "<label for='txtdir'>Direccion</label><input type='text' name='txtdir' value='".sacar_tipo_user($db, $_REQUEST["usuario"],"direccion")."'><br>";
						            echo "<label for='mail'>Correo</label><input type='text' name='mail' value='".sacar_tipo_user($db, $_REQUEST["usuario"],"correo")."'><br>";
						            echo "<label>Tipo perfil</label><input type='text' name='txttipo' value='".sacar_tipo_user($db, $_REQUEST["usuario"],"tipo_perfil")."' readonly><br>";
						            echo "<br>";
						            echo "<input type='submit' name='guardar_editado' value='Guardar Perfil'>";
						            echo "</form>";
						            break;
						    }
						}
						?>
					</div>
				</section>
			<!-- Main -->
				<article id="main">
					<header class="special container">
						<span class="icon solid fa-chart-bar"></span>
						<h2><strong>Noticias</strong></h2>
						<p>En este apartado pondremos las noticias destacadas de nuestra Sociedad.</p>
					</header>
					<!-- One -->
						<?php			      				    
						    echo listar_noticias($db,"publica");
						    if(isset($_SESSION['usuario'])){
						        echo listar_noticias($db,"privada");
						    }	
						?>														
				</article>
			<!-- Footer -->
				<footer id="footer">

					<ul class="icons">
						<li><a href="#" class="icon brands circle fa-twitter"><span class="label">Twitter</span></a></li>
						<li><a href="#" class="icon brands circle fa-facebook-f"><span class="label">Facebook</span></a></li>
						<li><a href="#" class="icon brands circle fa-google-plus-g"><span class="label">Google+</span></a></li>
						<li><a href="#" class="icon brands circle fa-github"><span class="label">Github</span></a></li>
						<li><a href="#" class="icon brands circle fa-dribbble"><span class="label">Dribbble</span></a></li>
					</ul>

					<ul class="copyright">
						<li>&copy; Untitled</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
					</ul>

				</footer>

		</div>

		<!-- Scripts -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/jquery.dropotron.min.js"></script>
			<script src="../assets/js/jquery.scrolly.min.js"></script>
			<script src="../assets/js/jquery.scrollex.min.js"></script>
			<script src="../assets/js/browser.min.js"></script>
			<script src="../assets/js/breakpoints.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>

	</body>
</html>