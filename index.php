<!DOCTYPE html>
<html>
<head>
    <title>FFmpeg File Upload & Convert</title>
</head>
<body>
    <h2>Upload an MP4 file to convert to 480p</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" name="video" accept="video/mp4" required><br><br>
        <input type="submit" name="submit" value="Upload & Convert">
    </form>

<?php
if (isset($_POST['submit'])) {
    if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/uploads/';
        $outputDir = __DIR__ . '/output/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        if (!is_dir($outputDir)) mkdir($outputDir, 0777, true);

        $tmpFile = $_FILES['video']['tmp_name'];
        $originalName = basename($_FILES['video']['name']);
        $targetFile = $uploadDir . $originalName;
        move_uploaded_file($tmpFile, $targetFile);

        $outputFile = $outputDir . 'converted_' . $originalName;
        $command = "ffmpeg -i " . escapeshellarg($targetFile) . " -vf scale=-1:480 -c:a copy " . escapeshellarg($outputFile);

        echo "<p>Processing video with FFmpeg...</p>";
        exec($command, $output, $returnCode);

        if ($returnCode === 0 && file_exists($outputFile)) {
            echo "<p>Conversion successful! <a href='output/" . basename($outputFile) . "' download>Download converted video</a></p>";
        } else {
            echo "<p style='color:red;'>FFmpeg failed to convert the video.</p>";
        }
    } else {
        echo "<p style='color:red;'>File upload error.</p>";
    }
}
?>
</body>
</html>
