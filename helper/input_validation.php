<script>
    function inputValidation(...args) {
        let isValidated = true;
        $.each(args, function(i, e) {
            let element = $(`#${e}`);
            if (element.val().trim() == '') {
                invalidField(e, 'Field is required.');
                isValidated = false;
            } else {
                validField(e);
            }
        });
        return isValidated;
    }

    function invalidField(field, msg) {
        $('#' + field).addClass('is-invalid').removeClass('is-valid');
        $('#' + field).next().html(msg);
    }

    function validField(field) {
        $('#' + field).addClass('is-valid').removeClass('is-invalid');
        $('#' + field).next().html();
    }
</script>