var companyVariables = $.parseJSON($(`link[rel="params"]`).attr('_cl'));
$(".overlay").css('display', 'block');

const menuItems = $('.left-sidenav ul.metismenu'),
        menuItemsPlaceholder = $('.menu-items-placeholder');
menuItemsPlaceholder.show();

$(() => {
    $(`[data-toggle="tooltip"]`).tooltip();
    $(".overlay").css('display', 'none');
});

const Toast = Swal.mixin({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 6000,
    onOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
});

var sL = () => {
    menuItems.show();
    menuItemsPlaceholder.hide();
    $(".main-content-loader.main-body-loader").css({display: "flex"});
}

var hL = () => {
    $(".main-content-loader.main-body-loader").css({display: "none"}); 
    menuItemsPlaceholder.hide();
    menuItems.show();
}

var internetCheck;
var noInternet = false;
var offlineValue = $(`div[class="offline-check"]`).attr("offline-value");

function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57) && (charCode != 46)) {
        return false;
    }
    return true;
}

async function dOC() {
    return $.ajax({
        url : `${baseUrl}users/onlineCheck`,
        type : "POST",
        data: { onlineCheck: true },
        timeout : 5000
    });
}

$(`table[class~="simple-table"]`).dataTable({
    iDisplayLength: 5,
});

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

var recon;

function reConnect() {

    $(`div[class~="offline-placeholder"] button[type="button"]`).on('click', async function() {

        $(`div[class~="offline-placeholder"] button[type="button"]`).html(`Reconnecting &nbsp; <i class="fa fa-spin fa-spinner"></i>`).css({'display':'inline-flex'});
        $(`div[class~="offline-placeholder"] button[type="button"]`).prop('disabled', true);
        $(`div[class~="offline-placeholder"] button[type="reset"]`).removeClass('hidden');
        recon = setInterval(function() {
            dOC().then((itResp) => {
                if(itResp == 1) {
                    clearInterval(recon);
                    noInternet = false;
                    $(`div[class="connection-lost"]`).css('display','none');
                    $(`div[class~="offline-placeholder"] div[class~="offline-content"] p`).html(``);
                    $(`div[class="connection-restored"]`).css('display','block');
                    $(`div[class~="offline-placeholder"] button[type="reset"]`).addClass('hidden');
                    $(`div[class~="offline-placeholder"] button[type="button"]`).html(`Connection Restored`).removeClass('btn-warning').addClass('btn-success');
                    $(`div[class~="offline-placeholder"] button[type="button"]`).prop('disabled', false);
                    setTimeout(function() {
                        window.location.href='';
                    }, 2000);
                } else {
                    $(`div[class~="offline-placeholder"]`).css({'display':'flex'});
                    $(`div[class~="offline-placeholder"] button[type="button"]`).html(`Reconnecting &nbsp; <i class="fa fa-spin fa-spinner"></i>`).css({'display':'inline-flex'});
                    $(`div[class~="offline-placeholder"] button[type="button"]`).prop('disabled', false);
                }
            }).catch((err) => {
                $(`div[class~="offline-placeholder"]`).css({'display':'flex'});
                $(`div[class~="offline-placeholder"] button[type="button"]`).html(`Reconnecting &nbsp; <i class="fa fa-spin fa-spinner"></i>`).css({'display':'inline-flex'});
                $(`div[class~="offline-placeholder"] button[type="button"]`).prop('disabled', true);
            });
        }, 3000);
    });

    $(`div[class~="offline-placeholder"] button[type="reset"]`).on('click', function(){
        $(`div[class~="offline-placeholder"] button[type="reset"]`).addClass('hidden');
        $(`div[class~="offline-placeholder"] button[type="button"]`).html(`Reconnect`);
        $(`div[class~="offline-placeholder"] button[type="button"]`).prop('disabled', false);
        clearInterval(recon);
    });
    
}
reConnect();

var syncOfflineData = async (dataToSync) => {
    await listIDB(dataToSync).then((resp) => {
        if(resp.length > 0) {
            $.post(baseUrl + `doprocess_db/sync/${dataToSync}`, {syncData: resp}, function(data) {
                if(data.status == 200) {}
            }, 'json');
        }
    });
}

var preloadData = async (dataset) => {
    $.ajax({
        url: `${baseUrl}doprocess_db/preloadData`,
        data: {preloadData: true, dataType: dataset},
        dataType: "json",
        type: "post",
        success: function(resp) {
            if(resp.status == 200) {
                if(resp.request == "reports")  {
                    var queryResult = resp.result;
                    $.each(queryResult, function(i, e) {
                        upIDB("reports", e);
                    });
                } else {
                    upIDB(dataset, resp.result);
                }
            }
        }
    });
}

function show_modal(modalDiv) {
    $("#owner_div").css('display', 'none');
    $("#account_owner").val('');
    $(".form-result").html('');
    
    $(`#${modalDiv}`).modal('show');
}

$(function() {
    $(`select[class~="selectpicker"]`).select2({ width: '100%' });
    $(`select[class~="selectpicker2"]`).select2({ width: '100%' });
});

function delI() {
    $(`div[class="form-result"]`).html(``);
    $(`div[class="main-content"]`).on('click', `button[class~="delete-item"], a[class~="delete-item"]`, function(e) {
        let itemId = $(this).attr('data-id');
        let itemMsg = $(this).attr('data-msg');
        let itemUrl = $(this).attr('data-url');
        let itemToDelete = $(this).attr('data-request');

        $(`div[id="deleteData"] form[class="submitThisForm"]`).attr('action', itemUrl);
        $(`div[id="deleteData"] input[name="itemToDelete"]`).val(itemToDelete);
        $(`div[id="deleteData"] input[name="itemId"]`).val(itemId);
        $(`div[id="deleteData"] div[class~="details-pane"]`).html(itemMsg);
        $(`div[class~="delete-modal"]`).modal('show');

    });
}

delI();

function inputController() {
    $(`input[class~="input_ctrl"]`).on('input', function(e) {
        let row_id = $(this).attr("data-row-value");
        let quantity = $(`input[id="product_quantity_${row_id}"]`).val();
        let price = $(`input[id="product_price_${row_id}"]`).val();

        let calculate = (parseInt(quantity) * parseInt(price));

        $(`span[data-row-value="${row_id}"]`).html(formatCurrency(calculate));

        if($(`input[id="product_price_${row_id}"]`).parents(`tr`).hasClass('selected')) {
            $(`button[data-row-value="${row_id}"]`).html('Update').addClass('btn-primary update-button').removeClass('btn-success');
        }
    });
}

function dismissModal(sessionName, modalWindow) {

    $(`div[class="main-content"]`).on('click', `button[class~="dismiss-modal"]`, function() {

        let modalDiv = $(this).attr('data-modal');

        $(`#${modalDiv}`).modal('hide');
        if ($(`#${modalDiv} form`).length) {
            $(`#${modalDiv} form`)[0].reset();
        }
        
        $(`div[class="form-result"]`).html('');

        if(modalDiv == 'productsListModalWindow') {
            setTimeout(function(){
                $(`body[class~="main-body"]`).addClass('modal-open');
            }, 1000);
        }
    });

}

