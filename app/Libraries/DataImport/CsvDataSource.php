<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 2019-05-23
 */

namespace App\Libraries\DataImport;


use App\Libraries\DataImport\Interfaces\IDataSource;

class CsvDataSource implements IDataSource
{
    public $fieldMap=[];
    public $rawData='';
    public $utf8Encode=true;

    /**
     * Constructs and sets a raw csv data from string if passed.
     * @param $raw - string, containing raw csv
     *
     */
    public function __construct($raw=null)
    {
        if($raw) $this->setRaw($raw);
    }


    /**
     * Sets a raw csv data from string
     * @param $raw - string, containing raw csv
     *
     */

    function setRaw($raw)
    {
        $this->rawData=$raw;
    }


    /**
     * Reads csv string and returns data with fields, supplied in fieldMap property
     */
    function getData()
    {
        if($this->utf8Encode) $data=utf8_encode($this->rawData);
        else $data=$this->rawData;

        $rows=explode("\n",str_replace("\r\n","\n",$data));

        $headers=null;
        $return=[];
        $count=0;

        foreach ($rows as $row) {
            $columns=explode(";",$row );
            if(!$headers) {
                $headers=$columns;
                continue;
            }


            if(count($columns)!=count($headers)) continue;

            foreach ($headers as $key) {
                $value=array_shift($columns);
                if(isset($this->fieldMap[$key])) $field=$this->fieldMap[$key];
                else continue;
                $return[$count][$field]=$value;
            }

            $count++;

        }
        return $return;
    }
}