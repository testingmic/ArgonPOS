const iName = 'argonPOS-db';
const iVer = 1;
var storeValues = $.parseJSON($(`link[rel="params"]`).attr('_cl'));

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

function cOS() {
    var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
    var open = indexedDB.open(iName, iVer);
    open.onupgradeneeded = function(evt) {
        var obST = {
            request_products: 'product_id',
            customers: 'customer_id',
            sales: 'order_id',
            reports: 'reports_id'
        };
        var store;
        $.each(obST, function(sN, storeUniqueId) {
            if(sN == "reports") {
                store = evt.currentTarget.result.createObjectStore(
                    sN, { keyPath: storeUniqueId, autoIncrement: true });
            } else {
                store = evt.currentTarget.result.createObjectStore(
                    sN, { keyPath: storeUniqueId, autoIncrement: false });

                store.createIndex(storeUniqueId, storeUniqueId, { unique: true });
            }
        });
    };
    open.onsuccess = function() {}
}
cOS();


async function dPv(sN) {
    await listIDB(sN).then((result) => {
        var curDate = jsDate('fulldate');
        $.each(result, function(i, e) {
            if (e.today_date != curDate) {
                del(sN, e.order_id).then((res) => {});
            }
        });
    });
}

function listIDB(sN) {
    return new Promise((resolve, reject) => {
        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB
        var open = indexedDB.open(iName, iVer);
        open.onsuccess = function() {
            var db = open.result;
            var tx = db.transaction(sN, 'readwrite');
            var store = tx.objectStore(sN);
            var itemList;
            var results = store.getAll().onsuccess = evt => {
                itemList = evt.target.result;
                resolve(itemList);
            }
            tx.oncomplete = function() {
                db.close()
            }
        }
    });
}

function upIDB(sN, obDet) {
    return new Promise((resolve, reject) => {
        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB
        var open = indexedDB.open(iName, iVer);
        open.onsuccess = function() {
            var db = open.result;
            var tx = db.transaction(sN, 'readwrite');
            var store = tx.objectStore(sN);
            var req;
            try {
                $.each(obDet, function(ie, value) {
                    req = store.put(value);
                });
            } catch (e) {
                if (e.name == 'DataCloneError') {}
            }
            req.onsuccess = function(evt) {
                resolve(200);
            };
            req.onerror = function() {};
        };
    });
}

function aIDB(sN, obDet) {
    return new Promise((resolve, reject) => {
        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB
        var open = indexedDB.open(iName, iVer);
        open.onsuccess = function() {
            var db = open.result;
            var tx = db.transaction(sN, 'readwrite');
            var store = tx.objectStore(sN);
            var req;
            try {
                $.each(obDet, function(ie, value) {
                    req = store.add(value);
                });
            } catch (e) {
                if (e.name == 'DataCloneError') {}
            }
            req.onsuccess = function(evt) {
                resolve(200);
            };
            req.onerror = function() {};
        };
    });
}

function clearDBStore(sN) {
    return new Promise((resolve, reject) => {
        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB
        var open = indexedDB.open(iName, iVer);
        open.onsuccess = function() {
            var db = open.result;
            var tx = db.transaction(sN, 'readwrite');
            var store = tx.objectStore(sN);
            var req;
            try {
                req = store.clear();
            } catch (e) {
                if (e.name == 'DataCloneError') {}
                throw e;
            }
            req.onsuccess = function(evt) {
                resolve(200);
            };
            req.onerror = function() {};
        };
    });
}

function del(sN, recordId) {
    return new Promise((resolve, reject) => {
        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB
        var open = indexedDB.open(iName, iVer);
        open.onsuccess = function() {
            var db = open.result;
            var tx = db.transaction(sN, 'readwrite');
            var store = tx.objectStore(sN);
            var itemList;
            var results = store.delete(recordId).onsuccess = evt => {
                resolve(200);
            }
            tx.oncomplete = function() {
                db.close()
            }
        }
    });
}

function sPIDB() {

    return new Promise((resolve, reject) => {

        var response = new Array();
        var status = 'error', disAmt = 0,
            result, amtP, credit = 0,
            amountBalance = 0;

        var log_date = jsDate();
        var discountAmt = 0.00;
        var transactionId = randomInt(13);
        var sales_type = "Sales";
        var orderId = `INV${randomInt(13)}`;
        var customerId = $(`select[name="customer"]`).val();
        var paidAmount = parseFloat($(`input[name="amount_paying"]`).val());
        var totalToPay = parseFloat($(`span[class="total-to-pay-amount"]`).attr("data-order-total")).toFixed(2);
        var amountToBePaid = parseFloat($(`span[class="total-to-pay-amount"]`).attr("data-order-total"));
        var paymentType = $(`select[name="payment_type"]`).val();
        var customerName = $(`h6[class~="selected-customer-name"] h3`).text();
        
        var discountType = $(`input[name="discount_type"]:checked`).val();
        if($(`input[name="discount_amount"]`).val().length > 0) {
            disAmt = parseFloat($(`input[name="discount_amount"]`).val());
        } else {
            disAmt = 0;
        }

        if (paymentType == "credit") {
            credit = 1;
            amtP = 0;
            amountBalance = totalToPay;
        } else {
            amtP = paidAmount;
            amountBalance = (paidAmount - totalToPay);
        }

        if(discountType == "cash") {
            totalToPay = totalToPay - disAmt;
        } else {
            disAmt = parseFloat((disAmt/100)*totalToPay).toFixed(2);
            totalToPay = (totalToPay - disAmt);
        }

        var userId = storeValues._ud;
        var userName = storeValues._un;
        var branchId = storeValues._clb;
        var clientId = storeValues._cl;

        var regItems = [];
        var calNewT = 0;

        $.each($(`tbody[class="products-table-body"] tr[class="products-row"] input`), function(i, e) {
            var attrk = $(this).attr('data-row');
            var quantity = $(this).val();
            var product_title = $(this).attr('data-name');
            var unit_price = $(`input[name="products[${attrk}][price]"]`).val();

            if (typeof attrk !== typeof undefined && attrk !== false) {

                var eachTotal = (quantity * unit_price);
                var autoId = `${randomString(20)}`;

                regItems.push({
                    id: randomInt(12),
                    auto_id: autoId,
                    product_title: product_title,
                    clientId: clientId,
                    branchId: branchId,
                    order_id: orderId,
                    product_id: attrk,
                    product_quantity: quantity,
                    product_unit_price: unit_price,
                    product_total: eachTotal,
                    order_date: log_date
                });
                calNewT += eachTotal;
            }
        });

        var recalculatedValue = parseFloat(calNewT).toFixed(2);

        var mainSalesDetails = [{
            clientId: clientId,
            source: "Evelyn",
            branchId: branchId,
            mode: "offline",
            order_id: orderId,
            customer_id: customerId,
            customer_name: customerName,
            customer_fullname: customerName,
            customer_contact: '',
            saleItems: regItems,
            ordered_by_id: customerId,
            recorded_by: userId,
            sale_team_name: userName,
            credit_sales: credit,
            order_amount_paid: paidAmount,
            overall_order_amount: (amountToBePaid + disAmt),
            order_amount_balance: amountBalance,
            order_discount: disAmt,
            order_date: log_date,
            total_expected_selling_price: (amountToBePaid + disAmt),
            total_cost_price: recalculatedValue,
            order_status: 'confirmed',
            payment_type: paymentType,
            transaction_id: transactionId
        }];
        
        if (regItems.length < 1) {
            result = 'Sorry! You have not selected any products.';
        } else {
           
            var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB

            var open = indexedDB.open(iName, iVer);

            open.onsuccess = function() {
                var db = open.result;
                var tx = db.transaction('sales', 'readwrite');
                var store = tx.objectStore('sales');

                var req, req2;

                try {
                    $.each(mainSalesDetails, function(ie, value) {
                        req = store.add(value);
                    });
                } catch (e) {
                    if (e.name == 'DataCloneError') {}
                }
                req.onsuccess = function(evt) {
                    result = 'Payment Recorded';
                    status = 'success';

                    response = { status: status, result: result, orderId: orderId };
                    resolve(response);
                };
                req.onerror = function() {
                    result = 'Error processing request';
                    response = { status: status, result: result };
                    resolve(response);
                };
            };
        }
    });
}

function gIDBR(sN, recordId) {
    return new Promise((resolve, reject) => {
        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB
        var open = indexedDB.open(iName, iVer);
        open.onsuccess = function() {
            var db = open.result;
            var tx = db.transaction(sN, 'readwrite');
            var store = tx.objectStore(sN);
            var itemList;
            var results = store.get(recordId).onsuccess = evt => {
                itemList = evt.target.result;
                resolve(itemList);
            }
            tx.oncomplete = function() {
                db.close()
            }
        }
    });
}

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

