<?php

namespace App;

use App\Libraries\DataImport\Interfaces\IImportable;
use App\Libraries\DataImport\Traits\DSImporter;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model implements IImportable
{
    use DSImporter;
    protected $fillable=['id', 'department_id', 'full_name','salary'];

}
