$(document).ready(function () {

	var count = $(".itemRow").length;

	showproduct("#productName_1");	

	// $(document).on('click', '#checkAll', function () {
	// 	$(".itemRow").prop("checked", this.checked);
	// });
	// $(document).on('click', '.itemRow', function () {
	// 	if ($('.itemRow:checked').length == $('.itemRow').length) {
	// 		$('#checkAll').prop('checked', true);
	// 	} else {
	// 		$('#checkAll').prop('checked', false);
	// 	}
	// });
	

	// JQUERY PARA AÑADIR UNA FILA MAS
	$(document).on('click', '#addRows', function () {
		count++;
		var htmlRows = '';
		htmlRows += '<tr>';
		htmlRows += '<td><input class="itemRow" type="checkbox"></td>';
		htmlRows += '<td><input type="text" name="productCode[]" id="productCode_' + count + '" class="form-control" autocomplete="off"></td>';

		//htmlRows += '<td><select name="select" class="form-control"><option value="value1"></option>{% for product in products %}<option  name="productName[]" value="value1"> {{ product.name }} </option>{% endfor %}</select></td>';

		htmlRows += '<td><input type="text" name="productName[]" id="productName_' + count + '" class="form-control" autocomplete="off"><div class="suggestions" id="suggestions_' + count+ '"></div></td>';
		htmlRows += '<td><input type="number" name="quantity[]" id="quantity_' + count + '" class="form-control quantity" autocomplete="off"></td>';
		htmlRows += '<td><input type="number" name="price[]" id="price_' + count + '" class="form-control price" autocomplete="off"></td>';
		htmlRows += '<td><input type="number" name="total[]" id="total_' + count + '" class="form-control total" autocomplete="off"></td>';
		htmlRows += '</tr>';
		htmlRows += '<div id="suggestions_' + count+ '</div>';
		$('#invoiceItem').append(htmlRows);
		showproduct("#productName_"+count);
	});

	// JQUERY PARA ELIMINAR UNA FILA
	$(document).on('click', '#removeRows', function () {
		$(".itemRow:checked").each(function () {
			$(this).closest('tr').remove();
		});
		$('#checkAll').prop('checked', false);
		calculateTotal();
	});
	// $(document).on('blur', "[id^=quantity_]", function () {
	// 	calculateTotal();
	// });
	// $(document).on('blur', "[id^=price_]", function () {
	// 	calculateTotal();
	// });
	// $(document).on('blur', "#taxRate", function () {
	// 	calculateTotal();
	// });
	// $(document).on('blur', "#amountPaid", function () {
	// 	var amountPaid = $(this).val();
	// 	var totalAftertax = $('#totalAftertax').val();
	// 	if (amountPaid && totalAftertax) {
	// 		totalAftertax = totalAftertax - amountPaid;
	// 		$('#amountDue').val(totalAftertax);
	// 	} else {
	// 		$('#amountDue').val(totalAftertax);
	// 	}
	// });
	// $(document).on('click', '.deleteInvoice', function () {
	// 	var id = $(this).attr("id");
	// 	if (confirm("Are you sure you want to remove this?")) {
	// 		$.ajax({
	// 			url: "action.php",
	// 			method: "POST",
	// 			dataType: "json",
	// 			data: { id: id, action: 'delete_invoice' },
	// 			success: function (response) {
	// 				if (response.status == 1) {
	// 					$('#' + id).closest("tr").remove();
	// 				}
	// 			}
	// 		});
	// 	} else {
	// 		return false;
	// 	}
	// });
});

function calculateTotal() {
	var totalAmount = 0;
	$("[id^='price_']").each(function () {
		var id = $(this).attr('id');
		id = id.replace("price_", '');
		var price = $('#price_' + id).val();
		var quantity = $('#quantity_' + id).val();
		if (!quantity) {
			quantity = 1;
		}
		var total = price * quantity;
		$('#total_' + id).val(parseFloat(total));
		totalAmount += total;
	});
	$('#subTotal').val(parseFloat(totalAmount));
	var taxRate = $("#taxRate").val();
	var subTotal = $('#subTotal').val();
	if (subTotal) {
		var taxAmount = subTotal * taxRate / 100;
		$('#taxAmount').val(taxAmount);
		subTotal = parseFloat(subTotal) + parseFloat(taxAmount);
		$('#totalAftertax').val(subTotal);
		var amountPaid = $('#amountPaid').val();
		var totalAftertax = $('#totalAftertax').val();
		if (amountPaid && totalAftertax) {
			totalAftertax = totalAftertax - amountPaid;
			$('#amountDue').val(totalAftertax);
		} else {
			$('#amountDue').val(subTotal);
		}
	}
}

function showproduct(productName) {
	//AJAX PARA MOSTRAR EL ARRAY DE PRODUCTOS 

	var idempresa = $("#idempresa").text();
	$(productName).blur(function () {
		var numberline = productName.split("_")[1];
		$('#suggestions_' + numberline).hide();
		
	});
	
	$(productName).keyup(function () {		
		
		var parametros = idempresa + "/" + $(this).val();
		var numberline = productName.split("_")[1];
		$('#suggestions_' + numberline).show();

		$.ajax('/invoice/search/enterprise/' + parametros, {
			dataType: 'json',
			contentType: 'application/json',
			cache: false
		})
			.done(function (response) {
				var html = "";
				$.each(response, function (index, element) {
					
					html += response[index].name + "<br/>";
				}); 

				$('#suggestions_' + numberline).html(html);
				//Al hacer click en alguna de las sugerencias
				$('.suggest-element').on('click', function () {
					//Obtenemos la id unica de la sugerencia pulsada
					var id = $(this).attr('id');
					//Editamos el valor del input con data de la sugerencia pulsada
					$('#key').val($('#' + id).attr('data'));
					//Hacemos desaparecer el resto de sugerencias
					$('#suggestions');
					alert('Has seleccionado el ' + id + ' ' + $('#' + id).attr('data'));
					return false;
				});
			})			
	});
}