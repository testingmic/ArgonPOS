const customerIndex = 0, productsIndex = 1, paymentIndex = 2, completeIndex = 3;
	var labels = [
		"Customer",
		"Products",
		"Payment",
		"Complete"
	];
	const leftArrow = "<i class='fa fa-arrow-circle-left font-12 mr-1'></i>";
	const rightArrow = "<i class='fa fa-arrow-circle-right font-12 ml-1'></i>";
	$(`button[class~="discardSale_trigger"]`).css('display', 'none');
	
	$("#pos-form-horizontal").steps({
		headerTag: "h3",
		bodyTag: "fieldset",
		transitionEffect: "slide",
		enablePagination: false,
		onFinished(e){
			rtRegForm();
			$(".content-loader.register-form-loader").css({display: "none"});
		},
		onInit(event, newIndex){
			let self = this;
			$("[data-step-action='previous'").prop("disabled", newIndex == customerIndex);
			$("[data-step-action='next'").toggle(newIndex !== completeIndex);
			$("[data-step-action='finish'").toggle(newIndex == completeIndex);
			$(".print-receipt").toggle(newIndex == completeIndex);
			$("[data-step-action='previous'").html(leftArrow + labels[newIndex]);
			$("[data-step-action='next'").html(labels[newIndex+1] + rightArrow);
			$(".custom-steps-actions").on("click", "button[data-step-action]", function(e){
				$(self).steps($(this).data("stepAction"));
			});
			$(`select[class~="custom-select2"]`).select2({ width: '280px' });
		},
		onStepChanged(event, currentIndex, newIndex){
			$(".register-form ul[role='tablist'] li").addClass("disabled")
			$(".register-form ul[role='tablist'] li.current").removeClass("disabled")
		},
		onStepChanging(event, currentIndex, newIndex){

			var selectedPayType = $(`select[class~="payment-type-select"]`).val();
	  		var amountPaid = parseFloat($(`input[name="amount_paying"]`).val());
	  		var amountPayable = parseFloat($(`input[name="amount_to_pay"]`).attr("max"));
	  		var customerSelected = $(`select[class~="customer-select"]`).val();

	  		if (isNaN(amountPaid)) {
	  			amountPaid = 0;
	  		}

	  		if(currentIndex == customerIndex) {
	  			if(customerSelected == "WalkIn") {
	  				$(`h6[class~="selected-customer-name"]`).html('Walk In Customer');
	  				$("[data-bind-html='customer']").html(`Walk In Customer`);
	  			}
	  		}

	      	if(newIndex == paymentIndex) {
	        let preferredPayment = $(`select[class~="customer-select"]`).children("option:selected").data('prefered-payment');
	        
		        if(preferredPayment != null) { 	
			        if(preferredPayment.length > 0) {
			          $(`select[name="payment_type"]`).val(preferredPayment).change();
			        }
			    }
	      	}

			if(currentIndex == customerIndex && customerSelected == "0"){
				Toast.fire({
					type: 'error',
					title: "Please select a customer"
				});
				return false;
			}	
			else if((currentIndex == productsIndex && !$("input:checkbox.product-select:checked").length) && (newIndex != 0)) {
				Toast.fire({
					type: 'error',
					title: "Please select product(s)"
				});
				return false;					
			}
			else if((currentIndex == paymentIndex && selectedPayType == "0") && (newIndex != 1)) {
				Toast.fire({
					type: 'error',
					title: "Please select a payment type"
				});
				return false;
			}
			else if(((selectedPayType == "cash") && (amountPaid < amountPayable) && (newIndex == completeIndex))) {
				Toast.fire({
					type: 'error',
					title: "Amount being paid is less than the total amount."
				});
				return false;
			}
			else {
			 // Set navigation button texts
  			$("[data-step-action='previous']").html(leftArrow + (labels[newIndex-1] || labels[0]));
  			$("[data-step-action='next']").html(labels[newIndex+1] + rightArrow);
  			$("[data-step-action='previous']").prop("disabled", newIndex == customerIndex);

			if(newIndex == productsIndex) $("[data-step-action='next']").prop("disabled", false);
  			$(".newCustomer_trigger").toggle(newIndex == customerIndex);
  			
  			if(newIndex != customerIndex) {
  				$(`button[class~="discardSale_trigger"]`).css('display', 'block');
  			} else {
  				$(`button[class~="discardSale_trigger"]`).css('display', 'none');
  			}

  			if(newIndex == 3) {
  				$(`button[class~="discardSale_trigger"]`).css('display', 'none');	
  			}
  			$("[data-step-action='next'], [data-step-action='previous']").toggle(newIndex !== completeIndex);
  			$("[data-step-action='finish']").toggle(newIndex == completeIndex);
  			$(".print-receipt").toggle(newIndex == completeIndex);
  			$("button.remove-row").prop("disabled", newIndex == completeIndex).addClass("text-muted");
  			$("input.product-quantity").prop("readonly", newIndex == completeIndex);
  			$(`div[class~="order_discounting"] input`).prop('disabled', newIndex == completeIndex);

  			if(newIndex == completeIndex){
  				
  				dOC().then((itResp) => {
		            if(itResp == 1) {
		                noInternet = false;
		                $(`div[class="connection"]`).css('display','none');
		            } else {
		                noInternet = true;
		                $(`div[class="connection"]`).css('display','block');
		            }
		        }).catch((err) => {
		            noInternet = true;
		            $(`div[class="connection"]`).css('display','block');
		        });

		        if(noInternet) {

		        	sPIDB().then((resp) => {
		        		
		        		if(resp.status == 'error') {
		        			Toast.fire({
		  						type: 'error',
		  						title: resp.result
		  					});
		        		} else {
		        			$("[data-bind-html='orderId']").html(resp.orderId);
		        			$(`span[class="generated_order"]`).html(resp.orderId);
		  					Toast.fire({
		  						type: 'success',
		  						title: "Payment Successfully Recorded"
		  					});
		  					$(".cash-process-loader").removeClass("d-flex");
		  					if(companyVariables.prt == "yes") {
		  						$(`button[class~="print-receipt"]`).trigger('click');
		  					}
		  					fetchPOSProductsList();
		  					fetchPOSCustomersList();
		        		}

		        	});

		        } else {
	  				svReg().then((res) => {
	  					$("[data-bind-html='orderId']").html(res.data._oid);
	  					$(`span[class="generated_order"]`).html(res.data._oid);
	  					Toast.fire({
	  						type: 'success',
	  						title: "Payment Successfully Recorded"
	  					});
	  					if(companyVariables.prt == "yes") {
	  						$(`button[class~="print-receipt"]`).trigger('click');
	  					}
	  					fetchPOSProductsList();
	  					fetchPOSCustomersList();
	  					$(".cash-process-loader").removeClass("d-flex");
	  				});
	  			}
  			}
  			return true;
  		}
		}
	});

	$(`a[class~="discard-sale"]`).on('click', function(e) {
		Toast.fire({
		type: 'success',
		title: 'Transaction was successfully discarded'
	});
	
	setTimeout(function() {
		window.location.href = `${baseUrl}point-of-sale`;
	}, 1500);
	});

	function svReg() {
		let formData = $("#pos-form-horizontal.register-form").serialize();
		let totalToPay = $("[data-order-total]").data("orderTotal");
		let discountType = $(`input[name="discount_type"]:checked`).val();
		let discountAmount = $(`input[name="discount_amount"]`).val();
		formData += `&total_to_pay=${totalToPay}&discountType=${discountType}&discountAmount=${discountAmount}`;
		return $.post(baseUrl+"aj/pointOfSaleProcessor/saveRegister", formData)
	}

	let productPrices = [];
	
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
			ckEmpTb($("#products-table"));
		}
		else{
			$(`table[id="products-table"] thead tr`).show();
			$(`td.product-title-cell`).parent().hide();
			$(`td.product-title-cell:Contains(${input})`).parents(`tr[data-category='${selectedCat}']`).show();
			ckEmpTb($("#products-table"));
		}
	});

	var initialiteProductSelect = () => {
  	$(".product-select").on("change", async function(){
  		if($(this).is(":checked")) {
  			await adProR($(this).data())
  			.then((row) => {
  				rcalTot();
  				amtPay();
  				let subTotalBox = $(".row-subtotal", $(`tr.products-row[data-row-id='${row.productId}']`));
  				let receipt_subTotal = $(".receipt-row-subtotal", $(`tr.receipt-product-row[data-row-id='${row.productId}']`));
  				let receipt_qty = $(".receipt-row-quantity", $(`tr.receipt-product-row[data-row-id='${row.productId}']`));
  				$(`.product-quantity[data-row='${row.productId}']`).on("input", function(){
  					let currentInput = $(this);
  					let selectedQty = currentInput.val().length ? parseInt(currentInput.val()) : 0;
  					let maximumQty = parseInt(currentInput.attr('data-max'));
  					let productName = currentInput.attr('data-name');

  					if(selectedQty < 1) { 
  						selectedQty = 1;
  						currentInput.val(1); 
  					}
  					let subtotal = (productPrices[row.productId]*selectedQty).toFixed(2);
  					subTotalBox.text(subtotal);
  					receipt_qty.text(selectedQty);
  					receipt_subTotal.text(formatCurrency(subtotal));
  					rcalTot();
  					amtPay();
  					
  					if(selectedQty > maximumQty) {
  						currentInput.val(maximumQty);
  						Toast.fire({
	  						type: 'error',
	  						title: `Sorry. Maximum number of available ${productName} is ${maximumQty}`
	  					});
  					}
  				})
  				$(`.remove-row[data-row='${row.productId}']`).on("click", function(){
  					rvPtRow(row.productId);
  					$(`.product-select[data-product-id='${row.productId}']`).prop({"checked": false})
  				})
  				$(".print-receipt").on("click", printReceipt)
  			})
  		}
  		else rvPtRow($(this).data("productId"))
  	});
}

	$(".category-select").on("change", function(){
		let selectedCat = $(this).val();
		let searchInput = $("#products-search-input").val();
		$(".temp-row").remove();
		$(`table[id="products-table"] thead tr`).show();
		if(selectedCat == "all" || selectedCat == "") {
			if(searchInput.length){
				$(`td.product-title-cell`).parent().hide();
				$(`td.product-title-cell:Contains(${searchInput})`).parent().show();			
				ckEmpTb($("#products-table"));		
			}
			else {
				$("tr", $("#products-table")).show();
				ckEmpTb($("#products-table"));
			}
		}
		else{
			if(searchInput.length){
				$(`td.product-title-cell`).parents(`tr:not([data-category='${selectedCat}'])`).hide();
				$(`td.product-title-cell:Contains(${searchInput})`).parents(`tr[data-category='${selectedCat}']`).show();	
				ckEmpTb($("#products-table"));
			}
			else{
				$(`tr[data-category='${selectedCat}']`, $("#products-table")).show();
				$(`tr:not([data-category='${selectedCat}'])`, $("#products-table")).hide();
				ckEmpTb($("#products-table"));
			}
		}
		$(`table[id="products-table"] thead tr`).show();
	});

	$(".customer-select").on("change", function(){
		if($(this).val() == "0") $(".selected-customer-name").html("No Customer Selected");
		else {
			
			let selectedOption = $(this).children("option:selected");
			let selectedContact = $(this).children("option:selected").data('contact');
      let preferredPayment = $(this).children("option:selected").data('prefered-payment');

      if(preferredPayment != null) { 	
	      if(preferredPayment.length > 0) {
	        $(`select[name="payment_type"]`).val(preferredPayment)
	      }
	  }

  		$("[data-bind-html='customer']").html(`
  			${selectedOption.text()}<br>
  				<span class="text-primary"><small>(${selectedContact})</small>
  		`);
			$(".selected-customer-name").html(`
				<h3 class='text-success'>
					<span class="email-fullname">${selectedOption.text()}</span><br>
					<span class="text-primary"><small>(${selectedContact})</small></span>
				</h3>`);
			$("input[id='receipt-email']").val(selectedOption.data("email"));
		}
	});

	$(".payment-type-select").on("change", async function(){

		var selectedPaymentType = $(this).val();

		$(`input[name="amount_paying"]`).prop("value","");
		$(`input[name="amount_to_pay"]`).attr({"max": $(".total-to-pay-amount").attr("data-order-total"), "value": formatCurrency($(".total-to-pay-amount").attr("data-order-total"))});
		$(`input[name="amount_balance"]`).prop("value","0.00");

		$("[data-bind-html='payment']").html(formatCurrency($(".total-to-pay-amount").attr("data-order-total")));
		$("[data-bind-html='balance']").html(selectedPaymentType == 'credit' ? formatCurrency($(".total-to-pay-amount").attr("data-order-total")) : '0.00');

		if(selectedPaymentType == "0") {
			$(".selected-payment-type").html("None Selected");
		}
		else {
			$(".selected-payment-type").html(`<h5 class='text-success'>${$(this).children("option:selected").text()}</h5>`);
		}
		rcalTot();
		// Show Payment Button For Teller Payment
		if (["MoMo", "card", "cash"].includes(selectedPaymentType)) {

		$("[data-step-action='next']").prop("disabled", true);

		var paymentTotal = $(".total-to-pay-amount").attr("data-order-total");

		if(["MoMo", "card"].includes(selectedPaymentType)) {
			$(".make-online-payment").attr("data-order-total", paymentTotal);
			$(`div[class~="cash-processing"]`).slideUp('fast');
			$(".make-online-payment").removeClass("d-none");
		} else {
			$(".make-online-payment").addClass("d-none");
			$(`div[class~="cash-processing"]`).slideDown('fast');
			$(`input[name="amount_paying"]`).focus();
		}
	} else {
		$(".total-to-pay-amount").attr("data-order-total", formatCurrency($(".total-to-pay-amount").attr("data-order-total")));
		$(`div[class~="cash-processing"]`).slideUp('fast');
		$(".make-online-payment").addClass("d-none");
		$(".make-online-payment").removeAttr("data-order-total");
		$("[data-step-action='next']").prop("disabled", false);
	}
	});

	function rtRegForm() {
		let regForm = $(".register-form");
		let firstTab = $(".register-form ul[role='tablist'] li.first");
		regForm.trigger("reset");
		firstTab.removeClass("disabled")
		$("a", firstTab).trigger("click");
		$(".receipt-table-body").html('');
		$(`input[name="discount_amount"]`).val('');
		$(".selected-customer-name").html("No Customer Selected");
		$(`button[class~="discardSale_trigger"]`).css('display', 'none');
		$(".products-table-body tr:not(.empty-message-row)").remove();
		$(`input[name="amount_paying"]`).attr({"max": 0, "min": 0});
		$(`input[name="amount_to_pay"]`).attr({"max": 0, "value": ""});
		$(`span[class="total-to-pay-amount"]`).attr("data-order-total", "0.00");
		$(`div[class~="order_discounting"] input`).prop('disabled', false);
		rcalRowNum();
		$(".payment-type-select").trigger("change")
		rcalTot();
	}

	function adProR(rowData) {

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
			<td style="padding-top:20px">${rowData.productName}</td>
			<td style="padding-top:20px">${rowData.productPrice}</td>
			<td>
			<input type='number' data-name="${rowData.productName}" form="pos-form-horizontal" name="products[${rowData.productId}][qty]" min="1" data-max='${rowData.product_max}' data-row='${rowData.productId}' class='form-control product-quantity' value="${qty}">
			<input type="hidden" data-name="${rowData.productName}" form="pos-form-horizontal" name="products[${rowData.productId}][price]" value="${rowData.productPrice}"></td>
			<td style="padding-top:20px" class='row-subtotal'>${subTotal}</td>
			<td class='p-0'><button class='btn btn-sm mb-1 mt-4 btn-outline-danger remove-row' data-row='${rowData.productId}'><i class='fa fa-times'></i></button></td>
			</tr>`;
			let rr = `<tr class='receipt-product-row' data-row-id='${rowData.productId}'>
				<td>${rowData.productName}</td>
				<td class='receipt-row-quantity'>${qty}</td>
				<td class="text-right receipt-row-subtotal">${formatCurrency(subTotal)}</td>
			</tr>`;
			tbody.append(tr);
			$("[data-bind-html='productrow']").append(rr);
			resolve(rowData)
		})
	}

	function rvPtRow(rowId){
		let tbody = $(".products-table-body");
		let receipt_tbody = $(".receipt-table-body");
		$(`tr.receipt-product-row[data-row-id="${rowId}"]`, receipt_tbody).remove();
		$(`tr.products-row[data-row-id="${rowId}"]`, tbody).remove();
		rcalRowNum();
		rcalTot();
	}

var overallSubTotal = 0, totalDiscountDeducted = 0;

	function rcalTot(){
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

			$("tr.products-row .row-subtotal").each(function(){
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

	var amtPay = () => {
		let max = parseFloat($(`input[name="amount_paying"]`).attr('max'));
		let value = parseFloat($(`input[name="amount_paying"]`).val());
		let paymentType = $(".payment-type-select").val();
		let balance = (value-max);

		if($(`input[name="amount_paying"]`).val().length > 0) {
			$(`input[name="amount_balance"]`).val(formatCurrency(balance));

			$(".make-online-payment").addClass("d-none");
		$(".make-online-payment").removeAttr("data-order-total");
		$("[data-step-action='next']").prop("disabled", false);

		$(`[data-bind-html='amount_paid']`).html(`${companyVariables.cur} ${formatCurrency(value)}`);
		$("[data-bind-html='payment']").html(`${companyVariables.cur} ${formatCurrency(overallSubTotal-totalDiscountDeducted)}`);
			$("[data-bind-html='balance']").html(paymentType == 'credit' ? `${companyVariables.cur} ${formatCurrency(value)}` : `${companyVariables.cur} ${formatCurrency(balance)}`);
		} else {
			$(`input[name="amount_balance"]`).val('0.00');

			$(".make-online-payment").addClass("d-none");
		$(".make-online-payment").attr("data-order-total", max);
		$(`[data-bind-html='amount_paid']`).html(`${companyVariables.cur} 0.00`);
		$("[data-bind-html='payment']").html(paymentType == 'credit' ? `${companyVariables.cur} ${formatCurrency(max)}` : "${companyVariables.cur} 0.00");
			$("[data-bind-html='balance']").html(paymentType == 'credit' ? `${companyVariables.cur} ${formatCurrency(max)}` : "${companyVariables.cur} 0.00");
		}
}

$(`input[name="amount_paying"]`).on('keyup', function() {
	amtPay();
});

$(`input[name="discount_amount"]`).on('keyup', function() {
		rcalTot();
		amtPay();
	});

	$(`input[name="discount_type"]`).on('change', function() {
	$(`input[name="discount_amount"]`).trigger('focus');
		rcalTot();
		amtPay();
	});

	function rcalRowNum(){
		if(!$("tr.products-row").length) $(".empty-message-row").show();
		$("tr.products-row").each(function(index, el){
			let rowNumber = index +1;
			$("td:first", $(this)).text(rowNumber)
		})
	}

	$(`button[class~="send-email"]`).on('click', function(evt) {
		let thisBtn = $(this);
		let thisEmail = $(`input[id="receipt-email"]`);
		let fullname = $(`h6[class~="selected-customer-name"] span[class="email-fullname"]`).html();

		if(thisEmail.val().length > 5) {
			thisBtn.prop('disabled', true)
					.html(`Sending <i class="fa fa-spinner fa-spin"></i>`);
			thisEmail.prop('disabled', true);

			$.ajax({
				url: `${baseUrl}aj/pointOfSaleProcessor/sendMail`,
				type: `POST`,
				data: {sendMail: true, thisEmail: thisEmail.val(), fullname: fullname},
				dataType: "json",
				success: function(resp) {
					Toast.fire({
						type: resp.status,
						title: resp.message
					});
				}, error: function(err) {
					Toast.fire({
					type: 'error',
					title: 'Error processing request!'
				});
				thisEmail.prop('disabled', false);
		  		thisBtn.prop('disabled', false).html(`Send`);
		  	}, complete: function(data) {
		  		thisEmail.prop('disabled', false);
		  		thisBtn.prop('disabled', false).html(`Send`);
		  	}
			});
		} else {
			Toast.fire({
			type: 'error',
			title: 'Please enter a valid email address!'
		});
		thisEmail.prop('disabled', false);
  		thisBtn.prop('disabled', false).html(`Send`);
  	}
	});

	function ckEmpTb(table){
		let tableCols = table.find("thead tr th").length;
		let tableRows = table.find("tbody tr:visible").length;
		if(!tableRows) table.find("tbody").append(`<tr class='temp-row'><td colspan='4' class='text-center'>No Item Found</td></tr>`);
	}

	var paymentCheck;
	var paymentWindow;

	function printReceipt(e) {
		e.preventDefault();
		let orderId = $(`span[class="generated_order"]`).html();
		window.open(
		`${baseUrl}receipt/${orderId}`,
		`Sales Invoice - Receipt #${orderId}`,
		`width=650,height=750,resizable,scrollbars=yes,status=1`
		);
	}

	$(".make-online-payment").on("click", async function(e) {
		e.preventDefault();
		await svReg().then(function(res) {
  		if (res.status == "success") {
  			$(`span[class="generated_order"]`).html(res.data._oid);
  			var userEmail = $("input[id='receipt-email']").val();
	  		$.ajax({
	  			url: baseUrl + "aj/pointOfSaleProcessor/processMyPayment",
	  			data: { processMyPayment: true, orderId: res.data.orderId, orderTotal: res.data.orderTotal, userEmail: userEmail },
	  			dataType: "json",
	  			type: "POST",
	  			cache: false,
	  			beforeSend: function() {
	  				$(".payment-type-select, [data-step-action='previous']").prop("disabled", true);
		  			$(".make-online-payment").addClass("d-none");
		  			$(".cancel-online-payment").removeClass("d-none");
	  				$(".payment-processing-span").html(`
	  					<span class="fa fa-spinner fa-spin mr-3"></span>
	  				`);
	  			},
	  			success: function(data) {
	  				if (data.status == true) {
	  					if (data.message.action == true) {
	  						$(`[data-bind-html='amount_paid']`).html(`${companyVariables.cur} ${formatCurrency(res.data.orderTotal)}`);
	  						paymentWindow = window.open(data.message.msg, "_blank");
	  						paymentCheck = setInterval(function() {
	  							ckPayState();
	  						}, 3000);
	  					} else {
	  						$(".payment-processing-span").html(data.message.msg);
	  					}
	  				} else {
		  				Toast.fire({
							type: "error",
							title: "Payment Failed! Please try again."
						});
						$(".payment-processing-span").html(``);
		  				$(".payment-type-select, [data-step-action='previous']").prop("disabled", false);
		  				$(".make-online-payment").removeClass("d-none");
		  				$(".cancel-online-payment").addClass("d-none");
	  				}
	  			},
	  			error: function() {
	  				Toast.fire({
						type: "error",
						title: "Error Processing Request! Please try again"
					});
					$(".payment-processing-span").html(``);
		  			$(".payment-type-select, [data-step-action='previous']").prop("disabled", false);
		  			$(".make-online-payment").removeClass("d-none");
		  			$(".cancel-online-payment").addClass("d-none");
	  			}
	  		});
	  	} else {
	  		$(".payment-processing-span").html(`
					<p class="text-center text-danger">
						Payment Failed To Process Request
					</p>
				`);
				$(".payment-type-select, [data-step-action='previous']").prop("disabled", false);
	  	}
		});
});

