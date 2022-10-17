<?php 

if (strlen(session_id()) < 1){
	session_start();//Validamos si existe o no la sesión

require_once "../modelos/Compra.php";

$compra=new Compra();

$idtbcompra=isset($_POST["idtbcompra"])? limpiarCadena($_POST["idtbcompra"]):"";
$idproveedor=isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";
$idtbusuario=$_SESSION["idtbusuario"];
$ctipo_comprobante=isset($_POST["ctipo_comprobante"])? limpiarCadena($_POST["ctipo_comprobante"]):"";
$tbcompra_serie=isset($_POST["tbcompra_serie"])? limpiarCadena($_POST["tbcompra_serie"]):"";
$tbcompra_numero=isset($_POST["tbcompra_numero"])? limpiarCadena($_POST["tbcompra_numero"]):"";
$tbc_fecha_emision=isset($_POST["tbc_fecha_emision"])? limpiarCadena($_POST["tbc_fecha_emision"]):"";
$tbc_fecha_recepcion=isset($_POST["tbc_fecha_recepcion"])? limpiarCadena($_POST["tbc_fecha_recepcion"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$total_compra=isset($_POST["total_compra"])? limpiarCadena($_POST["total_compra"]):"";

switch ($_GET["op"]){
	case 'guardaryeditar':
		if (empty($idtbcompra)){
			$rspta=$compra->insertar($idproveedor,$idtbusuario,$ctipo_comprobante,$tbcompra_serie,$tbcompra_numero,$tbc_fecha_emision,$tbc_fecha_recepcion,$impuesto,$total_compra,$_POST["idproducto_compra"],$_POST["$cantidad"],$_POST["$precio_compra"]);
			echo $rspta ? "Compra registrada" : "La compra no se pudo registrar";
		}
		else {
		}
	break;

	case 'anular':
		$rspta=$compra->desactivar($idtbcompra);
 		echo $rspta ? "Compra Anulada" : "La compra no se puede anular";
	break;

	case 'mostrar':
		$rspta=$compra->mostrar($idtbcompra);
 		//Codificar el resultado utilizando json
 		echo json_encode($rspta);
	break;

	case 'listar':
		$rspta=$compra->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>($reg->estado=='Aceptado')?'<button class="btn btn-warning" onclick="mostrar('.$reg->idtbcompra.')"><i class="fa fa-pencil"></i></button>'.
 					' <button class="btn btn-danger" onclick="anular('.$reg->idtbcompra.')"><i class="fa fa-close"></i></button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->idtbcompra.')"><i class="fa fa-pencil"></i></button>',
 				"1"=>$reg->fecha,
 				"2"=>$reg->proveedor,
 				"3"=>$reg->usuario,
 				"4"=>$reg->ctipo_comprobante,
 				"5"=>$reg->tbcompra_serie.'-'.$reg->tbcompra_numero,
 				"6"=>$reg->total_compra,
 				"7"=>$reg->tbc_fecha_recepcion,
 				"8"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':
 				'<span class="label bg-red">Anulado</span>'
 								
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);

	break;

	case 'selectProveedor':

	require_once "../modelos/Persona.php";
		$persona = new Persona();

		$rspta = $persona->listarP();

		while ($reg = $rspta->fetch_object())
				{
				echo '<option value=' . $reg->idpersona . '>' . $reg->razon_social . '</option>';
				}
	break;

	case 'listarMaterial':
	require_once "../modelos/Material.php";

		$material=new Material();

		$rspta=$material->listarActivos();
 		//Vamos a declarar un array
 		$data= Array();

 		//recorro todos los registros con la variable reg
 		//y añado uno a uno al array
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				"0"=>'<button class="btn btn-warning" onclick="agregarDetalle('.$reg->idproducto_compra.',\''.$reg->proc_nombre.'\')"><span class="fa fa-plus"></span></button>',
 				"1"=>$reg->proc_nombre,	
 				"2"=>$reg->proc_marca,
 				"3"=>$reg->proc_descripcion,
 				"4"=>$reg->stock,
 				"5"=>"<img src='../files/material/".$reg->imagen."' height='60px' width='100px' >"
 								
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
//Fin de las validaciones de acceso
}
else
{
  require 'noacceso.php';
}

ob_end_flush();
?>