var populateRequestsList = (requestsData, tableName) => {
    $(`table[id="${tableName}"]`).dataTable().fnDestroy();
    $(`table[id="${tableName}"]`).dataTable({
        "aaData": requestsData,
        "iDisplayLength": 10,
        "buttons": ["copy", "print","csvHtml5"],
        "lengthChange": !1,
        "dom": "Bfrtip",
        "columns": [
           {"data": 'row_id'},
           {"data": 'request_id'},
           {"data": 'branch_name'},
           {"data": 'customer_name'},
           {"data": 'quote_value'},
           {"data": 'recorded_by'},
           {"data": 'request_date'},
           {"data": 'action'}
        ]
    });

    delI();
    hL();
}

var discountCalculator = () => {

    $(`input[name="discount_type"]`).on('change', function() {
        $(`input[name="discount_amount"]`).val('');
    });

    $(`input[name="discount_amount"]`).on('input', function() {
        let discount = $(this).val();
        let discountType = $(`input[name="discount_type"]:checked`).val();
        let subTotal = parseFloat($(`span[class="subtotal-total"]`).attr('data-subtotal'));
        
        let discount_amt = 0;

        if(discount.length > 0) {
            if(discountType == "cash") {
                if(discount > subTotal) {
                    discount = subTotal;
                    $(`input[name="discount_amount"]`).val(subTotal);
                }
                discount_amt = parseFloat(discount);
            } else if(discountType == "percentage") {
                if(discount > 100) {
                    discount = 100;
                    $(`input[name="discount_amount"]`).val(100);
                }
                discount_amt = (parseFloat(discount)/100)*subTotal;
            }
        }

        let overallTotal = parseFloat(subTotal - discount_amt).toFixed();
        $(`span[class="discount_total"]`)
            .attr('data-discount_total', discount_amt)
            .html(discount_amt.toFixed(2));
        $(`span[class="overalltotal"]`)
            .attr('data-overalltotal', overallTotal)
            .html(formatCurrency(overallTotal));
        $(`td[data-overalltotal]`).attr('data-overalltotal', overallTotal);
    });
}

if($(`table[class~="productsList"]`).length) {

    $(`div[class="main-content"]`).on('click', `a[class~="add-category"]`, function(e) {
        $(`input[name="category_name"]`).val('');
        $(`input[name="categoryId"]`).val('');
        $(`input[name="request"]`).val("add");
        $(`div[class~="categoryModal"]`).modal('show');
    });

    $(`div[class~="categoryModal"] button[type="submit"]`).on('click', function() {
        let name = $(`input[name="category_name"]`).val();
        let id = $(`input[name="categoryId"]`).val();
        let request = $(`input[name="request"]`).val();

        $(`div[class="form-content-loader"]`).css("display","none");

        $.post(baseUrl+"aj/categoryManagement/saveCategory", {name: name, id: id, dataset: request}, (res) => {
            if(res.status == 200){
                $(`div[class~="categoryModal"]`).modal('hide');
                Toast.fire({
                    type: 'success',
                    title: res.message
                });
                $(`div[class="form-content-loader"]`).css("display","none");
                listProductCategories();
            } else {
                Toast.fire({
                    type: 'error',
                    title: res.message
                });
                $(`div[class="form-content-loader"]`).css("display","none");                 
            }
        }, 'json')
        .catch((err) => {
            Toast.fire({
                type: 'error',
                title: "Error Processing Request"
            })      
            $(`div[class="form-content-loader"]`).css("display","none");
        });
    });

    var populateCategoryList = (productsCategoryData) => {
        hL();
        $(`table[class~="productsList"]`).dataTable().fnDestroy();
        $(`table[class~="productsList"]`).dataTable({
            "aaData": productsCategoryData,
            "iDisplayLength": 10,
            "buttons": ["copy", "print","csvHtml5"],
            "lengthChange": !1,
            "dom": "Bfrtip",
            "columns": [
               {"data": 'row'},
               {"data": 'category'},
               {"data": 'products_count'},
               {"data": 'action'}
            ]
        });

        delI();

        $(`div[class="main-content"]`).on('click', `a[class~="edit-category"]`, function(e) {
            let categoryId = $(this).data('id');
            let categoryData = $(this).data('content');    
            $(`input[name="category_name"]`).val(categoryData.category);
            $(`input[name="categoryId"]`).val(categoryData.id);
            $(`input[name="request"]`).val("update");
            $(`div[class~="categoryModal"]`).modal('show');
        });
    }

    function listProductCategories() {
        $.ajax({
            method: "POST",
            url: `${baseUrl}aj/categoryManagement/listProductCategories`,
            data: { listProductCategories: true},
            dataType: "JSON",
            success: function(resp) {
                populateCategoryList(resp.result);
            }, complete: function(data) {
                hL();
            }, error: function(err) {
                hL();
            }
        });
    }

    listProductCategories();
}

