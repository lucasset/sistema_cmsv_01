<?php 

require_once "../modelos/Calzado.php";

$calzado=new Calzado();

$idmodelo=isset($_POST["idmodelo"])? limpiarCadena($_POST["idmodelo"]):"";
$cod_modelo=isset($_POST["cod_modelo"])? limpiarCadena($_POST["cod_modelo"]):"";
$modelo_descripcion=isset($_POST["modelo_descripcion"])? limpiarCadena($_POST["modelo_descripcion"]):"";
$modelo_genero=isset($_POST["modelo_genero"])? limpiarCadena($_POST["modelo_genero"]):"";
$modelo_etapah=isset($_POST["modelo_etapah"])? limpiarCadena($_POST["modelo_etapah"]):"";
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
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/calzado/" . $imagen);
			}
		}


		if (empty($idmodelo)){
			$rspta=$calzado->insertar($cod_modelo,$modelo_descripcion,$modelo_genero,$modelo_etapah,$imagen);
			echo $rspta ? "Calzado registrado" : "Calzado no se pudo registrar";
		}
		else {
			$rspta=$calzado->editar($idmodelo,$cod_modelo,$modelo_descripcion,$modelo_genero,$modelo_etapah,$imagen);
			echo $rspta ? "Calzado actualizado" : "Calzado no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$calzado->desactivar($idmodelo);
 		echo $rspta ? "Calzado Desactivado" : "Calzado no se puede desactivar";
	break;

	case 'activar':
		$rspta=$calzado->activar($idmodelo);
 		echo $rspta ? "Calzado activado" : "Calzado no se puede activar";
	break;

	case 'mostrar':
		$rspta=$calzado->mostrar($idmodelo);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$calzado->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		//recorro todos los registros con la variable reg
 		//y añado uno a uno al array
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->condicion)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idmodelo.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idmodelo.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idmodelo.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idmodelo.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->cod_modelo,	
 				"2"=>$reg->modelo_descripcion,
 				"3"=>$reg->modelo_genero,
 				"4"=>$reg->modelo_etapah,
 				"5"=>"<img src='../files/calzado/".$reg->imagen."' height='60px' width='100px' >",
 				"6"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':
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
}

?>