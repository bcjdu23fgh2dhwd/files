<?php

namespace Interpro\Files\Http;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Interpro\Files\Exception\FilesException;
use Interpro\Files\FieldProviding\FieldSaver;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FilesController extends Controller
{
    private $fSaver;

    public function __construct(FieldSaver $fSaver)
    {
        $this->fSaver = $fSaver;
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

        $this->fSaver->save([['entity_name' => $entity_name, 'entity_id' => $entity_id, 'name' => $name, 'value' => $link]]);

        return $link;
    }

    public function upload(Request $request){

        try
        {
            $validator = Validator::make(
                $request->all(),
                [
                    'entity_name' => 'required',
                    'entity_id' => 'integer|min:0',
                    'name' => 'required',
                    'file' => 'required',
                ]
            );

            if($validator->fails()){
                return ['status'=>false, 'error'=>$validator->errors()->setFormat(':message<br>')->all()];
            }

            $entity_name = $request->input('entity_name');
            $field_name = $request->input('name');
            $file = $request->file('file');

            if($request->has('entity_id'))
            {
                $entity_id = $request->input('entity_id');
            }
            else
            {
                $entity_id = 0;
            }

            $link = $this->fileSaveOperation($entity_name, $entity_id, $field_name, $file);

            return ['status'=>'OK', 'link' => $link];
        }
        catch(\Exception $e)
        {
            return ['status' => $e->getMessage()];
        }
    }

}

