<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Calzado
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($cod_modelo,$modelo_descripcion,$modelo_genero,$modelo_etapah,$imagen)
	{
		$sql="INSERT INTO modelo_calzado (cod_modelo,modelo_descripcion,modelo_genero,modelo_etapah,imagen,condicion)
		VALUES ('$cod_modelo','$modelo_descripcion','$modelo_genero','$modelo_etapah','imagen','1')";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para editar registros
	public function editar($idmodelo,$cod_modelo,$modelo_descripcion,$modelo_genero,$modelo_etapah,$imagen)
	{
		$sql="UPDATE modelo_calzado SET cod_modelo='$cod_modelo', modelo_descripcion='$modelo_descripcion',modelo_genero='$modelo_genero',modelo_etapah='$modelo_etapah',imagen='$imagen' WHERE idmodelo='$idmodelo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para desactivar
	public function desactivar($idmodelo)
	{
		$sql="UPDATE modelo_calzado SET condicion='0' WHERE idmodelo='$idmodelo'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar
	public function activar($idmodelo)
	{
		$sql="UPDATE modelo_calzado SET condicion='1' WHERE idmodelo='$idmodelo'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idmodelo)
	{
		$sql="SELECT * FROM modelo_calzado WHERE idmodelo='$idmodelo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM modelo_calzado";
		return ejecutarConsulta($sql);		
	}

		//Implementar un método para listar los registros y mostrar
		//en el select

	public function select()
	{
		$sql="SELECT * FROM modelo_calzado where condicion=1";
		return ejecutarConsulta($sql);		
	}
	
}

?>