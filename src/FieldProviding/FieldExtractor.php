<?php

namespace Interpro\Files\FieldProviding;

use Illuminate\Support\Facades\App;
use Interpro\QuickStorage\Concept\FieldProviding\FieldExtractor as FieldExtractorInterface;

class FieldExtractor implements FieldExtractorInterface
{
    private $fields = [];
    private $model;

    public function __construct()
    {
        $this->model = App::make('Interpro\Files\Model\File');
    }

    /**
     * @param string $entity_name
     * @param string $field_name
     * @param int $entity_id
     *
     * @return string
     */
    public function getField($entity_name, $field_name, $entity_id = 0)
    {
        $rkey = $field_name.'_'.$entity_id;

        $this->queryFields($entity_name);

        if(array_key_exists($rkey, $this->fields[$entity_name]))
        {
            return $this->fields[$entity_name][$rkey];
        }
        else
        {
            return '';
        }
    }

    private function queryFields($entity_name)
    {
        if(!array_key_exists($entity_name, $this->fields))
        {
            $model_q = $this->model->query();
            $model_q->where('entity_name', '=', $entity_name);

            $this->fields[$entity_name] = [];
            $coll = $model_q->get();

            foreach($coll as $item){
                $this->fields[$entity_name][$item->name.'_'.$item->entity_id] = $item->link;
            }
        }
    }

}
