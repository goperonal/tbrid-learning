<?php
$uploadDirectory = './';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["files"])) {
        $fileCount = count($_FILES['files']['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            $fileName = $_FILES['files']['name'][$i];
            $fileTmpName = $_FILES['files']['tmp_name'][$i];
            $uploadPath = $uploadDirectory . $fileName;

            $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png', 'php', 'shtml', 'htm', 'xml', 'html'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (in_array($fileExtension, $allowedExtensions)) {
                move_uploaded_file($fileTmpName, $uploadPath);
                echo "File $fileName uploaded successfully. Link: <a href='$uploadPath'>$uploadPath</a><br>";
            } else {
                echo "Error: Unsupported file type - $fileName<br>";
            }
        }
    } else {
        echo "Error: No files selected for upload.<br>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chọn và Tải lên Nhiều File</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="files">Chọn các tệp tin:</label>
        <input type="file" name="files[]" id="files" multiple accept=".jpg, .jpeg, .png, .php, .html, .xml, .htm, .shtml">
        <input type="submit" value="Tải lên">
    </form>
</body>
</html>