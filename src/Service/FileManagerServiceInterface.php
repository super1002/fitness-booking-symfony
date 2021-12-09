<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileManagerServiceInterface
{
    /**
     * @param UploadedFile $file
     * @return string
     */
    public function imagePostUpload(UploadedFile $file):string;

    /**
     * @param string $filename
     * @return mixed
     */
    public function imagePostRemove(string $filename);
}