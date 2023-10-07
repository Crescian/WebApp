<script>
    function loadJobPosition(employee, posObject) {
        if (employee == '') {
            $('#' + posObject).html('');
        } else {
            $.ajax({
                url: '../functions/common_functions.php',
                type: 'POST',
                dataType: 'JSON',
                cache: false,
                data: {
                    action: 'load_job_pos_name_employee',
                    employee: employee
                },
                success: function(result) {
                    $('#' + posObject).html(result.pos_name);
                }
            });
        }
    }

    function loadSelectValue(inTable, inField, inObject, connection) {
        $.ajax({
            url: '../functions/common_functions.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_select_values',
                inTable: inTable,
                inField: inField,
                connection: connection
            },
            success: function(result) {
                console.log(result);
                $.each(result, (key, value) => {
                    var optionExists = ($(`#` + inObject + ` option[value="${value}"]`).length > 0);
                    if (!optionExists) {
                        $('#' + inObject).append(`<option value="${value}">${value}</option>`);
                    }
                });
            }
        });
    }

    function loadSelectValueWithId(inTable, inFieldId, inField, inObject, connection) {
        $.ajax({
            url: '../functions/common_functions.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_select_values_with_id',
                inTable: inTable,
                inField: inField,
                inFieldId: inFieldId,
                connection: connection
            },
            success: function(result) {
                $.each(result, (key, value) => {
                    var optionExists = ($(`#` + inObject + ` option[value="${key}"]`).length > 0);
                    if (!optionExists) {
                        $('#' + inObject).append(`<option value="${key}">${value}</option>`);
                    }
                });
            }
        });
    }

    function loadJoNumberDescription(jonumber, inObject, inObjectOrderid) {
        $.ajax({
            url: '../functions/common_functions.php',
            type: 'POST',
            dataType: 'JSON',
            data: {
                action: 'load_jonumber_description',
                jonumber: jonumber
            },
            success: function(result) {
                $('#' + inObject).val(result.descriptions);
                $('#' + inObjectOrderid).val(result.orderid);
            }
        });
    }

    function loadSelectValues(inObject, result) {
        $("#" + inObject).empty();
        setTimeout(function() {
            optionText = "Choose...";
            optionValue = "";
            let optionExists = ($(`#` + inObject + ` option[value="${optionValue}"]`).length > 0);
            if (!optionExists) {
                $('#' + inObject).append(`<option value="${optionValue}"> ${optionText}</option>`);
            }
            if (result != '') {
                $.each(result, (key, value) => {
                    var optionExists = ($(`#` + inObject + ` option[value="${key}"]`).length > 0);
                    if (!optionExists) {
                        $('#' + inObject).append(`<option value="${key}">${value}</option>`);
                    }
                });
            }
        }, 100);
    }
</script>