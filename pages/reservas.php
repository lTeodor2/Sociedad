<?php
session_start();
require '../conn/acciones_base.php';
require '../conn/constantes.php';
$mensaje="";
$opcion="";
if (isset($_REQUEST["inicio"])){
    session_destroy();
    header("location:../index.php");
}
//Al hacer clic en una opciÃ³n esa opciÃ³n es asignada a la variable de sesiÃ³n
if (isset($_REQUEST["opcion"])){
    $_SESSION["opcion"]=$_REQUEST["opcion"];
}
//Si existe la variable de sesiÃ³n se crea una variable llamada opciÃ³n (para simplificar el uso) y se crea una conexiÃ³n con la base de datos
if (isset($_SESSION["opcion"])){
    $opcion=$_SESSION["opcion"];
    $db=conectaBD();
}

//Guardar un nuevo producto

if (isset($_REQUEST["editar"])){
    $_SESSION["opcion"]="editar";
}
//Una vez hecha la edición esta se guarda en la base de datos
if (isset($_REQUEST["guardar_editado"])){
    editar_noticia($db,$_REQUEST['id_not'], $_REQUEST["titulo"], $_REQUEST["desc"], $_REQUEST["texto"], $_REQUEST["tipo"], $_REQUEST["imagen"], $_REQUEST["id_user"]);
    //Después de guardar los cambios vuelvo al listado, se podría poner un mensaje también..
    $_SESSION["opcion"]="listar";
}
if (isset($_REQUEST["todas"])){
    header("location:pages/perfil.php?opcion=listar_todas_reservas");
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
?>
<html>
	<head>
		<title>Sociedad Ametza - Reservas</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<link rel="stylesheet" href="../assets/css/noscript.css" />
		<link rel="stylesheet" href="../assets/css/almacen.css" />
	</head>
	<body class="index is-preload">
		<div id="page-wrapper">
		<header id="header" class="alt">
			<h1 class='button primary'><a href="<?php session_destroy();echo "../index.php";?>">Inicio</a></h1>
    		<nav id="nav">
    			<ul>
    				<li <?php if($opcion=="listar") echo "class='button secundary'";?>><a href="<?php echo $_SERVER["PHP_SELF"]?>?opcion=listar">Listar Reserva</a></li>
    				<li <?php if($opcion=="nueva_reserva") echo "class='button secundary'";?>><a href="<?php echo $_SERVER["PHP_SELF"]?>?opcion=nueva_reserva">Nueva Reserva</a></li>
    				<li <?php if($opcion=="gastos") echo "class='button secundary'";?>><a href="<?php echo $_SERVER["PHP_SELF"]?>?opcion=gastos">Gastos</a></li>
    			</ul>
    		</nav>
		</header>
		<section id="banner">
    		<div class="inner">
        		<table>
            		<?php                		
                		if (!isset($_SESSION["opcion"])){
                		    echo "<h2 class='centrar'>Para utilizar esta web es necesario cargar en phpmyadmin el archivo base.sql para así crear la base de datos</h2>";
                		    echo "<p>Si quieres descargarlo pulsa <a href='".CARPETA."base.sql' dowload='base.sql'>Aquí</a>.<p>";
                		}else{
                		    switch ($_SESSION["opcion"]){
                		        case "listar":
                		            echo "<tr>";
                                    echo "<th>N. reserva</th>";
                                    echo "<th>FECHA</th>";
                                    echo "<th>NUMERO DE COMENSALES</th>";
                                    echo "<th>TURNO</th>";
                                    echo "<th>DNI DEL SOCIO</th>";
                                    echo "<th colspan='2' style='text-align: center;'>Acciones</th>";
                                    echo "</tr>";
                		            listar_reservas($db);
                		            echo "</table>";
                		            if (isset($_REQUEST["borrar"])){
                		                borrar_reserva($db, $_REQUEST["reser"]);
                		                header("Location:revision_reservas_gastos.php?opcion=listar");
                		            }
                		            break;
                		        case "inicio":
                		              echo "<p>Bienvenido a la administracion de las reservas y gastos.</p>";
                		              break;               		                       		        
                		        case "nueva_reserva":
                		            echo "<p>Haz tu reserva</p>";
                		            echo "<form action='' method='post'>";
                		            echo "<p><input type='date' name='fecha' placeholder='Fecha' required id='fecha'></p>";
                		            echo "<p>TURNO: <select name='turno'>
            		                                  <option>Almuerzo</option>
                                                  		<option>Comida</option>
                                               			<option>Merienda</option>
                                               			<option>Cena</option>
              	                                 </select></p>";
                		            echo "<p><label for='num_comensales'>Cantidad de comensales</label><input type='number' name='num_comensales' min='1' max='58' size='30' required id='numero'></p>";
                		            echo "<p><input type='text' name='dni' placeholder='DNI' size='30' required id='dni'></p>";
                		            echo "<p><input type='submit' name='reservar' value='Reservar'></p>";
                		            echo "</form>";
                		            break;
                		        case "gastos":                		            
                		            echo "<tr>";
                		            echo "<th>NUMERO DE FACTURA</th>";
                		            echo "<th>FECHA</th>";
                		            echo "<th>DNI</th>";
                		            echo "<th>ID PRODUCTOS</th>";
                		            echo "<th>PRECIO</th>";
                		            echo "<th>CANTIDAD</th>";
                		            echo "<th>BORRAR</th></tr>";
                		            listar_facturas($db);
                		            if (isset($_REQUEST["borrar"])){
                		                borrar_factura($db, $_REQUEST["fact"]);
                		                header("Location:revision_reservas_gastos.php");
                		            }
                		    }
                		    
                		}
                		
                		?>
        		</table>
    		</div>
		</section>
		<!-- Scripts -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/jquery.dropotron.min.js"></script>
			<script src="../assets/js/jquery.scrolly.min.js"></script>
			<script src="../assets/js/jquery.scrollex.min.js"></script>
			<script src="../assets/js/browser.min.js"></script>
			<script src="../assets/js/breakpoints.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<script src="../assets/js/main.js"></script>
		</div>
	</body>
</html>