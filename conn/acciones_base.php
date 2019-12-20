<?php
// Función para conectarnos con una base de datos en general. Depende de los valores guardados en la constantews
function conectaBD(){
    try {
        $db = new PDO("mysql:host=".SERVIDOR.";dbname=".BASE.";charset=utf8",USUARIO,CLAVE);
        $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        $db->setAttribute(PDO::NULL_TO_STRING, true);
    } catch (PDOException $e) {
        die ("<p><H3>No se ha podido establecer la conexión.</p><p>Compruebe si está activado el servidor de bases de
		datos MySQL.</H3></p>\n <p>Error: ".$e->getMessage()."</p>\n");
    }
    return $db;
}
//Esta función lista los productos que hay en la tabla.
function listar_productos($db){
    $consulta="select * from producto";
    $productos = $db->query($consulta);
    if (!$productos) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else foreach ($productos as $producto){
        echo "<tr>";
        echo "<td><p>".$producto["pk_producto"]."</p></td>";
        echo "<td><strong>".$producto["nombre"]."</strong></td>";
        echo "<td>".$producto["precio_unitario"]."</td>";
        echo "<td>".$producto["cantidad"]."</td>";
        echo "<td><img src='../images/".$producto["imagen"]."' alt='".$producto["imagen"]."' width='70' height='120'></img></td>";
        echo "<td><form action='almacen.php' method='post'><input type='hidden' name='borrar_prod' value='".$producto['pk_producto']."'><input type='submit' name='borrar' value='Borrar'></form></td>";
        echo "<td><form action='almacen.php' method='get'><input type='hidden' name='id_prod' value='".$producto['pk_producto']."'><input type='submit' name='editar' value='Modificar'></form></td>";
        echo "</tr>";
    }
}
//Listar las noticias en administracion
function listar_noticias2($db){
    $consulta="select * from noticia";
    $noticias = $db->query($consulta);
    if (!$noticias) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else foreach ($noticias as $noticia){
        echo "<tr>";
        echo "<td><p>".$noticia["pk_noticia"]."</p></td>";
        echo "<td><strong>".$noticia["titulo"]."</strong></td>";
        echo "<td>".$noticia["descripcion"]."</td>";
        echo "<td>".$noticia["texto"]."</td>";
        echo "<td>".$noticia["tipo"]."</td>";
        echo "<td>".$noticia["imagen"]."</td>";
        echo "<td>".$noticia["fk_dni"]."</td>";
        echo "<td><form action='noticias.php' method='post'><input type='hidden' name='borrar_noticia' value='".$noticia['pk_noticia']."'><input type='submit' name='borrar' value='Borrar'></form></td>";
        echo "<td><form action='noticias.php' method='get'><input type='hidden' name='id_not' value='".$noticia['pk_noticia']."'><input type='submit' name='editar' value='Modificar'></form></td>";
        echo "</tr>";
    }
}
//Listar las noticias
function listar_noticias($db,$tipo){
    $consulta="select * from noticia where tipo='$tipo'";
    $noticias = $db->query($consulta);
    if (!$noticias) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else{        
        foreach ($noticias as $noticia){
            echo '<section class="wrapper style2 container special-alt">';
            echo '<div class="row gtr-50">';
            echo '<div class="col-8 col-12-narrower">';
            echo '<header><h2>'.$noticia["titulo"].'</h2></header>';
            echo '<p>'.$noticia["descripcion"].'</p>';
            echo '<footer>
					<ul class="buttons">
						<li><a href="#" class="button">Leer más</a></li>
					</ul>
				  </footer>';
            echo '</div>';
            echo '<div class="col-4 col-12-narrower imp-narrower">
									<ul class="featured-icons">
										<li><span class="icon fa-clock"><span class="label">Feature 1</span></span></li>
										<li><span class="icon solid fa-volume-up"><span class="label">Feature 2</span></span></li>
										<li><span class="icon solid fa-laptop"><span class="label">Feature 3</span></span></li>
										<li><span class="icon solid fa-inbox"><span class="label">Feature 4</span></span></li>
										<li><span class="icon solid fa-lock"><span class="label">Feature 5</span></span></li>
										<li><span class="icon solid fa-cog"><span class="label">Feature 6</span></span></li>
									</ul>

								</div>';
            echo '</div>';
            echo '</div>';
            echo '</section>';
        }
    }
}
//Esta funcion borra un producto de la base de datos
function borrar_producto($db,$producto){
    $consulta="delete from producto where pk_producto='".$producto."'";
    $num_filas=$db->exec($consulta);
    if ($num_filas===false) {
        $error=$db->errorInfo();
        print "Error en la consulta. Error ". $error[2];
    }   
}
function borrar_noticia($db,$noticia){
    $consulta="delete from noticia where pk_noticia='".$noticia."'";
    $num_filas=$db->exec($consulta);
    if ($num_filas===false) {
        $error=$db->errorInfo();
        print "Error en la consulta. Error ". $error[2];
    }
}
function es_unico($db,$producto){
    $consulta="select pk_producto
			from producto
			where pk_producto='".$producto."'";
    $producto = $db->query($consulta);
    if (!$producto) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else {
        if ($producto->rowcount()==0) return true;
        else return false;
    } 
}
function es_unico_noticia($db,$noticia){
    $consulta="select pk_noticia
			from noticia
			where pk_noticia='".$noticia."'";
    $noticia = $db->query($consulta);
    if (!$noticia) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else {
        if ($noticia->rowcount()==0) return true;
        else return false;
    }
}
function meter_producto($db,$nombre,$precio,$cantidad,$imagen){
    $consulta="insert into producto values ('.pk_producto.nextval.','".$nombre."','".$precio."','".$cantidad."','".$imagen."')";
    $num_filas=$db->exec($consulta);
    if ($num_filas===false) {
        $error=$db->errorInfo();
        print "Error en la consulta. Error ". $error[2];
    }else return true;
}
function meter_noticia($db,$titulo,$desc,$texto,$tipo,$imagen,$usuario){
    $consulta="insert into noticia values ('.pk_noticia.nextval.','".$titulo."','".$desc."','".$texto."','".$tipo."','".$imagen."','".$usuario."')";
    $num_filas=$db->exec($consulta);
    if ($num_filas===false) {
        $error=$db->errorInfo();
        print "Error en la consulta. Error ". $error[2];
    }else return true;
}
// Esta función nos devuelve el nombre de un usuario dado..
function sacar_datos($db,$producto,$campo){
    $consulta="select pk_producto, nombre, precio_unitario, cantidad from producto where pk_producto='".$producto."'";
    $productos = $db->query($consulta);
    if (!$productos) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else foreach ($productos as $un_producto){
        return $un_producto[$campo];
    }
}
function sacar_noticia($db,$noticia,$campo){
    $consulta="select pk_noticia, titulo, descripcion, texto, tipo, imagen, fk_dni from noticia where pk_noticia='".$noticia."'";
    $noticias = $db->query($consulta);
    if (!$noticias) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else foreach ($noticias as $un_noticia){
        return $un_noticia[$campo];
    }
}
function sacar_tipo_user($db,$usuario,$campo){
    $consulta="select * from usuario where nombre='".$usuario."'";
    $usuario = $db->query($consulta);
    if (!$usuario) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else foreach ($usuario as $un_usuario){
        return $un_usuario[$campo];
    }
}
//Esta función edita el usuario pasado por parámetro con los datos también pasados.La clave si está en blanco no se cambia, si se ha metido
//otra se encripta..
function editar_producto($db,$producto,$nombre,$precio,$cant,$imagen){
    if ($producto!="")
        $consulta="update producto set nombre='".$nombre."', precio_unitario='".$precio."', cantidad='".$cant."', imagen='".$imagen."' where pk_producto='".$producto."'";
        else  $consulta="update set nombre='".$nombre."', precio_unitario='".$precio."', cantidad='".$cant."', imagen='".$imagen."' where pk_producto='".$producto."'";
        $num_filas=$db->exec($consulta);
        if ($num_filas===false) {
            $error=$db->errorInfo();
            print "Error en la consulta. Error ". $error[2];
        }
        
}
function editar_noticia($db,$id_noticia,$titulo,$desc,$texto,$tipo,$imagen){
    if ($id_noticia!="")
        $consulta="update noticia set titulo='".$titulo."', descripcion='".$desc."', texto='".$texto."', tipo='".$tipo."', imagen='".$imagen."' where pk_noticia='".$id_noticia."'";
        else  $consulta="update noticia set titulo='".$titulo."', descripcion='".$desc."', texto='".$texto."', tipo='".$tipo."', imagen='".$imagen."' where pk_noticia='".$id_noticia."'";
        $num_filas=$db->exec($consulta);
        if ($num_filas===false) {
            $error=$db->errorInfo();
            print "Error en la consulta. Error ". $error[2];
        }
        
}

//Esta función edita el usuario pasado por parámetro con los datos también pasados.La clave si está en blanco no se cambia, si se ha metido
//otra se encripta..
//function editar_usuario($db,$usuario,$clave,$nombre){
//    if ($clave!="")
    //        $consulta="update usuarios set nombre='".$nombre."', clave='".md5($clave)."' where usuario='".$usuario."'";
    //        else  $consulta="update usuarios set nombre='".$nombre."' where usuario='".$usuario."'";
    //    $num_filas=$db->exec($consulta);
    //    if ($num_filas===false) {
    //        $error=$db->errorInfo();
    //        print "Error en la consulta. Error ". $error[2];
    //    }
    //
    //}

//Metemos la reserva a la base de datos
function meter_reserva($db,$id_reserva,$fecha,$num_comensales,$turno,$dni){
    if (distinta_fecha($db, $fecha) && distinto_turno($db, $turno)){
        $consulta="insert into reservas values ('0','".$fecha."','".$num_comensales."','".$turno."','".$dni."')";
        $num_filas=$db->exec($consulta);
        if ($num_filas===false) {
            $error=$db->errorInfo();
            print "Error en la consulta. Error ". $error[2];
        }else return true;
    }
}

//Esta función devuelve true si la FECHA pasada por parámetro no existe en la tabla reservas
function distinta_fecha($db,$fecha){
    $consulta="select fecha
			from reservas
			where fecha='".$fecha."'";
    $fecha = $db->query($consulta);
    if (!$fecha) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else {
        if ($fecha->rowcount()==0) return true;
        else return false;
    }
}

//Esta función devuelve true si el TURNO pasada por parámetro no existe en la tabla reservas
function distinto_turno($db,$turno){
    $consulta="select turno
			from reservas
			where turno='".$turno."'";
    $turno = $db->query($consulta);
    if (!$turno) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else {
        if ($turno->rowcount()==0) return true;
        else return false;
    }
}

//Lista las reservas guardadas en la base de datos
function listar_reservas($db){
    $consulta="select id_reserva,fecha,num_comensales,turno,dni from reservas";
    $reservas = $db->query($consulta);
    if (!$reservas) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else foreach ($reservas as $reserva){
        echo "<tr>";
        echo "<td>".$reserva["id_reserva"]."</td>";
        echo "<td>".$reserva["fecha"]."</td>";
        echo "<td>".$reserva["num_comensales"]."</td>";
        echo "<td>".$reserva["turno"]."</td>";
        echo "<td>".$reserva["dni"]."</td>";
        echo "<td><form action='' method='post'><input type='hidden' name='reser' value='".$reserva['id_reserva']."'><input type='submit' name='borrar' value='BORRAR'></input></form></td>";
        echo "</tr>";
    }
}
//Calcula el numero de reservas hechas
function numero_reservas($db){
    $consulta="select count(id_reserva) as 'num_reservas' from reservas";
    $reservas = $db->query($consulta);
    if (!$reservas) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else foreach ($reservas as $reserva){
        return $reserva["num_reservas"];
    }
}

//Borra reservas
function borrar_reserva($db,$id_reserva){
    $consulta="delete from reservas where id_reserva='".$id_reserva."'";
    $num_filas=$db->exec($consulta);
    if ($num_filas===false) {
        $error=$db->errorInfo();
        print "Error en la consulta. Error ". $error[2];
    }
}

//Calcula el numero de productos
function numero_productos($db){
    $consulta="select count(pk_producto) as 'num_productos' from producto";
    $productos = $db->query($consulta);
    if (!$productos) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else foreach ($productos as $producto){
        return $producto["num_productos"];
    }
}

function listar_prod($db){
    $consulta="select pk_producto, nombre, precio_unitario from producto";
    $productos = $db->query($consulta);
    if (!$productos) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else foreach ($productos as $producto){
        echo "<option value=".$producto["pk_producto"].">".$producto["nombre"]."</option>";
        
    }
}


//Meter factura
function meter_factura($db,$id_factura,$fecha,$dni,$pk_producto,$cantidad){
    $consulta="insert into facturas values ('0','".$fecha."','".$dni."','".$pk_producto."','".$cantidad."')";
    $num_filas=$db->exec($consulta);
    if ($num_filas===false) {
        $error=$db->errorInfo();
        print "Error en la consulta. Error ". $error[2];
    }else return true;
}


//Lista las facturas guardadas en la base de datos
function listar_facturas($db){
    $consulta="select id_factura,fecha,dni,pk_producto,cantidad from facturas";
    $facturas = $db->query($consulta);
    if (!$facturas) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else foreach ($facturas as $factura){
        echo "<tr>";
        echo "<td>".$factura["id_factura"]."</td>";
        echo "<td>".$factura["fecha"]."</td>";
        echo "<td>".$factura["dni"]."</td>";
        echo "<td>".$factura["pk_producto"]."</td>";
        echo "<td>".$factura["cantidad"]."</td>";
        echo "<td><form action='' method='post'><input type='hidden' name='fact' value='".$factura['id_factura']."'><input type='submit' name='borrar' value='BORRAR'></input></form></td>";
        echo "</tr>";
    }
}

//Calcula el numero de facturas hechas
function numero_facturas($db){
    $consulta="select count(id_factura) as 'num_facturas' from facturas";
    $facturas = $db->query($consulta);
    if (!$facturas) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else foreach ($facturas as $factura){
        return $factura["num_facturas"];
    }
}

//Borrar factura
function borrar_factura($db,$id_factura){
    $consulta="delete from facturas where id_factura='".$id_factura."'";
    $num_filas=$db->exec($consulta);
    if ($num_filas===false) {
        $error=$db->errorInfo();
        print "Error en la consulta. Error ". $error[2];
    }
}



function existe_dni($db,$dni){
    $consulta="select pk_dni
			from usuario
			where pk_dni='".$dni."'";
    $usuario = $db->query($consulta);
    if (!$usuario) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else {
        if ($usuario->rowcount()!=0) return true;
        else return false;
    }
    
}
function logear_usuario2($db,$usuario,$clave){
$consulta="select nombre
			from usuario
			where nombre='".$usuario."' and password='".$clave."' and estado=1";
    $usuario = $db->query($consulta);
    if (!$usuario) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else {
        if ($usuario->rowcount()==0) return "<span class='aviso'>Usuario o contraseña incorrectos</span>";
        else return header('location:index.php?opcion=correcto');
    }
}



























function listar_datos_usuario($db){
    $consulta="select pk_dni,nombre,telefono,direccion,correo,tipo_perfil,estado from usuario";
    $usuarios = $db->query($consulta);
    if (!$usuarios) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else foreach ($usuarios as $usu){
        echo "<tr>";
        echo "<td><p>".$usu["pk_dni"]."</p></td>";
        echo "<td><strong>".$usu["nombre"]."</strong></td>";
        echo "<td>".$usu["telefono"]."</td>";
        echo "<td>".$usu["direccion"]."</td>";
        echo "<td>".$usu["correo"]."</td>";
        echo "<td>".$usu["tipo_perfil"]."</td>";
        echo "<td>".$usu["estado"]."</td>";
        echo "<td><form action='perfil.php' method='post'><input type='hidden' name='borrar_usuario' value='".$usu['pk_dni']."'><input type='submit' name='borrar' value='Borrar'></form></td>";
        echo "<td><form action='perfil.php' method='get'><input type='hidden' name='id_user' value='".$usu['pk_dni']."'><input type='submit' name='editar' value='Modificar'></form></td>";
        echo "</tr>";
    }
}
//Esta borra el usuario pasado por parámetro
function borrar_usuario($db,$usuario){
    $consulta="delete from usuarios where usuario='".$usuario."'";
    $num_filas=$db->exec($consulta);
    if ($num_filas===false) {
        $error=$db->errorInfo();
        print "Error en la consulta. Error ". $error[2];
    }
   
}
function es_unico_user($db,$dni){
    $consulta="select nombre
			from usuario
			where pk_dni='".$dni."'";
    $usuario = $db->query($consulta);
    if (!$usuario) {
        $error=$db->errorInfo();
        print "<p>Error en la consulta. Error ". $error[2] ."</p>";
    } else {
        if ($usuario->rowcount()==0) return true;
        else return false;
    }
}
function meter_usuario($db,$dni,$nombre,$telefono,$direccion,$correo,$clave,$estado){
    $consulta="insert into usuario (pk_dni,nombre,telefono,direccion,correo,password,estado)
                            values ('".$dni."','".$nombre."','".$telefono."','".$direccion."','".$correo."','".md5($clave)."','".$estado."')
               ";
    $num_filas=$db->exec($consulta);
    if ($num_filas===false) {
        $error=$db->errorInfo();
        print "Error en la consulta. Error ". $error[2];
    }else return true;
}

