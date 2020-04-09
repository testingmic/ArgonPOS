var resetProductForm  = () => {
    $(`div[class~="existing-product"]`).addClass('hidden');
    $(`div[class~="new-product"], div[class~="new-product-content"]`).removeClass('hidden');
    $(`form[id="addProductForm"] [class="modal-title"]`).html('Add New Product');
    $(`form[id="addProductForm"] button[type="submit"]`).html('Add Product');
    $(`form[id="addProductForm"] input[name="price"]`).val('');
    $(`form[id="addProductForm"] input[name="title"]`).val('');
    $(`div[class="form-content-loader"]`).css("display","none");
    $(`form[id="addProductForm"] input[name="cost"]`).val();
    $(`form[id="addProductForm"] input[name="threshold"]`).val();
    $(`form[id="addProductForm"] select[name="product_id"], form[id="addProductForm"] select[name="category"]`).val('null').change();
    $(`form[id="addProductForm"] textarea[name="description"]`).val('');
    $(`form[id="addProductForm"] input[name="product_image"]`).val('');
    $(`form[id="addProductForm"] select[name="product_type"]`).val('New').change();
}

$(`button[class~="pop-new-modal"]`).on('click', function() {
    resetProductForm();
});

var formControls = () => {
    $(`div[class~="update-stock-rows"] select[name^="product_id_"]`).on('change', function() {
        
        let cost_price = $(this).children('option:selected').data('cost-price');
        let retail_price = $(this).children('option:selected').data('retail-price');
        let threshold = $(this).children('option:selected').data('threshold');
        let rowId = $(this).data("row");

        $(`div[class~="update-stock-rows"] input[name="price_${rowId}"]`).val(retail_price);
        $(`div[class~="update-stock-rows"] input[name="cost_${rowId}"]`).val(cost_price);
        $(`div[class~="update-stock-rows"] input[name="threshold_${rowId}"]`).val(threshold);

    });
}

var transferProduct = (productId) => {

    let transfer_from = currentBranchId;

    $.ajax({
        url: baseUrl + "aj/inventoryManagement",
        type: "POST",
        dataType: "json",
        data: { getWarehouseProduct: true, productId: productId, transferFrom: transfer_from },
        cache: false,
        beforeSend: function() {
            $(".transferProductModal").modal("show");
        },
        success: function(data) {
            if (data.status == "success") {
                $(".prodImg").attr("src", `${baseUrl}${data.product.image}`);
                $(".prodName").html(data.product.product_title);
                $(".prodQty").html(`${data.product.quantity} Quantity Available`);
                $(`div[class~="transferProductModal"] [name="transferProductQuantity"]`).attr("max", data.product.quantity);
                $(`div[class~="transferProductModal"] [name="transferProductID"]`).val(data.product.product_id);
            } else {
                $(".prodName").html(`<p class="text-danger">Product Not Found</p>`);
            }
        },
        complete: function(data) {
        }
    });

}

$(`select[class~="selectpicker"]`).select2();
var submitTransferProduct = () => {
    
    $("form[class~='submit-transfer-product']").on("submit", function(e) {
        e.preventDefault();

        let msg = $(".transfer-form-message");

        if($(`form[class~='submit-transfer-product'] select[name="branchId"]`).val() == 'null') {
            Toast.fire({
                type: 'error',
                title: 'Please select branch to transfer items to continue.'
            });
        }
        else if (confirm("Do you want to transfer product now?")) {

            $.ajax({
                url: baseUrl + "aj/inventoryManagement/submitTransferProduct",
                type: "POST",
                dataType: "json",
                data: $(this).serialize() + "&request=submitTransferProduct",
                cache: false,
                beforeSend: function() {
                    $(`div[class="form-content-loader"]`).css("display","flex");
                    $("*", ".submit-transfer-product").prop("disabled", true);
                },
                success: function(data) {

                    if (data.status == true) {
                        Toast.fire({
                            type: 'success',
                            title: 'Product Successfully Transfered'
                        });
                        $(".submit-transfer-product")[0].reset();
                        
                        clearAllRows();
                        fetchAllProducts(branchID);
                        setTimeout(function() {
                            $(".transferProductModal").modal("hide");
                        }, 2000);
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data.message
                        });
                    }
                },
                error: function() {
                    msg.html(`
                        <p class="alert alert-danger"></p>
                    `);
                    Toast.fire({
                        type: 'error',
                        title: 'Error Processing Request'
                    });
                    $(`div[class="form-content-loader"]`).css("display","none");
                }, complete: function() {
                    $(`div[class="form-content-loader"]`).css("display","none");
                    $("*", ".submit-transfer-product").prop("disabled", false);
                    setTimeout(function() {
                        msg.empty();
                    }, 3000);
                }
            });

        }
    });

}

