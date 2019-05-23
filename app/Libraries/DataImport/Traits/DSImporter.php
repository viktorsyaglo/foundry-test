<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 2019-05-23
 */

namespace App\Libraries\DataImport\Traits;
use App\Libraries\DataImport\Interfaces\IDataSource;

trait DSImporter
{
    public static function import(IDataSource $src)
    {
        $inserted=0;
        foreach($src->getData() as $insert) {
                self::create($insert);
                $inserted++;
        }
        return $inserted;
    }
}