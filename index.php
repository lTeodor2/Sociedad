<?php 
session_start();
require 'conn/acciones_base.php';
require 'conn/constantes.php';
$db=conectaBD();
$mensaje="";
$opcion="";
//Al dar a inicio se destruye la sesiÃ³n..
if (isset($_REQUEST["salir"])){
    session_destroy();
    header("location:index.php?opcion=login");
}
if (isset($_REQUEST["opcion"])){
    $_SESSION["opcion"]=$_REQUEST["opcion"];
}
if (isset($_SESSION["opcion"])){
    $opcion=$_SESSION["opcion"];
    $db=conectaBD();
}
//Logeo asignamos el usuario logeado a variable de sesion usuario
//if (isset($_REQUEST["entrar"])){
//    $mensaje=logear_usuario($db, $_REQUEST['usuario'], $_REQUEST['clave']);
//    if ($mensaje==TRUE){
//        $_SESSION['usuario']=$_REQUEST['usuario'];
//        header("location:index.php?opcion=correcto");
//    }
//}
if (isset($_REQUEST['entrar'])){
    $mensaje=logear_usuario2($db, $_REQUEST["usuario"], $_REQUEST["clave"]);
    $_SESSION['usuario']=$_REQUEST['usuario'];
}
if (isset($_REQUEST["gestion_reserva"])){
    header("location:pages/reservas.php?opcion=inicio");
}
if (isset($_REQUEST["reservar"])){
    if (distinta_fecha($db, $_REQUEST["fecha"]) && distinto_turno($db, $_REQUEST["turno"])){
        if (meter_reserva($db, "0", $_REQUEST["fecha"], $_REQUEST["num_comensales"], $_REQUEST["turno"], $_REQUEST["dni"])){
            $mensaje="<span>Reserva hecha de forma correcta</span>";
        }
    }else{
        echo "Lo sentimos, ese turno ya está reservado";
    }
}
//Permisos de usuario para entrar para administrar perfil y administrador
if (isset($_REQUEST["user"])){
    if (sacar_tipo_user($db, $_SESSION['usuario'], 'tipo_perfil') == 'comun'){
        header("location:pages/perfil.php?opcion=user");
    }
    if (sacar_tipo_user($db, $_SESSION['usuario'], 'tipo_perfil') == 'bodeguero'){
        header("location:pages/perfil.php?opcion=user");
    }
    if (sacar_tipo_user($db, $_SESSION['usuario'], 'tipo_perfil') == 'admin'){
        header("location:pages/perfil.php?opcion=administracion");
    }
}
//Permisos de usuario para entrar en noticias
if (isset($_REQUEST["reserva"])){
        header("location:pages/reservas.php?opcion=inicio");   
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
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="stylesheet" href="assets/css/noscript.css" />
	</head>
	<body class="index is-preload">
		<div id="page-wrapper">
			<!-- Header -->
				<header id="header" class="alt">
					<h1 id="logo"><a href="index.php?opcion=login">Sociedad Ametza</a></h1>
					<nav id="nav">
						<ul>
							<?php if(isset($_SESSION['usuario'])){if (sacar_tipo_user($db, $_SESSION['usuario'], 'tipo_perfil') == 'bodeguero'){ echo '<li class="button primary"><a href="pages/almacen.php?opcion=inicio">Almacén</a></li>';}} ?>
							<?php if(isset($_SESSION['usuario'])){if (sacar_tipo_user($db, $_SESSION['usuario'], 'tipo_perfil') == "admin"){ echo '<li class="button primary"><a href="pages/almacen.php?opcion=inicio">Almacén</a></li>';}} ?> 
							<?php if(isset($_SESSION['usuario'])){if (sacar_tipo_user($db, $_SESSION['usuario'], 'tipo_perfil') == "admin"){ echo '<li class="button primary"><a href="pages/noticias.php?opcion=inicio">Noticias</a></li>';}} ?>
							<li class="button secundary"><a href="index.php?opcion=intro">Pulsar aquí</a></li>
							<!--  <li <?php if($opcion=="login");?>><a href="<?php echo $_SERVER["PHP_SELF"]?>?opcion=login" class="button primary">Iniciar Sesión</a></li> -->
						</ul>
					</nav>
				</header>

			<!-- Banner -->
				<section id="banner">					
					<div class="inner">
						<?php
						if (!isset($_SESSION["opcion"])){
						    echo "<h2 class='centrar'>Para utilizar esta web es necesario cargar en phpmyadmin el archivo bdgastronomia.sql para asÃ­ crear la base de datos</h2>";
						    echo "<p>Si quieres descargarlo pulsa <a href='".CARPETA."bdgastronomia.sql' dowload='bdgastronomia.sql'>AquÃ­</a>.<p>";
						}else{
						    switch ($_SESSION["opcion"]){
						        case "intro":
						            echo "<header>";
						            echo "<h2>Sociedad Ametza</h2>";
						            echo "</header>";
						            echo "<p>Si eres socio,";
						            echo "<br />";
						            echo "inicia sesion primero.";
						            echo "<br />";
						            echo "<footer>";
						            echo "<ul class='buttons stacked'>";
						            echo '<li><a href="'.$_SERVER["PHP_SELF"].'?opcion=login" class="button fit scrolly">Entrar en el area de cliente</a></li>';
						            echo "</ul>";
						            echo "</footer>";
						            break;
						        case "login":
						            echo "<form action='".$_SERVER["PHP_SELF"]."' method='get' class='bases'>";
						            echo "<p><label for='usuario'>Usuario</label><input type='text' required name='usuario'></p>";
						            echo "<p><label for='clave'>Contraseña</label><input type='password' name='clave'></p>";
						            echo "<p><input type='submit' name='entrar' value='Entrar'></p>";
						            echo $mensaje;
						            echo "</form>";
						            break;
						        case "correcto":
						            echo "<p>Entraste</p>".$_SESSION['usuario'];
						            echo "<form action='".$_SERVER["PHP_SELF"]."' method='post'>";
						            echo "<br>";
						            echo "<p><input type='submit' name='user' value='Pefil'></p>";
						            echo "<br>";						            
						            echo "<p><input type='submit' name='reserva' value='Reserva'></p>";
						            echo "<br>";
						            echo "<p><input type='submit' name='gestion_reserva' value='Gestionar Reservas y gastos' required></p>";
						            echo "<br>";
						            echo "<p><input type='submit' name='salir' value='Salir'></p>";
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
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.dropotron.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.scrollex.min.js"></script>
			<script src="assets/js/browser.min.js"></script>
			<script src="assets/js/breakpoints.min.js"></script>
			<script src="assets/js/util.js"></script>
			<script src="assets/js/main.js"></script>

	</body>
</html>