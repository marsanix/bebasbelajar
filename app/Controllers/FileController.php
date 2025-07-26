<?php

namespace App\Controllers;

class FileController extends BaseController
{
    public function image($filename)
    {
        $path = WRITEPATH . 'uploads/images/' . $filename;

        if (!is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $mime = mime_content_type($path);
        return $this->response->setHeader('Content-Type', $mime)->setBody(file_get_contents($path));
    }

    function publicImage($filename)
    {
        $path = WRITEPATH . 'uploads/images/' . $filename;

        if (!is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $mime = mime_content_type($path);
        return $this->response->setHeader('Content-Type', $mime)->setBody(file_get_contents($path));
    }
}
