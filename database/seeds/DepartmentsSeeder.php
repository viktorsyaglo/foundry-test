<?php

use Illuminate\Database\Seeder;
use \App\Department;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $src=new \App\Libraries\DataImport\CsvDataSource(file_get_contents('database/csv/department_table.csv'));
        $src->fieldMap=['id'=>'id','department'=>'name'];
        Department::import($src);
    }
}
