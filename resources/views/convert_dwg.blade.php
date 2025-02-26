<!DOCTYPE html>
<html>
<head>
    <title>Image Canvas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        canvas {
            border: 1px solid #000;
        }
    </style>
</head>
<body>
    <input type="file" id="dwg" name="dwg">
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        
        $('#dwg').change(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var fileInput = document.getElementById('dwg');
            var file = fileInput.files[0];
            
            if (file) {
                var formData = new FormData();
                formData.append('dwg', file);

                $.ajax({
                    url: "{{ route('convert.dwg.save') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('File uploaded successfully');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error uploading file:', error);
                    }
                });
            } else {
                console.error('No file selected');
            }
        });
    });

</script>
</html>