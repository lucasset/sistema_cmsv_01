<?php 

require_once "../modelos/Autorizacion.php";

$autorizacion=new Autorizacion();

switch ($_GET["op"]){
	
	case 'listar':
		$rspta=$autorizacion->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		//recorro todos los registros con la variable reg
 		//y añado uno a uno al array
 		while ($reg=$rspta->fetch_object()){
 			$data[]=array( 				
 				"0"=>$reg->nombre_campo 				
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