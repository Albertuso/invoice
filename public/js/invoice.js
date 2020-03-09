$(document).ready(function () {

	var count = $(".itemRow").length;

	showproduct("#productName_1");

	$(document).on('click', '#checkAll', function () {
		$(".itemRow").prop("checked", this.checked);
	});
	$(document).on('click', '.itemRow', function () {
		if ($('.itemRow:checked').length == $('.itemRow').length) {
			$('#checkAll').prop('checked', true);
		} else {
			$('#checkAll').prop('checked', false);
		}
	});

	// JQUERY PARA AÑADIR UNA FILA MAS
	$(document).on('click', '#addRows', function () {
		count++;
		var htmlRows = '';
		htmlRows += '<tr>';
		htmlRows += '<td><input class="itemRow" type="checkbox"></td>';
		htmlRows += '<td><input type="text" name="productName[]" id="productName_' + count + '" class="form-control" autocomplete="off"><div class="suggestions" id="suggestions_' + count + '"></div></td>';
		// htmlRows += '<td><input type="text" name="description[]" id="description_' + count + '" class="form-control" autocomplete="off"></td>';
		htmlRows += '<td><input type="number" name="quantity[]" min="0" pattern="^[0-9]+" id="quantity_' + count + '" class="form-control quantity" autocomplete="off"></td>';
		htmlRows += '<td><input type="number" name="price[]" min="0" pattern="^[0-9]+" id="price_' + count + '" class="form-control price" autocomplete="off"></td>';
		htmlRows += '<td><input type="number" name="VAT[]" min="0" pattern="^[0-9]+" id="vat_' + count + '" class="form-control price" autocomplete="off"></td>';
		htmlRows += '<td><input type="number" readonly min="0" pattern="^[0-9]+" name="total[]" id="total_' + count + '" class="form-control total" autocomplete="off"></td>';
		htmlRows += '</tr>';
		htmlRows += '<div id="suggestions_' + count + '</div>';
		$('#invoiceItem').append(htmlRows);
		showproduct("#productName_" + count);
	});

	// JQUERY PARA ELIMINAR UNA FILA
	$(document).on('click', '#removeRows', function () {
		$(".itemRow:checked").each(function () {
			$(this).closest('tr').remove();
		});
		$('#checkAll').prop('checked', false);
		calculateTotal();
	});
	$(document).on('change', "[id^=quantity_]", function () {
		calculateTotal();
	});
	$(document).on('change', "[id^=price_]", function () {
		calculateTotal();
	});
	$(document).on('change', "[id^=vat_]", function () {
		calculateTotal();
	});
	$(document).on('blur', "#taxRate", function () {
		calculateTotal();
	});
});

function decimales(x) {
	return Math.round((Number.parseFloat(x) + Number.EPSILON) * 100) / 100;
}

function calculateTotal() {
	var subTotal = 0;
	var sumVat = 0;
	var sumTotal = 0;
	var total = 0;
	$("[id^='price_']").each(function () {
		$(this).on('blur', function () {
			var number = $(this).val()
			if (number < 0) {
				$(this).val(0);
				calculateTotal();
			}
		});

		var id = $(this).attr('id');
		id = id.replace("price_", '');
		var price = $('#price_' + id).val();
		var quantity = $('#quantity_' + id).val();
		if (!quantity) {
			quantity = 1;
		}
		var vat = $('#vat_' + id).val();
		sumVat += (price * quantity * (vat / 100));
		// var total = (price * quantity) + (price * quantity * (vat / 100));
		var ivaProduct = (price * quantity * (vat / 100));
		total = price * quantity + ivaProduct;
		$('#total_' + id).val(parseFloat(decimales(total)));
		subTotal += decimales(price * quantity);
		// subTotal = total - vat;
		sumTotal += total; //suma de totales
	});
	$('#invoice_subtotal').val(parseFloat(subTotal));
	$('#budget').val(parseFloat(subTotal));

	$('#taxAmount').val(decimales(sumVat));
	$('#taxRate').val(decimales(sumVat));
	$('#invoice_total').val(decimales(sumTotal));
	$('#budget_total').val(decimales(sumTotal));
	$('#invoice_subtotal').val(decimales(subTotal));
	$('#budget_subtotal').val(decimales(subTotal));

}

function showproduct(productName) {
	//AJAX PARA MOSTRAR EL ARRAY DE PRODUCTOS 

	var idempresa = $("#idempresa").text();
	$(productName).blur(function () {
		var numberline = productName.split("_")[1];
	});

	$(productName).keyup(function () {

		var parametros = idempresa + "/" + $(this).val();
		var numberline = productName.split("_")[1];
		$('#suggestions_' + numberline).slideDown();

		$.ajax('/invoice/search/enterprise/' + parametros, {
			dataType: 'json',
			contentType: 'application/json',
			cache: false
		})
			.done(function (response) {
				var html = "";
				$.each(response, function (index, element) {

					// index es el numero de respuestas (1, 2, 3... dependiendo)

					html += '<div id="' + index + '" class="suggestions_' + index + ' suggest-element">' + response[index].name + "</div>";
					//console.log(element.price);
					console.log("linea id:" + index);

				});

				// me saca en el div oculto la informacion devuelta ajax
				$('#suggestions_' + numberline).html(html);


				//Al hacer click en alguna de las sugerencias devueltas ajax
				$('.suggest-element').on('click', function () {

					console.log("numberline: " + numberline);
					$('#suggestions_' + numberline).slideToggle();
					//Obtenemos la id unica de la sugerencia pulsada
					var id = $(this).attr('id');

					//Hacemos desaparecer el resto de sugerencias
					$('#suggestions');
					//alert('Has seleccionado el ' + id + ' ' + $('#' + id).attr('data'));
					$('#suggestions_' + id)

					console.log(response[$(this)[0].id]);

					$('#price_' + numberline).val(response[$(this)[0].id].price);
					$('#description_' + numberline).val(response[$(this)[0].id].description);
					$('#vat_' + numberline).val(response[$(this)[0].id].vat);
					$('#productName_' + numberline).val(response[$(this)[0].id].name);
					oldcantity = $('#quantity_' + numberline).val();
					if (oldcantity == "" || oldcantity == null) {
						$('#quantity_' + numberline).val(1);
					}
					calculateTotal();
					// $("#price_1").val(5)
					return false;
				});
			})
	});
}