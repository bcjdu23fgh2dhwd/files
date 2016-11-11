<?php

namespace Interpro\Files\Model;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table = 'files';
    public $timestamps = false;
    protected static $unguarded = true;
}
