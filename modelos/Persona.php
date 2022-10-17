<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Persona
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($tipo_persona,$razon_social,$tipo_documento,$documento_identidad,$direccion,$celular,$whatsapp,$correo_electronico,$fecha_registro)
	{
		$sql="INSERT INTO tbpersona (tipo_persona,razon_social,tipo_documento,documento_identidad,direccion,celular,whatsapp,correo_electronico,fecha_registro)
		VALUES ('$tipo_persona','$razon_social','$tipo_documento','$documento_identidad','$direccion','$celular','$whatsapp','$correo_electronico','$fecha_registro')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idpersona,$tipo_persona,$razon_social,$tipo_documento,$documento_identidad,$direccion,$celular,$whatsapp,$correo_electronico,$fecha_registro)
	{
		$sql="UPDATE tbpersona SET tipo_persona='$tipo_persona',razon_social='$razon_social',tipo_documento='$tipo_documento',documento_identidad='$documento_identidad',direccion='$direccion',celular='$celular',whatsapp='$whatsapp',correo_electronico='$correo_electronico',fecha_registro='$fecha_registro' WHERE idpersona='$idpersona'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para eliminar categorías
	public function eliminar($idpersona)
	{
		$sql="DELETE FROM tbpersona WHERE idpersona='$idpersona'";
		return ejecutarConsulta($sql);
	}
	
	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idpersona)
	{
		$sql="SELECT * FROM tbpersona WHERE idpersona='$idpersona'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listarp()
	{
		$sql="SELECT * FROM tbpersona WHERE tipo_persona='Proveedor'";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los registros
	public function listarc()
	{
		$sql="SELECT * FROM tbpersona WHERE tipo_persona='Cliente'";
		return ejecutarConsulta($sql);		
	}

	
}

?>