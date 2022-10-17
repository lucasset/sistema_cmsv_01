var tabla;

//Función que se ejecuta al inicio
function init(){
	
	mostrarform(false);
	listar();

	$("#formulario").on("submit",function(e)
	{
		guardaryeditar(e);	
	});
	//Cargamos los items al select proveedor
	$.post("../ajax/compra.php?op=selectProveedor", function(r){
	            $("#idproveedor").html(r);
	            $('#idproveedor').selectpicker('refresh');
	});
	   
}

//Función limpiar
function limpiar()
{
	$("#idproveedor").val("");
	$("#proveedor").val("");
	$("#tbcompra_serie").val("");
	$("#tbcompra_numero").val("");
	$("#tbc_fecha_emision").val("");
	$("#tbc_fecha_recepcion").val("");
	$("#impuesto").val("");

	$("#total_compra").val("");
	$(".filas").remove();
	$("#total").html("0");
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
		listarMaterial();

		$("#guardar").hide();
		$("#btnGuardar").show();
		$("#btnCancelar").show();
		
		$("#btnAgregarArt").show();
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
					url: '../ajax/compra.php?op=listar',
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

function listarMaterial()
{
	tabla=$('#tblmateriales').dataTable(
	{
		"lengthMenu": [ 5, 10, 25, 75, 100],//mostramos el menú de registros a revisar
		"aProcessing": true,//Activamos el procesamiento del datatables
	    "aServerSide": true,//Paginación y filtrado realizados por el servidor
	    dom: '<Bl<f>rtip>',//Definimos los elementos del control de tabla
	    buttons: [		          
		            
		        ],
		"ajax":
				{
					url: '../ajax/compra.php?op=listarMaterial',
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
	//$("#btnGuardar").prop("disabled",true);
	var formData = new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/compra.php?op=guardaryeditar",
	    type: "POST",
	    data: formData,
	    contentType: false,
	    processData: false,

	    success: function(datos)
	    {                    
	          bootbox.alert(datos);	          
	          mostrarform(false);
	          listar();
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

		$("#cod_modelo").val(data.cod_modelo);
		$("#modelo_descripcion").val(data.modelo_descripcion);
		$("#modelo_genero").val(data.modelo_genero);
		$("#modelo_genero").selectpicker('refresh');
		$("#modelo_etapah").val(data.modelo_etapah);
		$("#modelo_etapah").selectpicker('refresh');
		$("#imagenmuestra").show();
		$("#imagenmuestra").attr("src","../files/calzado/"+data.imagen);
		$("#imagenactual").val(data.imagen);
		$("#idproducto_").val(data.idproducto_);
 		

 	})
}

//Función para anular registros
function anular(idtbcompra)
{
	bootbox.confirm("¿Está Seguro de anular la compra?", function(result){
		if(result)
        {
        	$.post("../ajax/tbcompra.php?op=anular", {idtbcompra : idtbcompra}, function(e){
        		bootbox.alert(e);
	            tabla.ajax.reload();
        	});	
        }
	})
}

//Declaracion de variables necesarios para trabajar con las compras
//y sus detalles

var impuesto=18;
var cont=0;
var detalles=0;
$("#guardar").hide();
$("#ctipo_comprobante").change(marcarImpuesto);

function marcarImpuesto()
{
	var ctipo_comprobante=$("#ctipo_comprobante option:selected").text();
	if (ctipo_comprobante=='Factura')
    {
        $("#impuesto").val(impuesto); 
    }
    else
    {
        $("#impuesto").val("0"); 
    }
  }

  function agregarDetalle(idproducto_compra,producto_compra)
  {
  	var cantidad=1;
  	var precio_compra=1;

  	if (idproducto_compra!="")
    {
    	var subtotal=cantidad*precio_compra;
    	var fila='<tr class="filas" id="fila'+cont+'">'+
    	'<td><button type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
    	'<td><input type="hidden" name="idproducto_compra[]" value="'+idproducto_compra+'">'+producto_compra+'</td>'+
    	'<td><input type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
    	'<td><input type="number" name="precio_compra[]" id="precio_compra[]" value="'+precio_compra+'"></td>'+
    	'<td><span name="subtotal" id="subtotal'+cont+'">'+subtotal+'</span></td>'+
    	'<td><button type="button" onclick="modificarSubototales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
    	'</tr>';
    	cont++;
    	detalles=detalles+1;
    	$('#detalles').append(fila);
    	modificarSubototales();
    }
    else
    {
    	alert("Error al ingresar el detalle, revisar los datos de los Materiales");
    }
  }

function modificarSubototales()
  {
  	var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_compra[]");
    var sub = document.getElementsByName("subtotal");

    for (var i = 0; i <cant.length; i++) {
    	var inpC=cant[i];
    	var inpP=prec[i];
    	var inpS=sub[i];

    	inpS.value=inpC.value * inpP.value;
    	document.getElementsByName("subtotal")[i].innerHTML = inpS.value;

  }

  calcularTotales();

}

function calcularTotales(){
	var sub = document.getElementsByName("subtotal");
	var total = 0.0;

	for (var i = 0; i <sub.length; i++) {
		total += document.getElementsByName("subtotal")[i].value;
	}

	$("#total").html("S/. " + total);
	$("#total_compra").val(total);
	//metodo muestra los valores de guardar si almenos se
	//tiene un detalle
	evaluar();


}

function evaluar(){
	if (detalles>0)
	{
		$("#guardar").show();
	}
	else
	{
		$("#guardar").hide();
		cont=0;
	}
}

function eliminarDetalle(indice){
	$("#fila" + indice).remove();
  	calcularTotales();
  	detalles=detalles-1;
  	evaluar();
}


init();