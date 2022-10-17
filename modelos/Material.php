<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Material
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($proc_nombre,$proc_marca,$proc_descripcion,$stock,$imagen)
	{
		$sql="INSERT INTO producto_compra (proc_nombre,proc_marca,proc_descripcion,stock,imagen,estado)
		VALUES ('$proc_nombre','$proc_marca','$proc_descripcion','$stock','$imagen','1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idproducto_compra,$proc_nombre,$proc_marca,$proc_descripcion,$stock,$imagen)
	{
		$sql="UPDATE producto_compra SET proc_nombre='$proc_nombre', proc_marca='$proc_marca',proc_descripcion='$proc_descripcion',stock='$stock',imagen='$imagen' WHERE idproducto_compra='$idproducto_compra'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar
	public function desactivar($idproducto_compra)
	{
		$sql="UPDATE producto_compra SET estado='1' WHERE idproducto_compra='$idproducto_compra'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar
	public function activar($idproducto_compra)
	{
		$sql="UPDATE producto_compra SET estado='1' WHERE idproducto_compra='$idproducto_compra'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idproducto_compra)
	{
		$sql="SELECT * FROM producto_compra WHERE idproducto_compra='$idproducto_compra'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM producto_compra";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros activos
	public function listarActivos()
	{
		$sql="SELECT idproducto_compra,proc_nombre,proc_marca,proc_descripcion,stock,imagen,estado FROM producto_compra WHERE estado='1'";
		return ejecutarConsulta($sql);		
	}

		//Implementar un método para listar los registros y mostrar
		//en el select

	public function select()
	{
		$sql="SELECT * FROM producto_compra where estado=1";
		return ejecutarConsulta($sql);		
	}
	
}

?>