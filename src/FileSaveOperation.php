<?php

namespace Interpro\Files\Exception;

use Interpro\Files\FieldProviding\FieldSaver;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileSaveOperation
{
    private $fSaver;

    public function __construct()
    {
        $this->fSaver = new FieldSaver();
    }

    /**
     * @param $entity_name
     * @param $entity_id
     * @param $name
     * @param UploadedFile $uploadedFile
     *
     * @return string
     */
    public function fileSaveOperation($entity_name, $entity_id, $name, UploadedFile $uploadedFile)
    {
        $files_dir = public_path('files');

        if (!is_writable($files_dir))
        {
            throw new FilesException('Дирректория для загрузки файлов ('.$files_dir.') не доступна для записи!');
        }

        $original_name = $uploadedFile->getClientOriginalName();

        $link = $files_dir.'/'.$original_name;

        $uploadedFile->move(
            $files_dir,
            $original_name
        );

        chmod($link, 0644);

        //$this->fSaver->save([['entity_name' => $entity_name, 'entity_id' => $entity_id, 'name' => $name, 'value' => $link]]);

        return $link;
    }
}