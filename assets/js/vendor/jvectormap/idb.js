const iName = 'argonPOS-db';
const iVer = 1;

function cOS() {
    var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
    var open = indexedDB.open(iName, iVer);
    open.onupgradeneeded = function(evt) {
        var obST = {
            users: 'user_id',
            request_products: 'product_id',
            branches: 'branch_id',
            quotes: 'request_id',
            orders: 'request_id',
            customers: 'customer_id',
            requests: 'request_id',
            payment_types: 'payment_type_id',
            sales: 'order_id',
            payment_types: 'id',
            settings: 'this_key',
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


function updateUsersRecordIndexDb(obDet) {
    return new Promise((resolve, reject) => {
        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB
        var open = indexedDB.open(iName, iVer);
        open.onsuccess = function() {
            var db = open.result;
            var tx = db.transaction('users', 'readwrite');
            var store = tx.objectStore('users');
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

function deleteUserFromIndexDb(uniqueId) {
    return new Promise((resolve, reject) => {
        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB
        var open = indexedDB.open(iName, iVer);
        open.onsuccess = function() {
            var db = open.result;
            var tx = db.transaction('users', 'readwrite');
            var store = tx.objectStore('users');
            var req;
            try {
                var newInfo = { user_id: uniqueId, deleted: 1 };
                req = store.put(newInfo);
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

        var userId = companyVariables._ud;
        var userName = companyVariables._un;
        var branchId = companyVariables._clb;
        var clientId = companyVariables._cl;

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
            order_status: 'confirmed',
            payment_type: paymentType,
            transaction_id: transactionId
        }];
        
        var recalculatedValue = parseFloat(calNewT).toFixed(2);
        
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