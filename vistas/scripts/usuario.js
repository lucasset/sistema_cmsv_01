var tabla;

//Función que se ejecuta al inicio
function init(){
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	})

	$("#imagenmuestra").hide();
	//Mostramos las autorizaciones
	$.post("../ajax/usuario.php?op=autorizacion&id=",function(r){
	        $("#autorizacion").html(r);
	});
	

}

//Función limpiar
function limpiar()
{
	$("#unombre").val("");
	$("#uapellido").val("");
	$("#unumero_documento").val("");
	$("#ucargo").val("");
	$("#udireccion").val("");
	$("#ucelular").val("");
	$("#uwhatsapp").val("");
	$("#ucorreo_electronico").val("");
	$("#login").val("");
	$("#clave").val("");
	$("#ucod_recuperacion").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#idtbusuario").val("");
}

//Función mostrar formulario
function mostrarform(flag)
{
	limpiar();
	if (flag)
	{
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//Función cancelarform
function cancelarform()
{
	limpiar();
	mostrarform(false);
}

//Función Listar
function listar()
{
	tabla=$('#tbllistado').dataTable(
	{
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	    buttons: [		          
		            'copyHtml5',
		            'excelHtml5',
		            'csvHtml5',
		            'pdf'
		        ],
		"ajax":
				{
					url: '../ajax/usuario.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
					}
				},
		"language": {
            "lengthMenu": "Mostrar : _MENU_ registros",
            "buttons": {
            "copyTitle": "Tabla Copiada",
            "copySuccess": {
                    _: '%d líneas copiadas',
                    1: '1 línea copiada'
                }
            }
        },
		"bDestroy": true,
		"iDisplayLength": 5,//Paginación
	    "order": [[ 0, "desc" ]]//Ordenar (columna,orden)
	}).DataTable();
}
//Función para guardar o editar

function guardaryeditar(e)
{
	e.preventDefault(); //No se activará la acción predeterminada del evento
	$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/usuario.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
	          bootbox.alert(datos);	          
	          mostrarform(false);
	          tabla.ajax.reload();
	    }

	});
	limpiar();
}

function mostrar(idtbusuario)
{
	$.post("../ajax/usuario.php?op=mostrar",{idtbusuario : idtbusuario}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);

		$("#unombre").val(data.unombre);
		$("#uapellido").val(data.uapellido);
		$("#utipo_documento").val(data.utipo_documento);
		$('#utipo_documento').selectpicker('refresh');
		$("#unumero_documento").val(data.unumero_documento);
		$("#ucargo").val(data.ucargo);
		$("#udireccion").val(data.udireccion);
		$("#ucelular").val(data.ucelular);
		$("#uwhatsapp").val(data.uwhatsapp);
		$("#ucorreo_electronico").val(data.ucorreo_electronico);
		$("#login").val(data.login);
		$('#login').selectpicker('refresh');
		$("#clave").val(data.clave);
		$("#ucod_recuperacion").val(data.ucod_recuperacion);
		$("#ufecha_ingreso").val(data.ufecha_ingreso);
		$("#ufecha_ingreso").selectpicker('refresh');
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/usuario/"+data.imagen);
		$("#imagenactual").val(data.imagen);
 		$("#idtbusuario").val(data.idtbusuario);
 		//generarbarcode();

 	});

 	$.post("../ajax/usuario.php?op=autorizacion&id="+idtbusuario,function(r){
	        $("#autorizacion").html(r);
	});
}

//Función para desactivar registros
function desactivar(idtbusuario)
{
	bootbox.confirm("¿Está Seguro de desactivar el usuario?", function(result){
		if(result)
        {
        	$.post("../ajax/usuario.php?op=desactivar", {idtbusuario : idtbusuario}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para activar registros
function activar(idtbusuario)
{
	bootbox.confirm("¿Está Seguro de activar el Usuario?", function(result){
		if(result)
        {
        	$.post("../ajax/usuario.php?op=activar", {idtbusuario : idtbusuario}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

init();