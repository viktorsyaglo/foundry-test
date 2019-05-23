<?php

use Illuminate\Database\Seeder;
use \App\Employee;
class EmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $src=new \App\Libraries\DataImport\CsvDataSource(file_get_contents('database/csv/employee_table.csv'));
        $src->fieldMap=['id'=>'id','dep_id'=>'department_id', 'full_name'=>'full_name','salary'=>'salary'];
        Employee::import($src);
    }
}
