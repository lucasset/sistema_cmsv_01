var tabla;

//Función que se ejecuta al inicio
function init(){
	
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	});

	$("#imagenmuestra").hide();
	   
}

//Función limpiar
function limpiar()
{
	$("#idproducto_compra").val("");
	$("#proc_nombre").val("");
	$("#proc_marca").val("");
	$("#proc_descripcion").val("");
	$("#stock").val("");
	$("#imagen").attr("src","");
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
		
	}
	else
	{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		
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
					url: '../ajax/material.php?op=listar',
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
		url: "../ajax/material.php?op=guardaryeditar",
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

function mostrar(idproducto_compra)
{
	$.post("../ajax/material.php?op=mostrar",{idproducto_compra : idproducto_compra}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);

		$("#idproducto_compra").val(data.idproducto_compra);
		$("#proc_nombre").val(data.proc_nombre);
		$("#proc_marca").val(data.proc_marca);
		$("#proc_descripcion").val(data.proc_descripcion);
		$("#stock").val(data.stock);
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/material/"+data.imagen);
		$("#imagenactual").val(data.imagen);
		
 		

 	})
}

//Función para desactivar registros
function desactivar(idproducto_compra)
{
	bootbox.confirm("¿Está Seguro de desactivar el Material?", function(result){
		if(result)
        {
        	$.post("../ajax/material.php?op=desactivar", {idproducto_compra : idproducto_compra}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para activar registros
function activar(idproducto_compra)
{
	bootbox.confirm("¿Está Seguro de activar el Material?", function(result){
		if(result)
        {
        	$.post("../ajax/material.php?op=activar", {idproducto_compra : idproducto_compra}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

init();