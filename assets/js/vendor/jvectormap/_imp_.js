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
                url: `${baseUrl}aj/importManager/uploadCSVData/${currentData}`,
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

        // dataCount = acceptedArray.length - dataCount;

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

    // file selected
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
            url: `${baseUrl}aj/importManager/loadCSV`,
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