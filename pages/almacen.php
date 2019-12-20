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
        if (es_unico($db, $_REQUEST["nombre"])){
            if (meter_producto($db, $_REQUEST["nombre"],$_REQUEST["precio"], $_REQUEST["cantidad"], $_REQUEST["imagen"])){
                $mensaje="<span>Producto añadido de forma correcta</span>";
            }
        }else{
            echo "El producto ya existe";
        }
    }
    if (isset($_REQUEST["editar"])){
        $_SESSION["opcion"]="editar";
    }
    //Una vez hecha la edición esta se guarda en la base de datos
    if (isset($_REQUEST["guardar_editado"])){
         editar_producto($db,$_REQUEST['id_prod'], $_REQUEST["nombre"], $_REQUEST["precio"], $_REQUEST["cant"], $_REQUEST["imagen"]);
         //Después de guardar los cambios vuelvo al listado, se podría poner un mensaje también..
         $_SESSION["opcion"]="listar";
    }
?>
<html>
	<head>
		<title>Sociedad Ametza - Almacen</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="../assets/css/main.css" />
		<link rel="stylesheet" href="../assets/css/noscript.css" />
		<link rel="stylesheet" href="../assets/css/almacen.css" />
	</head>
	<body class="index is-preload">
		<div id="page-wrapper">
		<header id="header" class="alt">
			<h1 class='button primary'><a href="<?php echo "../index.php";?>">Inicio</a></h1>
    		<nav id="nav">
    			<ul>
    				<li <?php if($opcion=="listar") echo "class='button secundary'";?>><a href="<?php echo $_SERVER["PHP_SELF"]?>?opcion=listar">Listar Productos</a></li>
    				<li <?php if($opcion=="nuevo") echo "class='button secundary'";?>><a href="<?php echo $_SERVER["PHP_SELF"]?>?opcion=nuevo">Nuevo Producto</a></li>
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
                		            echo "<th>Id. Producto</th>";
                		            echo "<th>Nombre</th>";
                		            echo "<th>Precio</th>";
                		            echo "<th>Cantidad</th>";
                		            echo "<th>Imagen</th>";
                		            echo "<th colspan='2' style='text-align: center;'>Acciones</th>";
                		            echo "</tr>";
                		            echo listar_productos($db);
                		            if (isset($_REQUEST['borrar'])){
                		                borrar_producto($db, $_REQUEST['borrar_prod']);
                		                header("location: almacen.php");
                		            }
                		            break;
                		        case "inicio":
                		              echo "<p>Bienvenido al area del bodeguero.</p>";
                		              break;
                		        case "nuevo":
                		            echo "<form action='".$_SERVER["PHP_SELF"]."' method='post'>";
                		            //Es interesante añadir el maxlenth para evitar fallos al meter datos a la base de datos.
                		            echo "<p><label for='nombre'>Nombre del articulo</label><input type='text' required name='nombre' maxlength='25'></p>";
                		            echo "<br>";
                		            echo "<p><label for='precio'>Precio</label><input type='number' name='precio' min='0' max='30' step='0.01' maxlength='5'></p>";
                		            echo "<br>";
                		            echo "<p><label for='cantidad'>Cantidad del articulo</label><input type='text' name='cantidad' maxlength='30'></p>";
                		            echo "<br>";
                		            echo "<p>Imagen:</p><input type='file' name='imagen' value='Imagen'/>";
                		            echo "<br>";
                		            echo "<br>";
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
                		            echo "<p><label for='id_prod'>ID del producto: </label><input type='text' name='id_prod' maxlength='30' style='text-align: center;' value='".sacar_datos($db, $_REQUEST["id_prod"],"pk_producto")."' readonly></p>";
                		            echo "<p><label for='nombre'>Actualizar nombre: </label><input type='text' name='nombre' maxlength='30' style='text-align: center;' placeholder='".sacar_datos($db, $_REQUEST["id_prod"],"nombre")."'></p>";
                		            echo "<p><label>Actualizar articulo</label></p>";
                		            echo "<p><label for='precio'>Precio: </label><input type='text' name='precio' title='Precio actual del producto' style='text-align: center;' placeholder='".sacar_datos($db, $_REQUEST["id_prod"],"precio_unitario")."'></p>";                		
                		            echo "<p><label for='cant'>Cantidad: </label><input type='text' name='cant' maxlength='6' title='Stock actual del producto' style='text-align: center;' placeholder='".sacar_datos($db, $_REQUEST["id_prod"],"cantidad")."'></p>";                		        
                		            echo "<p><label for='imagen'>Actualizar imagen</label><input type='file' name='imagen'></p>";
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