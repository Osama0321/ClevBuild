<!-- resources/views/upload.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload DWG File</title>
</head>
<body>
    <form action="{{ route('forge.upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" accept=".dwg">
        <button type="submit">Upload</button>
    </form>
</body>
</html>