async function listRequests(requestType, tableName) {

    sL();

    await dOC().then((itResp) => {
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

        await listIDB(`${requestType.toString().toLowerCase()}s`).then((res) => {
            populateRequestsList(res, `${tableName}`);
        });

        return false;
    }

    $.ajax({
        method: "POST",
        url: `${baseUrl}aj/listRequests`,
        data: { listRequests: "true", requestType: requestType },
        dataType: "JSON",
        beforeSend: function() {},
        success: function(resp) {
            populateRequestsList(resp.result, `${tableName}`);
            upIDB(`${requestType.toString().toLowerCase()}s`, resp.result);
        }, complete: function(data) {
            inputController();
            hL();
        }, error: function(err) {
            hL();
        }
    });
}

function removeItem(sessionName) {
    $(`div[class="main-content"]`).on('click', `span[class~="remove-item"]`, function(e) {

        var productId = $(this).attr('data-value');
        var thisSum = $(this).attr('data-sum');
        $.ajax({
            type: "POST",
            url: `${baseUrl}doprocess_sales/removeItem`,
            data: { removeItem: "true", sessionName: sessionName, productId:productId },
            dataType: "JSON",
            success: function(resp) {
            }, complete: function(data) {
                $(`tr[data-row="${productId}"]`).remove();
                let overAll = $(`td[data-overall]`).attr('data-total');
                let curTotal = (parseInt(overAll) - parseInt(thisSum));

                $(`td[data-overall]`).attr('data-total', curTotal);
                $(`span[class="overall-total"]`).html(formatCurrency(curTotal))

            }
        });
    });
}

function serealizeSelects(select) {
    
    var array = [];
    select.each(function() {
        array.push($(this).val())
    });
    return array;

}

function formatCurrency(total) {
    var neg = false;
    if(total < 0) {
        neg = true;
        total = Math.abs(total);
    }
    return (neg ? "-" : '') + parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
}

function randomInt(length = 10) {
   var result           = '';
   var characters       = '0123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}

function randomString(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}

function jsDate(dateType = 'datetime') {
    var d = new Date(),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear(),
        hour = d.getHours(),
        minute = d.getMinutes(),
        seconds = d.getSeconds();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    if(dateType == 'datetime') {
        return [year, month, day].join('-') + " " + [hour, minute, seconds].join(':'); 
    } else if(dateType == 'fulldate') {
        return [year, month, day].join('-'); 
    }
}

$(`.send-to-list div:last`).css({
    'border-radius':'0px 5px 5px 0px',
    'margin-right':'0px'
});

function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

$(`div[class="main-content"]`).on('click', `a[class~="logout"]`, async function(e) {
    e.preventDefault();

    var toPerform = $(this).attr('data-value');
    var href = $(this).attr('href');

    await dOC().then((itResp) => {
        if(itResp == 1) {
            offline = false;
            $(`div[class="connection"]`).css('display','none');
        } else {
            offline = true;
            $(`div[class="connection"]`).css('display','block');
        }
    }).catch((err) => {
        offline = true;
        $(`div[class="connection"]`).css('display','block');
    });

    if(offline) {
        $(`div[class~="clearCache"]`).modal('show');

        $(`button[class~="confirm-clear-cache"]`).on('click', function(e) {
            caches.delete('argonPOS-Static-v1').then((res) => {
                window.location.href = baseUrl;
            });
        });
    }

    $.ajax({
        type: "POST",
        url: `${baseUrl}al/dLg`,
        data: {doLogout: true, toPerform: toPerform},
        dataType: "json",
        success: function(e) {
            if(e.status == 200) {
                window.location.href = baseUrl;
            }
        }
    });
});

function stripHtml(html) {
    return html.replace(/(<([^>]+)>)/ig, "");
}

var populateCustOptionsList = (data) => {
    $(`select[class~="customer-select"]`).find('option').remove().end();
    if(!$(`span[class="hide-walk-in-customer"]`).length) {
        $(`select[class~="customer-select"]`).append('<option value="WalkIn" data-prefered-payment="" data-contact="No Contact" selected="selected">Walk In Customer</option>');
    } else {
        $(`select[class~="customer-select"]`).append('<option value="null" data-contact="No Contact" selected="selected">-- Select Customer --</option>');
    }
    $.each(data, function(i, e) {
        $(`select[class~="customer-select"]`).append(`<option data-prefered-payment='${e.preferred_payment_type}' data-email='${e.email}' data-contact='${e.phone_1}' value='${e.customer_id}'>${e.fullname} (${e.phone_1})</option>`);
    });
}

var fetchPOSCustomersList = async () => {

    await dOC().then((itResp) => {
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
        var info = await listIDB('customers').then((resp) => {
            var row_id = 0, newResults = [];
            $.each(resp, function(i, e) {
                if(e.deleted != 1) {
                    row_id++;
                    e.row_id = row_id;
                    newResults.push(e);
                }
            });
            populateCustOptionsList(newResults);
        });
        return false;
    }

    await syncOfflineData('customers').then((resp) => {
        $.post(baseUrl + "aj/fetchCustomersOptionsList", {fetchCustomersOptionsList: true}, async function(data) {
            await clearDBStore('customers').then((resp) => {
                populateCustOptionsList(data.result);
                upIDB('customers', data.result);
            });
        }, 'json');
    });

}

if($(`select[class~="customer-select"]`).length) {
    fetchPOSCustomersList();
}

var triggerCellClick = () => {
    $(".product-title-cell").on("click", function(e) {
        let cellCheckbox = $(this).siblings("td").find(".checkbox input:checkbox");
        cellCheckbox.prop("checked", !cellCheckbox.is(":checked"))
        cellCheckbox.trigger("change")
    });
}

var populatePOSProductsList = (data) => {

    let htmlData = ``;

    $.each(data, function(i, e) {

        var trClass,
        checkbox = `<div class="checkbox checkbox-primary checkbox-single">
                <input type="checkbox" name="products[${e.product_id}][id]" value="${e.product_id}" data-product_max="${e.product_quantity}" class="product-select d-block" id="productCheck-${e.product_id}" data-product-id="${e.product_id}" data-product-name="${e.product_title}" data-product-price="${e.price}" data-product-img="${e.image}">
                <label for="productCheck-${e.product_id}">
                </label>
            </div>`;

        if(e.product_quantity < 1) {
            checkbox = ``;
            trClass = `class="text-danger" title="Out of Stock"`;
        } else {
            trClass = `title="${e.product_quantity} Stock Quantity Left"`;
        }

        htmlData += `<tr ${trClass} data-toggle="tooltip" id="productCheck-${e.product_id}" data-product-id="${e.product_id}" data-product-name="${e.product_title}" data-product-price="${e.price}" data-product-img="${e.image}" style="transition: all 0.8s ease" data-category="${e.category_id}" data-product_max="${e.product_quantity}">
            <td>
                ${checkbox}
            </td>
            <td><img class="product-title-cell" src="${e.image}" width="24px"></td>
            <td class="product-title-cell" style="cursor:pointer">${e.product_title}</td>
            <td>${e.price}</td>
        </tr>`;
    });

    $(`tbody[class="pos-products-list"]`).html(htmlData);

    $(`tr[data-toggle="tooltip"]`).tooltip();
    triggerCellClick();
    initialiteProductSelect();
}

var fetchPOSProductsList = async () => {

    await dOC().then((itResp) => {
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
        var info = await listIDB('request_products').then((resp) => {
            var row_id = 0, newResults = [];
            $.each(resp, function(i, e) {
                if(e.deleted != 1) {
                    row_id++;
                    e.row_id = row_id;
                    newResults.push(e);
                }
            });
            populatePOSProductsList(newResults);
        });
        return false;
    }

    $.post(baseUrl + "aj/fetchPOSProductsList", {fetchPOSProductsList: true}, async function(data) {
        await clearDBStore('request_products').then((resp) => {
            upIDB('request_products', data.result).then((resp) => {
                populatePOSProductsList(data.result);
            });
        });
    }, 'json');

}

if($(`tbody[class="pos-products-list"]`).length) {
    fetchPOSProductsList();
}

var populateUsersList = (usersObject) => {
    
    $(`table[class~="usersAccounts"]`).dataTable().fnDestroy();
    $(`table[class~="usersAccounts"]`).dataTable({
        "aaData": usersObject,
        "iDisplayLength": 10,
        "buttons": ["copy", "print","csvHtml5"],
        "lengthChange": !1,
        "dom": "Bfrtip",
        "columns": [
           {"data": 'row_id'},
           {"data": 'fullname'},
           {"data": 'branch_name'},
           {"data": 'access_level'},
           {"data": 'contact'},
           {"data": 'email'},
           {"data": 'registered_date'},
           {"data": 'action'}
        ]
    });

    editUserDetails();
    editUserAccessLevel();
    deleteUserDetails();
    hL();
}

var fetchUsersLists = async () => {
    
    if ($("table[class~='usersAccounts']").length) {
        
        sL();

        await dOC().then((itResp) => {
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

            var info = await listIDB('users').then((resp) => {
                var row_id = 0, newResults = [];
                $.each(resp, function(i, e) {
                    if(e.deleted != 1) {
                        row_id++;
                        e.row_id = row_id;

                        newResults.push(e);
                    }
                });

                populateUsersList(newResults);

            });

            hL();

            return false;
        }

        $.ajax({
            url: baseUrl + "aj/userManagement/fetchUsersLists",
            type: "POST",
            data: { fetchUsersLists: true },
            dataType: "json",
            cache: false,
            beforeSend: function() {
                sL();
            },
            success: function(data) {
                populateUsersList(data.message);
                aIDB('users', data.message);
            },
            error: function() {
                
            },
            complete: function() {
                
            }
        });

    }

}

fetchUsersLists();


async function deleteMyItem(delete_id, page, callBack = "") {

    if (delete_id != "" && page != "") {

        await dOC().then((itResp) => {
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

            if(page == "evUser") {
                var deleteResp = await deleteUserFromIndexDb(delete_id).then((resp) => {
                    $(".show-delete-msg").html(`<p class="alert alert-success text-white">
                        User Have Been Successfully Deleted.
                    </p>`);
                    setTimeout(function() {
                        $(".show-delete-msg").empty();
                        $(`div[class~="deleteModal"]`).modal('hide');
                    }, 1500);
                    fetchUsersLists();
                });

                return false;
            }
        }

        $.ajax({
            url: baseUrl + "doprocess_deletedata",
            type: "POST",
            data: { request: "deleteMyData", delete_id: delete_id, page, page },
            dataType: "json",
            cache: false,
            beforeSend: function() {
                $(".show-delete-msg").html(`
                    <p class="text-center"><span class="fa fa-spinner fa-spin"></span><br>Please Wait...</p>
                `);
                $(".confirm-delete-btn").hide();
            },
            success: function(data) {
                if (data.status == true) {
                    $(".show-delete-msg").html(`<p class="alert alert-success text-white">${data.message}</p>`);
                } else {
                    $(".show-delete-msg").html(`<p class="alert alert-danger text-white">${data.message}</p>`);
                    $(".confirm-delete-btn").fadeIn(1000);
                }
            },
            error: function() {

                $(".show-delete-msg").html(`<p class="alert alert-success">Error Processing Request</p>`);

                $(".confirm-delete-btn").fadeIn(1000);
            },
            complete: function() {
                callBack;

                setTimeout(function() {
                    $(".show-delete-msg").empty();
                }, 3000);
            }
        })

    }

}

$(`form[class~="submitThisForm"]`).on("submit", async function(e) {

    e.preventDefault();


    if (confirm("Do you want to submit this form?")) {

        $(`div[class="form-content-loader"]`).css("display","flex");

        await dOC().then((itResp) => {
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

            if($(`input[name="this-form"]`).val() == "branches") {
                
                var uniqueId;
                
                if($(`input[name="record_type"]`).val() == 'update-record') {
                    uniqueId = $(`input[name="branchId"]`).val();
                } else {
                    uniqueId = randomString(12);
                }
                
                let branch_status = $(`input[name="status"]`).val();
                let branch_type = $(`select[name="branchType"]`).val();
                let branchType = `<br><span class='badge ${((branch_type == 'Store') ? "badge-primary" : "badge-success")}'>${branch_type}</span>`;

                let actionBtn = `<div width="100%" align="center">
                    <button class="btn btn-sm btn-outline-success edit-branch" data-branch-id="${uniqueId}\">
                            <i class="mdi mdi-pencil-outline"></i>
                        </button>
                    <button class="btn btn-sm "${((branch_status == 1) ? "btn-outline-danger" : "btn-outline-primary")}" delete-item" data-url="${baseUrl}aj/branchManagment/updateStatus" data-state="${branch_status}" data-msg="${((branch_status == 1) ? "Are you sure you want to set the {$data->branch_name} as inactive?" : "Do you want to proceed and set the {$data->branch_name} as active?")}" data-request="branch-status" data-id="${uniqueId}">
                            <i class="fa ${((branch_status == 1) ? "fa-stop" : "fa-play")}"></i>
                        </button> </div>`;
                
                let branch_name = $(`input[name="branchName"]`).val();

                if(branch_name.length < 3) {
                    $(".form-result").html(`<p class="alert alert-danger text-white">Branch name cannot be empty.</p>`);
                } else {
                    let location = $(`input[name="location"]`).val();
                    let phone = $(`input[name="phone"]`).val();
                    let email = $(`input[name="email"]`).val();
                    let status =   `<div align='center'>${((branch_status == 1) ? "<span class='badge badge-success'>Active</span>" : "<span class='badge badge-danger'>Inactive</span>")}</div>`;

                    var formDetails = [{
                        branch_id: uniqueId, contact: phone,
                        branch_name: branch_name+branchType,
                        location: location, email: email,
                        branch_type: branch_type, status: status,
                        branch_name_text: branch_name,
                        action: actionBtn
                    }];

                    await upIDB('branches', formDetails).then((resp) => {
                        if(resp == 200) {
                            $(".form-result").html(`
                                <p class="alert alert-success text-white">Branch Have Been Successfully Registered.</p>
                            `);
                            if ($(`[name="record_type"]`).val() == "new-record") {
                                $(".submitThisForm:visible")[0].reset();
                            }
                            fetchBranchLists();
                            setTimeout(function() {
                                $(".form-result").empty();
                                $(`div[id="newModalWindow"]`).modal('hide');
                            }, 2000);
                        } else {
                            $(".form-result").html(`<p class="alert alert-danger text-white">Error encountered while processing request.</p>`);
                        }
                    });
                }

                $(`div[class="form-content-loader"]`).css("display","none");

                return false;
            }
            else if($(`input[name="this-form"]`).val() == "users") {
                
                var uniqueId;

                if($(`input[name="record_type"]`).val() == 'update-record') {
                    uniqueId = $(`input[name="user_id"]`).val();
                } else {
                    uniqueId = randomString(12);
                }

                var actionBtn = `
                    <div class='text-center'><button class="btn btn-sm btn-outline-success edit-user" data-user-id="${uniqueId}">
                            <i class="mdi mdi-pencil-outline"></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-user" data-user-id="${uniqueId}">
                        <i class="mdi mdi-trash-can"></i>
                    </button></div>`;

                var formDetails = [{
                    user_id: uniqueId,
                    access_level_id: $(`select[name="access_level"]`).val(),
                    access_level: $(`select[name="access_level"]`).children('option:selected').attr('data-name'),
                    fullname: $(`input[name="fullName"]`).val(),
                    email: $(`input[name="email"]`).val(),
                    contact: $(`input[name="phone"]`).val(),
                    branchId: $(`select[name="branchId"]`).val(),
                    branch_name: $(`select[name="branchId"]`).children('option:selected').attr('data-name'),
                    gender: $(`select[name="gender"]`).val(),
                    registered_date: jsDate(),
                    action: actionBtn
                }];

                if($(`input[name="record_type"]`).val() != 'update-record') {
                    await aIDB('users', formDetails).then((resp) => {
                        if(resp == 200) {
                            $("form[class~='submitThisForm']")[0].reset();
                            $(".form-result").html(`
                                <p class="alert alert-success text-white">User Have Been Successfully Registered.</p>
                            `);
                            setTimeout(function() {
                                $(".form-result").empty();
                                fetchUsersLists();
                            }, 2000);
                        } else {
                            console.log('Ooops!!!!');
                        }
                    });
                } else {
                    await updateUsersRecordIndexDb(formDetails).then((resp) => {
                        if(resp == 200) {
                            $("form[class~='submitThisForm']")[0].reset();
                            $(".form-result").html(`
                                <p class="alert alert-success text-white">User Have Been Successfully Updated.</p>
                            `);
                            setTimeout(function() {
                                $(".form-result").empty();
                                fetchUsersLists();
                            }, 3000);
                        } else {
                            console.log('Ooops!!!!');
                        }
                    });
                }

                $(`div[class="form-content-loader"]`).css("display","none");
                return false;
            }

        }

        $.ajax({
            url: $(this).attr("action"),
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(data) {
                if (data.status == true) {
                    Toast.fire({
                        type: "success",
                        title: data.message
                    });
                    if ($(`[name="record_type"]`).val() == "new-record") {
                        $(".submitThisForm:visible")[0].reset();
                    }
                    fetchUsersLists();
                    fetchBranchLists();
                    if($(`table[class~="productsList"]`).length) {
                        listProductCategories();
                    }
                    if($(`table[class~="customersList"]`).length) {
                        listCustomers();
                    }
                    if((data.thisRequest == 'Quote') || (data.thisRequest == 'Order')) {
                        listRequests(data.thisRequest, data.tableName);
                    }
                } else {
                    Toast.fire({
                        type: "error",
                        title: data.message
                    });
                }
            },
            error: function(err) {
                Toast.fire({
                    type: "error",
                    title: "Error Processing Request"
                });
                $(`div[class="form-content-loader"]`).css("display","none");
            },
            complete: function(data) {
                $(`div[class~="delete-modal"]`).modal('hide');
                setTimeout(function() {
                    $(".form-result").empty();
                }, 1200);
                $(`div[class="form-content-loader"]`).css("display","none");
                $(".submit-form").prop("disabled", false);
            }
        });

    }

});

var populateUserDetails = (data) => {
    $(`[name="fullName"]`).val(data.fullname);
    $(`[name="access_level"]`).val(data.access_level_id).change();
    $(`[name="gender"]`).val(data.gender).change();
    $(`[name="phone"]`).val(data.contact);
    $(`[name="email"]`).val(data.email);
    $(`[name="branchId"]`).val(data.branchId).change();
    $(`[name="userId"]`).val(data.user_id);
    $(`[name="record_type"]`).val("update-record");
    $(`[name="weekly_target"]`).val(data.weekly_target);
    $(`[name="daily_target"]`).val(data.daily_target);
    $(`[name="monthly_target"]`).val(data.monthly_target);

    $("#newModalWindow").modal("show");
}

var editUserDetails = () => {

    $(`div[class="main-content"]`).on("click", ".edit-user", async function(e) {

        e.preventDefault();
        sL();

        var userId = $(this).data("user-id");

        await dOC().then((itResp) => {
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

            var info = await gIDBR('users', userId).then((resp) => {
                populateUserDetails(resp);
                return false;
            });

            hL();

            return false;
        }

        if (userId != "") {
            $.ajax({
                url: baseUrl + "aj/userManagement/getUserDetails",
                data: { getUserDetails: true, userId: userId },
                dataType: "json",
                type: "POST",
                cache: false,
                success: function(resp) {
                    if (resp.status == true) {
                        populateUserDetails(resp.message);
                    } else {
                        Toast.fire({
                            type: "error",
                            title: "User there was an error while fetching user information."
                        })
                    }
                },
                error: function(err) {
                }, complete: function(data) {
                    hL();
                }
            })
        }
    });
}


var editUserAccessLevel = () => {

    $(`div[class="main-content"]`).on("click", `button[class~="edit-access-level"]`, function(e) {
        e.preventDefault();

        var user_id = $(this).data("user-id");

        if (user_id != "") {
            $.ajax({
                url: baseUrl + "aj/userManagement/permissionManagement",
                data: { getUserAccessLevels: true, user_id: user_id },
                dataType: "json",
                type: "POST",
                cache: false,
                beforeSend: function() {

                    $(`div[class~="launchModal"] div[class~="show-modal-title"]`).html("Edit User Access Level");
                    $(`div[class~="launchModal"] div[class~="show-modal-body"]`).html(`<div class="col-12"><div class="card"><div class="card-body"><p class="text-center"><span class="fa fa-spinner fa-spin"></span></p></div></div></div>`);
                },
                success: function(data) {

                    if (data.status == true) {

                        $(`div[class~="launchModal"]`).modal("show");

                        var displayPermission = `
                        <div class="row"><div class="settings-form-msg col-12"></div>`;

                        $.each(data.message.permissions, function(key, page) {

                            var permitted = null;

                            displayPermission += `
                            <div class="col-md-4 col-sm-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="mt-0">${key[0].toUpperCase() + key.slice(1)}</h5>
                                        <div class="general-label">`;
                            var ii = 0;
                            $.each(page, function(roleKey, roleValue) {
                                ii++;
                                permitted = (roleValue == 1) ? 'checked' : null;

                                displayPermission += `
                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="checkbox my-2">
                                            <div class="custom-control custom-checkbox">
                                                <input data-value="${roleValue}" data-name="${key.toString().toLowerCase()},${roleKey.toString().toLowerCase()}" ${permitted} type="checkbox" class="custom-control-input user-access-levels" id="customCheck_${key}_${ii}" data-parsley-multiple="groups" data-parsley-mincheck="2">
                                                <label class="custom-control-label" for="customCheck_${key}_${ii}">${roleKey[0].toUpperCase() + roleKey.slice(1)}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>`;

                            });

                            displayPermission += `</div></div></div></div>`;

                        });

                        displayPermission += `
                        </div>
                        <div class="col-12 mb-3">
                            <button data-user-id="${user_id}" class="btn btn-primary float-right access-level-submit-btn">
                                Save Settings
                            </button>
                        </div>
                        `;

                        $(`div[class~="launchModal"] div[class~="show-modal-body"]`).html(displayPermission);

                    } else {
                        Toast.fire({
                            type: "error",
                            title: data.message
                        });
                    }

                },
                error: function() {
                    Toast.fire({
                        type: "error",
                        title: "Error Processing Request"
                    });
                },
                complete: function() {
                    saveAccessLevelSettings();
                }
            })
        }
    });
}

function deleteUserDetails() {

    $(`div[class="main-content"]`).on("click", ".delete-user", function(e) {
        e.preventDefault();

        var user_id = $(this).data("user-id");

        if (user_id != "") {

            $(".show-delete-body").html("Do You Want To Proceed In Deleting This User?");
            $(".confirm-delete-btn").show().attr("onclick", `deleteMyItem("${user_id}", "evUser", fetchUsersLists())`);

            $(".deleteModal").modal("show");
        }
    });
}

$(`div[class="main-content"]`).on("change", `[name="access_level"]`, function(e) {
    e.preventDefault();

    var access_level = $(`[name="access_level"]`).val();

    $.ajax({
        url: baseUrl + "aj/userManagement",
        type: "POST",
        data: { request: "fetchAccessLevelPermissions", access_level: access_level },
        dataType: "json",
        cache: false,
        beforeSend: function() {

            $(`.permissions-row`).html(`
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <p class="text-center">
                                <span class="fa fa-spinner fa-spin"></span>
                            </p>
                        </div>
                    </div>
                </div>
            `);
        },
        success: function(data) {

            if (data.status == true) {

                var displayPermission = ``;

                $.each(data.message.permissions, function(key, page) {

                    var permitted = null;

                    displayPermission += `
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mt-0">${key[0].toUpperCase() + key.slice(1)}</h5>
                                <div class="general-label">`;
                    var ii = 0;
                    $.each(page, function(roleKey, roleValue) {
                        ii++;
                        permitted = (roleValue == 1) ? 'checked' : null;

                        displayPermission += `
                        <div class="form-group row">
                            <div class="col-12">
                                <div class="checkbox my-2">
                                    <div class="custom-control custom-checkbox">
                                        <input data-value="${roleValue}" data-name="${key.toString().toLowerCase()},${roleKey.toString().toLowerCase()}" ${permitted} type="checkbox" class="custom-control-input user-access-levels" id="customCheck_${key}_${ii}" data-parsley-multiple="groups" data-parsley-mincheck="2">
                                        <label class="custom-control-label" for="customCheck_${key}_${ii}">${roleKey[0].toUpperCase() + roleKey.slice(1)}</label>
                                    </div>
                                </div>
                            </div>
                        </div>`;

                    });

                    displayPermission += `
                                </div>
                            </div>
                        </div>
                    </div>`;

                });

                displayPermission += `
                <div class="col-12 mb-3">
                    <button class="btn btn-primary float-right access-level-submit-btn">
                        Save Settings
                    </button>
                </div>
                `;
                $(`.permissions-row`).html(displayPermission);

            } else {
                $(`.permissions-row`).html(`
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <p class="alert alert-danger">${data.message}</p>
                            </div>
                        </div>
                    </div>
                `);
            }

        },
        error: function() {
            $(".settings-form-msg").html(`
                <p class="alert alert-danger">Error Processing Request</p>
            `);
        },
        complete: function() {
            setTimeout(function() {
                $(".settings-form-msg").empty();
            }, 3000);
        }
    });
});


var saveAccessLevelSettings = () => {

    $(`div[class="main-content"]`).on("click", ".access-level-submit-btn", function(e) {
        e.preventDefault();
        $(`.settings-form-msg`).html(``);

        if (confirm("Do You Want To Save Permissions Now?")) {

            var items_array = [];
            var aclUser = ($("button[data-user-id]").length) ? $(".access-level-submit-btn").data("user-id") : (($(`[name="access_user"]`).length) ? $(`[name="access_user"]`).val() : "");
            var acl = ($("button[data-user-id]").length) ? aclUser : $(`[name="access_level"]`).val();
            var isChecked = 0;

            $.each($(`input[class~="user-access-levels"]`), function(i, e) {

                isChecked = ($(this).is(":checked")) ? 1 : 0;

                items_array.push($(this).attr('data-name') + "," + isChecked);

            });

            $.ajax({

                url: baseUrl + "aj/userManagement/saveAccessLevelSettings",
                type: "POST",
                data: { saveAccessLevelSettings: true, aclSettings: items_array, acl: acl, accessUser: aclUser },
                dataType: "json",
                cache: false,
                beforeSend: function() {
                    $(`.settings-form-msg`).html(`
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <p class="text-center">
                                        <span class="fa fa-spinner fa-spin"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    `);
                    $("*", ".settings-form-a").prop("disabled", true);
                    $("*", ".permissions-row").prop("disabled", true);
                },
                success: function(data) {
                    if (data.status == true) {
                        $(`.settings-form-msg`).html(``);
                        Toast.fire({
                            type: "success",
                            title: data.message
                        });
                    } else {
                        $(`.settings-form-msg`).html(``);
                        Toast.fire({
                            type: "error",
                            title: data.message
                        });
                    }
                },
                error: function() {
                    $(`.settings-form-msg`).html(``);
                    $("*", ".settings-form-a").prop("disabled", false);
                    $("*", ".permissions-row").prop("disabled", false);
                    Toast.fire({
                        type: "error",
                        title: "Error Processing Request"
                    });
                },
                complete: function() {
                    $("*", ".settings-form-a").prop("disabled", false);
                    $("*", ".permissions-row").prop("disabled", false);
                    setTimeout(function() {
                        $(`.settings-form-msg`).empty();
                    }, 3000);
                }

            });
        }
    });

}


$(`button[class~="add-new-modal"], a[class~="add-new-modal"]`).on('click', function(e) {
    $(`[name="record_type"]`).val("new-record");
    $("div[id='newModalWindow'] form")[0].reset();
    
    if($(`select[name="branchType"]`).length) {
        $(`div[id='newModalWindow'] form select[name="branchType"]`).val('Store').change();
        $(`div[id='newModalWindow'] form select[name="status"]`).val('Active').change();
    } else {
        $("div[id='newModalWindow'] form select").val('null').change();
    }

});


var populateBranchesDetails = (data) => {
    $(`[name="branchName"]`).val(data.branch_name_text);
    $(`[name="branchType"]`).val(data.branch_type).change();
    $(`[name="phone"]`).val(data.contact);
    $(`[name="email"]`).val(data.email);
    $(`[name="branchId"]`).val(data.branch_id);
    $(`[name="location"]`).val(data.location);
    $(`[name="record_type"]`).val("update-record");

    $("#newModalWindow").modal("show");

    hL();
    $(`div[class="form-content-loader"]`).css("display","none");
}

var editBranchDetails = () => {

    $(`div[class="main-content"]`).on("click", `button[class~="edit-branch"]`, async function(e) {
        e.preventDefault();

        $(`div[class="form-content-loader"]`).css("display","flex");

        var branchId = $(this).data("branch-id");

        sL();

        await dOC().then((itResp) => {
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
            await gIDBR('branches', branchId).then((res) => {
                populateBranchesDetails(res);
                hL();
                return false;
            });
        }

        if (branchId != "") {
            $.ajax({
                url: baseUrl + "aj/branchManagment/getBranchDetails",
                data: { getBranchDetails: true, branchId: branchId },
                dataType: "json",
                type: "POST",
                cache: false,
                beforeSend: function() {
                },
                success: function(data) {
                    populateBranchesDetails(data.message);
                }, error: function(err) {
                    $(`div[class="form-content-loader"]`).css("display","none");
                }
            })
        }
    });

}


var populateBranchesList = (data) => {
    $(`table[class~="branchesLists"]`).dataTable().fnDestroy();
    $(`table[class~="branchesLists"]`).dataTable({
        "aaData": data,
        "iDisplayLength": 10,
        "buttons": ["copy", "print","csvHtml5"],
        "lengthChange": !1,
        "dom": "Bfrtip",
        "columns": [
           {"data": 'row_id'},
           {"data": 'branch_name'},
           {"data": 'location'},
           {"data": 'contact'},
           {"data": 'email'},
           {"data": 'status'},
           {"data": 'action'}
        ]
    });

    $(`div[class="form-content-loader"]`).css("display","none");
    editBranchDetails();
    hL();
    delI();
}

async function fetchBranchLists() {
    
    if ($(`table[class~="branchesLists"]`).length) {

        var selector = "list-all-branches";
        let colspan = "6";

        sL();
        $(`div[class="form-content-loader"]`).css("display","flex");

        $(`div[class~="delete-modal"]`).modal('hide');

        await dOC().then((itResp) => {
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
            var info = await listIDB('branches').then((resp) => {
                var row_id = 0, newResults = [];
                $.each(resp, function(i, e) {
                    if(e.deleted != 1) {
                        row_id++;
                        e.row_id = row_id;

                        newResults.push(e);
                    }
                });

                populateBranchesList(newResults);

            });
            
            hL();

            return false;
        }
            
        $.ajax({
            url: baseUrl + "aj/branchManagment/fetchBranchesLists",
            type: "POST",
            data: { request: "fetchBranchesLists" },
            dataType: "json",
            cache: false,
            success: function(data) {
                populateBranchesList(data.message);
                upIDB('branches', data.message);
            },
            error: function(err) {
                $(`div[class="form-content-loader"]`).css("display","none");
            },
            complete: function() {}
        });        

    }

}
fetchBranchLists();

function cusPurHis() {
                    
    let userId = $(`a[class="view-user-sales"]`).attr('data-value');
    let fullname = $(`a[class="view-user-sales"]`).attr('data-name');
    var recordType = $(`a[class="view-user-sales"]`).attr('data-record');           

    $.ajax({
        type: "POST",
        url: `${baseUrl}aj/reportsAnalytics/generateReport`,
        data: {generateReport: true, salesAttendantHistory: true, queryMetric:"salesAttendantPerformance", userId: userId, recordType: recordType},
        dataType: "JSON",
        beforeSend: function() {
            $(`div[class~="attendantHistory"] div[class~="modal-body"]`).html(`<div align="center">Loading records <i class="fa fa-spin fa-spinner"></i></div>`);
        }, success: function(resp) {
            var trData = `<div class="table-responsive"><table width="100%" class="table  orderHistory">`;
                trData += `<thead>`;
                trData += `<tr class="text-uppercase">`;
                trData += `<td>#</td>`;
                trData += `<td>Order ID</td>`;
                trData += `<td>Order Amount</td>`;
                trData += `<td>Date</td>`;
                trData += `<td>Payment Mode</td>`;
                trData += `<td></td>`;
                trData += `</tr>`;
                trData += `</thead>`;
            var creditBadge = ``, count=0;
            $.each(resp.result, function(i, e) {

                count++;

                if(e.payment_type == 'cash') {
                    creditBadge = `<span class="badge badge-success">Cash Sale</span>`;
                } else if(e.payment_type == 'momo') {
                    creditBadge = `<span class="badge badge-primary">Mobile Money</span>`;
                } else if(e.payment_type == 'card') {
                    creditBadge = `<span class="badge badge-secondary">Card Payment</span>`;
                } else if(e.payment_type == 'credit') {
                    creditBadge = `<span class="badge badge-danger">Credit</span>`;
                }

                trData += `<tr>`;
                trData += `<td>${count}</td>`;
                trData += `<td><a class="get-sales-details text-success" data-sales-id="${e.order_id}" href="javascript:void(0)" title="View Order Details">${e.order_id}</a></td>`;
                trData += `<td>${companyVariables.cur} ${e.order_amount_paid}</td>`;
                trData += `<td>${e.order_date}</td>`;
                trData += `<td>${creditBadge}</td>`;
                trData += `<td><a href="${baseUrl}invoice/${e.order_id}" title="View Purchase Details"><i class="fa fa-print"></i></a></td>`;
                trData += `</tr>`;
            });

            trData += `</table></div>`;

            $(`div[class~="attendantHistory"] div[class~="modal-body"]`).html(trData);

            $(`table[class~="orderHistory"]`).dataTable().fnDestroy();
            $(`table[class~="orderHistory"]`).DataTable({
                "buttons": ["copy", "print","csvHtml5"],
                "lengthChange": !1,
                "dom": "Bfrtip",
            });

        }, complete: function(data) {}, error: function(err) {
            $(`div[class~="attendantHistory"] div[class~="modal-body"]`).html(`
                <p align="center">No records found.</p>
            `);
        }
    });
    
}

$(`form[id="updateProductForm"]`).on('submit', function(e) {
        
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
                title: resp.message.result
            });

            if(resp.status == "success") {
                setTimeout(function() {
                    window.location.href = `${baseUrl}products/${resp.message.productId}`;
                }, 1500);
            }
            $(`div[class="form-content-loader"]`).css("display","none");
        }, error: function(err) {
            $(`div[class="form-content-loader"]`).css("display","none");
        }
    });

});

$(`button[class~="resend-email-button"]`).on('click', function(evt) {
    let thisBtn = $(this);
    let thisEmail = $(`input[name="send_email"]`);
    let fullname = $(`input[name="fullname"]`).val();
    let thisRequest = $(`input[name="request_type"]`).val();

    if(thisEmail.val().length > 5) {
        thisBtn.prop('disabled', true)
                .html(`Sending <i class="fa fa-spinner fa-spin"></i>`);
        thisEmail.prop('disabled', true);

        $.ajax({
            url: `${baseUrl}aj/pointOfSaleProcessor/sendMail`,
            type: `POST`,
            data: {sendMail: true, thisEmail: thisEmail.val(), fullname: fullname, thisRequest: thisRequest},
            dataType: "json",
            success: function(resp) {
                Toast.fire({
                    type: resp.status,
                    title: resp.message
                });
                if(resp.status == "success") {
                    $(`div[class~="sendMailModal"]`).modal('hide');
                }
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

if($(`table[class~="customersList"]`).length) {

    $("#updateCustomerForm").on("submit", async function(event) {
    
        event.preventDefault();
        let formData = $(this).serialize();

        $.post(baseUrl+"aj/customerManagement/updateCustomerDetails", formData, (res) => {
            if(res.status == 200){
                Toast.fire({
                    type: 'success',
                    title: res.message
                });
                listCustomers();
                $("#updateCustomerForm").parents(".modal").modal("hide");
                $("#updateCustomerForm").trigger("reset");
            } else {
                Toast.fire({
                    type: 'error',
                    title: res.message
                });                 
            }
            $(".content-loader", $("#updateCustomerForm")).css({display: "none"});
        })
        .catch((err) => {
            Toast.fire({
                type: 'error',
                title: "Error Processing Request"
            })      
            $(".content-loader", $("#updateCustomerForm")).css({display: "none"});
        })
    });

    $(`div[class="main-content"]`).on('click', `a[class~="add-customer"]`, function(e) {
        $(`div[id="newCustomerModal"]`).modal('show');
        $(`select[name="nc_title"]`).val('null').change();
        $(`div[id="newCustomerModal"] input[name="request"]`).val('add-record');
        $(`div[id="newCustomerModal"] h5[class="modal-title"]`).html('Add Customer');
        $(`div[id="newCustomerModal"] form[id="updateCustomerForm"] input`).val('');
        $(`input[name="customer_id"] form[id="updateCustomerForm"] input`).val('');
    });

    $(`div[class="main-content"]`).on('click', `a[class~="edit-customer"]`, function(e) {
        let userId = $(this).data('value');
            userData = $(`a[data-id='${userId}']`).data('info');

        $(`div[id="newCustomerModal"]`).modal('show');
        $(`div[id="newCustomerModal"] h5[class="modal-title"]`).html('Update Customer');
        $(`div[id="newCustomerModal"] select[name="nc_title"]`).val(userData.title).change();
        $(`div[id="newCustomerModal"] input[name="nc_firstname"]`).val(userData.firstname);
        $(`div[id="newCustomerModal"] input[name="nc_lastname"]`).val(userData.lastname);
        $(`div[id="newCustomerModal"] input[name="nc_email"]`).val(userData.email);
        $(`div[id="newCustomerModal"] input[name="nc_contact"]`).val(userData.phone_1);
        $(`div[id="newCustomerModal"] input[name="residence"]`).val(userData.residence);
        $(`div[id="newCustomerModal"] input[name="customer_id"]`).val(userId);
        $(`div[id="newCustomerModal"] input[name="request"]`).val('update-record');
    });

    var populateCustomersList = (customerData) => {
        hL();
        $(`table[class~="customersList"]`).dataTable().fnDestroy();
        $(`table[class~="customersList"]`).dataTable({
            "aaData": customerData,
            "iDisplayLength": 10,
            "buttons": ["copy", "print","csvHtml5"],
            "lengthChange": !1,
            "dom": "Bfrtip",
            "columns": [
               {"data": 'row_id'},
               {"data": 'fullname'},
               {"data": 'email'},
               {"data": 'phone_1'},
               {"data": 'date_log'},
               {"data": 'action'}
            ]
        });

        delI();

    }

    function listCustomers() {
        $.ajax({
            method: "POST",
            url: `${baseUrl}aj/customerManagement/listCustomers`,
            data: { listCustomers: true},
            dataType: "JSON",
            success: function(resp) {
                populateCustomersList(resp.result);
            }, complete: function(data) {
                hL();
            }, error: function(err) {
                hL();
            }
        });
    }

    listCustomers();
}

$("#newCustomer_form").on("submit", async function(event) {
    event.preventDefault();
    let formData = $(this).serialize();
    $(".content-loader", $("#newCustomerModal")).css({display: "flex"});
    
    await dOC().then((itResp) => {
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

        let customerId = randomInt(12);

        var formDetails = [{
            customer_id: `EV${customerId}`,
            title: $(`select[name="nc_title"]`).val(),
            firstname: $(`input[name="nc_firstname"]`).val(),
            lastname: $(`input[name="nc_lastname"]`).val(),
            phone_1: $(`input[name="nc_contact"]`).val(),
            email: $(`input[name="nc_email"]`).val(),
            clientId: companyVariables.clientId,
            branchId: companyVariables.branchId,
            fullname: $(`input[name="nc_firstname"]`).val() + ' ' + $(`input[name="nc_lastname"]`).val() 
        }];

        if($(`input[name="nc_firstname"]`).val().length < 2) {
            Toast.fire({
                type: 'error',
                title: `Please enter customer's firstname`
            });
            $(".content-loader", $("#newCustomerModal")).css({display: "none"});

            return false;
        } else if($(`input[name="nc_lastname"]`).val().length < 2) {
            Toast.fire({
                type: 'error',
                title: `Please enter customer's lastname`
            });
            $(".content-loader", $("#newCustomerModal")).css({display: "none"});

            return false;
        } else {
            var details = await aIDB('customers', formDetails).then((resp) => {

                $("#newCustomer_form").parents(".modal").modal("hide");
                $(".content-loader", $("#newCustomerModal")).css({display: "none"});

                $(".customer-select").children("option:first").after(`<option selected data-email='${$(`input[name="nc_email"]`).val()}' data-contact='${$(`input[name="nc_contact"]`).val()}' value=${customerId}>${$(`input[name="nc_firstname"]`).val()} ${$(`input[name="nc_lastname"]`).val()}</option>`)
                if($(`input[name="nc_email"]`).length){
                    $("#receipt-email").val($(`input[name="nc_email"]`).val());
                }

                $("#newCustomer_form").trigger("reset");
                $(".customer-select").trigger("change");

            });

            return false;
        }
    }

    $.post(baseUrl+"aj/pointOfSaleProcessor/quick-add-customer", formData, (res) => {
        if(res.status == "success"){
            $(".customer-select").children("option:first").after(`<option selected data-email='${res.data[3]}' data-contact='${res.data[4]}' value=${res.data[0]}>${res.data[1]} ${res.data[2]}</option>`)
            if(res.data[3]){
                $("#receipt-email").val(res.data[3]);
            }
            $(".customer-select").trigger("change");
            $("#newCustomer_form").parents(".modal").modal("hide");
            $("#newCustomer_form").trigger("reset");
        } else {
            Toast.fire({
                type: 'error',
                title: res.message
            })                  
        }
        $(".content-loader", $("#newCustomerModal")).css({display: "none"});
    })
    .catch((err) => {
        Toast.fire({
            type: 'error',
            title: "Error Processing Request"
        })      
        $(".content-loader", $("#newCustomerModal")).css({display: "none"});
    })
});

$(`a[class="select-branch"], a[class~="change-branch"]`).on('click', function(e) {
    e.preventDefault();
    let redir = $(this).data('href');
    $(`div[class="redirection-href"]`).attr('data-href', redir);
    $(`div[class~="importModal"]`).modal('show');
});

$(`div[class~="complete-branch-selection"]`).on('click', function() {
    let branchId = $(this).data('branch-id');
    $(this).children('div').css({'border': 'solid 1px blue'});

    Toast.fire({
        type: "success",
        title: "Branch selection was successful."
    });

    $(`div[class="form-content-loader"]`).css("display","flex");

    $.ajax({
        type: "POST",
        url: `${baseUrl}aj/importManager/setBranchId`,
        data: {setBranchId: true, curBranchId: branchId},
        success: function(resp) {
            window.location.href = $(`div[class="redirection-href"]`).attr('data-href');
        }, complete: function(data) {
            $(`div[class="form-content-loader"]`).css("display","none");
        }
    });
});