if($(`table[class~="simple-table"]`).length) {
    $(`table[class~="simple-table"]`).dataTable({iDisplayLength: 5});
}

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
    await dOC().then((itResp) => {
        if(itResp == 1) {
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
        document.getElementById("categoryModal").focus();
    });

    $(`div[class~="categoryModal"] button[type="submit"]`).on('click', function() {
        let name = $(`input[name="category_name"]`).val();
        let id = $(`input[name="categoryId"]`).val();
        let request = $(`input[name="request"]`).val();

        $(`div[class="form-content-loader"]`).css("display","none");

        $.post(baseUrl+"api/categoryManagement/saveCategory", {name: name, id: id, dataset: request}, (res) => {
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
               {"data": 'category_id'},
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
            url: `${baseUrl}api/categoryManagement/listProductCategories`,
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
            $(`div[class~="offline-placeholder"]`).css('display','none');
        } else {
            noInternet = true;
            $(`div[class="connection"]`).css('display','block');
            $(`div[class~="offline-placeholder"]`).css('display','flex');
        }
    }).catch((err) => {
        noInternet = true;
        $(`div[class~="offline-placeholder"]`).css('display','flex');
        $(`div[class="connection"]`).css('display','block');
    });

    $.ajax({
        method: "POST",
        url: `${baseUrl}api/listRequests`,
        data: { listRequests: "true", requestType: requestType },
        dataType: "JSON",
        beforeSend: function() {},
        success: function(resp) {
            populateRequestsList(resp.result, `${tableName}`);
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
            $(`div[class~="offline-placeholder"]`).css('display','none');
        } else {
            offline = true;
            $(`div[class="connection"]`).css('display','block');
            $(`div[class~="offline-placeholder"]`).css('display','flex');
        }
    }).catch((err) => {
        offline = true;
        $(`div[class="connection"]`).css('display','block');
        $(`div[class~="offline-placeholder"]`).css('display','flex');
    });

    if(offline) {
        $(`div[class~="clearCache"]`).modal('show');

        $(`button[class~="confirm-clear-cache"]`).on('click', function(e) {
            caches.delete('argonPOS-Static-v1').then((res) => {
                window.location.href = `${baseUrl}login`;
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

var popCustLst = (data) => {
    $(`select[class~="customer-select"]`).find('option').remove().end();
    if(!$(`span[class="hide-walk-in-customer"]`).length) {
        $(`select[class~="customer-select"]`).append('<option value="WalkIn" data-prefered-payment="" data-contact="No Contact" selected="selected">Walk In Customer</option>');
    } else {
        $(`select[class~="customer-select"]`).append('<option value="null" data-contact="No Contact" selected="selected">-- Select Customer --</option>');
    }
    $.each(data, function(i, e) {
        $(`select[class~="customer-select"]`).append(`<option data-prefered-payment='${e.preferred_payment_type}' data-email='${e.email}' data-contact='${e.phone_1}' title='${e.fullname} (${e.phone_1})' value='${e.customer_id}'>${e.fullname}</option>`);
    });
}

var ftchCutLst = async () => {

    await dOC().then((itResp) => {
        if(itResp == 1) {
            noInternet = false;
            $(`div[class="connection"]`).css('display','none');
            $(`div[class~="offline-placeholder"]`).css('display','none');
        } else {
            noInternet = true;
            $(`div[class="connection"]`).css('display','block');
            $(`div[class~="offline-placeholder"]`).css('display','flex');
            $(`a[class~="shortcut-offline"]`).css({'filter': 'blur(4px)', 'pointer-events': 'none'});
            $(`li[class~="offline-menu"]`).css({'background-color': '#f6f9fc','filter': 'blur(3px)','pointer-events': 'none'});
        }
    }).catch((err) => {
        noInternet = true;
        $(`div[class="connection"]`).css('display','block');
        $(`div[class~="offline-placeholder"]`).css('display','flex');
        $(`a[class~="shortcut-offline"]`).css({'filter': 'blur(4px)', 'pointer-events': 'none'});
        $(`li[class~="offline-menu"]`).css({'background-color': '#f6f9fc','filter': 'blur(3px)','pointer-events': 'none'});
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
            popCustLst(newResults);
        });
        return false;
    }

    await syncOfflineData('customers').then((resp) => {
        $.post(baseUrl + "api/fetchCustomersOptionsList", {fetchCustomersOptionsList: true}, async function(data) {
            await clearDBStore('customers').then((resp) => {
                popCustLst(data.result);
                if(data.result.length) {
                    upIDB('customers', data.result);
                }
            });
        }, 'json');
    });

}

if($(`select[class~="customer-select"]`).length) {
    ftchCutLst();
}

var trgClClk = () => {
    $(".product-title-cell").on("click", function(e) {
        let cellCheckbox = $(this).siblings("td").find(".checkbox input:checkbox");
        cellCheckbox.prop("checked", !cellCheckbox.is(":checked"))
        cellCheckbox.trigger("change")
    });
}

var popPrdLst = (data) => {

    let htmlData = ``;

    $.each(data, function(i, e) {

        var trClass,
        checkbox = `<div class="checkbox checkbox-primary checkbox-single">
                <input type="checkbox" name="products[${e.product_id}][id]" value="${e.product_id}" data-product_max="${e.product_quantity}" class="product-select d-block" id="productCheck-${e.product_id}" data-product-id="${e.product_id}" data-product-name="${e.product_title}" data-product-code="${e.product_code}" data-product-price="${e.price}" data-product-img="${e.image}">
                <label for="productCheck-${e.product_id}">
                </label>
            </div>`;

        if(e.product_quantity < 1) {
            checkbox = ``;
            trClass = `class="text-danger" title="Out of Stock"`;
        } else {
            trClass = `title="${e.product_quantity} Stock Quantity Left"`;
        }

        htmlData += `<tr ${trClass} data-toggle="tooltip" id="productCheck-${e.product_id}" data-product-id="${e.product_id}" data-product-code="${e.product_code}" data-product-name="${e.product_title}" data-product-price="${e.price}" data-product-img="${e.image}" style="transition: all 0.8s ease" data-category="${e.category_id}" data-product_max="${e.product_quantity}">
            <td data-product-code="${e.product_code}">
                ${checkbox}
            </td>
            <td><img class="product-title-cell" src="${e.image}" width="24px"></td>
            <td class="product-title-cell" style="cursor:pointer">${e.product_title}</td>
            <td>${e.price}</td>
        </tr>`;
    });

    $(`tbody[class="pos-products-list"]`).html(htmlData);

    $(`tr[data-toggle="tooltip"]`).tooltip();
    trgClClk();
    initPrdSelt();
}

var ftcPrdList = async () => {

    await dOC().then((itResp) => {
        if(itResp == 1) {
            noInternet = false;
            $(`div[class="connection"]`).css('display','none');
            $(`div[class~="offline-placeholder"]`).css('display','none');
        } else {
            noInternet = true;
            $(`div[class="connection"]`).css('display','block');
            $(`div[class~="offline-placeholder"]`).css('display','flex');
            $(`a[class~="shortcut-offline"]`).css({'filter': 'blur(4px)', 'pointer-events': 'none'});
            $(`li[class~="offline-menu"]`).css({'background-color': '#f6f9fc','filter': 'blur(3px)','pointer-events': 'none'});
        }
    }).catch((err) => {
        noInternet = true;
        $(`div[class="connection"]`).css('display','block');
        $(`div[class~="offline-placeholder"]`).css('display','flex');
        $(`a[class~="shortcut-offline"]`).css({'filter': 'blur(4px)', 'pointer-events': 'none'});
        $(`li[class~="offline-menu"]`).css({'background-color': '#f6f9fc','filter': 'blur(3px)','pointer-events': 'none'});
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
            popPrdLst(newResults);
        });
        return false;
    }

    $.post(baseUrl + "api/fetchPOSProductsList", {fetchPOSProductsList: true}, async function(data) {
        await clearDBStore('request_products').then((resp) => {
            popPrdLst(data.result);
            if(data.result.length) {
                upIDB('request_products', data.result);
            }
        });
    }, 'json');

}

if($(`tbody[class="pos-products-list"]`).length) {
    ftcPrdList();
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

        $.ajax({
            url: baseUrl + "api/userManagement/fetchUsersLists",
            type: "POST",
            data: { fetchUsersLists: true },
            dataType: "json",
            cache: false,
            beforeSend: function() {
                sL();
            },
            success: function(data) {
                populateUsersList(data.message);
            },
            error: function() {
                
            },
            complete: function() {
                
            }
        });

    }

}

fetchUsersLists();


async function deleteMyItem(itemId, page, callBack = "") {

    if (itemId != "") {

        await dOC().then((itResp) => {
            if(itResp == 1) {
                noInternet = false;
                $(`div[class="connection"]`).css('display','none');
                $(`div[class~="offline-placeholder"]`).css('display','none');
            } else {
                noInternet = true;
                $(`div[class="connection"]`).css('display','block');
                $(`div[class~="offline-placeholder"]`).css('display','flex');
            }
        }).catch((err) => {
            noInternet = true;
            $(`div[class="connection"]`).css('display','block');
            $(`div[class~="offline-placeholder"]`).css('display','flex');
        });

        if(noInternet) {
            Toast.fire({
                type: 'error',
                title: 'Error Processing Request'
            });
            return false;
        }

        $.ajax({
            url: baseUrl + "api/userManagement/deleteUser",
            type: "POST",
            data: { deleteUser: true, itemId: itemId },
            dataType: "json",
            cache: false,
            beforeSend: function() {
                $(".show-delete-msg").html(`
                    <p class="text-center"><span class="fa fa-spinner fa-spin"></span><br>Please Wait...</p>
                `);
                $(".confirm-delete-btn").hide();
            },
            success: function(data) {
                Toast.fire({
                    type: data.status,
                    title: data.message
                });
                $(`div[class~="deleteModal"]`).modal('hide');
                $(".confirm-delete-btn").fadeIn(1000);
            },
            error: function() {
                Toast.fire({
                    type: 'error',
                    title: 'Error Processing Request'
                });
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
                $(`div[class~="offline-placeholder"]`).css('display','none');
            } else {
                noInternet = true;
                $(`div[class="connection"]`).css('display','block');
                $(`div[class~="offline-placeholder"]`).css('display','flex');
            }
        }).catch((err) => {
            noInternet = true;
            $(`div[class="connection"]`).css('display','block');
            $(`div[class~="offline-placeholder"]`).css('display','flex');
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
                url: baseUrl + "api/userManagement/getUserDetails",
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
                url: baseUrl + "api/userManagement/permissionManagement",
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
        url: baseUrl + "api/userManagement",
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

                url: baseUrl + "api/userManagement/saveAccessLevelSettings",
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
                $(`div[class~="offline-placeholder"]`).css('display','none');
            } else {
                noInternet = true;
                $(`div[class="connection"]`).css('display','block');
                $(`div[class~="offline-placeholder"]`).css('display','flex');
            }
        }).catch((err) => {
            noInternet = true;
            $(`div[class="connection"]`).css('display','block');
            $(`div[class~="offline-placeholder"]`).css('display','flex');
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
                url: baseUrl + "api/branchManagment/getBranchDetails",
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
                $(`div[class~="offline-placeholder"]`).css('display','none');
            } else {
                noInternet = true;
                $(`div[class="connection"]`).css('display','block');
                $(`div[class~="offline-placeholder"]`).css('display','flex');
            }
        }).catch((err) => {
            noInternet = true;
            $(`div[class="connection"]`).css('display','block');
            $(`div[class~="offline-placeholder"]`).css('display','flex');
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
            url: baseUrl + "api/branchManagment/fetchBranchesLists",
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

var triggerPrintReceipt = () => {
    $(`div[class="main-content"]`).on('click', `a[class~="print-receipt"]`, function(e) {
        let orderId = $(this).data('sales-id');
        window.open(
            `${baseUrl}receipt/${orderId}`,
            `Sales Invoice - Receipt #${orderId}`,
            `width=650,height=750,resizable,scrollbars=yes,status=1`
        );
    });
}

function cusPurHis() {
                    
    let userId = $(`a[class="view-user-sales"]`).attr('data-value');
    let fullname = $(`a[class="view-user-sales"]`).attr('data-name');
    var recordType = $(`a[class="view-user-sales"]`).attr('data-record');           

    $.ajax({
        type: "POST",
        url: `${baseUrl}api/reportsAnalytics/generateReport`,
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
                trData += `<td><a onclick="return getSalesDetails('${e.order_id}')" class="get-sales-details text-success" data-sales-id="${e.order_id}" href="javascript:void(0)" title="View Order Details">${e.order_id}</a></td>`;
                trData += `<td>${storeValues.cur} ${e.order_amount_paid}</td>`;
                trData += `<td>${e.order_date}</td>`;
                trData += `<td>${creditBadge}</td>`;
                trData += `<td><a href="javascript:void(0);" class="print-receipt" data-sales-id="${e.order_id}" title="View Purchase Details"><i class="fa fa-print"></i></a></td>`;
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

        }, complete: function(data) {
            triggerPrintReceipt();
        }, error: function(err) {
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
            url: `${baseUrl}api/pointOfSaleProcessor/sendMail`,
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

if($(`table[class~="customersList"], span[class~="customersList"]`).length) {

    $("#updateCustomerForm").on("submit", async function(event) {
    
        event.preventDefault();
        let formData = $(this).serialize();

        $.post(baseUrl+"api/customerManagement/updateCustomerDetails", formData, (res) => {
            if(res.status == 200){
                Toast.fire({
                    type: 'success',
                    title: res.message
                });
                if($(`span[class~="customersList"]`).length) {
                    setTimeout(() => {
                        window.location.href = '';
                    }, 1200);
                } else  {
                    listCustomers();
                }
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
            url: `${baseUrl}api/customerManagement/listCustomers`,
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

    if($(`table[class~="customersList"]`).length) {
        listCustomers();
    }
}

$("#newCustomer_form").on("submit", async function(event) {
    event.preventDefault();
    let formData = $(this).serialize();
    $(".content-loader", $("#newCustomerModal")).css({display: "flex"});
    
    await dOC().then((itResp) => {
        if(itResp == 1) {
            noInternet = false;
            $(`div[class="connection"]`).css('display','none');
            $(`div[class~="offline-placeholder"]`).css('display','none');
        } else {
            noInternet = true;
            $(`div[class="connection"]`).css('display','block');
            $(`div[class~="offline-placeholder"]`).css('display','flex');
        }
    }).catch((err) => {
        noInternet = true;
        $(`div[class="connection"]`).css('display','block');
        $(`div[class~="offline-placeholder"]`).css('display','flex');
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
            clientId: storeValues.clientId,
            branchId: storeValues.branchId,
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

    $.post(baseUrl+"api/pointOfSaleProcessor/quick-add-customer", formData, (res) => {
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
        url: `${baseUrl}api/importManager/setBranchId`,
        data: {setBranchId: true, curBranchId: branchId},
        success: function(resp) {
            window.location.href = $(`div[class="redirection-href"]`).attr('data-href');
        }, complete: function(data) {
            $(`div[class="form-content-loader"]`).css("display","none");
        }
    });
});

if($(".make-online-payment").length) {

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
            $(`span[class="sub_total"]`).html(`${storeValues.cur} 0.00`);
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
            $(".register-form ul[role='tablist'] li").addClass("disabled");
            $(".register-form ul[role='tablist'] li.current").removeClass("disabled");
            if(currentIndex == productsIndex) {
                document.getElementById("products-search-input").focus();
            }
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
                    title: "Please select a customer."
                });
                return false;
            }   
            else if((currentIndex == productsIndex && !$("input:checkbox.product-select:checked").length) && (newIndex != 0)) {
                Toast.fire({
                    type: 'error',
                    title: "Please select at least one product to proceed"
                });
                return false;                   
            }
            else if((currentIndex == paymentIndex && selectedPayType == "0") && (newIndex != 1)) {
                Toast.fire({
                    type: 'error',
                    title: "Please select the payment type to proceed."
                });
                return false;
            }
            else if(((selectedPayType == "cash") && (amountPaid < amountPayable) && (newIndex == completeIndex))) {
                Toast.fire({
                    type: 'error',
                    title: "The Amount being paid is less than the Total Amount."
                });
                return false;
            }
            else {
            
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
                            if(storeValues.prt == "yes") {
                                $(`button[class~="print-receipt"]`).trigger('click');
                            }
                            ftcPrdList();
                            $(`select[class~="customer-select"]`).val('WalkIn').change();
                        }

                    });

                } else {
                    svReg().then((res) => {
                        if(res.status == "success") {
                            $("[data-bind-html='orderId']").html(res.data._oid);
                            $(`span[class="generated_order"]`).html(res.data._oid);
                            Toast.fire({
                                type: 'success',
                                title: "Payment Successfully Recorded"
                            });
                            if(storeValues.prt == "yes") {
                                $(`button[class~="print-receipt"]`).trigger('click');
                            }
                            ftcPrdList();
                            $(`select[class~="customer-select"]`).val('WalkIn').change();
                            $(".cash-process-loader").removeClass("d-flex");
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: "Error processing the Sale Record."
                            });
                        }
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
        return $.post(baseUrl+"api/pointOfSaleProcessor/saveRegister", formData)
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
            $(`td.product-title-cell:Contains(${input}), td[data-product-code~="${input}"]`).parent().show();
            ckEmpTb($("#products-table"));
        }
        else{
            $(`table[id="products-table"] thead tr`).show();
            $(`td.product-title-cell`).parent().hide();
            $(`td.product-title-cell:Contains(${input})`).parents(`tr[data-category='${selectedCat}']`).show();
            ckEmpTb($("#products-table"));
        }
    });

    var initPrdSelt = () => {
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
                    });
                    $(`tr[class='products-row']`).on('click', function(e) {
                        $(`input[name="products[${$(this).data('row-id')}][qty]"]`).focus();
                    });
                    $(`.remove-row[data-row='${row.productId}']`).on("click", function(){
                        rvPtRow(row.productId);
                        $(`.product-select[data-product-id='${row.productId}']`).prop({"checked": false})
                    });
                    $(".print-receipt").on("click", printReceipt);
                    document.getElementById("products-search-input").focus();
                });
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
        $(`span[class="sub_total"]`).html(`${storeValues.cur} 0.00`);
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
            <input type='number' style="width:75px;text-align:center" data-name="${rowData.productName}" form="pos-form-horizontal" name="products[${rowData.productId}][qty]" min="1" data-max='${rowData.product_max}' data-row='${rowData.productId}' class='form-control product-quantity' value="${qty}">
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
        document.getElementById("products-search-input").focus();
        rcalRowNum();
        rcalTot();
    }

    var overallSubTotal = 0, totalDiscountDeducted = 0;

        function rcalTot(){
            let totalToPay = 0;
                overallSubTotal = 0;

            if($("tr.products-row .row-subtotal").length){
                let discountAmount;

                let discountType = $(`input[name="discount_type"]:checked`).val();
                if($(`input[name="discount_amount"]`).val().length > 0) {
                    discountAmount = parseFloat($(`input[name="discount_amount"]`).val());
                } else {
                    discountAmount = 0;
                }
                
                totalDiscountDeducted = 0;

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
            $(`span[class="sub_total"]`).html(`${storeValues.cur} ${formatCurrency(overallSubTotal)}`);
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

            $(`[data-bind-html='amount_paid']`).html(`${storeValues.cur} ${formatCurrency(value)}`);
            $("[data-bind-html='payment']").html(`${storeValues.cur} ${formatCurrency(overallSubTotal-totalDiscountDeducted)}`);
                $("[data-bind-html='balance']").html(paymentType == 'credit' ? `${storeValues.cur} ${formatCurrency(value)}` : `${storeValues.cur} ${formatCurrency(balance)}`);
            } else {
                $(`input[name="amount_balance"]`).val('0.00');

                $(".make-online-payment").addClass("d-none");
            $(".make-online-payment").attr("data-order-total", max);
            $(`[data-bind-html='amount_paid']`).html(`${storeValues.cur} 0.00`);
            $("[data-bind-html='payment']").html(paymentType == 'credit' ? `${storeValues.cur} ${formatCurrency(max)}` : "${storeValues.cur} 0.00");
                $("[data-bind-html='balance']").html(paymentType == 'credit' ? `${storeValues.cur} ${formatCurrency(max)}` : "${storeValues.cur} 0.00");
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
                url: `${baseUrl}api/pointOfSaleProcessor/sendMail`,
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
                url: baseUrl + "api/pointOfSaleProcessor/processMyPayment",
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
                            $(`[data-bind-html='amount_paid']`).html(`${storeValues.cur} ${formatCurrency(res.data.orderTotal)}`);
                            
                            $(`div[class~="payment-backdrop"]`).removeClass('hidden');
                            paymentWindow = window.open(data.message.msg,
                                `Payment for #${res.data.orderId}`,
                                `width=700,height=600,resizable,scrollbars=yes,status=1,left=${($(window).width())*0.25}`
                            );

                            $(`button[class~="return-to-payment-window"]`).on('click', function() {
                                paymentWindow.focus();
                            });
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
                        $(`div[class~="payment-backdrop"]`).addClass('hidden');
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
            url: `${baseUrl}api/pointOfSaleProcessor/checkPaymentStatus`,
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
                        $(`div[class~="payment-backdrop"]`).addClass('hidden');
                        $("[data-step-action='next']").prop("disabled", false);
                        $(".payment-type-select").prop("disabled", false);
                        $(`span[class="payment-processing-span"]`).html(``);
                    } else {
                        $(`div[class~="payment-backdrop"]`).addClass('hidden');
                        $(".payment-type-select, [data-step-action='previous']").prop("disabled", false);
                        $(".make-online-payment").removeClass("d-none");
                        $(".payment-processing-span").empty();
                    }

                    Toast.fire({
                        type: toastType,
                        title: toastMsg
                    });
                    $(".cancel-online-payment").addClass("d-none");
                }
            }
        });

    }

    $(".cancel-online-payment, button[class~='cancel-ongoing-payment-activity']").on("click", function() {

        $.post(`${baseUrl}api/pointOfSaleProcessor/cancelPayment`, {cancelPayment: true}, function(data) {
            let toastType = "error";
            let toastMsg  = "Failed To Cancel";
            if (data.status == 200) {
                clearInterval(paymentCheck);
                $(`div[class~="payment-backdrop"]`).addClass('hidden');
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
        }, 'json');
    });

}


var summaryItems = {
    'total-sales': 'totalSales'
};

async function getSalesDetails(salesID) {

    $(".show-modal-title").html("Sale Details");
    $(`div[class="form-content-loader"]`).css("display","flex");
    
    $(".launchModal").modal("show");

    await dOC().then((itResp) => {
        if (itResp == 1) {
            offline = false;
            $(`div[class="connection"]`).css('display', 'none');
            $(`div[class~="offline-placeholder"]`).css('display','none');
        } else {
            offline = true;
            $(`div[class="connection"]`).css('display', 'block');
            $(`div[class~="offline-placeholder"]`).css('display','flex');
        }
    }).catch((err) => {
        offline = true;
        $(`div[class="connection"]`).css('display', 'block');
        $(`div[class~="offline-placeholder"]`).css('display','flex');
    });

    if (offline) {

        var trData = `
            <div class="row table-responsive">`;

        var salesInfo = await gIDBR('sales', salesID).then((salesResult) => {

            trData += `<table class="table table-bordered">
                    <tr>
                        <td colspan='2' class='text-center'>
                            <strong>Served By: </strong> ${salesResult.recorded_by}<br>
                            <strong>Point of Sale: </strong> ${storeValues._clbn}
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Customer Name</strong>: ${salesResult.customer_fullname}</td>
                        <td align='left'><strong>Transaction ID:</strong>: ${salesResult.order_id}</td>
                    </tr>
                    <tr>
                        <td><strong>Contact</strong>: ${salesResult.customer_contact}</td>
                        <td align='left'><strong>Transaction Date</strong>: ${salesResult.order_date}</td>
                    </tr>
                </table>`;

            trData += `<table class="table table-bordered">
                    <thead>
                        <tr>
                            <td class="text-left">Product</td>
                            <td class="text-left">Quantity</td>
                            <td class="text-right">Unit Price</td>
                            <td class="text-right">Total</td>
                        </tr>
                    </thead>
                    <tbody>`;

            var subTotal = 0,
                discount = salesResult.order_discount;

            $.each(salesResult.saleItems, function(i, e) {
                trData += `
                    <tr>
                        <td>${e.product_title}</td>
                        <td>${e.product_quantity}</td>
                        <td class=\"text-right\">${storeValues.cur} ${e.product_unit_price}</td>
                        <td class=\"text-right\">${storeValues.cur} ${e.product_total}</td>
                    </tr>`;
                subTotal += parseFloat(e.product_total);
            });

            var overall = subTotal - discount;

            trData += `<tr>
                    <td style="font-weight:bolder;text-transform:uppercase" colspan="3" class="text-right">Subtotal</td>
                    <td style="font-weight:bolder;text-transform:uppercase" class="text-right">
                        ${storeValues.cur} ${formatCurrency(subTotal)}
                    </td>
                </tr>
                <tr>
                    <td style="font-weight:;text-transform:uppercase" colspan="3" class="text-right">Discount</td>
                    <td style="font-weight:;text-transform:uppercase" class="text-right">${storeValues.cur} ${discount}</td>
                </tr>
                <tr>
                    <td style="font-weight:bolder;text-transform:uppercase" colspan="3" class="text-right">Overall Total</td>
                    <td style="font-weight:bolder;text-transform:uppercase" class="text-right">${storeValues.cur} ${formatCurrency(overall)}</td>
                </tr>

                </tbody>
                </table>
            </div>
            <div class="card">
                <div class="row">
                    <div class="offline-placeholder main-body-loader" style="display: flex">
                        <div class="offline-content text-center">
                            <p>You are offline</p>
                            <button type="button" class="btn cursor btn-warning">Reconnect</button>
                        </div>
                    </div>
                </div>
            </div>`;

            $(".show-modal-body").html(trData);
            $(`div[class="form-content-loader"]`).css("display","none");
            reConnect();

        });

        return false;
    }

    $.ajax({
        url: baseUrl + "api/dashboardAnalytics/getSalesDetails",
        type: "POST",
        dataType: "json",
        data: { getSalesDetails: true, salesID: salesID },
        beforeSend: function() {
            $(".show-modal-title").html("Sale Details");
        },
        success: function(data) {
            if (data.status == true) {
                $(".show-modal-body").html(data.message);
            } else {
                $(".show-modal-body").html(`
                    <p class="alert alert-danger text-white text-center">No sales records found</p>
                `);
            }
        },
        complete: function() {
            $(`div[class="form-content-loader"]`).css("display","none");
        }, error: function(data) {
            $(`div[class="form-content-loader"]`).css("display","none");
        }
    });
    
}

$(function() {

    var populateBranchData = (branchInfo) => {
        $(`table[class~="branch-overview"]`).dataTable().fnDestroy();
        $(`table[class~="branch-overview"]`).dataTable({
            "aaData": branchInfo,
            "iDisplayLength": 5,
            "buttons": ["copy", "print","csvHtml5"],
            "lengthChange": !1,
            "dom": "Bfrtip",
            "columns": [
                { "data": 'branch_name' },
                { "data": 'total_sales' },
                { "data": 'highest_sales' },
                { "data": 'lowest_sales' },
                { "data": 'average_sales' },
                { "data": 'orders_count'},
                { "data": 'square_feet_sales'}
            ]
        });
    }

    var populateSalesTeamData = (teamInfo, chartLabels, chartData) => {

        $(`div[class="attendant-chart"]`).html(``);
        $(`div[class="attendant-chart"]`).html(`<div id="attendant-performance" class="apex-charts"></div>`);

        $(`table[class~="attendant-performance"]`).dataTable().fnDestroy();
        $(`table[class~="attendant-performance"]`).dataTable({
            "iDisplayLength": 5,
            "aaData": teamInfo,
            "buttons": ["copy", "print","csvHtml5"],
            "lengthChange": !1,
            "dom": "Bfrtip",
            "columns": [
                {"data":'fullname'},
                {"data":'sales.amount'},
                {"data":'sales.orders'},
                {"data":'sales.average_sale'},
                {"data":'targets.target_amount'},
                {"data":'targets.target_percent'},
                {"data":'items.total_items_sold'},
                {"data":'items.average_items_sold'}
            ]
        });

        var options2 = {
            chart: {
                height: 400,
                type: 'bar',
                toolbar: {
                    show: false
                },
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            series: [{
                name: 'Sales Recorded',
                data: chartData
            }],
            colors: ["#17a2b8"],
            yaxis: {
                axisBorder: {
                    show: true,
                    color: '#bec7e0',
                },
                axisTicks: {
                    show: true,
                    color: '#bec7e0',
                },
            },
            xaxis: {
                categories: chartLabels
            },
            states: {
                hover: {
                    filter: 'none'
                }
            },
            grid: {
                borderColor: '#f1f3fa'
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function(y) {
                        if (typeof y !== "undefined") {
                            return storeValues.cur + formatCurrency(y);
                        }
                        return y;

                    }
                }
            },
        }

        var apex_bar1_branch = new ApexCharts(
            document.querySelector("#attendant-performance"),
            options2
        );

        apex_bar1_branch.render();
    }

    var populateProductsPerformance = (productsInfo) => {

        if($(`ul[class~="most-performing-products"]`).length) {
            var productsArray = ``;

                $.each(productsInfo, function(i, e) {
                    productsArray += `<li class="list-group-item px-0">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <a href="${baseUrl}products/${e.id}" class="avatar rounded-circle">
                            <img alt="" src="${baseUrl}${e.product_image}">
                          </a>
                        </div>
                        <div class="col">
                          <h5>${e.product_title}</h5>
                          <div class="progress progress-xs mb-0">
                            <div class="progress-bar bg-orange" role="progressbar" aria-valuenow="${e.percentage}" aria-valuemin="0" aria-valuemax="100" style="width: ${e.percentage}%;"></div>
                          </div>
                          <div class="row justify-content-between">
                            <div class="pl-2"><strong>Sold:</strong> ${e.quantity_sold}</div>
                            <div class="pr-3"><strong>Revenue:</strong> ${e.total_selling_revenue}</div>
                          </div>
                        </div>
                      </div>
                    </li>`;
                });

            $(`ul[class~="most-performing-products"]`).html(productsArray);

        } else {
            $(`table[class~="products-performance"]`).dataTable().fnDestroy();
            $(`table[class~="products-performance"]`).dataTable({
                "iDisplayLength": 7,
                "aaData": productsInfo,
                "buttons": ["copy", "print","csvHtml5"],
                "lengthChange": !1,
                "dom": "Bfrtip",
                "columns": [
                    { "data": 'row_id'},
                    { "data": 'product_title'},
                    { "data": 'orders_count'},
                    { "data": 'quantity_sold'},
                    { "data": 'total_selling_cost'},
                    { "data": 'total_selling_revenue'},
                    { "data": 'product_profit'}
                ]
            });
        }
    }

    var branchPerformance = (periodSelected = 'today') => {
        if ($(`table[class~="branch-overview"]`).length) {
            $.ajax({
                type: "POST",
                url: `${baseUrl}api/reportsAnalytics/generateReport`,
                data: { generateReport: true, queryMetric: "branchPerformance", salesPeriod: periodSelected},
                dataType: "JSON",
                beforeSend: function() {
                },
                success: function(resp) {
                    populateBranchData(resp.result.summary);
                }, complete: function(data) {
                    hL();
                }
            });
        }
    }

    var summaryItems = (periodSelected = 'today') => {
        $.ajax({
            type: "POST",
            url: `${baseUrl}api/reportsAnalytics/generateReport`,
            data: { generateReport: true, queryMetric: "summaryItems", salesPeriod: periodSelected },
            dataType: "JSON",
            beforeSend: function() {
                sL();
                $(`div[data-report] p[class~="text-truncate"]`).html(`
                    Loading <i class="fa fa-spin fa-spinner"></i>
                `);
            },
            success: function(resp) {
                $.each(resp.result, function(i, e) {
                    $(`div[data-report="${e.column}"] h3[class~="my-3"]`).html(e.total);
                    $(`div[data-report="${e.column}"] p[class~="text-truncate"]`).html(e.trend);
                });
            },
            error: function(err) {
            }
        });
    }

    var salesOverview = (periodSelected = 'today') => {

        $.ajax({
            type: "POST",
            url: `${baseUrl}api/reportsAnalytics/generateReport`,
            data: { generateReport: true, queryMetric: "salesOverview", salesPeriod: periodSelected },
            dataType: "JSON",
            success: function(resp) {

                $(`div[class="chart-sales"]`).html(``);
                $(`div[class="chart-sales"]`).html(`<div id="sales-overview-chart" class="apex-charts"></div>`);

                $(`p[data-model="highest-sale"] span`).html(resp.result.sales.highest);
                $(`p[data-model="lowest-sale"] span`).html(resp.result.sales.lowest);

                $(`p[data-model="total-sales"] span`).html(resp.result.sales.comparison.total_sales);
                $(`p[data-model="total-actual-sales"] span`).html(resp.result.sales.comparison.total_actual_sales);
                $(`p[data-model="total-credit-sales"] span`).html(resp.result.sales.comparison.total_credit_sales);

                $(`p[data-model="sales-with-discount"] span`).html(resp.result.sales.discount_effect.total_sale);
                $(`p[data-model="sales-without-discount"] span`).html(resp.result.sales.discount_effect.discounted_sale);

                populateProductsPerformance(resp.result.sales.products_performance);

                if ($("#sales-overview-chart").length) {
                    
                    var thisOpts = {
                        chart: {
                            height: 374,
                            type: 'line',
                            shadow: {
                                enabled: false,
                                color: '#bbb',
                                top: 3,
                                left: 2,
                                blur: 3,
                                opacity: 1
                            },
                            zoom: false
                        },
                       
                        plotOptions: {
                            bar: {
                                columnWidth: '30%'
                            }
                        },
                        stroke: {
                            width: [4, 0],
                            curve: 'smooth'
                        },
                        series: [{
                            type: 'line',
                            name: 'Total Sales With Discount',
                            data: resp.result.sales.discount_effect.with_discount
                        }, {
                            type: 'area',
                            name: 'Total Sales Without Discount',
                            data: resp.result.sales.discount_effect.without_discount
                        }],
                        xaxis: {
                            type: 'datetime',
                            categories: resp.result.labeling,
                            axisBorder: {
                                show: true,
                                color: '#bec7e0',
                            },
                            axisTicks: {
                                show: true,
                                color: '#f1646c',
                            },
                        },   
                        colors: ["#1ecab8", "#f7cda0"],                 
                        markers: {
                            size: 4,
                            opacity: 0.9,
                            colors: ["#ffbc00"],
                            strokeColor: "#fff",
                            strokeWidth: 2,
                            style: 'hollow',
                            hover: {
                                size: 7,
                            }
                        },
                        yaxis: {
                            title: {
                                text: 'Sales Values',
                            },
                        },
                        fill: {
                          type: 'gradient',
                          gradient: {
                            gradientToColors: ['#f1646c'],
                            shadeIntensity: 0.1,
                            type: 'horizontal',
                            opacityFrom: 0.7,
                            opacityTo: 1,
                            stops: [0, 100, 100, 100]
                          },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: function(y) {
                                    if (typeof y !== "undefined") {
                                        return storeValues.cur + formatCurrency(y);
                                    }
                                    return y;

                                }
                            }
                        },
                        grid: {
                          row: {
                            colors: ['transparent', 'transparent'], 
                            opacity: 0.2
                          },
                          borderColor: '#185a9d'
                        }
                    }

                    if (periodSelected == 'today') {
                        delete thisOpts.xaxis.type;
                    }

                    var chart = new ApexCharts(
                        document.querySelector("#sales-overview-chart"),
                        thisOpts
                    );

                    chart.render();

                }


                if($(`div[class="chart-comparison"]`).length){
                   
                    $(`div[class="chart-comparison"]`).html(``);
                    $(`div[class="chart-comparison"]`).html(`<div id="sales-comparison" class="apex-charts"></div>`);
                    var compareSales = {
                        chart: {
                            height: 420,
                            type: 'line',
                            stacked: false,
                            toolbar: {
                                show: true
                            },
                            zoom: false,
                            shadow: {
                                enabled: false,
                                color: '#bbb',
                                top: 3,
                                left: 2,
                                blur: 3,
                                opacity: 1
                            },
                        },
                        stroke: {
                            width: [0, 2, 4],
                            curve: 'smooth'
                        },
                        plotOptions: {
                            bar: {
                                columnWidth: '40%'
                            }
                        },
                        colors: ["#1ecab8", "#fbb624", "#f93b7a"],
                        series: [{
                            name: 'Total Sales',
                            type: 'column',
                            data: resp.result.data
                        }, {
                            name: 'Paid Sales',
                            type: 'area',
                            data: resp.result.sales.actuals
                        }, {
                            name: 'Credit Sales',
                            type: 'line',
                            data: resp.result.sales.credit
                        }],
                        fill: {
                            opacity: [0.85, 0.25, 1],
                            gradient: {
                                inverseColors: false,
                                shade: 'light',
                                type: "vertical",
                                opacityFrom: 0.85,
                                opacityTo: 0.55,
                                stops: [0, 100, 100, 100]
                            }
                        },
                        labels: resp.result.labeling,
                        markers: {
                            size: 2,
                            opacity: 0.9,
                            colors: ["#ffbc00"],
                            strokeColor: "#fff",
                            strokeWidth: 2,
                            style: 'hollow',
                            hover: {
                                size: 7,
                            }
                        },
                        xaxis: {
                            type: 'datetime',
                            axisBorder: {
                                show: true,
                                color: '#bec7e0',
                            },
                            axisTicks: {
                                show: true,
                                color: '#bec7e0',
                            },
                        },
                        yaxis: {
                            title: {
                                text: 'Saes Values',
                            },
                        },
                        tooltip: {
                            shared: true,
                            intersect: false,
                            y: {
                                formatter: function(y) {
                                    if (typeof y !== "undefined") {
                                        return storeValues.cur + formatCurrency(y);
                                    }
                                    return y;

                                }
                            }
                        },
                        grid: {
                            borderColor: '#f1f3fa'
                        }
                    }
                    if (periodSelected == 'today') {
                        delete compareSales.xaxis.type;
                    }
                    var compareChart = new ApexCharts(
                        document.querySelector("#sales-comparison"),
                        compareSales
                    );
                    compareChart.render();
                }


                if($(`div[class="payment-chart"]`).length){
                    $(`div[class="payment-chart"]`).html(``);
                    $(`div[class="payment-chart"]`).html(`<div id="payment-options" class="apex-charts"></div>`);
                    var paymentOptions = {
                      chart: {
                          height: 380,
                          type: 'donut',
                      }, 
                      series: resp.result.sales.payment_options.payment_values,
                      legend: {
                          show: true,
                          position: 'bottom',
                          horizontalAlign: 'center',
                          verticalAlign: 'middle',
                          floating: false,
                          fontSize: '14px',
                          offsetX: 0,
                          offsetY: -10
                      },
                      tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function(y) {
                                if (typeof y !== "undefined") {
                                    return storeValues.cur + formatCurrency(y);
                                }
                                return y;

                            }
                        }
                      },
                      labels: resp.result.sales.payment_options.payment_option,
                      colors: ["#08aeb0", "#232f5b","#f06a6c", "#f1e299", "#08aeb0"],
                      responsive: [{
                          breakpoint: 600,
                          options: {
                              chart: {
                                  height: 270
                              },
                              legend: {
                                  show: true
                              },
                          }
                      }],
                      fill: {
                          type: 'gradient'
                      }
                    }
                    var paymentChart = new ApexCharts(
                        document.querySelector("#payment-options"),
                        paymentOptions
                    );
                    paymentChart.render();
                }

                if($(`div[class="category-chart"]`).length) {
                    $(`div[class="category-chart"]`).html(``);
                    $(`div[class="category-chart"]`).html(`<div id="category-options" class="apex-charts"></div>`);
                    var productCategoryOptions = {
                      chart: {
                          height: 450,
                          type: 'donut',
                      }, 
                      series: resp.result.sales.category_sales.data,
                      legend: {
                          show: true,
                          position: 'bottom',
                          horizontalAlign: 'center',
                          verticalAlign: 'middle',
                          floating: false,
                          fontSize: '14px',
                          offsetX: 0,
                          offsetY: -10
                      },
                      tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function(y) {
                                if (typeof y !== "undefined") {
                                    return storeValues.cur + formatCurrency(y);
                                }
                                return y;

                            }
                        }
                      },
                      labels: resp.result.sales.category_sales.labels,
                      colors: ["#08aeb0", "#232f5b","#f06a6c", "#f1e299", "#08aeb0"],
                      responsive: [{
                          breakpoint: 600,
                          options: {
                              chart: {
                                  height: 270
                              },
                              legend: {
                                  show: true
                              },
                          }
                      }],
                      fill: {
                          type: 'gradient'
                      }
                    }
                    var productCategoryChart = new ApexCharts(
                        document.querySelector("#category-options"),
                        productCategoryOptions
                    );
                    productCategoryChart.render();
                }


                $(`div[class="revenue-chart"]`).html(``);
                $(`div[class="revenue-chart"]`).html(`<div id="revenue-trend" class="apex-charts"></div>`);
                if($("#revenue-trend").length) {
                    var revenueOptions = {
                      chart: {
                          height: 450,
                          type: 'line',
                          stacked: false,
                          zoom: false,
                          toolbar: {
                              show: false
                          }
                      },
                      dataLabels: {
                          enabled: false
                      },
                      stroke: {
                          width: [0, 0, 2]
                      },
                      plotOptions: {
                        bar: {
                            columnWidth: '40%'
                        }
                      },
                      series: [{
                          name: 'Products Cost Price',
                          type: 'column',
                          data: resp.result.sales.revenue.cost
                      }, {
                          name: 'Products Selling Price',
                          type: 'column',
                          data: resp.result.sales.revenue.selling
                      }, {
                          name: 'Profit Generated',
                          type: 'line',
                          data: resp.result.sales.revenue.profit
                      }],
                      colors: ["#fa5c7c", "#20016c", "#77d0ba"],
                      xaxis: {
                          type: 'datetime',
                          categories: resp.result.labeling,
                          axisBorder: {
                            show: true,
                            color: '#bec7e0',
                          },  
                          axisTicks: {
                            show: true,
                            color: '#bec7e0',
                        }, 
                      },
                      yaxis: [
                          {
                              opposite: true,
                              axisTicks: {
                                  show: true,
                              },
                              axisBorder: {
                                  show: true,
                                  color: '#77d0ba'
                              },
                              labels: {
                                  style: {
                                      color: '#77d0ba',
                                  }
                              },
                              title: {
                                  text: "Products Cost, Selling & Revenue Generated"
                              }
                          },

                      ],
                      tooltip: {
                          followCursor: true,
                          y: {
                              formatter: function (y) {
                                  if (typeof y !== "undefined") {
                                      return storeValues.cur + formatCurrency(y);
                                  }
                                  return y;
                              }
                          }
                      },
                      grid: {
                          borderColor: '#f1f3fa'
                      },
                      legend: {
                          offsetY: -10,
                      },
                      responsive: [{
                          breakpoint: 600,
                          options: {
                              yaxis: {
                                  show: false
                              },
                              legend: {
                                  show: false
                              }
                          }
                      }]
                    }
                    if (periodSelected == 'today') {
                        delete revenueOptions.xaxis.type;
                    }
                    var revenueChart = new ApexCharts(
                        document.querySelector("#revenue-trend"),
                        revenueOptions
                    );
                    revenueChart.render();
                }
                $(`div[class~="apexcharts-legend"]`).addClass('hidden');
            },
            error: function(err) {
                hL();
            }, complete: function(data) {
                setTimeout(function() {
                    hL();
                    $(`div[class~="apexcharts-legend"]`).removeClass('center hidden');
                }, 1000);
            }
        });
    }

    var salesAttendantHistory = () => {

        $(`div[class="main-content"]`).on('click', `a[class~="view-user-sales"]`, function() {

            var userId = $(this).attr('data-value');
            var fullname = $(this).attr('data-name');
            var recordType = $(this).attr('data-record');

            if (recordType == "attendant") {
                $(`div[class~="attendantHistory"] h5`).html(`${fullname}'s Sales History`);
            } else {
                $(`div[class~="attendantHistory"] h5`).html(`${fullname}'s Purchases History`);
            }
            $(`div[class~="attendantHistory"]`).modal('show');

            $.ajax({
                type: "POST",
                url: `${baseUrl}api/reportsAnalytics/generateReport`,
                data: { generateReport: true, salesAttendantHistory: true, queryMetric: "salesAttendantPerformance", userId: userId, recordType: recordType },
                dataType: "JSON",
                beforeSend: function() {
                    $(`div[class~="attendantHistory"] div[class~="modal-body"]`).html(`<div align="center">Loading records <i class="fa fa-spin fa-spinner"></i></div>`);
                },
                success: function(resp) {
                    var trData = `<table width="100%" class="table table-bordered orderHistory">`;
                    trData += `<thead>`;
                    trData += `<tr class="text-uppercase">`;
                    trData += `<td>Order ID</td>`;
                    trData += `<td>Customer Name</td>`;
                    trData += `<td>Order Amount</td>`;
                    trData += `<td>Date</td>`;
                    trData += `<td></td>`;
                    trData += `</tr>`;
                    trData += `</thead>`;
                    var creditBadge = ``;
                    $.each(resp.result, function(i, e) {

                        if (e.payment_type == 'cash') {
                            creditBadge = `<span class="text-gray">Cash Sale</span>`;
                        } else if (e.payment_type == 'momo') {
                            creditBadge = `<span class="text-gray">Mobile Money</span>`;
                        } else if (e.payment_type == 'card') {
                            creditBadge = `<span class="text-gray">Card Payment</span>`;
                        } else if (e.payment_type == 'credit') {
                            creditBadge = `<span class="text-gray">Credit</span>`;
                        }

                        trData += `<tr>`;
                        trData += `<td><a onclick="getSalesDetails('${e.order_id}');" class="get-sales-details" data-sales-id="${e.order_id}" href="javascript:void(0)" title="View Order Details">${e.order_id}</a><br>${creditBadge}</td>`;
                        trData += `<td><a onclick="getSalesDetails('${e.order_id}');" data-name="${e.fullname}" href="javascript:void(0);" title="Click to list customer orders history" data-value="${e.customer_id}" class="customer-orders">${e.fullname}</a></td>`;
                        trData += `<td>${storeValues.cur}${e.order_amount_paid}</td>`;
                        trData += `<td>${e.order_date}</td>`;
                        trData += `<td><a class="print-receipt" data-sales-id="${e.order_id}" href="javascript:void(0)" title="View Purchase Details"><i class="fa fa-print"></i></a></td>`;
                        trData += `</tr>`;
                    });

                    trData += `</table>`;

                    $(`div[class~="attendantHistory"] div[class~="modal-body"]`).html(trData);

                    $(`table[class~="orderHistory"]`).DataTable();

                },
                complete: function(data) {
                    triggerPrintReceipt();
                },
                error: function(err) {
                    $(`div[class~="attendantHistory"] div[class~="modal-body"]`).html(`
                        <p align="center">No records found.</p>
                    `);
                }
            });

        });
    }

    var salesAttendantPerformance = (periodSelected = 'today') => {
        if ($(`div[id="attendant-performance"]`).length) {
            $.ajax({
                type: "POST",
                url: `${baseUrl}api/reportsAnalytics/generateReport`,
                data: { generateReport: true, queryMetric: "salesAttendantPerformance", salesPeriod: periodSelected },
                dataType: "JSON",
                beforeSend: function() {

                },
                success: function(resp) {

                    populateSalesTeamData(resp.result.list, resp.result.names, resp.result.sales);

                },
                complete: function(data) {
                    salesAttendantHistory();
                    $(`div[class~="apexcharts-legend"]`).removeClass('center');
                },
                error: function(err) {
                }
            });
        }
    }

    var ordersCount = (periodSelected = 'today') => {
        $.ajax({
            type: "POST",
            url: `${baseUrl}api/reportsAnalytics/generateReport`,
            data: { generateReport: true, queryMetric: "ordersCount", salesPeriod: periodSelected },
            dataType: "JSON",
            beforeSend: function() {
                $(`div[class~="orders-loader"]`).html(`
                    Loading <i class="fa fa-spin fa-spinner"></i>
                `);
            },
            success: function(resp) {

                $(`div[class~="orders-loader"]`).html(``);

                $(`div[class="apexchart-wrapper"]`).html(``);
                $(`div[class="apexchart-wrapper"]`).html(`<div id="dash_spark_1" class="chart-gutters"></div>`);
                $(`div[data-report="orders-trend"] h3[class="mn-3"]`).html(`${resp.result.count} <small>total orders</small>`);

                var ordersTrend = {
                    chart: {
                        type: 'line',
                        height: 385,
                        sparkline: {
                            enabled: false
                        },
                        toolbar: {
                            show: false
                        },
                        zoom: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    fill: {
                        opacity: [0.45, 0.25, 1]
                    },
                    series: [{
                        name: 'All Orders',
                        type: 'line',
                        data: resp.result.data
                    }, {
                        name: 'New Customers',
                        type: 'line',
                        data: resp.result.unique_customers
                    }, {
                        name: 'Returning Customers',
                        type: 'line',
                        data:resp.result.returning_customers
                    }],
                    yaxis: {
                        min: 0
                    },
                    xaxis: {
                        type: 'datetime',
                        categories: resp.result.labeling,
                        axisBorder: {
                            show: true,
                            color: '#bec7e0',
                        },
                        axisTicks: {
                            show: true,
                            color: '#bec7e0',
                        },
                    },
                    colors: ["#e6c255", "#1ecab8", "#ffc107"],
                    tooltip: {
                        shared: true,
                        intersect: false
                    },
                    grid: {
                        borderColor: '#f1f3fa'
                    }
                }

                if (periodSelected == 'today') {
                    delete ordersTrend.xaxis.type;
                }
                new ApexCharts(document.querySelector("#dash_spark_1"), ordersTrend).render();

            }, error: function(err) {
                $(`div[class~="orders-loader"]`).html(``);
            }, complete: function(data) {
                $(`div[class~="apexcharts-legend"]`).removeClass('center');
            }
        });
    }

    var topContactsPerformance = (periodSelected = 'today') => {

        if ($(`table[class~="custPerformance"]`).length) {
            $.ajax({
                type: "POST",
                url: `${baseUrl}api/reportsAnalytics/generateReport`,
                data: { generateReport: true, queryMetric: "topContactsPerformance", salesPeriod: periodSelected },
                dataType: "JSON",
                beforeSend: function() {},
                success: function(resp) {
                    var count = 0,
                        trData = ``;

                    $(`table[class~="custPerformance"]`).dataTable().fnDestroy();
                    $(`table[class~="custPerformance"]`).dataTable({
                        "aaData": resp.result,
                        "iDisplayLength": 10,
                        "buttons": ["copy", "print","csvHtml5"],
                        "lengthChange": !1,
                        "dom": "Bfrtip",
                        "columns": [
                            { "data": 'row_id' },
                            { "data": 'fullname' },
                            { "data": 'orders_count' },
                            { "data": 'total_amount' },
                            { "data": 'total_balance' },
                            { "data": 'action' }
                        ]
                    });
                },
                error: function(err) {},
                complete: function(data) {
                    salesAttendantHistory();
                }
            });
        }
    }

    var fetchSalesRecords = () => {
        var colspan = "7";

        var period = $(`select[name="periodSelected"]`).val();

        $.ajax({
            url: `${baseUrl}api/dashboardAnalytics/getSales`,
            type: "POST",
            dataType: "json",
            data: { getSales: true, salesPeriod: period },
            beforeSend: function() {},
            success: function(data) {

                $(`table[class~="salesLists"]`).dataTable().fnDestroy();
                if (data.status == true) {
                    $(`table[class~="salesLists"]`).dataTable({
                        "aaData": data.message.table,
                        "iDisplayLength": 10,
                        "buttons": ["copy", "print","csvHtml5"],
                        "lengthChange": !1,
                        "dom": "Bfrtip",
                        "columns": [
                            { "data": 'row' },
                            { "data": 'order_id' },
                            { "data": 'fullname' },
                            { "data": 'phone' },
                            { "data": 'date' },
                            { "data": 'amount' },
                            { "data": 'action' }
                        ]
                    });

                    $(".total-sales").html(data.message.totalSales.total);
                    $(".total-sales-trend").html(data.message.totalSales.trend);
                    
                    $(".total-served").html(data.message.totalServed.total);
                    $(".total-served-trend").html(data.message.totalServed.trend);
                    
                    $(".average-sales").html(data.message.averageSales.total);
                    $(".average-sales-trend").html(data.message.averageSales.trend);

                    $(".total-discounts").html(data.message.totalDiscount.total);
                    $(".total-discounts-trend").html(data.message.totalDiscount.trend);
                    
                    $(".total-credit-sales").html(data.message.totalCredit.total);
                    $(".total-credit-sales-trend").html(data.message.totalCredit.trend);

                    $(".total-profit").html(data.message.salesComparison.profit);
                    $(".total-profit-trend").html(data.message.salesComparison.profit_trend);

                    $(".total-selling").html(data.message.salesComparison.selling);
                    $(".total-selling-trend").html(data.message.salesComparison.selling_trend);

                    $(".total-cost").html(data.message.salesComparison.cost);
                    $(".total-cost-trend").html(data.message.salesComparison.cost_trend);
                } else {
                    $(`table[class~="salesLists"]`).dataTable();
                }

            },
            complete: function(data) {
                $(`div[class="main-content"]`).on('click', `a[class~="print-receipt"]`, function(e) {
                    let orderId = $(this).data('sales-id');
                    window.open(
                        `${baseUrl}receipt/${orderId}`,
                        `Sales Invoice - Receipt #${orderId}`,
                        `width=650,height=750,resizable,scrollbars=yes,status=1`
                    );
                });
                hL();
            }, error: function(err) {
            }
        });
    }

    var fetchInventoryRecords = () => {

        if ($(`table[class~="inventoryLists"]`).length) {
            var colspan = "7";

            $.ajax({
                url: `${baseUrl}api/dashboardAnalytics/fetchInventoryRecords`,
                type: "POST",
                dataType: "json",
                data: { fetchInventoryRecords: true, request: "fetchInventoryRecords" },
                success: function(data) {
                    if (data.status == true) {
                        $(`table[class~="inventoryLists"]`).dataTable().fnDestroy();
                        $(`table[class~="inventoryLists"]`).dataTable({
                            "aaData": data.message,
                            "iDisplayLength": 10,
                            "columns": [
                                { "data": 'row' },
                                { "data": 'product' },
                                { "data": 'quantity' },
                                { "data": 'price' },
                                { "data": 'recordedBy' },
                                { "data": 'orderDate' }
                            ]
                        });
                    }
                },
                complete: function() {}
            });
        }
    }

    var fetchProductThresholds = () => {
        if ($(`table[class~="thresholdLists"]`).length) {
            var colspan = "3";
            $.ajax({
                url: baseUrl + "api/dashboardAnalytics",
                type: "POST",
                dataType: "json",
                data: { request: "getProductThresholds" },
                success: function(data) {
                    if (data.status == true) {
                        $(`table[class~="thresholdLists"]`).dataTable().fnDestroy();
                        $(`table[class~="thresholdLists"]`).dataTable({
                            "aaData": data.message,
                            "iDisplayLength": 10,
                            "columns": [
                                { "data": 'row' },
                                { "data": 'product' },
                                { "data": 'quantity' }
                            ]
                        });
                    }
                },
                complete: function() {}
            });
        }
    }

    async function dashboardAnalitics() {

        async function loadSalesData() {
            return new Promise((resolve, reject) => {
                var data = listIDB('sales');
                resolve(data);
            });
        }

        var salesData = await loadSalesData();

        Array.prototype.unique = function() {
            return this.filter(function(value, index, self) {
                return self.indexOf(value) === index;
            });
        }

        var totals = 0,
            credits = 0,
            orders = 0, expectedSellingPrice = 0,
            productsCostPrice = 0,
            customers = 0, row = 0;
        var customerArray = new Array();
        var salesArray = new Array();
        var salesFigures = new Array();
        var creditSalesArray = new Array();
        var paidSalesArray = new Array();

        var creditBadge = ``, hourAndValue = new Array(), vHours = new Array();

        $.each(salesData, function(i, e) {

            if ((storeValues._hi != 1) && (e.clientId == storeValues._cl)) {
                if ((e.branchId == storeValues._clb) && (e.recorded_by == storeValues._ud)) {
                    orders += 1;
                    customerArray.push(e.customer_id);
                    totals += parseFloat(e.order_amount_paid);
                    productsCostPrice += parseFloat(e.total_cost_price);
                    expectedSellingPrice += parseFloat(e.total_expected_selling_price);
                    salesFigures.push(parseFloat(e.order_amount_paid));
                    
                    vHours.push(parseFloat(e.hour_of_day));

                    if(e.credit_sales == 1) {
                        creditSalesArray.push(parseFloat(e.order_amount_paid));
                    } else if(e.credit_sales == 0) {
                        paidSalesArray.push(parseFloat(e.order_amount_paid));
                    }
                }
            } else if((e.clientId == storeValues._cl)) {
                orders += 1;
                if(e.credit_sales == 1) {
                    creditSalesArray.push(parseFloat(e.order_amount_paid));
                } else if(e.credit_sales == 0) {
                    paidSalesArray.push(parseFloat(e.order_amount_paid));
                }
               
                productsCostPrice += parseFloat(e.total_cost_price);
                customerArray.push(e.customer_id);
                totals += parseFloat(e.order_amount_paid);
                salesFigures.push(parseFloat(e.order_amount_paid));
                expectedSellingPrice += parseFloat(e.total_expected_selling_price);

                vHours.push(parseFloat(e.hour_of_day));
            }

            if ((e.credit_sales == 1) && (e.clientId == storeValues._cl)) {
                credits += parseFloat(e.order_amount_paid);
            }

            if (e.payment_type == 'cash') {
                creditBadge = `<span class="text-gray">Cash Sale</span>`;
            } else if (e.payment_type == 'momo') {
                creditBadge = `<span class="text-gray">Mobile Money</span>`;
            } else if (e.payment_type == 'card') {
                creditBadge = `<span class="text-gray">Card Payment</span>`;
            } else if (e.payment_type == 'credit') {
                creditBadge = `<span class="text-gray">Credit</span>`;
            }

            if ((storeValues._hi != 1) && (e.clientId == storeValues._cl)) {
                if (e.recorded_by == storeValues._ud) {
                    salesArray.push({
                        row: orders,
                        order_id: `${e.order_id} <br> ${creditBadge}`,
                        fullname: `<a href="javascript:void(0)" type="button" class="get-sales-details text-success" data-sales-id="${e.order_id}" onclick="return getSalesDetails('${e.order_id}')">${e.customer_fullname}</a>`,
                        phone: e.customer_contact,
                        date: e.order_date,
                        amount: e.order_amount_paid,
                        action: `<a href="javascript:void(0)" type="button" class="get-sales-details text-success" data-sales-id="${e.order_id}" onclick="return getSalesDetails('${e.order_id}')">
                            <i class="fa fa-eye"></i>
                        </a>`
                    });
                }
            } else if((e.clientId == storeValues._cl)) {
                salesArray.push({
                    row: orders,
                    order_id: `${e.order_id} <br> ${creditBadge}`,
                    fullname: `<a href="javascript:void(0)" type="button" class="get-sales-details text-success" data-sales-id="${e.order_id}" onclick="return getSalesDetails('${e.order_id}')">${e.customer_fullname}</a>`,
                    phone: e.customer_contact,
                    date: e.order_date,
                    amount: e.order_amount_paid,
                    action: `<a href="javascript:void(0)" type="button" class="get-sales-details text-success" data-sales-id="${e.order_id}" onclick="return getSalesDetails('${e.order_id}')">
                        <i class="fa fa-eye"></i>
                    </a>`
                });
            }
        });

        var lowestSale = `${storeValues.cur} ${formatCurrency(Math.min(...salesFigures))}`;
        var highestSale = `${storeValues.cur} ${formatCurrency(Math.max(...salesFigures))}`;
        var totalDiscount = `${storeValues.cur} (${formatCurrency(expectedSellingPrice - totals)})`;
        var creditTotal = `${storeValues.cur} ${formatCurrency(credits)}`;
        var salesTotal = `${storeValues.cur} ${formatCurrency(totals)}`;
        var totalCost = `${storeValues.cur} ${formatCurrency(productsCostPrice)}`;
        var totalProfit = `${storeValues.cur} ${formatCurrency(totals-productsCostPrice)}`;
        var creditPercent = `<span class='text-danger'>${storeValues.cur} ${parseFloat((credits/totals)*100).toFixed(2)}% of Total Sales</span>`;
        var average = `${storeValues.cur} ${formatCurrency(totals/orders)}`;

        var hValues = new Array();
        for(var i = 0; i < vHours.length; i++) {
            if(hValues[vHours[i]] !== undefined) {
                hValues[vHours[i]] += parseFloat(salesFigures[i]);
            } else if(typeof vHours[i] !== undefined) {
                hValues[vHours[i]] = parseFloat(salesFigures[i]);
            }
        }

        var hourOfTheDay = {0:"12AM",1:"1AM",2:"2AM",3:"3AM",4:"4AM",5:"5AM",6:"6AM",7:"7AM",8:"8AM",9:"9AM",10:"10AM",11:"11AM",12:"12PM",13:"1PM",14:"2PM",15:"3PM",16:"4PM",17:"5PM",18:"6PM",19:"7PM",20:"8PM",21:"9PM",22:"10PM",23:"11PM"};

        var goodHour = new Array(), goodValues = new Array();
        $.each(hValues, function(i, e) {
            if(e != undefined) {
                goodValues.push(e);
                goodHour.push(hourOfTheDay[i]);
            }
        });

        var data = {
            total: salesTotal,
            credit: creditTotal,
            orders: orders,
            customers: customerArray.unique(),
            creditPercent: creditPercent,
            salesHistory: salesArray,
            averageSale: average,
            profit: totalProfit,
            selling: salesTotal,
            cost: totalCost,
            highest: highestSale,
            lowest: lowestSale,
            analitics: {
                hourValues: goodValues,
                goodHour: goodHour
            }
        };

        return data;
    }

    $(`span[class~="switch-button"]`).on('click', async function(e) {

        let show = $(this).attr('data-show-content');
        let hide = $(this).attr('data-hide-content');

        $(`div[class~="${show}"]`).removeClass('hidden').fadeIn('slow');
        $(`div[class~="${hide}"]`).addClass('hidden').fadeOut('slow');

        await $.ajax({
            url: `${baseUrl}api/branchManagment/saveReportsRecord`,
            data: { saveReportsRecord: true, attendantPerformance: show },
            type: "POST",
            dataType: "JSON",
            success: function(resp) {
            }
        });
    });

    if ($(`div[class~="dashboard-reports"], div[class~="overallSalesHistory"], div[class~="pos-reporting"]`).length) {

        var offline = true;

        async function loadDashboardInformation() {

            await dOC().then((itResp) => {
                if (itResp == 1) {
                    offline = false;
                    $(`div[class="connection"]`).css('display', 'none');
                    $(`div[class~="offline-placeholder"]`).css('display','none');
                } else {
                    offline = true;
                    $(`div[class="connection"]`).css('display', 'block');
                    $(`div[class~="offline-placeholder"]`).css('display','flex');
                    $(`a[class~="shortcut-offline"]`).css({'filter': 'blur(4px)', 'pointer-events': 'none'});
                    $(`li[class~="offline-menu"]`).css({'background-color': '#f6f9fc','filter': 'blur(3px)','pointer-events': 'none'});
                }
            }).catch((err) => {
                offline = true;
                $(`div[class="connection"]`).css('display', 'block');
                $(`div[class~="offline-placeholder"]`).css('display','flex');
            });

            if(offline) {

                $(`select[name="periodSelected"], select[name="periodSelect"]`).prop('disabled', true);
                $(`div[class~="offline-placeholder"] button[type="button"]`).html(`Reconnect`).css({ 'display': 'inline-flex' });
                $(`a[class~="shortcut-offline"]`).css({'filter': 'blur(4px)', 'pointer-events': 'none'});
                $(`li[class~="offline-menu"]`).css({'background-color': '#f6f9fc','filter': 'blur(3px)','pointer-events': 'none'});
                dashboardAnalitics().then(async (dashboardInsights) => {

                    if($(`table[class~="salesLists"]`).length) {
                        
                        $(`table[class~="salesLists"]`).dataTable().fnDestroy();

                        $(`table[class~="salesLists"]`).dataTable({
                            "aaData": dashboardInsights.salesHistory,
                            "iDisplayLength": 10,
                            "columns": [
                                { "data": 'row' },
                                { "data": 'order_id' },
                                { "data": 'fullname' },
                                { "data": 'phone' },
                                { "data": 'date' },
                                { "data": 'amount' },
                                { "data": 'action' }
                            ]
                        });


                    }

                    if ($(`div[class~="dashboard-reports"]`).length) {
                        $(".total-sales").html(dashboardInsights.total);
                        $(".total-sales-trend").html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Total Sales Today</span>`);
                        $(".total-served").html(dashboardInsights.orders);
                        $(".total-served-trend").html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Customers Served</span>`);
                        $(".total-products").html(dashboardInsights.averageSale);
                        $(".total-products-worth").html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Sold today</span>`);
                        $(".total-credit-sales").html(dashboardInsights.credit);
                        $(".total-credit-sales-trend").html(dashboardInsights.creditPercent);

                        $(".total-profit").html(dashboardInsights.profit);
                        $(".total-profit-trend").html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Profits made from sale</span>`);

                        $(".total-selling").html(dashboardInsights.selling);
                        $(".total-selling-trend").html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Revenue less Discount</span>`);

                        $(".total-cost").html(dashboardInsights.cost);
                        $(".total-cost-trend").html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Costs of Items sold</span>`);
                    }

                    if($(`div[class~="pos-reporting"]`).length) {

                        await gIDBR('reports', "branch_performance").then((branchInsight) => {
                            delete branchInsight.reports_id;
                            var branchData = new Array();
                            populateBranchData(Object.values(branchInsight));
                        }).then((resp) => {
                            gIDBR('reports', "sales_attendant_performance").then((attendantInsight) => {
                                populateSalesTeamData(Object.values(attendantInsight.list), Object.values(attendantInsight.names), Object.values(attendantInsight.sales))
                            });
                        }).then((res) => {
                            gIDBR('reports', "products_performance").then((productsInsight) => {
                                delete productsInsight.reports_id;
                                populateProductsPerformance(Object.values(productsInsight));
                            });
                        });

                        $(`p[data-model="highest-sale"] span`).html(dashboardInsights.highest);
                        $(`p[data-model="lowest-sale"] span`).html(dashboardInsights.lowest);
                       
                        $(`div[data-report="total-sales"] h3[class="my-3"]`).html(dashboardInsights.total);
                        $(`div[data-report="total-sales"] p[class~="text-truncate"]`).html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Total Sales Today</span>`);

                        $(`div[data-report="average-sales"] h3[class="my-3"]`).html(dashboardInsights.averageSale);
                        $(`div[data-report="average-sales"] p[class~="text-truncate"]`).html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Average Sales Today</span>`);

                        $(`div[data-report="highest-sales"] h3[class="my-3"]`).html(dashboardInsights.highest);
                        $(`div[data-report="highest-sales"] p[class~="text-truncate"]`).html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> highest Sales Today</span>`);

                        $(`div[data-report="total-orders"] h3[class="my-3"]`).html(dashboardInsights.orders);
                        $(`div[data-report="total-orders"] p[class~="text-truncate"]`).html(`<span class="text-success"><i class="mdi mdi-trending-up"></i> Total Sales Today</span>`);

                        var thisOpts = {
                            chart: {
                                height: 374,
                                type: 'line',
                                shadow: {
                                    enabled: false,
                                    color: '#bbb',
                                    top: 3,
                                    left: 2,
                                    blur: 3,
                                    opacity: 1
                                },
                            },
                            stroke: {
                                width: 5,
                                curve: 'smooth'
                            },
                            series: [{
                                name: 'Total Sales',
                                data: dashboardInsights.analitics.hourValues
                            }],
                            xaxis: {
                                type: 'datetime',
                                categories: dashboardInsights.analitics.goodHour,
                                axisBorder: {
                                    show: true,
                                    color: '#bec7e0',
                                },
                                axisTicks: {
                                    show: true,
                                    color: '#bec7e0',
                                },
                            },
                            fill: {
                                type: 'gradient',
                                gradient: {
                                    shade: 'dark',
                                    gradientToColors: ['#43cea2'],
                                    shadeIntensity: 1,
                                    type: 'horizontal',
                                    opacityFrom: 1,
                                    opacityTo: 1,
                                    stops: [0, 100, 100, 100]
                                },
                            },
                            markers: {
                                size: 4,
                                opacity: 0.9,
                                colors: ["#ffbc00"],
                                strokeColor: "#fff",
                                strokeWidth: 2,
                                style: 'hollow',
                                hover: {
                                    size: 7,
                                }
                            },
                            yaxis: {
                                title: {
                                    text: 'Sales Values',
                                },
                            },
                            tooltip: {
                                shared: true,
                                intersect: false,
                                y: {
                                    formatter: function(y) {
                                        if (typeof y !== "undefined") {
                                            return storeValues.cur + formatCurrency(y);
                                        }
                                        return y;

                                    }
                                }
                            },
                            grid: {
                                row: {
                                    colors: ['transparent', 'transparent'],
                                    opacity: 0.2
                                },
                                borderColor: '#185a9d'
                            },
                            responsive: [{
                                breakpoint: 600,
                                options: {
                                    chart: {
                                        toolbar: {
                                            show: false
                                        }
                                    },
                                    legend: {
                                        show: false
                                    },
                                }
                            }]
                        }

                        delete thisOpts.xaxis.type;
                        
                        var chart = new ApexCharts(
                            document.querySelector("#sales-overview-chart"),
                            thisOpts
                        );

                        chart.render();
                       
                    }
                });
                
                hL();

                return false;
            }

            $(`div[class~="offline-placeholder"]`).css({ 'display': 'none' });
            $(`div[class~="offline-placeholder"] button[type="button"]`).prop('disabled', false);

            if ($(`div[class~="dashboard-reports"], div[class~="overallSalesHistory"]`).length) {
                fetchSalesRecords();
                salesAttendantPerformance(period);

                if($(`div[class~="sales-overview-data"]`).length) {
                    salesOverview('this-week');
                }

            } else if($(`div[class~="pos-reporting"]`).length) {

                var period = $(`select[name="periodSelect"]`).val();
                
                if ($(`div[class~="reports-summary"]`).length) {
                    summaryItems(period);
                    salesOverview(period);
                    salesAttendantPerformance(period);
                    if($(`div[id="dash_spark_1"]`).length) {
                        ordersCount(period);
                    }
                    topContactsPerformance(period);
                    branchPerformance(period);
                }

                $(`select[name="periodSelect"]`).on('change', function() {
                    sL();
                    var periodSelected = $(this).val();
                    summaryItems(periodSelected);
                    salesOverview(periodSelected);
                    if($(`div[id="dash_spark_1"]`).length) {
                        ordersCount(periodSelected);
                    }
                    salesAttendantPerformance(periodSelected);
                    topContactsPerformance(periodSelected);
                    branchPerformance(periodSelected);
                });

            }

            $(`select[name="periodSelected"]`).on('change', function() {
                fetchSalesRecords();
            });

        }

        loadDashboardInformation();
    }

});


if($(`div[class~="request-form"]`).length) {
    
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

            $("tr.products-row .row-subtotal").each(function(){
                let subtotalVal = parseFloat($(this).html());
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
        $(`span[class="sub_total"]`).html(`${storeValues.cur} ${formatCurrency(overallSubTotal)}`);
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
            
            <td class='products-row-number' style='padding-top: 30px;'>
            <td style="padding-top: 30px;">${rowData.productName}</td>
            <td><input type="number" min="1" onkeypress="return isNumber(event)" data-name="${rowData.productName}" form="pos-form-horizontal" name="products[${rowData.productId}][price]" class="form-control product-price" style="width:110px" value="${rowData.productPrice}"></td>
            <td>
            <input type='number' onkeypress="return isNumber(event)" data-name="${rowData.productName}" form="pos-form-horizontal" name="products[${rowData.productId}][qty]" min="1" data-max='${rowData.product_max}' data-row='${rowData.productId}' class='form-control product-quantity' value="${qty}">
            </td>
            <td class='row-subtotal' style="padding-top: 30px;">${subTotal}</td>
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

    var initPrdSelt = () => {
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
                $.post(`${baseUrl}api/pushRequest`, {selectedProducts, customerId, request, discountAmt, discountType}, function(resp) {
                    
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
                                window.location.href = `${baseUrl}export/${resp.result.invoiceNumber}`;
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

}


$(() => {
    
    $(`a[class~="read-instructions"]`).on('click', function() {
        $(`div[class~="instructionsModal"]`).modal('show');
    });

    var foundArrayList = new Array(),
        csvContent = new Array();

    $(`div[class~="upload-buttons"] button[type="cancel"]`).on('click', function(evt) {
        if(confirm("Are you sure you want to cancel?")) {
            Toast.fire({
                type: "success",
                title: "Data upload was successfully cancelled"
            });

            setTimeout(() => {
                window.location.href=`${baseUrl}import`;
            }, 1000);
        }
    });

    $(`div[class~="upload-buttons"] button[type="submit"]`).on('click', function(evt) {
        var btnClick = $(this);
            btnClick.prop({
                'disabled': true, 'title': 'Processing content'
            }).html(`<i class="fa fa-spin fa-spinner"></i> Processing Content`);

        $(`div[class="form-content-loader"]`).css("display","flex");
        $.each($(`div[class~="csv-rows-content"] select`), function(id, key) {
            var selId = $(this).data('id'),
                selName = $(this).attr('data-name');
                csvContent[selName] = new Array();
            $.each($(`div[class~="csv-row-data-${selId}"] p`), function(i, e) {
                var pgText = $(this).text();
                csvContent[selName].push(pgText);
            });
        });

        if(confirm("Do you want to continue data upload?")) {
            $(".main-content-loader.main-body-loader").css({display: "flex"});
            $.ajax({
                type: "POST",
                url: `${baseUrl}api/importManager/uploadCSVData/${currentData}`,
                data: {csvKey: Object.keys(csvContent), csvValues: Object.values(csvContent), uploadCSVData: true},
                dataType: "json",
                success: function(resp) {
                    if(resp.status == "success") {
                        Toast.fire({
                            type: resp.status,
                            title: resp.message
                        });
                        setTimeout(() => {
                            window.location.href = `${baseUrl}${currentData}s`;
                        }, 2000);
                    } else {
                        Toast.fire({
                            type: "error",
                            title: resp.message
                        });
                    }
                }, complete: function(data) {
                    $(".main-content-loader.main-body-loader").css({display: "none"});
                    btnClick.prop({
                        'disabled': false, 'title': ''
                    }).html(`<i class="fa fa-upload"></i> Continue Data Import`);
                }, error: function(err) {
                    Toast.fire({
                        type: "error",
                        title: "Sorry! An error was encountered while processing the request"
                    });
                    btnClick.prop({
                        'disabled': false, 'title': ''
                    }).html(`<i class="fa fa-upload"></i> Continue Data Import`);
                    $(".main-content-loader.main-body-loader").css({display: "none"});
                }
            });
        }

    });

    var fileCheckerText = () => {
        
        var selectValues = new Array(),
            dataCount = 0;
        $.each($(`div[class~="csv-rows-content"] select`), function(i, e) {
            selectValues.push($(this).val());
        });

        $.each(selectValues, function(i, e) {
            if($.inArray(e, acceptedArray) === -1) {
                dataCount++;
            }
        });

        if(dataCount != 0) {
            $(`div[class~="file-checker"]`).css('display', 'block');
            if(dataCount > 1) {
                $(`div[class~="file-checker"]`).html(`<h2>There are <span class="text-danger">${dataCount} columns</span> that are not matched in the uploaded file.</h2>`);
            } else {
                $(`div[class~="file-checker"]`).html(`<h2>There is <span class="text-danger">${dataCount} column</span> not matched in the uploaded file.</h2>`);
            }
            $(`button[class~="upload-button"]`).css("display","none");
            $(`button[class~="cancel-button"]`).css("display","none");
        } else {
            $(`button[class~="upload-button"]`).css("display","inline-block");
            $(`button[class~="cancel-button"]`).css("display","inline-block");
            $(`div[class~="file-checker"]`).html(`<h2>All matched! Ready for us to upload the ${currentData}s information?.</h2>`);
        }

        $(`div[class~="upload-text"]`).removeClass('hidden');
        $(`button[class~="cancel-button"]`).css("display","inline-block");
        $(`div[class="form-content-loader"]`).css("display","none");
        $(`form[class="csvDataImportForm"]`).css("display","none");

    }

    var changeSelectInfo = (id, value) => {
        
        if ($.inArray(value, acceptedArray) !== -1) {
            $(`div[data-row="${id}"] div[class="text-center"]`)
                .html(`<h3 class="text-success"><i class="fa fa-check-circle"></i> Valid Column</h3>`);
                $(`select[name="first_col_${id}"]`).val(value).change();

            foundArrayList.push(value);
        } else {
            $(`div[data-row="${id}"] div[class="text-center"]`)
                .html(`<h3 class="text-danger"><i class="fa fa-exclamation-triangle"></i> Unmatched Column</h3>`);
        }

    }

    var selectChange = () => {

        $(`div[class~="csv-rows-content"] select`).on('change', function(i, e) {

            var thisKey = $(this);
                thisValue = thisKey.val(),
                thisId = thisKey.data('id');

            if(($.inArray(thisValue, foundArrayList) === -1) && ($.inArray(thisValue, acceptedArray) !== -1)) {
                
                $(`div[data-row="${thisId}"] div[class="text-center"]`)
                    .html(`<h3 class="text-success"><i class="fa fa-check-circle"></i> Valid Column</h3>`);
                $(`div[data-row="${thisId}"] select`).attr('data-name', thisValue);
                
                foundArrayList.push(thisValue);

            } else if(($.inArray(thisValue, foundArrayList) !== -1)) {
                var otherKey = $(`select[data-name="${thisValue}"]`),
                    otherId = otherKey.data('id');

                $(`div[data-row="${thisId}"] select`).attr('data-name', thisValue);

                $(`div[data-row="${thisId}"] div[class="text-center"]`)
                    .html(`<h3 class="text-success"><i class="fa fa-check-circle"></i> Valid Column</h3>`);
                $(`div[data-row="${otherId}"] div[class="text-center"]`)
                    .html(`<h3 class="text-danger"><i class="fa fa-exclamation-triangle"></i> Unmatched Column</h3>`);

                otherKey.val('null').change();

            } else if(($.inArray(thisValue, foundArrayList) === -1) && ($.inArray(thisValue, acceptedArray) === -1)) {
                $(`div[data-row="${thisId}"] select`).attr('data-name', 'null');
                $(`div[data-row="${thisId}"] div[class="text-center"]`)
                    .html(`<h3 class="text-danger"><i class="fa fa-exclamation-triangle"></i> Unmatched Column</h3>`);
            }
            
            fileCheckerText();
        });

    }

    var populateSelect = (headerData, mainContent) => {
        
        var htmlData = '',
            selectData = $(`div[class~="csv-rows-content"] div[class="form-row"] select`).html(),
            iv = 0;
            
            $(`div[class~="csv-rows-content"]`).html(``);

        $.each(headerData, async function(i, e) {
            htmlData = `<div class="col-md-6 col-sm-12 col-lg-3" style="min-width:250px" data-row="${iv}">
                <div class="form-row">
                    <div class="text-center" style="width:100%"></div>
                    <select data-name="${e}" data-id="${i}" name="first_col_${iv}" id="first_col_${iv}" class="form-control selectpicker">
                        ${selectData}
                    </select>
                    <div style="width:100%; background:#fff; border-radius:5px; padding-top:10px; margin-top: 5px" class="csv-row-data-${iv} mb-3"></div>
                </div>
            </div>`;
            $(`div[class~="csv-rows-content"]`).append(htmlData);
            iv++;
            
            await changeSelectInfo(i, e);
        });

        var ii = 0;
        $.each(mainContent, function(i, e) {
            var thisData;
            $.each(e, function(iv, v){
                $(`div[class~="csv-row-data-${iv}"]`).append(`<p style="padding-left: 5px" data-row-id="${ii}" data-column-id="${iv}" class="border-bottom pb-2">${e[iv]}</p>`);
            });
            ii++;
        });

        selectChange();
        fileCheckerText();
        $(`input[name="csv_file"]`).val(``);
        $(`select[class~="selectpicker"]`).select2({width: "100%"});
    }

    $(`input[id="csv_file"]`).change(function(){
        var fd = new FormData();
        var files = $('input[id="csv_file"]')[0].files[0];
        fd.append('csv_file', files);
        csvDataImportForm(fd);
    });

    function csvDataImportForm(formdata) {

        $(`div[class="form-content-loader"]`).css("display","flex");

        $.ajax({
            type: 'POST',
            url: `${baseUrl}api/importManager/loadCSV`,
            data: formdata,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData:false,
            success: function(resp){
                populateSelect(resp.column, resp.csvData);
                $(`div[class~="csv-rows-counter"]`).html(`A total of <strong>${resp.data_count} items</strong> will be imported.`);
            }, error: function(err) {
                Toast.fire({
                    type: "error",
                    title: "Sorry! An unknown file type was uploaded."
                });
                $(`input[name="csv_file"]`).val(``);
                $(`div[class="form-content-loader"]`).css("display","none");
            }
        });
    }

});

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
        url: baseUrl + "api/inventoryManagement",
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
                url: baseUrl + "api/inventoryManagement/submitTransferProduct",
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
        url: baseUrl + "api/inventoryManagement/getAllProducts",
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
            url: `${baseUrl}api/inventoryManagement/updateWareHouseStock`,
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
                    <div class="input-group-prepend"><span class="input-group-text">${storeValues.cur}</span></div>
                    <input type="number" step="0.1" value="0.00" class="form-control" name="cost_${lastRowId}">
                </div>
            </div>
            <div class="col-md-2 mb-3">
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">${storeValues.cur}</span></div>
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
                url: baseUrl + "api/inventoryManagement/bulkTransferProducts",
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
if($(`div[id="payment_options"]`).length) {
    function loadPaymentOptions() {       
      $.ajax({
        url: `${baseUrl}api/branchManagment/loadPaymentOptions`,
        data: { loadPaymentOptions: true },
        type: "POST",
        dataType: "JSON",
        success: function(resp) {
          if(resp.status == 200) {
            var paymentOptions = resp.message;
            $.each($(`div[id="payment_options"] div[class="col-lg-4"] input`), function(i, e) {
              if(($.inArray($(this).attr(`data-module`), paymentOptions) !== -1) && ($(this).attr('data-value') == 'checked')) {
                $(this).attr('checked', true);
                $(this).parent('label').addClass('active');
              } else if(($.inArray($(this).attr(`data-module`), paymentOptions) === -1) && ($(this).attr('data-value') == 'unchecked')) {
                $(this).attr('checked', true);
                $(this).parent('label').addClass('active');
              }
            });
          } else {
            $.each($(`div[id="payment_options"] div[class="col-lg-4"] input`), function(i, e) {
              if(($.inArray($(this).attr(`data-module`), paymentOptions) === -1) && ($(this).attr('data-value') == 'unchecked')) {
                $(this).attr('checked', true);
                $(this).parent('label').addClass('active');
              }
            });
          }
        }, complete: function(data) {
          $(`div[class="form-content-loader"]`).css("display","none");
        }, error: function(err) {
          $(`div[class="form-content-loader"]`).css("display","none");
        }
      })
    }
    loadPaymentOptions();
}