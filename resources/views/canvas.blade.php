<!DOCTYPE html>
<html>
<head>
    <title>Image Canvas</title>
    <style>
        canvas {
            border: 1px solid #000;
        }
    </style>
</head>
<body>
    <canvas id="imageCanvas" width="400" height="300"></canvas>
        <a href="{{ route('') }}"></a>
    <script>
        window.onload = function() {
            // Get the canvas element
            var canvas = document.getElementById('imageCanvas');
            var context = canvas.getContext('2d');

            // Load the image
            var image = new Image();
            image.onload = function() {
                // Draw the image on the canvas
                context.drawImage(image, 0, 0, canvas.width, canvas.height);
            };
            image.src = '{{ asset("sample.jpg") }}'; // Replace with the path to your image
        };
    </script>
</body>
</html>