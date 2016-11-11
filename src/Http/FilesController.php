<?php

namespace Interpro\Files\Http;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Interpro\Files\Exception\FileSaveOperation;

class FilesController extends Controller
{

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

            $saveOper = new FileSaveOperation();

            $link = $saveOper->fileSaveOperation($entity_name, $entity_id, $field_name, $file);

            return ['status'=>'OK', 'link' => $link];
        }
        catch(\Exception $e)
        {
            return ['status' => $e->getMessage()];
        }
    }

}

