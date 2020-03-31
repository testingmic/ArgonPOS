const DB_NAME = 'einventory-db';
const DB_VERSION = 1;

function createObjectStores() {

    var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB;
    var open = indexedDB.open(DB_NAME, DB_VERSION);

    open.onupgradeneeded = function(evt) {
        var objectStores = {
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

        $.each(objectStores, function(storeName, storeUniqueId) {
            if(storeName == "reports") {
                store = evt.currentTarget.result.createObjectStore(
                    storeName, { keyPath: storeUniqueId, autoIncrement: true });
            } else {
                store = evt.currentTarget.result.createObjectStore(
                    storeName, { keyPath: storeUniqueId, autoIncrement: false });

                store.createIndex(storeUniqueId, storeUniqueId, { unique: true });
            }
        });

    };

    open.onsuccess = function() {
        console.log('Object Stores successfully created');
    }
}

createObjectStores();


function listIndexDBRecords(storeName) {

    return new Promise((resolve, reject) => {

        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB

        var open = indexedDB.open(DB_NAME, DB_VERSION);
        open.onsuccess = function() {
            var db = open.result;
            var tx = db.transaction(storeName, 'readwrite');
            var store = tx.objectStore(storeName);

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

function updateIndexDBData(storeName, objectDetails) {

    return new Promise((resolve, reject) => {

        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB

        var open = indexedDB.open(DB_NAME, DB_VERSION);

        open.onsuccess = function() {
            var db = open.result;
            var tx = db.transaction(storeName, 'readwrite');
            var store = tx.objectStore(storeName);

            var req;

            try {
                $.each(objectDetails, function(ie, value) {
                    req = store.put(value);
                });
            } catch (e) {
                if (e.name == 'DataCloneError') {
                    console.log("This engine doesn't know how to clone a Blob, use Firefox");
                }
            }
            req.onsuccess = function(evt) {
                resolve(200);
            };
            req.onerror = function() {

            };
        };

    });

}

function addDataToIndexDB(storeName, objectDetails) {

    return new Promise((resolve, reject) => {

        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB

        var open = indexedDB.open(DB_NAME, DB_VERSION);

        open.onsuccess = function() {
            var db = open.result;
            var tx = db.transaction(storeName, 'readwrite');
            var store = tx.objectStore(storeName);

            var req;

            try {
                $.each(objectDetails, function(ie, value) {
                    req = store.add(value);
                });
            } catch (e) {
                if (e.name == 'DataCloneError') {
                    console.log("This engine doesn't know how to clone a Blob, use Firefox");
                }
            }
            req.onsuccess = function(evt) {
                resolve(200);
            };
            req.onerror = function() {

            };
        };

    });

}

function clearDBStore(storeName) {

    return new Promise((resolve, reject) => {

        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB

        var open = indexedDB.open(DB_NAME, DB_VERSION);

        open.onsuccess = function() {
            var db = open.result;
            var tx = db.transaction(storeName, 'readwrite');
            var store = tx.objectStore(storeName);

            var req;

            try {
                req = store.clear();
            } catch (e) {
                if (e.name == 'DataCloneError') {
                    console.log("This engine doesn't know how to clone a Blob, use Firefox");
                }
                throw e;
            }

            req.onsuccess = function(evt) {
                resolve(200);
            };
            req.onerror = function() {
                console.error("clearUsers error", this.error);
            };
        };

    });

}


function updateUsersRecordIndexDb(objectDetails) {

    return new Promise((resolve, reject) => {

        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB

        var open = indexedDB.open(DB_NAME, DB_VERSION);

        open.onsuccess = function() {
            var db = open.result;
            var tx = db.transaction('users', 'readwrite');
            var store = tx.objectStore('users');

            var req;

            try {
                $.each(objectDetails, function(ie, value) {
                    req = store.put(value);
                });
            } catch (e) {
                if (e.name == 'DataCloneError') {
                    console.log("This engine doesn't know how to clone a Blob, use Firefox");
                }
            }
            req.onsuccess = function(evt) {
                resolve(200);
            };
            req.onerror = function() {

            };
        };

    });

}

function deleteUserFromIndexDb(uniqueId) {

    return new Promise((resolve, reject) => {

        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB

        var open = indexedDB.open(DB_NAME, DB_VERSION);

        open.onsuccess = function() {
            var db = open.result;
            var tx = db.transaction('users', 'readwrite');
            var store = tx.objectStore('users');

            var req;

            try {
                var newInfo = { user_id: uniqueId, deleted: 1 };
                req = store.put(newInfo);
            } catch (e) {
                if (e.name == 'DataCloneError') {
                    console.log("This engine doesn't know how to clone a Blob, use Firefox");
                }
                throw e;
            }

            req.onsuccess = function(evt) {
                resolve(200);
            };
            req.onerror = function() {
                console.error("deleteUser error", this.error);
            };
        };

    });

}

function saveRegisterIntoIndexDB() {

    return new Promise((resolve, reject) => {

        var response = new Array();
        var status = 'error', discountAmount = 0,
            result, amountPaid, credit = 0,
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
            discountAmount = parseFloat($(`input[name="discount_amount"]`).val());
        } else {
            discountAmount = 0;
        }

        if (paymentType == "credit") {
            credit = 1;
            amountPaid = 0;
            amountBalance = totalToPay;
        } else {
            amountPaid = paidAmount;
            amountBalance = (paidAmount - totalToPay);
        }

        if(discountType == "cash") {
            totalToPay = totalToPay - discountAmount;
        } else {
            discountAmount = parseFloat((discountAmount/100)*totalToPay).toFixed(2);
            totalToPay = (totalToPay - discountAmount);
        }

        var userId = companyVariables._ud;
        var userName = companyVariables._un;
        var branchId = companyVariables._clb;
        var clientId = companyVariables._cl;

        var registerProductsList = [];
        var calculateNewTotal = 0;

        $.each($(`tbody[class="products-table-body"] tr[class="products-row"] input`), function(i, e) {
            var attrk = $(this).attr('data-row');
            var quantity = $(this).val();
            var product_title = $(this).attr('data-name');
            var unit_price = $(`input[name="products[${attrk}][price]"]`).val();

            if (typeof attrk !== typeof undefined && attrk !== false) {

                var eachTotal = (quantity * unit_price);
                var autoId = `${randomString(20)}`;

                registerProductsList.push({
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
                calculateNewTotal += eachTotal;
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
            saleItems: registerProductsList,
            ordered_by_id: customerId,
            recorded_by: userId,
            sale_team_name: userName,
            credit_sales: credit,
            order_amount_paid: paidAmount,
            overall_order_amount: (amountToBePaid + discountAmount),
            order_amount_balance: amountBalance,
            order_discount: discountAmount,
            order_date: log_date,
            order_status: 'confirmed',
            payment_type: paymentType,
            transaction_id: transactionId
        }];
        
        var recalculatedValue = parseFloat(calculateNewTotal).toFixed(2);
        
        if (registerProductsList.length < 1) {
            result = 'Sorry! You have not selected any products.';
        } else {
           
            var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB

            var open = indexedDB.open(DB_NAME, DB_VERSION);

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
                    if (e.name == 'DataCloneError') {
                        console.log("This engine doesn't know how to clone a Blob, use Firefox");
                    }
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

function getIndexRecord(storeName, recordId) {
    return new Promise((resolve, reject) => {
        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB
        var open = indexedDB.open(DB_NAME, DB_VERSION);
        open.onsuccess = function() {
            var db = open.result;
            var tx = db.transaction(storeName, 'readwrite');
            var store = tx.objectStore(storeName);
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

function del(storeName, recordId) {
    return new Promise((resolve, reject) => {
        var indexedDB = window.indexedDB || window.mozIndexedDB || window.webkitIndexedDB || window.msIndexedDB
        var open = indexedDB.open(DB_NAME, DB_VERSION);
        open.onsuccess = function() {
            var db = open.result;
            var tx = db.transaction(storeName, 'readwrite');
            var store = tx.objectStore(storeName);
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

async function delPrevs(storeName) {
    await listIndexDBRecords(storeName).then((result) => {
        var curDate = jsDate('fulldate');
        $.each(result, function(i, e) {
            if (e.today_date != curDate) {
                del(storeName, e.order_id).then((res) => {});
            }
        });
    });
}