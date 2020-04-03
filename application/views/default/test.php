<?php
// set a common variable
$baseUrl = $config->base_url();
$status = 500;

// accepted column names
$acpCols = [
    "customer_id"=>"Customer Code", "title"=>"Title","firstname"=>"Firstname", 
    "lastname"=>"Lastname", "phone_1"=>"Contact Number", "email"=>"Email Address", 
    "date_of_birth"=>"Date of Birth", "residence"=>"Residence", "city"=>"City"
];

// if there is any file
if(isset($_FILES['csv_file']) and !empty($_FILES['csv_file'])) {
    
    // set the response header
    header("Content-Type: application/json");

    // reading tmp_file name
    $fileData = fopen($_FILES['csv_file']['tmp_name'], 'r');

    // get the content of the file
    $column = fgetcsv($fileData);
    $csvData = array();
    $error = false;

    $i = 0;
    //using while loop to get the information
    while($row = fgetcsv($fileData)) {
        $i++;
        // push the data parsed by the user to the page
        $csvData[] = $row;
    }

    // set the data to send finally
    $output = array(
        'column'        => $column,
        'csvData'       =>  $csvData
    );

    print json_encode($output);
    exit;
}

// form content has been submitted
if(isset($_POST["loadData"], $_POST["csvKey"], $_POST["csvValues"]) && confirm_url_id(1, "csvData")) {
    
    // set the response header
    header("Content-Type: application/json");

    // begin processing
    $data = ($_POST);

    // begin the processing of the array data
    $sqlQuery = "INSERT INTO customers (`clientId`,`branchId`,`owner_id`,";
    
    // confirm that the keys are not empty
    if(!empty($_POST["csvKey"])) {
        // not found
        $notFound = 0;
        // check if the keys are all valid
        foreach($_POST["csvKey"] as $thisKey) {
            if(!in_array(strtolower($thisKey), array_keys($acpCols))) {
                $notFound++;
            }
        }

        // break the code if an error was found
        if($notFound) {
            $data = 'Invalid column parsed.';
        } else {
            // start at zero
            $i = 0;
            // continue processing the request
            foreach($_POST["csvKey"] as $thisKey) {
                // increment
                $i++;
                // append to the sql query
                $sqlQuery .= "`".xss_clean($thisKey)."`";
                // append a comma if the loop hasn't ended yet
                if($i < count($_POST["csvKey"])) $sqlQuery .= ",";
            }
            // append the last bracket
            $sqlQuery .= ") VALUES";

            $newCSVArray = [];
            // set the values
            if(!empty($_POST["csvValues"])) {
                // begin
                $iv = 0;
                // loop through the values list
                foreach($_POST["csvValues"] as $key => $eachCsvValue) {
                    // print each csv value
                    foreach($eachCsvValue as $eKey => $eValue) {
                        $newCSVArray[$eKey][] = $eachCsvValue[$eKey];
                    }
                }
                // set the status to true
                $status = 200;
            }

            // loop through each array dataset
            foreach($newCSVArray as $eachData) {
                // initializing
                $sqlQuery .= "('{$session->clientId}','{$session->branchId}','{$session->userId}',";
                $ik = 0;
                // loop through each data
                foreach($eachData as $eachKey => $eachValue) {
                    $ik++;
                    // create sql string for the values
                    $sqlQuery .= "'".xss_clean($eachValue)."'";

                    if($ik < count($_POST["csvKey"])) $sqlQuery .= ",";
                }
                // end
                $sqlQuery .= "),";
            }

            $data = substr($sqlQuery, 0, -1) . ';';
        }
    }

    // set the response value
    $response = [
        'status' => $status,
        'result' => $data
    ];

    // print the response value
    print json_encode($response);
    exit;
}