submitTransferProduct();


var fetchAllProducts = (branchID = null, location = branch_type) => {
    
    $.ajax({
        url: baseUrl + "aj/inventoryManagement/getAllProducts",
        type: "POST",
        dataType: "json",
        data: { request: true, getAllProducts: true, branchID: branchID, location: location },
        cache: false,
        beforeSend: function() {},
        success: function(data) {

            $(`table[id="allProducts"]`).dataTable().fnDestroy();
            $(`table[id="allProducts"]`).dataTable({
                "aaData": data.message,
                "iDisplayLength": 10,
                "buttons": ["copy", "print","csvHtml5"],
                "lengthChange": !1,
                "dom": "Bfrtip",
                "columns": [
                   {"data": 'row_id'},
                   {"data": 'product_name'},
                   {"data": 'category'},
                   {"data": 'price'},
                   {"data": 'quantity'},
                   {"data": 'indicator'},
                   {"data": 'action'}
                ]
            });
        },
        complete: function(data) {
            
        }
    });

}

$(`form[id="addProductForm"]`).on('submit', function(e) {
    
    e.preventDefault();
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: new FormData(this),
        dataType: 'JSON',
        contentType: false,
        cache: false,
        processData:false,
        beforeSend: function(){
            $(`div[class="form-content-loader"]`).css("display","flex");
        },
        success: function(resp){
            Toast.fire({
                type: resp.status,
                title: resp.message
            });
            if(resp.status == 'success') {
                fetchAllProducts(resp.branchId);
                resetProductForm();
                $(`form[id="addProductForm"]`)[0].reset();
            }
            $(`div[class="form-content-loader"]`).css("display","none");
        }, error: function(err) {
            $(`div[class="form-content-loader"]`).css("display","none");
            Toast.fire({
                type: "error",
                title: "Error processing request"
            });
        }
    });

});

var clearStockUpdateRows = () => {
    var lastRowId = $(`div[class~="update-stock-rows"] div[data-row]`).length;

    if (lastRowId > 1) {
        $.each($(`div[class~="update-stock-rows"] div[data-row]`), function(key, val) {
            if (lastRowId != 1) {
                $(`div[class~="update-stock-rows"] div[data-row="${lastRowId}"]`).remove();
                lastRowId--;
            }
        });
    }
}

$(`form[id="updateWareHouseStock"]`).on('submit', function(e) {
    e.preventDefault();

    var productsList = [];

    $.each($(`div[class~="update-stock-rows"] div[class~="stock-listing"]`), function(i, e) {
        var rowId = $(this).attr("data-row");
        if($(`select[id="product_id_${rowId}"]`).val() != 'null') {
            productsList.push($(`select[id="product_id_${rowId}"]`).val() + '|' + $(`input[name="cost_${rowId}"]`).val() + '|' + $(`input[name="price_${rowId}"]`).val() + '|' + $(`input[name="quantity_${rowId}"]`).val() + '|' + $(`input[name="threshold_${rowId}"]`).val());
        }
    });

    if(productsList.length < 1) {
        Toast.fire({
            type: 'error',
            title: 'Please select at least one item to continue.'
        });
    } else if(confirm("Do you confirm updating the stock levels?")) {
        
        var stockQuantities = productsList.join(",");

        $.ajax({
            url: `${baseUrl}aj/inventoryManagement/updateWareHouseStock`,
            data: {updateWareHouseStock: true, stockQuantities: stockQuantities},
            type: "POST",
            dataType: "json",
            beforeSend: function() {
                $(`div[class="form-content-loader"]`).css("display","flex");
            },
            success: function(data) {
                if(data.status == "error") {
                    Toast.fire({
                        type: 'error',
                        title: data.message
                    });
                } else if(data.status == "success") {
                    $(`div[id="updateProductModal"]`).modal('hide');
                    Toast.fire({
                        type: 'success',
                        title: data.message
                    });
                    $(`form[id="updateWareHouseStock"]`)[0].reset();
                    $(`form[id="updateWareHouseStock"] select[name="product_id_1"]`).val('null').change();
                    fetchAllProducts(currentBranchId);
                    clearStockUpdateRows();
                }
            },
            complete: function(resp) {
                $(`div[class="form-content-loader"]`).css("display","none");
            },
            error: function(err) {
                Toast.fire({
                    type: 'error',
                    title: 'Error Processing Request.'
                });
                $(`div[class="form-content-loader"]`).css("display","none");
            }
        });
    }

});

