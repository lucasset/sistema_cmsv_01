<?php 
session_start();

require_once "../modelos/Usuario.php";

$usuario=new Usuario();

$idtbusuario=isset($_POST["idtbusuario"])? limpiarCadena($_POST["idtbusuario"]):"";
$unombre=isset($_POST["unombre"])? limpiarCadena($_POST["unombre"]):"";
$uapellido=isset($_POST["uapellido"])? limpiarCadena($_POST["uapellido"]):"";
$utipo_documento=isset($_POST["utipo_documento"])? limpiarCadena($_POST["utipo_documento"]):"";
$unumero_documento=isset($_POST["unumero_documento"])? limpiarCadena($_POST["unumero_documento"]):"";
$ucargo=isset($_POST["ucargo"])? limpiarCadena($_POST["ucargo"]):"";
$udireccion=isset($_POST["udireccion"])? limpiarCadena($_POST["udireccion"]):"";
$ucelular=isset($_POST["ucelular"])? limpiarCadena($_POST["ucelular"]):"";
$uwhatsapp=isset($_POST["uwhatsapp"])? limpiarCadena($_POST["uwhatsapp"]):"";
$ucorreo_electronico=isset($_POST["ucorreo_electronico"])? limpiarCadena($_POST["ucorreo_electronico"]):"";
$login=isset($_POST["login"])? limpiarCadena($_POST["login"]):"";
$clave=isset($_POST["clave"])? limpiarCadena($_POST["clave"]):"";
$ucod_recuperacion=isset($_POST["ucod_recuperacion"])? limpiarCadena($_POST["ucod_recuperacion"]):"";
$ufecha_ingreso=isset($_POST["ufecha_ingreso"])? limpiarCadena($_POST["ufecha_ingreso"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar':

		if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name']))
		{
			$imagen=$_POST["imagenactual"];
		}
		else 
		{
			$ext = explode(".", $_FILES["imagen"]["name"]);
			if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png")
			{
				$imagen = round(microtime(true)) . '.' . end($ext);
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuario/" . $imagen);
			}
		}

		//Hash SHA256 en la contraseña
		$clavehash=hash("SHA256",$clave);


		if (empty($idtbusuario)){
			$rspta=$usuario->insertar($unombre,$uapellido,$utipo_documento,$unumero_documento,$ucargo,$udireccion,$ucelular,$uwhatsapp,$ucorreo_electronico,$login,$clavehash,$ucod_recuperacion,$ufecha_ingreso,$imagen,$_POST['autorizacion']);
			echo $rspta ? "Usuario registrado" : "Usuario no se pudo registrar con todo sus datos";
		}
		else {
			$rspta=$usuario->editar($idtbusuario,$unombre,$uapellido,$utipo_documento,$unumero_documento,$ucargo,$udireccion,$ucelular,$uwhatsapp,$ucorreo_electronico,$login,$clavehash,$ucod_recuperacion,$ufecha_ingreso,$imagen,$_POST['autorizacion']);
			echo $rspta ? "Usuario actualizado" : "Usuario no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$usuario->desactivar($idtbusuario);
 		echo $rspta ? "Usuario Desactivado" : "Usuario no se puede desactivar";
	break;

	case 'activar':
		$rspta=$usuario->activar($idtbusuario);
 		echo $rspta ? "Usuario activado" : "Usuario no se puede activar";
	break;

	case 'mostrar':
		$rspta=$usuario->mostrar($idtbusuario);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$usuario->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->ucondicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idtbusuario.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idtbusuario.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idtbusuario.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idtbusuario.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->unombre,
 				"2"=>$reg->uapellido,
 				"3"=>$reg->utipo_documento,
 				"4"=>$reg->unumero_documento,
 				"5"=>$reg->udireccion,
 				"6"=>$reg->ucelular,
 				"7"=>$reg->uwhatsapp,
 				"8"=>$reg->ucorreo_electronico,
 				"9"=>$reg->login,
 				"10"=>$reg->ufecha_ingreso,
 				"11"=>"<img src='../files/usuario/".$reg->imagen."' height='50px' width='50px' >",
 				"12"=>($reg->ucondicion)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	case 'autorizacion':
			//Obtenemos todos los permisos de la tabla autorizacion
		require_once "../modelos/Autorizacion.php";
		$autorizacion = new Autorizacion();
		$rspta = $autorizacion->listar();

		//Obtener los permisos asignados al usuario
		$id=$_GET['id'];
		$marcados = $usuario->listarmarcados($id);
		//Declaramos el array para almacenar todos los permisos marcados
		$valores=array();

		//Almacenar los permisos asignados al usuario en el array
		while ($per = $marcados->fetch_object())
			{
				array_push($valores, $per->idpermiso);
			}

		//Mostramos la lista de autorizacion en la vista y si están o no marcados
		while ($reg = $rspta->fetch_object())
				{
					$sw=in_array($reg->idpermiso,$valores)?'checked':'';
					echo '<li> <input type="checkbox" '.$sw.' name="autorizacion[]" value="'.$reg->idpermiso.'">'.$reg->nombre_campo.'</li>';
				}

	break;

	case 'verificar':
		$logina=$_POST['logina'];
		$clavea=$_POST['clavea'];

		//Hash SHA256 el la contraseña
		$clavehash=hash("SHA256",$clavea);

		$rspta=$usuario->verificar($logina,$clavehash);

		$fetch=$rspta->fetch_object();

		if (isset($fetch))
		{
			//Declaramos las variables de sesión
			$_SESSION['idtbusuario']=$fetch->idtbusuario;
			$_SESSION['unombre']=$fetch->unombre;
			$_SESSION['imagen']=$fetch->imagen;
			$_SESSION['login']=$fetch->login;

			//Obtenemos los permisos del usuario
	    	$marcados = $usuario->listarmarcados($fetch->idtbusuario);

	    	//Declaramos el array para almacenar todos los permisos marcados
			$valores=array();

			//Almacenamos los permisos marcados en el array
			while ($per = $marcados->fetch_object())
				{
					array_push($valores, $per->idpermiso);
				}

			//Determinamos los accesos del usuario
			in_array(1,$valores)?$_SESSION['portal']=1:$_SESSION['escritorio']=0;
			in_array(2,$valores)?$_SESSION['usuario']=1:$_SESSION['almacen']=0;
			in_array(3,$valores)?$_SESSION['ventas']=1:$_SESSION['compras']=0;
			in_array(4,$valores)?$_SESSION['compras']=1:$_SESSION['ventas']=0;
			in_array(5,$valores)?$_SESSION['consultav']=1:$_SESSION['acceso']=0;
			in_array(6,$valores)?$_SESSION['consultac']=1:$_SESSION['consultac']=0;
			in_array(7,$valores)?$_SESSION['almacen']=1:$_SESSION['consultav']=0;
		}

		echo json_encode($fetch);

	break;

	case 'salir';
	//limpiamos las variables de sesion
	session_unset();
	//Destrullamos la sesion
	session_destroy();
	//redireccionamos al login
	header("Location: ../index.php");

	break;

}

?>