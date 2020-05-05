$(function() {

	// Get the modal
	var modalWindow = document.getElementsByClassName("myModal")[0];
	var closeBtn = document.getElementsByClassName("close");
	
	// When the user clicks on <span> (x), close the modal
	$(`span[class~="close"]`).on('click', function() {
		$(`div[class~="myModal"]`).css("display","none");
	});

	// When the user clicks anywhere outside of the modal, close it
	window.onclick = function(event) {
		if (event.target == modalWindow) {
	  		$(`div[class~="myModal"]`).css("display","none");
	  	}
	}

	$(`button[class~="closeModal"]`).on('click', function() {
		$(`div[class~="myModal"]`).css("display","none");
	});
	
	$(`button[class~="validate-account"]`).on('click', function(evt) {
		$(`div[class="tabset"] input[id="tab1"]`).trigger('click');
	});

	$(`div[class~="importModal"] button[class~="confirm-import"]`).on('click', function(evt) {
		$(`div[class~="importModal"] button`).prop('disabled', true);
		$(`div[class~="importModal"] button[class~="confirm-import"]`).html(`Processing request...`);

		var formData = {
			'action': 'importData'
		}

		$.post(ajaxurl, formData, function(resp) {
			$(`div[class~="importModal"] button`).prop('disabled', false);
			$(`div[class~="importModal"] button[class~="confirm-import"]`).html(`Confirm`);

			if(resp.status == 'success') {
				alert('Data was successfully imported! Proceed to import the images.');
				window.location.href = $(`input[name="admin_pg_url"]`).val() + "admin.php?page=evelynpos_api&import_images";
			}
		}, 'json');

	});

	$(`form[id="apiValidateForm"]`).on('submit', function(evt) {
		evt.preventDefault();

		var formData = {
			'action': 'validateApiKeys',
			'username': $(`input[name="Username"]`).val(),
			'api_key': $(`input[name="apiKey"]`).val(),
		};

		$.post(ajaxurl, formData, function(resp) {
			if(resp.status == 'success') {
	    		$(`div[class="account-results"]`).html(`<div class="notice notice-success settings-error">Api Key was successfully validated!</div>`);
	    		setTimeout(function() {
	    			// window.location.href = '';
	    		}, 1000);
	    	} else if(resp.status == 'error') {
	    		$(`div[class="account-results"]`).html(`<div class="error">Error! Validating Api Keys</div>`);
	    	} else {
	    		$(`div[class="account-results"]`).html(`<div class="error">Error processing request!</div>`);
	    	}
	    	setTimeout(function() {
	    		$(`div[class="account-results"]`).html(``);
	    	}, 5000);
		}, 'json').catch((err) => {
	    	$(`div[class="account-results"]`).html(`<div class="error">Error processing request!</div>`);
	    });

	});

	$(`div[class~="account-wrapper"] button[type="submit"]`).on('click', function(evt) {
		var formData = {
			'action': 'updateAccountDetails',
			'company_name': $(`input[name="company_name"]`).val(),
			'email': $(`input[name="email"]`).val(),
			'website': $(`input[name="website"]`).val(),
			'primary_contact': $(`input[name="primary_contact"]`).val(),
			'secondary_contact': $(`input[name="secondary_contact"]`).val(),
			'address': $(`input[name="address"]`).val()
		};

		$(`div[class~="account-wrapper"] input, div[class~="account-wrapper"] button`).prop('disabled', true);

		$.post(ajaxurl, formData, function(resp) {
	    	console.log(resp);
	    	if(resp.status == 'success') {
	    		$(`div[class="account-results"]`).html(`<div class="notice notice-success settings-error">${resp.result}</div>`);
	    	} else if(resp.status == 'error') {
	    		$(`div[class="account-results"]`).html(`<div class="error">${resp.result}</div>`);
	    	} else {
	    		$(`div[class="account-results"]`).html(`<div class="error">Error processing request!</div>`);
	    	}

	    	$(`div[class~="account-wrapper"] input, div[class~="account-wrapper"] button`).prop('disabled', false);

	    	setTimeout(function() {
	    		$(`div[class="account-results"]`).html(``);
	    	}, 5000);
	    }, 'json').catch((err) => {
	    	$(`div[class="account-results"]`).html(`<div class="error">Error processing request!</div>`);
	    });
	});

	function populateInventoryList(inventoryData, navLink, startIndex = 0) {

		var tableHtml = ``;

		if(inventoryData == 'Limit Exceeded') {
			tableHtml += `<tr>
				<td colspan="7" class="text-center">No data found beyond this limit.</td>
			</tr>`;
		} else {
			let row_id = startIndex,
				importBadge = ``;
			$.each(inventoryData, function(i, e) {
				row_id++;

				if(e.imported == true) {
					importBadge = '<span class="badge badge-success">Imported</span>';
				}

				tableHtml += `<tr>
					<td>${row_id}</td>
					<td>${e.product_title}
						<br><div class="badge">${e.branch_name}</div>
						${importBadge}
					</td>
					<td>${e.category}</td>
					<td>${e.cost_price}</td>
					<td>${e.product_price}</td>
					<td>${e.quantity}</td>
					<td>
						<a href="javascript:void(0)" data-product-id="${e.pid}_${e.product_id}" class="edit-product button button-success">Edit</a> 
						<a class="button button-danger delete-product" data-product-id="${e.pid}_${e.product_id}" href="javascript:void(0)">Delete</a>
					</td>
				</tr>`; 
			});
		}

		tableHtml += navLink;

		$(`div[class="inventory-listing"] table[class~="inventory"] tbody`).html(tableHtml);
		$(`div[class="form-content-loader"]`).css('display', 'none');
		$(`div[class="inventory-listing"] table[class~="inventory"] tbody`).slideDown();

		$(`div[class="inventory-listing"] a[class~="delete-product"]`).on('click', function() {
			let productId = $(this).attr('data-product-id');

			$(`div[class~="deleteModal"] [id="productId"]`).val(productId);
			$(`div[class~="deleteModal"]`).css("display","block");

		});
	}


	function loadInventoryData(startIndex, endIndex, forceImport = 'no') {

		$(`div[class="form-content-loader"]`).css('display', 'flex');
		$(`div[class="inventory-listing"] table[class~="inventory"] tbody`).hide();

		$.post(ajaxurl, {action: 'loadInventory', loadInventory: true, startIndex: startIndex, endIndex: endIndex, forceImport: forceImport}, function(resp) {

			if(resp.status == true) {
				populateInventoryList(resp.result, resp.navLink, startIndex);
			} else if(resp.status == 'error') {
				$(`div[class="form-content-loader"]`).css('display', 'none');
				$(`div[class="inventory-listing"] table[class~="inventory"] tbody`)
					.html(`<tr><td colspan="7" class="text-center"><em>Sorry! An error was encountered while processing the request.</em></td></tr>`)
			}

		}, 'json').catch((err) => {
			$(`div[class="inventory-listing"] table[class~="inventory"] tbody`)
				.html(`<tr><td colspan="7" class="text-center"><em>Sorry! An error was encountered while processing the request.</em></td></tr>`)
		});

	}

	if($(`div[class="inventory-listing"] table[class~="inventory"]`).length) {
		let startIndex = $(`input[name="startIndex"]`).val(),
			endIndex = $(`input[name="endIndex"]`).val();
		loadInventoryData(startIndex, endIndex);
	}

	$(`div[class="evelyn-wrapper"] button[class~="import-inventory"]`).on('click', function(evt) {
		let startIndex = $(`input[name="startIndex"]`).val(),
			endIndex = $(`input[name="endIndex"]`).val(),
			forceImport = 'yes';

		loadInventoryData(startIndex, endIndex, forceImport);
	});

	$(`button[class~="import-data"]`).on('click', function() {
		$(`div[class~="importModal"]`).css("display","block");
	});

	$(`button[class~="sync-data"]`).on('click', function() {
		$(`div[class~="syncronizeModal"]`).css("display","block");
	});

});