// Check If Checked All Have Been Triggered
$("#customCheck_checkAll").on('click', function() {
    if ($(this).is(":checked")) {
        $(this).addClass('btn');
    }
    $(".alltransferProducts").prop('checked', $(this).prop('checked'));
});

$(".transfer-selected-products").on("click", function(e) {
    e.preventDefault();

    $(".transferBulkProductModal").modal("show");
});



var removeRow = () => {
    $(`span[class~="remove-row"]`).on('click', function() {
        let rowId = $(this).attr('data-value');
        $(`div[class~="bulk-products-list"] [data-row="${rowId}"]`).remove();
    });
}

var removeAppendRow = () => {
    $(`span[class~="remove-this-row"]`).on('click', function() {
        let rowId = $(this).attr('data-value');
        console.log(rowId);
        $(`div[class~="update-stock-rows"] div[data-row="${rowId}"]`).remove();
    });
}


var clearAllRows = () => {
    var lastRowId = $(`div[class~="bulk-products-list"] [data-row]`).length;

    if (lastRowId > 1) {
        $.each($(`div[class~="bulk-products-list"] [data-row]`), function(key, val) {
            if (lastRowId != 1) {
                $(`div[class~="bulk-products-list"] [data-row="${lastRowId}"]`).remove();
                lastRowId--;
            }
        });
    }
}

var maximumValue = () => {
    $('div[class~="bulk-products-list"] [data-row] select').on('change', function(e) {
        let quantity = $(this).children('option:selected').data('quantity');
        let dataRow = $(this).parents('div[class~="products-listing"]:first').attr('data-row');
        $(`input[id="i_product_${dataRow}"]`).attr('max', quantity);
    });

    $('div[class~="bulk-products-list"] [data-row] input').on('input', function(e) {
        let curInput = $(this);
        let maxValue = parseInt(curInput.attr('max'));
        let curValue = parseInt(curInput.val());
        if(curValue > maxValue) {
            curInput.val(maxValue);
        }
    });

    $('form[class~="submit-transfer-product"] input').on('input', function(e) {
        let curInput = $(this);
        let maxValue = parseInt(curInput.attr('max'));
        let curValue = parseInt(curInput.val());
        if(curValue > maxValue) {
            curInput.val(maxValue);
        }
    });    
}

$(`button[class~="topup-products"]`).on('click', function(e) {
    let htmlData = $('div[class~="bulk-products-list"] [data-row]:last select').html();
    var lastRowId = $(`div[class~="bulk-products-list"] [data-row]`).length;

    lastRowId++;

    let selectOptions = $('div[class~="bulk-products-list"] [data-row]:last select > option').length;

    if(selectOptions == lastRowId) {
        return false;
    }

    $(`div[class~="bulk-products-list"] [data-row]:last`).after(`
        <div class="products-listing mb-2" data-row="${lastRowId}">
            <div class="row">
                <div class="col-sm-6">
                    <select name="products[]" id="s_product_${lastRowId}" class="form-control selectpicker">
                        ${htmlData}
                    </select>
                </div>
                <div class="col-sm-5">
                    <input value="0" type="number" id="i_product_${lastRowId}" class="form-control" min="1" placeholder="Product Quantity" name="transferProductQuantity" required="">
                </div>
                <div class="col-sm-1">
                    <div class="text-center">
                        <span class="remove-row" style="font-weight:bold; font-size:16px; line-height:2.5rem; color:brown; cursor:pointer" data-value="${lastRowId}">X</span>
                    </div>
                </div>
            </div>
        </div>
    `);

    $('.selectpicker').select2();
    removeRow();
    maximumValue();
});