function ckPayState() {
	
	$.ajax({
		url: `${baseUrl}aj/pointOfSaleProcessor/checkPaymentStatus`,
		type: "POST",
		data: { checkPaymentStatus: true },
		dataType: "json",
		cache: false,
		success: function(data) {
			if (data.status != false) {

				clearInterval(paymentCheck);

				let toastType = "error";
				let toastMsg = "Payment Cancelled";

				if (data.status == true) {
					$(".payment-processing-span").html(`
						<p class="text-center">
						<span class="fa fa-check text-success"></span>
						</p>
					`);
					toastMsg = "Payment Successfully Made.";
					toastType= "success";
					let lastTab = $(".register-form ul[role='tablist'] li.last");
					lastTab.removeClass("disabled");
					$("a", lastTab).trigger("click");
					lastTab.addClass("disabled");
					$("[data-step-action='next']").prop("disabled", false);
					$(".payment-type-select").prop("disabled", false);
					$(`span[class="payment-processing-span"]`).html(``);
				} else {
					$(".payment-type-select, [data-step-action='previous']").prop("disabled", false);
					$(".make-online-payment").removeClass("d-none");
					$(".payment-processing-span").empty();
				}

				// Move To Next Tab
				Toast.fire({
					type: toastType,
					title: toastMsg
				});
				$(".cancel-online-payment").addClass("d-none");
			}
		}
	});

}

$(".cancel-online-payment").on("click", function() {

	$.post(`${baseUrl}aj/pointOfSaleProcessor/cancelPayment`, {cancelPayment: true}, function(data) {
		data = $.parseJSON( data );
		let toastType = "error";
		let toastMsg  = "Failed To Cancel";
		if (data.status == 200) {
			clearInterval(paymentCheck);
			$(".payment-type-select, [data-step-action='previous']").prop("disabled", false);
			$(".make-online-payment").removeClass("d-none");
			$(".cancel-online-payment").addClass("d-none");
			$(".payment-processing-span").empty();
			if (paymentWindow) {
				paymentWindow.close();
			}
			toastType = "success";
			toastMsg  = "Payment Cancelled";
		}

		Toast.fire({
			type: toastType,
			title: toastMsg
		});
	});
});