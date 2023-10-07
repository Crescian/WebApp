<script>
    function scanQrCode(count, location_name) {
        $('#qr_scannerModal').modal('show');
        let selectedDeviceId;
        const codeReader = new ZXing.BrowserMultiFormatReader();
        console.log('ZXing code reader initialized');

        codeReader.listVideoInputDevices().then((videoInputDevices) => {
            const sourceSelect = document.getElementById('sourceSelect');
            selectedDeviceId = videoInputDevices[0].deviceId;
            if (videoInputDevices.length >= 1) {
                videoInputDevices.forEach((element) => {
                    var optionExists = ($(`#sourceSelect option[value="${element.deviceId}"]`).length > 0);
                    if (!optionExists) {
                        $('#sourceSelect').append(`<option value="${element.deviceId}">${element.label}</option>`);
                    }
                });
                const sourceSelectPanel = document.getElementById('sourceSelectPanel');
                sourceSelectPanel.style.display = 'block';
            }
            console.log(`Started continous decode from camera with id ${selectedDeviceId}`);
        }).catch((err) => {
            console.error(err);
        });
        // onload event
        codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', handleDecodeResult);
        // onchange event
        sourceSelect.onchange = () => {
            selectedDeviceId = sourceSelect.value;
            codeReader.decodeFromVideoDevice(selectedDeviceId, 'video', handleDecodeResult);
        };

        function handleDecodeResult(result, err) {
            if (result) {
                console.log(result);
                if (result.text == location_name) {
                    validationQrScanner(count);
                    $('#qr_scannerModal').modal('hide');
                    codeReader.reset();
                    Swal.fire({
                        position: 'top',
                        icon: 'success',
                        title: 'QR Code Valid',
                        text: '',
                        showConfirmButton: false,
                        timer: 1000
                    });
                } else {
                    Swal.fire({
                        position: 'top',
                        icon: 'error',
                        title: 'Invalid QR Code',
                        text: '',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            }
            if (err && !(err instanceof ZXing.NotFoundException)) {
                console.error(err);
            }
            // validationQrScanner(count);
        }
        document.getElementById('closeModal').addEventListener("click", function() {
            codeReader.reset();
        });
    }
</script>