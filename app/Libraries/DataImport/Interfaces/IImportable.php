<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 2019-05-23
 */

namespace App\Libraries\DataImport\Interfaces;


interface IImportable
{
    public static function import(IDataSource $src);
}