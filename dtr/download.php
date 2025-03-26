<?php
// Ensure the file parameter is set
if (isset($_GET['file'])) {
    $filename = basename($_GET['file']);
    $filePath = "../export/" . $filename;
ss
    // Check if the file exists
    if (file_exists($filePath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        echo "Exported file not found.";
    }
} else {
    echo "Invalid request.";
}