maximumValue();


$(`div[class~="update-stock-rows"] button[class~="append-row"]`).on('click', function(e) {
    
    let htmlData = $('div[class~="update-stock-rows"] div[data-row]:last select').html();
    var lastRowId = $(`div[class~="update-stock-rows"] div[data-row]`).length;

    lastRowId++;

    let selectOptions = $('div[class~="update-stock-rows"] div[data-row]:last select > option').length;

    console.log(lastRowId, selectOptions);

    if(selectOptions == lastRowId) {
        return false;
    }

    $(`div[class~="update-stock-rows"] div[data-row]:last`).after(`
        <div class="row stock-listing" data-row="${lastRowId}">
            <div class="col-md-4 mb-3">
                <div>
                    <select data-row="${lastRowId}" name="product_id_${lastRowId}" id="product_id_${lastRowId}" class="form-control selectpicker">
                            ${htmlData}
                    </select>
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">${companyVariables.cur}</span></div>
                    <input type="number" step="0.1" value="0.00" class="form-control" name="cost_${lastRowId}">
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">${companyVariables.cur}</span></div>
                    <input type="number" step="0.1" value="0.00" class="form-control" name="price_${lastRowId}">
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <input type="number" step="1" value="0" class="form-control" min="1" name="quantity_${lastRowId}">
            </div>
            <div class="col-md-1 mb-3">
                <input type="number" step="1" value="0" class="form-control" min="1" name="threshold_${lastRowId}">
            </div>
            <div class="col-md-1 text-center">
                <span class="remove-this-row" style="font-weight:bold; font-size:16px; line-height:2.5rem; color:brown; cursor:pointer" data-value="${lastRowId}">X</span>
            </div>                        
        </div>
    `);

    $(`select[class~="selectpicker"]`).select2();
    removeAppendRow();
    formControls();

});
formControls();

$("form[class~='submit-bulk-transfer-product']").on("submit", function(e) {
    e.preventDefault();
    var formData = $(this).serialize();

    var productIds = [];
    $.each($(".bulk-products-list .products-listing"), function(i, e) {
        var rowId = $(this).attr("data-row");
        if(parseInt($(`input[id^="i_product_${rowId}"]`).val()) != 0) {
            productIds.push($(`select[id^="s_product_${rowId}"]`).val() + '=' + $(`input[id^="i_product_${rowId}"]`).val());
        }
    });

    if(productIds.length < 1) {
        Toast.fire({
            type: 'error',
            title: 'Please select at least one item to continue.'
        });
    } else if($(`form[class~='submit-bulk-transfer-product'] select[name="branchId"]`).val() == 'null') {
        Toast.fire({
            type: 'error',
            title: 'Please select branch to transfer items to continue.'
        });
    } else {

        if (confirm("Do you want to transfer these products?")) {

            $.ajax({
                url: baseUrl + "aj/inventoryManagement/bulkTransferProducts",
                type: "POST",
                dataType: "json",
                data: $(this).serialize() + "&request=true&bulkTransferProducts=true&productIds="+productIds,
                cache: false,
                beforeSend: function() {
                    $(`div[class="form-content-loader"]`).css("display","flex");
                    $("*", ".submit-bulk-transfer-product").prop("disabled", true);
                },
                success: function(data) {

                    if (data.status == true) {
                        Toast.fire({
                            type: 'success',
                            title: 'Product Successfully Transfered'
                        });
                        $(".submit-bulk-transfer-product")[0].reset();
                        $(".submit-bulk-transfer-product select").val('null').change();
                        clearAllRows();
                        setTimeout(function() {
                            window.location.href=`${baseUrl}inventory/inventory-details/${branchID}`;
                        }, 1500);
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data.message
                        });
                    }
                },
                error: function() {
                    Toast.fire({
                        type: 'error',
                        title: 'Error Processing Request'
                    });
                    $(`div[class="form-content-loader"]`).css("display","none");
                }, complete: function() {
                    $("*", ".submit-bulk-transfer-product").prop("disabled", false);
                    $(`div[class="form-content-loader"]`).css("display","none");
                    setTimeout(function() {
                        $(`div[class="transfer-bulk-form-message"]`).empty();
                    }, 3000);
                }
            });

        }

    }
    
});