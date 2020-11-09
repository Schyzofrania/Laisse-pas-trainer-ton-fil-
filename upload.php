<?php
if(isset($_POST['submit']))
{
    $uploads = count($_FILES['upload']['name']);
    if($uploads > 0)
    {
        for($i = 0; $i < $uploads; $i++)
        {
            $extensionFile = pathinfo($_FILES['upload']['name'][$i], PATHINFO_EXTENSION);
            $temporaryFile = $_FILES['upload']['tmp_name'][$i];
            $fileName = $_FILES['upload']['name'][$i];
            $fileSize = $_FILES['upload']['size'][$i];
            if(in_array($extensionFile, ['jpg', 'jpeg', 'png', 'gif']) && in_array(mime_content_type($temporaryFile), ['image/jpg', 'image/jpeg', 'image/png', 'image/gif']))
            {
                if(filesize($temporaryFile) <= 1000000)
                {
                    $name = uniqid() . '.' . $extensionFile;
                    $uploadFile = __DIR__ . '/uploads/' . $name;
                    $movedUpload = move_uploaded_file($temporaryFile, $uploadFile);
                    if($movedUpload)
                    {
                        echo 'file ' . $name . ' has been uploaded';
                    }
                    else
                    {
                        echo 'Problem with your upload !';
                    }
                }
                else
                {
                    echo 'File too big to be uploaded';
                }
            }
            else
            {
                echo 'Extension file not correct';
            }
        }
    }
    else
    {
        echo 'No files, please choose file(s)';
    }
}
if(isset($_GET['delete']) && !empty($_GET['delete']))
{
    $fileToDelete = __DIR__.'/uploads/'.$_GET['delete'];
    if(file_exists($fileToDelete))
    {
        unlink($fileToDelete);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laisse pas trainer ton file</title>
</head>
    <body>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="imageUpload">Upload images</label>    
            <input type="file" name="upload[]" multiple="multiple" id="imageUpload"/>
            <button name="submit">Upload</button>
        </form>
    </body>
</html>

<?php
    if(is_dir(__DIR__ . '/uploads'))
    {
        $images = new FilesystemIterator(dirname(__FILE__) . '/uploads');
        foreach($images as $image)
        {
            echo'<li>
                    <figure>
                        <img src="uploads/' . $image->getFilename() . ' " style="height: 100px; width: auto;">
                        <figcaption>Uploaded '.date("Y-m-d H:i:s", filemtime('uploads/'.$image->getFilename())).'</figcaption>
                    </figure>
                    <a href="?delete='.$image->getFilename().'">Delete file <em>'.$image->getFilename().'</em></a>
                </li>';
        }
    }