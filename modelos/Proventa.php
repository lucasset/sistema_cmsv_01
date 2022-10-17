<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Proventa
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($idmodelo,$vtalla,$vcolor,$vtipo_cantidad,$vcantidad,$vmonto_unitario)
	{
		$sql="INSERT INTO Producto_venta (idmodelo,vtalla,vcolor,vtipo_cantidad,vcantidad,vmonto_unitario,vestado_producto)
		VALUES ('$idmodelo','$vtipo_cantidad','$vcolor','$vtipo_cantidad','$vcantidad','$vmonto_unitario','0')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idProducto_venta,$idmodelo,$vtalla,$vcolor,$vtipo_cantidad,$vcantidad,$vmonto_unitario)
	{
		$sql="UPDATE Producto_venta SET idmodelo='$idmodelo', vtalla='$vtalla',vcolor='$vcolor',vtipo_cantidad='$vtipo_cantidad',vcantidad='$vcantidad',vmonto_unitario='$vmonto_unitario' WHERE idProducto_venta='$idProducto_venta'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar proventa
	public function desactivar($idProducto_venta)
	{
		$sql="UPDATE Producto_venta SET vestado_producto='0' WHERE idProducto_venta='$idProducto_venta'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar proventa
	public function activar($idProducto_venta)
	{
		$sql="UPDATE Producto_venta SET vestado_producto='1' WHERE idProducto_venta='$idProducto_venta'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idProducto_venta)
	{
		$sql="SELECT * FROM Producto_venta WHERE idProducto_venta='$idProducto_venta'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT p.idProducto_venta,p.idmodelo,m.cod_modelo,p.vtalla,p.vcolor,p.vtipo_cantidad,p.vcantidad,p.vmonto_unitario,p.vestado_producto FROM Producto_venta AS p 
		INNER JOIN modelo_calzado AS m ON p.idmodelo=m.idmodelo";
		return ejecutarConsulta($sql);		
	}
	
}

?>