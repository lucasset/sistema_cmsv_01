<?php 
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

Class Usuario
{
	//Implementamos nuestro constructor
	public function __construct()
	{

	}

	//Implementamos un método para insertar registros
	public function insertar($unombre,$uapellido,$utipo_documento,$unumero_documento,$ucargo,$udireccion,$ucelular,$uwhatsapp,$ucorreo_electronico,$login,$clave,$ucod_recuperacion,$ufecha_ingreso,$imagen,$autorizacion)
	{
		$sql="INSERT INTO tbusuario_empresa (unombre,uapellido,utipo_documento,unumero_documento,ucargo,udireccion,ucelular,uwhatsapp,ucorreo_electronico,login,clave,ucod_recuperacion,ufecha_ingreso,imagen,ucondicion)
		VALUES ('$unombre','$uapellido','$utipo_documento','$unumero_documento','$ucargo','$udireccion','$ucelular','$uwhatsapp','$ucorreo_electronico','$login','$clave','$ucod_recuperacion','$ufecha_ingreso','$imagen','1')";

		//return ejecutarConsulta($sql);
		//se le va a llamar a la funcion ejecutar consulta
		//y esta va a retornar el id del usuario que se ha registrado
		//y lo almacena en "idtbusuarionew"

		$idtbusuarionew=ejecutarConsulta_retornarID($sql);

		//hay que almacenar el numero de autorizaciones asignados
		//a este usuario

		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($autorizacion))
		{
			$sql_detalle = "INSERT INTO tbusuario_autorizacion(idtbusuario, idpermiso) VALUES('$idtbusuarionew', '$autorizacion[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;

			$num_elementos=$num_elementos + 1;
		}

		return $sw;
	}

	//Implementamos un método para editar registros
	public function editar($idtbusuario,$unombre,$uapellido,$utipo_documento,$unumero_documento,$ucargo,$udireccion,$ucelular,$uwhatsapp,$ucorreo_electronico,$login,$clave,$ucod_recuperacion,$ufecha_ingreso,$imagen,$autorizacion)
	{
		$sql="UPDATE tbusuario_empresa SET unombre='$unombre', uapellido='$uapellido',utipo_documento='$utipo_documento',unumero_documento='$unumero_documento',ucargo='$ucargo',uwhatsapp='$uwhatsapp',ucorreo_electronico='$ucorreo_electronico',login='$login',clave='$clave',ucod_recuperacion='$ucod_recuperacion',ufecha_ingreso='$ufecha_ingreso',imagen='$imagen' WHERE idtbusuario='$idtbusuario'";
		ejecutarConsulta($sql);

		//Eliminamos todos los permisos asignados para volverlos a registrar

		$sqldel="DELETE FROM tbusuario_autorizacion WHERE idtbusuario='$idtbusuario'";
		ejecutarConsulta($sqldel);

		$num_elementos=0;
		$sw=true;

		while ($num_elementos < count($autorizacion))
		{
			$sql_detalle = "INSERT INTO tbusuario_autorizacion(idtbusuario, idpermiso) VALUES('$idtbusuario', '$autorizacion[$num_elementos]')";
			ejecutarConsulta($sql_detalle) or $sw = false;

			$num_elementos=$num_elementos + 1;
		}

		return $sw;

		//
	}

	//Implementamos un método para desactivar
	public function desactivar($idtbusuario)
	{
		$sql="UPDATE tbusuario_empresa SET ucondicion='0' WHERE idtbusuario='$idtbusuario'";
		return ejecutarConsulta($sql);
	}

	//Implementamos un método para activar
	public function activar($idtbusuario)
	{
		$sql="UPDATE tbusuario_empresa SET ucondicion='1' WHERE idtbusuario='$idtbusuario'";
		return ejecutarConsulta($sql);
	}

	//Implementar un método para mostrar los datos de un registro a modificar
	public function mostrar($idtbusuario)
	{
		$sql="SELECT * FROM tbusuario_empresa WHERE idtbusuario='$idtbusuario'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//Implementar un método para listar los registros
	public function listar()
	{
		$sql="SELECT * FROM tbusuario_empresa";
		return ejecutarConsulta($sql);		
	}

	//Implementar un método para listar los permisos marcados
	public function listarmarcados($idtbusuario)
	{
		$sql="SELECT * FROM tbusuario_autorizacion WHERE idtbusuario='$idtbusuario'";
		return ejecutarConsulta($sql);
	}

	//Función para verificar el ingreso al sistema

	public function verificar($login,$clave)
	{
		$sql="SELECT idtbusuario,unombre,utipo_documento,unumero_documento,ucargo,ucelular,ucorreo_electronico,imagen,login FROM tbusuario_empresa WHERE login='$login' AND clave='$clave' AND ucondicion='1'";
			return ejecutarConsulta($sql);

		
	}
		
}

?>