function form_loader() {
  return '<div class="form-content-loader" style="display: none; min-height: 200px">
        <div class="offline-content text-center">
            <p><i class="fa fa-spin fa-spinner fa-3x"></i></p>
        </div>
    </div>';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Import CSV in PHP </title>
    <link rel="stylesheet" href="<?= $baseUrl ?>assets/css/argon.min9f1e.css?v=1.1.0" type="text/css">
    <link rel="stylesheet" href="<?= $baseUrl ?>assets/vendor/%40fortawesome/fontawesome-free/css/all.min.css" type="text/css">
    <link rel="stylesheet" href="<?= $baseUrl ?>assets/css/custom.css" type="text/css">
</head>
<body>
    <div class="container">
        <form method="post" action="<?= $config->base_url('test'); ?>" class="csvDataImportForm" enctype="multipart/form-data">
            <div class="row mt-5">
                <div class="col-md-6 m-auto border">
                    <?= form_loader(); ?>
                    <label> Import Data </label>
                    <div class="form-group">                    
                        <input type="file" name="csv_file" class="form-control cursor">
                    </div>
                    <input type="text" name="name" id="name" class="form-control">
                    <div class="form-group text-center mt-4">
                        <button type="submit" name="import" class="btn btn-success"> Import Data </button>
                    </div>
                </div>
            </div>
        </form>
        <div class="row mt-4 text-center">
            <div class="col-lg-12 file-checker"></div>
        </div>
        <div class="mt-4 csv-rows-content" style="overflow-x: auto; display: flex; flex: 1; flex-direction: row;">
            <div class="col-md-4" style="display: none;" data-row="1">
                <div class="form-row">
                    <select name="first_col_1" id="first_col_1" class="form-control">
                        <option value="null">Please Select</option>
                        <?php foreach($acpCols as $key=> $opts) { ?>
                            <?= "<option value=\"{$key}\">".ucfirst($opts)."</option>" ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row mt-4 mb-6">
            <div class="col-lg-12 text-center upload-button" style="display: none;">
               <button type="submit" style="margin-top: 50px;" class="btn btn-outline-success">
                    <i class="fa fa-upload"></i> Upload CSV Data
                </button>
            </div>
        </div>
    </div>
    <script src="<?= $baseUrl ?>assets/vendor/jquery/dist/jquery.min.js"></script>
    <script>
        
        var acceptedArray = <?= json_encode(array_keys($acpCols)); ?>,
            foundArrayList = new Array(),
            csvContent = new Array();
        $(`div[class~="upload-button"] button[type="submit"]`).on('click', function(evt) {
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

            $.ajax({
                type: "POST",
                url: `<?= $config->base_url('test/csvData'); ?>`,
                data: {csvKey: Object.keys(csvContent), csvValues: Object.values(csvContent), loadData: true},
                dataType: "json",
                success: function(resp) {
                    console.log(resp);
                }, complete: function(data) {
                    btnClick.prop({
                        'disabled': false, 'title': ''
                    }).html(`<i class="fa fa-upload"></i> Upload CSV Data`);
                }, error: function(err) {
                    btnClick.prop({
                        'disabled': false, 'title': ''
                    }).html(`<i class="fa fa-upload"></i> Upload CSV Data`);
                }
            });

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

            // dataCount = acceptedArray.length - dataCount;

            if(dataCount != 0) {
                $(`div[class~="file-checker"]`).css('display', 'block');
                if(dataCount > 1) {
                    $(`div[class~="file-checker"]`).html(`<h1>There are <span>${dataCount}</span> columns that are not matched in the uploaded file.</h1>`);
                } else {
                    $(`div[class~="file-checker"]`).html(`<h1>There is <span>${dataCount}</span> column not matched in the uploaded file.</h1>`);
                }
                $(`div[class~="upload-button"]`).css("display","none");
            } else {
                $(`div[class~="upload-button"]`).css("display","block");
                $(`div[class~="file-checker"]`).html(`<h1>All matched! Ready for us to check the customer information?.</h1>`);
            }

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
                htmlData = `<div class="col-md-3" data-row="${iv}">
                    <div class="form-row">
                        <div class="text-center" style="width:100%"></div>
                        <select data-name="${e}" data-id="${i}" name="first_col_${iv}" id="first_col_${iv}" class="form-control">
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

        }

        $(`form[class="csvDataImportForm"]`).on('submit', function(e) {
            
            e.preventDefault();
            
            $(`div[class="form-content-loader"]`).css("display","flex");

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData:false,
                success: function(resp){
                    populateSelect(resp.column, resp.csvData);
                }, error: function(err) {
                    $(`div[class="form-content-loader"]`).css("display","none");
                }
            });

        });        
    </script>
</body>
</html>