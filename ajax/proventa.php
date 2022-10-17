<?php 

require_once "../modelos/Proventa.php";

$proventa=new Proventa();

$idProducto_venta=isset($_POST["idProducto_venta"])? limpiarCadena($_POST["idProducto_venta"]):"";
$idmodelo=isset($_POST["idmodelo"])? limpiarCadena($_POST["idmodelo"]):"";
$vtalla=isset($_POST["vtalla"])? limpiarCadena($_POST["vtalla"]):"";
$vcolor=isset($_POST["vcolor"])? limpiarCadena($_POST["vcolor"]):"";
$vtipo_cantidad=isset($_POST["vtipo_cantidad"])? limpiarCadena($_POST["vtipo_cantidad"]):"";
$vcantidad=isset($_POST["vcantidad"])? limpiarCadena($_POST["vcantidad"]):"";
$vmonto_unitario=isset($_POST["vmonto_unitario"])? limpiarCadena($_POST["vmonto_unitario"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idProducto_venta)){
			$rspta=$proventa->insertar($idmodelo,$vtalla,$vcolor,$vtipo_cantidad,$vcantidad,$vmonto_unitario);
			echo $rspta ? "Producto venta registrado" : "Producto venta no se pudo registrar";
		}
		else {
			$rspta=$proventa->editar($idProducto_venta,$idmodelo,$vtalla,$vcolor,$vtipo_cantidad,$vcantidad,$vmonto_unitario);
			echo $rspta ? "Producto venta actualizado" : "Producto venta no se pudo actualizar";
		}
	break;

	case 'desactivar':
		$rspta=$proventa->desactivar($idProducto_venta);
 		echo $rspta ? "Producto venta Finalizado" : "Producto venta no se puede Finalizar";
	break;

	case 'activar':
		$rspta=$proventa->activar($idProducto_venta);
 		echo $rspta ? "Producto venta Activado" : "Producto venta no se puede Activar";
	break;

	case 'mostrar':
		$rspta=$proventa->mostrar($idProducto_venta);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$proventa->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		//recorro todos los registros con la variable reg
 		//y añado uno a uno al array
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->vestado_producto)?'<button class="btn btn-warning" onclick="mostrar('.$reg->idProducto_venta.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->idProducto_venta.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idProducto_venta.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->idProducto_venta.')"><i class="fa fa-check"></i></button>',
 				"1"=>$reg->cod_modelo,	
 				"2"=>$reg->vtalla,
 				"3"=>$reg->vcolor,
 				"4"=>$reg->vtipo_cantidad,
 				"5"=>$reg->vcantidad,
 				"6"=>$reg->vmonto_unitario,
 				"7"=>($reg->vestado_producto)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Finalizado</span>'
 								
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	case "selectCalzado":
		require_once "../modelos/Calzado.php";
		$calzado = new Calzado();

		$rspta = $calzado->select();

		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->idmodelo . '>' . $reg->cod_modelo . '</option>';
				}
	break;
}

?>