let productPrices = [];

var overallSubTotal = 0, totalDiscountDeducted = 0;

$(`input[name="amount_paying"]`).on('keyup', function() {
});

$(`input[name="discount_amount"]`).on('keyup', function() {
	recalculateTotalToPay();
});

$(`input[name="discount_type"]`).on('change', function() {
	$(`input[name="discount_amount"]`).trigger('focus');
	recalculateTotalToPay();
});

function recalculateTotalToPay(){
	let totalToPay = 0;

	if($("tr.products-row .row-subtotal").length){
		let discountAmount;

		let discountType = $(`input[name="discount_type"]:checked`).val();
		if($(`input[name="discount_amount"]`).val().length > 0) {
			discountAmount = parseFloat($(`input[name="discount_amount"]`).val());
		} else {
			discountAmount = 0;
		}
		
		totalDiscountDeducted = 0;
		overallSubTotal = 0;

		$("tr.products-row .row-subtotal div").each(function(){
			let subtotalVal = parseFloat($(this).text());
			totalToPay += subtotalVal;
			overallSubTotal += subtotalVal;
		});
		if(discountType == "cash") {
			totalToPay = totalToPay - discountAmount;
			totalDiscountDeducted = discountAmount;
		} else {
			discountAmount = parseFloat((discountAmount/100)*totalToPay).toFixed(2);
			totalToPay = (totalToPay - discountAmount);
			totalDiscountDeducted = discountAmount;
		}
		$(`th[data-bind-html='discount_amount']`).html(`${formatCurrency(discountAmount)}`);  
	}

	let paymentType = $(".payment-type-select").val();
	$(`span[class="sub_total"]`).html(`${companyVariables.cur} ${formatCurrency(overallSubTotal)}`);
	$("[data-bind-html='totaltopay']").html(formatCurrency(overallSubTotal));
	$(".total-to-pay-amount").text(formatCurrency(totalToPay));
	$(".total-to-pay-amount").attr("data-order-total", totalToPay);
	$(`input[name="amount_paying"]`).attr({"max": totalToPay, "min": 0});
	$(`input[name="amount_to_pay"]`).attr({"max": totalToPay, "value": formatCurrency(totalToPay)});
}

$.expr[':'].Contains = function(a,i,m){
	return $(a).text().toUpperCase().indexOf(m[3].toUpperCase())>=0;
};

$("#products-search-input").on('input', function(event) {
	let input = $(this).val();
	let selectedCat = $(".category-select").val();
	$(".temp-row").remove();
	if(selectedCat == "all" || selectedCat == ""){
		$(`table[id="products-table"] thead tr`).show();
		$(`td.product-title-cell`).parent().hide();
		$(`td.product-title-cell:Contains(${input})`).parent().show();
		checkForEmptyTable($("#products-table"));
	}
	else{
		$(`table[id="products-table"] thead tr`).show();
		$(`td.product-title-cell`).parent().hide();
		$(`td.product-title-cell:Contains(${input})`).parents(`tr[data-category='${selectedCat}']`).show();
		checkForEmptyTable($("#products-table"));
	}
});

$(".category-select").on("change", function(){
	let selectedCat = $(this).val();
	let searchInput = $("#products-search-input").val();
	$(".temp-row").remove();
	$(`table[id="products-table"] thead tr`).show();
	if(selectedCat == "all" || selectedCat == "") {
		if(searchInput.length){
			$(`td.product-title-cell`).parent().hide();
			$(`td.product-title-cell:Contains(${searchInput})`).parent().show();			
			checkForEmptyTable($("#products-table"));		
		}
		else {
			$("tr", $("#products-table")).show();
			checkForEmptyTable($("#products-table"));
		}
	}
	else{
		if(searchInput.length){
			$(`td.product-title-cell`).parents(`tr:not([data-category='${selectedCat}'])`).hide();
			$(`td.product-title-cell:Contains(${searchInput})`).parents(`tr[data-category='${selectedCat}']`).show();	
			checkForEmptyTable($("#products-table"));
		}
		else{
			$(`tr[data-category='${selectedCat}']`, $("#products-table")).show();
			$(`tr:not([data-category='${selectedCat}'])`, $("#products-table")).hide();
			checkForEmptyTable($("#products-table"));
		}
	}
	$(`table[id="products-table"] thead tr`).show();
});

