<?php
function upload_image($fieldname='image'){
    if (empty($_FILES[$fieldname]['name'])) return '';
    $targetDir = 'public/uploads/';
    if (!is_dir($targetDir)) mkdir($targetDir,0755,true);
    $filename = time() . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', basename($_FILES[$fieldname]['name']));
    $targetFile = $targetDir . $filename;
    if (move_uploaded_file($_FILES[$fieldname]['tmp_name'], $targetFile)) {
        return $targetFile;
    }
    return '';
}
?>
