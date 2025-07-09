<?php

namespace App\Controllers\admin;

use Caspian\Controller;

class ZSUploader extends Controller {

    private string $upload_folder;
    private string $full_adr;
    private string $full_browse_dir;
    private string $server;
    private string $ses_id;

    public function __construct()
    {
        parent::__construct();
        $validSession = Model::sessionLogin();
        if (empty($validSession)) {
            exit();
        }

        $this->server = $_SERVER['SERVER_NAME'];
        $this->upload_folder = "/public/webimg/postcontent/";

        $this->full_adr = $this->server . $this->upload_folder;
        $this->full_browse_dir = $_SERVER["DOCUMENT_ROOT"] . $this->upload_folder;
        $_SESSION['upload_adr'] = $this->full_browse_dir;

//        if (isset($_SESSION["id"])) {
//            $this->ses_id = $_SESSION["id"];
//            if (!file_exists($this->full_browse_dir . "/" . $this->ses_id)) {
//                mkdir($this->full_browse_dir . "/" . $this->ses_id, 0777, true);
//            }
//            $this->full_adr = $this->full_adr . $this->ses_id . "/";
//            $this->full_browse_dir = $this->full_browse_dir . $this->ses_id . "/";
//            $this->upload_folder = $this->upload_folder . $this->ses_id . "/";
//            $_SESSION['upload_adr'] = $this->full_browse_dir;
//        }
    }

    public function zs_browser()
    {
        $full_browse_dir = $this->full_browse_dir;
        $upload_folder = $this->upload_folder;
        $server = $this->server;

        $params = compact('full_browse_dir', 'upload_folder', 'server');
        $this->viewAdmin('zsuploader/zsbrowser_view', $params, 1, 1);
    }

    public function zs_upload()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION['upload_adr'])) {
            $full_adr = $_SESSION['upload_adr'];
        } else {
            $full_adr = "./images/Lessons/";
        }

        if (is_array($_FILES)) {
            if (is_uploaded_file($_FILES['userImage']['tmp_name'])) {
                $sourcePath = $_FILES['userImage']['tmp_name'];
                //Change THIS target path if you need to
                $targetPath = $full_adr . $_FILES['userImage']['name'];

                $filename = $_FILES['userImage']['name'];
                $loc = $targetPath;
                if (file_exists($loc)) {
                    $increment = 0;
                    list($name, $ext) = explode('.', $loc);
                    while (file_exists($loc)) {
                        $increment++;
                        // $loc is now "userpics/example1.jpg"
                        $loc = $name . $increment . '.' . $ext;
                        $filename = $name . '-' . $increment . '.' . $ext;
                        $targetPath = $filename;
                    }
                }

                if (move_uploaded_file($sourcePath, $targetPath)) {
                    chmod($targetPath, 0777);
                    echo "<img class='image-preview' src='$targetPath;' class='upload-preview' />";
                    echo '<script>location.reload();</script>';
                }
            }
        }
    }

    public function zs_delete()
    {
        if (!isset($_POST['file']) || empty($_POST['file'])) {
            echo 0;
        }
        // find the file
        $file = htmlspecialchars(strip_tags($_POST['file']));
        $file = str_replace('/', '', $file);

        $f = "..{$this->upload_folder}{$file}";
        $fh = "..{$this->upload_folder}thumbs/{$file}";

        if (is_file($f)) {
            unlink($f);
        }

        if (is_file($fh)) {
            unlink($fh);
        }

        echo 1;
    }
}
