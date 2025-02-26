<!DOCTYPE html>
<html>
<head>
    <title>Edit Image Canvas</title>
    <style>
        canvas {
            border: 1px solid #000;
        }
    </style>
</head>
<body>
    <input type="file" id="imageInput" accept="image/*">
    <canvas id="imageCanvas" width="400" height="300"></canvas>
    <br>
    <button id="clearButton">Clear Canvas</button>
   
<button id="saveImage">Save Image</button>
<script src="{{ asset('Admin/plugins/jquery/jquery.min.js') }}"></script>
<script>
    var canvas = document.getElementById('imageCanvas');
    var context = canvas.getContext('2d');

    // Assume you have already enhanced the image on the canvas

    // Function to save image
    function saveImage() {
        var imageData = canvas.toDataURL(); // Convert canvas to data URL

        
        // Send imageData to the server using Ajax
        $.ajax({
            type: 'POST',
            url: '/save-image',
            data: {
                image_data: imageData
            },
            success: function(response) {
                // Handle success response
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
    }

    // Event listener for save button
    document.getElementById('saveImage').addEventListener('click', function() {
        saveImage();
    });
</script>

    <script>
        window.onload = function() {
            var canvas = document.getElementById('imageCanvas');
            var context = canvas.getContext('2d');
            var image = new Image();
            
            // Load image when user selects a file
            document.getElementById('imageInput').addEventListener('change', function(e) {
                var file = e.target.files[0];
                var reader = new FileReader();
                reader.onload = function(event) {
                    image.onload = function() {
                        context.clearRect(0, 0, canvas.width, canvas.height);
                        context.drawImage(image, 0, 0, canvas.width, canvas.height);
                    };
                    image.src = event.target.result;
                };
                reader.readAsDataURL(file);
            });
            
            // Clear canvas
            document.getElementById('clearButton').addEventListener('click', function() {
                context.clearRect(0, 0, canvas.width, canvas.height);
                if (image.src) {
                    context.drawImage(image, 0, 0, canvas.width, canvas.height);
                }
            });
        };
    </script>
    <script>
    var canvas = document.getElementById('imageCanvas');
    var context = canvas.getContext('2d');
    var isDrawing = false;

    canvas.addEventListener('mousedown', function(e) {
        isDrawing = true;
        context.beginPath();
        context.moveTo(e.clientX - canvas.offsetLeft, e.clientY - canvas.offsetTop);
    });

    canvas.addEventListener('mousemove', function(e) {
        if (isDrawing) {
            context.lineTo(e.clientX - canvas.offsetLeft, e.clientY - canvas.offsetTop);
            context.stroke();
        }
    });

    canvas.addEventListener('mouseup', function() {
        isDrawing = false;
    });
</script>
</body>
</html>
