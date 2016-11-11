<?php

namespace Interpro\Files\FieldProviding;

use Interpro\Files\Exception\FilesException;
use Interpro\Files\Model\File;
use Interpro\QuickStorage\Concept\FieldProviding\FieldSaver as FieldSaverInterface;

class FieldSaver implements FieldSaverInterface
{
    /**
     * @param array $save_array
     *
     * @return void
     */
    public function save($save_array)
    {
        if(is_array($save_array))
        {
            foreach($save_array as $val_array)
            {
                $entity_name = '';
                $entity_id = 0;
                $name = '';
                $value = '';

                if(array_key_exists('entity_name', $val_array)){

                    $entity_name = $val_array['entity_name'];
                }
                else
                {
                    throw new FilesException('Отсутствует обязательное поле (entity_name) в сохраняемых значениях File');
                }

                if(array_key_exists('entity_id', $val_array)){

                    $entity_id = $val_array['entity_id'];
                }

                if(array_key_exists('name', $val_array)){

                    $name = $val_array['name'];
                }
                else
                {
                    throw new FilesException('Не передано имя идентификатора File поля.');
                }

                if(array_key_exists('value', $val_array)){

                    $value = $val_array['value'];
                }
                else
                {
                    $value = '';
                }

                $item = File::where('entity_name', $entity_name)->
                              where('entity_id', $entity_id)->
                              where('name', $name)->first();

                if(!$item)
                {
                    $item = new File();
                    $item->entity_name = $entity_name;
                    $item->entity_id = $entity_id;
                    $item->name = $name;
                    $item->link = $value;
                    $item->save();
                }
                elseif($item->link !== $value)
                {
                    $item->link = $value;
                    $item->save();
                }
            }
        }
    }

}
