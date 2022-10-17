<?php 

require_once "../modelos/Material.php";

$material=new Material();

$idproducto_compra=isset($_POST["idproducto_compra"])? limpiarCadena($_POST["idproducto_compra"]):"";
$proc_nombre=isset($_POST["proc_nombre"])? limpiarCadena($_POST["proc_nombre"]):"";
$proc_marca=isset($_POST["proc_marca"])? limpiarCadena($_POST["proc_marca"]):"";
$proc_descripcion=isset($_POST["proc_descripcion"])? limpiarCadena($_POST["proc_descripcion"]):"";
$stock=isset($_POST["stock"])? limpiarCadena($_POST["stock"]):"";
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
				move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/material/" . $imagen);
			}
		}


		if (empty($idproducto_compra)){
			$rspta=$material->insertar($proc_nombre,$proc_marca,$proc_descripcion,$stock,$imagen);
			echo $rspta ? "Material registrado" : "Material no se pudo registrar";
		}
		else {
			$rspta=$material->editar($idproducto_compra,$proc_nombre,$proc_marca,$proc_descripcion,$stock,$imagen);
			echo $rspta ? "Material actualizado" : "Material no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$material->desactivar($idproducto_compra);
 		echo $rspta ? "Material Desactivado" : "Material no se puede desactivar";
	break;

	case 'activar':
		$rspta=$material->activar($idproducto_compra);
 		echo $rspta ? "Material activado" : "Material no se puede activar";
	break;

	case 'mostrar':
		$rspta=$material->mostrar($idproducto_compra);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$material->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		//recorro todos los registros con la variable reg
 		//y añado uno a uno al array
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idproducto_compra.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idproducto_compra.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idproducto_compra.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idproducto_compra.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->proc_nombre,	
 				"2"=>$reg->proc_marca,
 				"3"=>$reg->proc_descripcion,
 				"4"=>$reg->stock,
 				"5"=>"<img src='../files/material/".$reg->imagen."' height='60px' width='100px' >",
 				"6"=>($reg->estado)?'<span class="label bg-green">Activado</span>':
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