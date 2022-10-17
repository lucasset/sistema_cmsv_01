<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Compra
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idproveedor,$idtbusuario,$ctipo_comprobante,$tbcompra_serie,$tbcompra_numero,$tbc_fecha_emision,$tbc_fecha_recepcion,$impuesto,$total_compra,$idproducto_compra,$cantidad,$precio_compra)
	{
		$sql="INSERT INTO tbcompra (idproveedor,idtbusuario,ctipo_comprobante,tbcompra_serie,tbcompra_numero,tbc_fecha_emision,tbc_fecha_recepcion,impuesto,total_compra,estado)
		VALUES ('$idproveedor','$idtbusuario','$ctipo_comprobante','$tbcompra_serie','$tbcompra_numero','$tbc_fecha_emision','$tbc_fecha_recepcion','$impuesto','$total_compra','Aceptado')";

		//return ejecutarConsulta($sql);
		//se le va a llamar a la funcion ejecutar consulta
		//y esta va a retornar el id del usuario que se ha registrado
		//y lo almacena en "idtbusuarionew"

		$idtbcompranew=ejecutarConsulta_retornarID($sql);

		//hay que almacenar el numero de autorizaciones asignados
		//a este usuario

		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($idproducto_compra))
		{
			$sql_detalle = "INSERT INTO producompra_tbcompra(idtbcompra,idproducto_compra,cantidad,precio_compra) VALUES('$idtbcompranew', '$idproducto_compra[$num_elementos]','$cantidad[$num_elementos]','$precio_compra[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;

			$num_elementos=$num_elementos + 1;
		}

		return $sw;
	}

	
	//Implementamos un método para desactivar
	public function anular($idtbcompra)
	{
		$sql="UPDATE tbcompra SET estado='Anulado' WHERE idtbcompra='$idtbcompra'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idtbcompra)
	{
		$sql="SELECT c.idtbcompra,DATE(c.tbc_fecha_emision) as fecha,c.idproveedor,p.razon_social as proveedor, u.idtbusuario, CONCAT(u.unombre,' ',u.uapellido) as usuario, c.ctipo_comprobante,c.tbcompra_serie,c.tbcompra_numero,c.total_compra,c.impuesto,c.tbc_fecha_recepcion,c.estado FROM tbcompra c INNER JOIN tbpersona p ON c.idproveedor=p.idpersona INNER JOIN tbusuario_empresa u ON c.idtbusuario=u.idtbusuario WHERE c.idtbcompra='$idtbcompra'";

		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT c.idtbcompra,DATE(c.tbc_fecha_emision) as fecha,c.idproveedor,p.razon_social as proveedor, u.idtbusuario, CONCAT(u.unombre,' ',u.uapellido) as usuario, c.ctipo_comprobante,c.tbcompra_serie,c.tbcompra_numero,c.total_compra,c.impuesto,c.tbc_fecha_recepcion,c.estado FROM tbcompra c INNER JOIN tbpersona p ON c.idproveedor=p.idpersona INNER JOIN tbusuario_empresa u ON c.idtbusuario=u.idtbusuario";
		return ejecutarConsulta($sql);		
	}

	
		
}

?>