function checkForEmptyTable(table){
	let tableCols = table.find("thead tr th").length;
	let tableRows = table.find("tbody tr:visible").length;
	if(!tableRows) table.find("tbody").append(`<tr class='temp-row'><td colspan='4' class='text-center'>No Item Found</td></tr>`);
}

function recalculateRowNumber(){
	if(!$("tr.products-row").length) {
		$(`div[class~="save-div"]`).addClass('hidden');
		$(".empty-message-row").show();
	}
	$("tr.products-row").each(function(index, el){
		let rowNumber = index +1;
		$("td:first", $(this)).text(rowNumber)
	})
}

function removeProductRow(rowId){
	let tbody = $(".products-table-body");
	let receipt_tbody = $(".receipt-table-body");
	$(`tr.receipt-product-row[data-row-id="${rowId}"]`, receipt_tbody).remove();
	$(`tr.products-row[data-row-id="${rowId}"]`, tbody).remove();
	recalculateRowNumber();
	recalculateTotalToPay();
}

function addProductRow(rowData) {

	return new Promise((resolve, reject) => {

		let qty = 1;
		let subTotal = parseFloat(rowData.productPrice)*qty;
		productPrices[rowData.productId] = parseFloat(rowData.productPrice);
		$(".empty-message-row").hide();
		let tbody = $(".products-table-body");
		let rowCount = $("tr.products-row", tbody).length || 0;
		rowCount++;

		let tr = `<tr class='products-row' data-row-id='${rowData.productId}'>
		
		<td class='products-row-number'>${rowCount}</td>
		<td>${rowData.productName}</td>
		<td><input type="number" min="1" onkeypress="return isNumber(event)" data-name="${rowData.productName}" form="pos-form-horizontal" name="products[${rowData.productId}][price]" class="form-control product-price" style="width:110px" value="${rowData.productPrice}"></td>
		<td>
		<input type='number' onkeypress="return isNumber(event)" data-name="${rowData.productName}" form="pos-form-horizontal" name="products[${rowData.productId}][qty]" min="1" data-max='${rowData.product_max}' data-row='${rowData.productId}' class='form-control product-quantity' value="${qty}">
		</td>
		<td class='row-subtotal'><div class="mt-2">${subTotal}</div></td>
		<td class='p-0'><button class='btn mt-4 btn-sm btn-outline-danger mb-1 remove-row' data-row='${rowData.productId}'><i class='fa fa-times'></i></button></td>
		</tr>`;
		let rr = `<tr class='receipt-product-row' data-row-id='${rowData.productId}'>
			<td>${rowData.productName}</td>
			<td class='receipt-row-quantity'>${qty}</td>
			<td class="text-right receipt-row-subtotal">${subTotal}</td>
		</tr>`;
		tbody.append(tr);
		$("[data-bind-html='productrow']").append(rr);
		resolve(rowData);
	})
}

var initialiteProductSelect = () => {
  	$(".product-select").on("change", async function(){
  		if($(this).is(":checked")) {
  			await addProductRow($(this).data())
  			.then((row) => {
  				recalculateTotalToPay();
  				$(`div[class~="save-div"]`).removeClass('hidden');
  				let subTotalBox = $(".row-subtotal", $(`tr.products-row[data-row-id='${row.productId}']`));
  				let receipt_subTotal = $(".receipt-row-subtotal", $(`tr.receipt-product-row[data-row-id='${row.productId}']`));
  				let receipt_qty = $(".receipt-row-quantity", $(`tr.receipt-product-row[data-row-id='${row.productId}']`));
  				$(`.product-quantity[data-row='${row.productId}'], input[name="products[${row.productId}][price]"]`).on("input", function(){
  					let subtotal;
  					let currentInput = $(this);
  					let selQuantityInput = $(`.product-quantity[data-row='${row.productId}']`);
  					let selectedQty = selQuantityInput.val().length ? parseInt(selQuantityInput.val()) : 0;
  					let productName = currentInput.attr('data-name');

  					if(selectedQty < 1) { 
  						selectedQty = 1;
  						selQuantityInput.val(1); 
  					}

  					if(currentInput.hasClass('product-quantity')) {
  						subtotal = (productPrices[row.productId]*selectedQty).toFixed(2);
  					} else {
  						let value;
  						if(isNaN(parseFloat(currentInput.val()))) {
  							value = 0;
  						} else {
  							value = parseFloat(currentInput.val());
  						}
  						subtotal = (value*selectedQty).toFixed(2);
  					}
  					
  					subTotalBox.text(subtotal);
  					receipt_qty.text(selectedQty);
  					receipt_subTotal.text(formatCurrency(subtotal));
  					recalculateTotalToPay();
  					
  					if((sessionName == "OrdersList") && currentInput.hasClass('product-quantity')) {
  						let maximumQty = parseInt(selQuantityInput.attr('data-max'));
	  					if(selectedQty > maximumQty) {
	  						selQuantityInput.val(maximumQty);
	  						Toast.fire({
		  						type: 'error',
		  						title: `Sorry. Maximum number of available ${productName} is ${maximumQty}`
		  					});
	  					}
	  				}
  				})
  				$(`.remove-row[data-row='${row.productId}']`).on("click", function(){
  					removeProductRow(row.productId);
  					$(`.product-select[data-product-id='${row.productId}']`).prop({"checked": false})
  				});
  			})
  		}
  		else removeProductRow($(this).data("productId"))
  	});
}

