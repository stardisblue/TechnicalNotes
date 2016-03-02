<?php

namespace techweb\lib\core\security;

use techweb\core\exception\IOException;
use techweb\core\exception\UploadException;
use techweb\core\exception\FileTypeException;

class File
{

    public static function checkSum($filePath)
    {
        return file_exists($filePath) ? hash('sha1', file_get_contents($filePath)) : null;
    }

    public static function moveUploadedFile(string $fileName, string $uploadPath, array $extensions = [], array $mimeTypes = [])
    {
        if (isset($_FILES[$fileName]) === false) {
            throw new UploadException('Can not find uploaded file in super global FILES');
        }

        $fileExtension = strrchr($_FILES[$fileName]['name'], '.');

        if (empty($extensions) === false && in_array($fileExtension, $extensions) === false) {
            throw new FileTypeException('Wrong file extension');
        }

        $uploadedFileName = hash('sha1', uniqid() . time()) . $fileExtension;
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);

        if (empty($mimeTypes) === false && in_array(finfo_file($fileInfo, $_FILES[$fileName]['tmp_name']), $mimeTypes) === false) {
            throw new FileTypeException('Wrong MIME type');
        }

        if (!is_writable(ROOT . '/' . $uploadPath)) {
            throw new IOException('Cannot write in destination directory');
        }

        finfo_close($fileInfo);

        if (move_uploaded_file($_FILES[$fileName]['tmp_name'], ROOT . '/' . $uploadPath . '/' . $uploadedFileName) === false) {
            throw new IOException('Failed to move the uploaded file');
        }
	
        return $uploadedFileName;
    }

}