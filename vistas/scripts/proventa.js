var tabla;

//Función que se ejecuta al inicio
function init(){
	
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	})
   
   //Cargamos los items al select calzado
	$.post("../ajax/proventa.php?op=selectCalzado", function(r){
	            $("#idmodelo").html(r);
	            $('#idmodelo').selectpicker('refresh');
    
});

}

//Función limpiar
function limpiar()
{
	$("#vtalla").val("");
	$("#vcolor").val("");
	$("#vtipo_cantidad").val("");
	$("#vcantidad").val("");
	$("#vmonto_unitario").val("");
	$("#idProducto_venta").val("")
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
					url: '../ajax/proventa.php?op=listar',
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
		url: "../ajax/proventa.php?op=guardaryeditar",
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

function mostrar(idProducto_venta)
{
	$.post("../ajax/proventa.php?op=mostrar",{idProducto_venta : idProducto_venta}, function(data, status)
	{
		data = JSON.parse(data);		
		mostrarform(true);

		$("#idmodelo").val(data.idmodelo);
		$("#idmodelo").selectpicker('refresh');
		$("#vtalla").val(data.vtalla);
		$("#vcolor").val(data.vcolor);
		$("#vtipo_cantidad").val(data.vtipo_cantidad);
		$("#vcantidad").val(data.vcantidad);
		$("#vmonto_unitario").val(data.vmonto_unitario);
		$("#idProducto_venta").val(data.idProducto_venta);
 		

 	})
}

//Función para desactivar registros
function desactivar(idProducto_venta)
{
	bootbox.confirm("¿Está Seguro de poner finalizado al producto?", function(result){
		if(result)
        {
        	$.post("../ajax/proventa.php?op=desactivar", {idProducto_venta : idProducto_venta}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Función para activar registros
function activar(idProducto_venta)
{
	bootbox.confirm("¿Está Seguro de activar el producto?", function(result){
		if(result)
        {
        	$.post("../ajax/proventa.php?op=activar", {idProducto_venta : idProducto_venta}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

init();