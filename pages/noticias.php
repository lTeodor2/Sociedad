<?php
session_start();
require '../conn/acciones_base.php';
require '../conn/constantes.php';
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
$mensaje="";
$opcion="";
//Guardar un nuevo producto
if (isset($_REQUEST["nuevo"])){
    if (es_unico_noticia($db, $_REQUEST["titulo"])){
        if (meter_noticia($db, $_REQUEST["titulo"],$_REQUEST["desc"], $_REQUEST["texto"], $_REQUEST["tipo"], $_REQUEST["imagen"], $_REQUEST["prueba"])){
            $mensaje="<span>Producto añadido de forma correcta</span>";
        }
    }else{
        echo "La noticia ya existe";
    }
}
if (isset($_REQUEST["editar"])){
    $_SESSION["opcion"]="editar";
}
//Una vez hecha la edición esta se guarda en la base de datos
if (isset($_REQUEST["guardar_editado"])){
    editar_noticia($db,$_REQUEST['id_not'], $_REQUEST["titulo"], $_REQUEST["desc"], $_REQUEST["texto"], $_REQUEST["tipo"], $_REQUEST["imagen"], $_REQUEST["id_user"]);
    //Después de guardar los cambios vuelvo al listado, se podría poner un mensaje también..
    $_SESSION["opcion"]="listar";
}
?>
<html>
	<head>
		<title>Sociedad Ametza - Noticias</title>
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
    				<li <?php if($opcion=="listar") echo "class='button secundary'";?>><a href="<?php echo $_SERVER["PHP_SELF"]?>?opcion=listar">Listar Noticias</a></li>
    				<li <?php if($opcion=="nuevo") echo "class='button secundary'";?>><a href="<?php echo $_SERVER["PHP_SELF"]?>?opcion=nuevo">Nueva Noticia</a></li>
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
                		            echo "<th><strong>Id. Noticia</strong></th>";
                		            echo "<th>Titulo</th>";
                		            echo "<th>Descripción</th>";
                		            echo "<th>Texto</th>";
                		            echo "<th>Tipo</th>";
                		            echo "<th>Imagen</th>";
                		            echo "<th>Creado</th>";
                		            echo "<th colspan='2' style='text-align: center;'>Acciones</th>";
                		            echo "</tr>";
                		            echo listar_noticias2($db);
                		            if (isset($_REQUEST['borrar'])){
                		                borrar_noticia($db, $_REQUEST['borrar_noticia']);
                		                header("location: noticias.php");
                		            }
                		            break;
                		        case "inicio":
                		              echo "<p>Bienvenido a la administracion de noticias.</p>";
                		              break;
                		        case "nuevo":
                		            echo "<form action='".$_SERVER["PHP_SELF"]."' method='get'>";
                		            //Es interesante añadir el maxlenth para evitar fallos al meter datos a la base de datos.
                		            echo "<p><label for='titulo'>Titulo de la noticia</label><input type='text' required name='titulo' maxlength='25'></p>";
                		            echo "<br>";
                		            echo "<p><label for='desc'>Descripcion</label><input type='text' name='desc' maxlength='50'></p>";
                		            echo "<br>";
                		            echo "<p><label for='texto'>Texto</label><input type='text' name='texto' maxlength='150'></p>";
                		            echo "<br>";
                		            echo "<p><label for='tipo'>Tipo</label><select name='tipo' required>
                                            <option value='vacio' selected disabled></option>
                                            <option value='publico'>Publico</option>
                                            <option value='privado'>Privado</option>                                            
                                            </select></p>";
                		            echo "<br>";
                		            echo "<p>Imagen:</p><input type='file' name='imagen'";
                		            echo "<br>";
                		            echo "<br>";
                		            echo "<p><label for='prueba'></label><input type='hidden' name='prueba' maxlength='150' value='111111111'></p>";
                		            echo "<p><input type='submit' name='nuevo' value='Guardar'></p>";
                		            echo $mensaje;
                		            echo "</form>";
                		            break;
                		        case "editar":
                		            /* Al editar es interesante tambiÃ©naÃ±adir el maxlenth para evitar fallos al meter datos a la base de datos.
                		             * En este caso dentro de los campos pongo el valor que ya existe en la base de datos.
                		             * El usuario no quiero que lo cambien y es por lo que uso readonly. disabled no manda la informaciÃ³n. Igual estÃ©ticamente queda
                		             * mejor, ya que queda en gris, pero serÃ­a necesario mandar el usuario a travÃ©s de un input hiden..
                		             */
                		            echo "<form action='".$_SERVER["PHP_SELF"]."' method='post'>";
                		            //El nombre de este campo (editar) lo pongo para facilitar las cosas en caso de retornar a esta secciÃ³n, por error en las contraseÃ±as
                		            echo "<p><label for='id_not'>ID de la noticia </label><input type='text' name='id_not' maxlength='30' style='text-align: center;' value='".sacar_noticia($db, $_REQUEST["id_not"],"pk_noticia")."' readonly></p>";
                		            echo "<p><label for='titulo'>Actualizar titulo </label><input type='text' name='titulo' maxlength='30' style='text-align: center;' value='".sacar_noticia($db, $_REQUEST["id_not"],"titulo")."'></p>";
                		            echo "<p><label>Actualizar noticia</label></p>";
                		            echo "<p><label for='desc'>Descripcion </label><input type='text' name='desc' title='Precio actual del producto' style='text-align: center;' value='".sacar_noticia($db, $_REQUEST["id_not"],"descripcion")."'></p>";                		
                		            echo "<p><label for='texto'>Texto</label><input type='text' name='texto' maxlength='6' title='Stock actual del producto' style='text-align: center;' value='".sacar_noticia($db, $_REQUEST["id_not"],"texto")."'></p>";
                		           // echo "<p><label for='tipo'>Tipo </label><input type='text' name='cant' maxlength='6' title='Stock actual del producto' style='text-align: center;' placeholder='".sacar_noticia($db, $_REQUEST["id_not"],"texto")."'></p>";
                		           echo "<br>";
                		            echo "<p><label for='tipo'>Tipo</label><select name='tipo'>
                                            <option value='".sacar_noticia($db, $_REQUEST["id_not"],"tipo")."' select disabled></option>
                                            <option value='publico'>publico</option>
                                            <option value='privado'>privado</option>
                                            </select></p>";
                		            echo "<br>";
                		            echo "<p><label for='imagen'>Actualizar imagen</label><input type='file' name='imagen'></p>";
                		            echo "<br>";
                		            echo "<p><label for='id_user'>Noticia creada por </label><input type='text' name='id_user' maxlength='30' style='text-align: center;' value='".sacar_noticia($db, $_REQUEST["id_not"],"fk_dni")."' readonly></p>";
                		            echo "<br>";
                		            echo "<p><input type='submit' name='guardar_editado' value='Guardar'></p>";
                		            echo $mensaje;
                		            echo "</form>";
                		            $_SESSION["opcion"]="listar";
                		            break;
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