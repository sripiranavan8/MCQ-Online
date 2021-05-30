<?php
class Upload
{
    public function insert($file, $pathToSave)
    {
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        $fileExt = explode('.', $fileName);
        $fileActualExt = strtolower(end($fileExt));
        if (!file_exists($pathToSave)) {
            mkdir($pathToSave, 0777, true);
        }
        $allowed = array('jpg', 'jpeg', 'png', 'pdf');
        if (in_array($fileActualExt, $allowed)) {
            if ($fileError === 0) {
                if ($fileSize < 5000000) {
                    $filenameNew = $fileExt[0] . uniqid('', true) . '.' . $fileActualExt;
                    $fileDestination = $pathToSave . $filenameNew;
                    move_uploaded_file($fileTmpName, $fileDestination);
                    return $filenameNew;
                } else {
                    echo "Your file is too big";
                }
            } else {
                echo "There was an error uploading your file!";
            }
        } else {
            echo "You cannot upload files of this type";
        }
    }
}
