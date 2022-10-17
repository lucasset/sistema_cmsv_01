<?php 

require_once "../modelos/Persona.php";

$persona=new Persona();

$idpersona=isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"]):"";
$tipo_persona=isset($_POST["tipo_persona"])? limpiarCadena($_POST["tipo_persona"]):"";
$razon_social=isset($_POST["razon_social"])? limpiarCadena($_POST["razon_social"]):"";
$tipo_documento=isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$documento_identidad=isset($_POST["documento_identidad"])? limpiarCadena($_POST["documento_identidad"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$celular=isset($_POST["celular"])? limpiarCadena($_POST["celular"]):"";
$whatsapp=isset($_POST["whatsapp"])? limpiarCadena($_POST["whatsapp"]):"";
$correo_electronico=isset($_POST["correo_electronico"])? limpiarCadena($_POST["correo_electronico"]):"";
$fecha_registro=isset($_POST["fecha_registro"])? limpiarCadena($_POST["fecha_registro"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idpersona)){
			$rspta=$persona->insertar($tipo_persona,$razon_social,$tipo_documento,$documento_identidad,$direccion,$celular,$whatsapp,$correo_electronico,$fecha_registro);
			echo $rspta ? "persona registrada" : "persona no se pudo registrar";
		}
		else {
			$rspta=$persona->editar($idpersona,$tipo_persona,$razon_social,$tipo_documento,$documento_identidad,$direccion,$celular,$whatsapp,$correo_electronico,$fecha_registro);
			echo $rspta ? "persona actualizada" : "persona no se pudo actualizar";
		}
	break;

	case 'eliminar':
		$rspta=$persona->eliminar($idpersona);
 		echo $rspta ? "persona eliminada" : "persona no se puede eliminar";
	break;

	case 'mostrar':
		$rspta=$persona->mostrar($idpersona);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listarp':
		$rspta=$persona->listarp();
 		//Vamos a declarar un array
 		$data= Array();

 		//recorro todos los registros con la variable reg
 		//y añado uno a uno al array
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button>',
 				"1"=>$reg->razon_social,	
 				"2"=>$reg->tipo_documento,
 				"3"=>$reg->documento_identidad,
 				"4"=>$reg->direccion,
 				"5"=>$reg->celular,
 				"6"=>$reg->whatsapp,
 				"7"=>$reg->correo_electronico,
 				"8"=>$reg->fecha_registro, 								
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	case 'listarc':
		$rspta=$persona->listarc();
 		//Vamos a declarar un array
 		$data= Array();

 		//recorro todos los registros con la variable reg
 		//y añado uno a uno al array
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button>',
 				"1"=>$reg->razon_social,	
 				"2"=>$reg->tipo_documento,
 				"3"=>$reg->documento_identidad,
 				"4"=>$reg->direccion,
 				"5"=>$reg->celular,
 				"6"=>$reg->whatsapp,
 				"7"=>$reg->correo_electronico,
 				"8"=>$reg->fecha_registro, 								
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