$(`div[class~="request-form"] button[class~="save-request"]`).on('click', function() {
    var btnClicked = $(this),
    	buttonClicked = $(this).attr('data-request'),
    	requestId = randomInt(12);
    	customerId = $(`select[name="customer"]`).val();
    	discountType = $(`input[name="discount_type"]:checked`).val();
    	request = $(`span[class="hide-walk-in-customer"]`).data('request');
    	discountAmt = $(`input[name="discount_amount"]`).val(),
    	selectedProducts = new Array(),
    	thisProductTotal = 0,
    	productSubTotal = 0;

    	$(`div[class="form-content-loader"]`).css("display","flex");

	    $.each($(`tbody[class="products-table-body"] tr[class="products-row"]`), function(i, e) {
	        let productId = $(this).data("row-id");
	            productQuantity = parseFloat($(`input[name="products[${productId}][qty]"]`).val());
	            productPrice = parseFloat($(`input[name="products[${productId}][price]"]`).val());
	            thisProductTotal = (productQuantity * productPrice);
	            productSubTotal += thisProductTotal;

	        selectedProducts.push({productId,productQuantity,productPrice,thisProductTotal});
	    });

	    let discountAmount = 0;

        if(discountAmt.length > 0) {
            if(discountType == "cash") {
                if(discountAmt > productSubTotal) {
                    discountAmt = productSubTotal;
                    $(`input[name="discount_amount"]`).val(productSubTotal);
                }
                discountAmount = parseFloat(discountAmt);
            } else if(discountType == "percentage") {
                if(discountAmt > 100) {
                    discountAmt = 100;
                    $(`input[name="discount_amount"]`).val(100);
                }
                discountAmount = (parseFloat(discountAmt)/100)*productSubTotal;
            }
        }

	    let overallTotal = parseFloat(productSubTotal - discountAmount).toFixed();

	    if(customerId == "null") {
	    	Toast({
	    		type: "error",
	    		title: "Please select customer to continue!"
	    	});
	    	$(`div[class="form-content-loader"]`).css("display","none");
	    	return false;
	    }

	    if(selectedProducts.length < 1) {
	    	Toast({
	    		type: "error",
	    		title: "Please select at least one product!"
	    	});
	    	$(`div[class="form-content-loader"]`).css("display","none");
	    	return false;	
	    }

	    if(confirm("Are you sure you want to complete this transaction?")) {
		    $.post(`${baseUrl}aj/pushRequest`, {selectedProducts, customerId, request, discountAmt, discountType}, function(resp) {
		    	
		    	if(resp.status != 200) {
		    		Toast({
		    			type: "error",
		    			title: resp.result
		    		});
		    		$(`div[class="form-content-loader"]`).css("display","none");
		    	} else {

		    		$(`div[class="form-content-loader"]`).css("display","none");
		    		
		    		Toast({
		    			type: "success",
		    			title: resp.result.message
		    		});

		    		if(buttonClicked == "save-invoice") {
	                    setTimeout(function() {
	                        window.location.href = `${baseUrl}invoice/${resp.result.invoiceNumber}`;
	                    }, 500);
	                } else {
	                    setTimeout(function() {
	                        window.location.href = `${baseUrl}requests/${resp.result.requestType}`;
	                    }, 1000);
	                }
		    	}
		    }, "json").catch((err) => {
		    	$(`div[class="form-content-loader"]`).css("display","none");
		    	Toast({
	    			type: "error",
	    			title: "Sorry! Error processing request."
	    		});
		    });
		}
});