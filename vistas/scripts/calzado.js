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
	   
}

//Función limpiar
function limpiar()
{
	$("#idmodelo").val("");
	$("#cod_modelo").val("");
	$("#modelo_descripcion").val("");
	$("#modelo_genero").val("");
	$("#modelo_etapah").val("");
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
};

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
					url: '../ajax/calzado.php?op=listar',
					type : "get",
					dataType : "json",						
					error: function(e){
						console.log(e.responseText);	
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
		url: "../ajax/calzado.php?op=guardaryeditar",
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

function mostrar(idmodelo)
{
	$.post("../ajax/calzado.php?op=mostrar",{idmodelo : idmodelo}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);

		$("#cod_modelo").val(data.cod_modelo);
		$("#modelo_descripcion").val(data.modelo_descripcion);
		$("#modelo_genero").val(data.modelo_genero);
		$("#modelo_genero").selectpicker('refresh');
		$("#modelo_etapah").val(data.modelo_etapah);
		$("#modelo_etapah").selectpicker('refresh');
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/calzado/"+data.imagen);
		$("#imagenactual").val(data.imagen);
		$("#idmodelo").val(data.idmodelo);
 		

 	})
}

//Función para desactivar registros
function desactivar(idmodelo)
{
	bootbox.confirm("¿Está Seguro de desactivar el Calzado?", function(result){
		if(result)
        {
        	$.post("../ajax/calzado.php?op=desactivar", {idmodelo : idmodelo}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para activar registros
function activar(idmodelo)
{
	bootbox.confirm("¿Está Seguro de activar el Calzado?", function(result){
		if(result)
        {
        	$.post("../ajax/calzado.php?op=activar", {idmodelo : idmodelo}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

init();