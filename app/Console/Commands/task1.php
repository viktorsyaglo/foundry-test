<?php

namespace App\Console\Commands;

use App\Department;
use App\Employee;
use Illuminate\Console\Command;

class task1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run foundry test task';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $r1=Department::sqlGetDepartmentsWithMinMaxSalary(1000);
        $this->line("Task2 (SQL): Departments where minimum salary > 1000. Sum and max salary are also displayed. ");
        $this->table(['id','department_name',  'max', 'sum'],json_decode(json_encode($r1),TRUE));

        $r2=Department::sqlGetDepartmentsWithMinMaxSalaryAndTopEmployee();
        $this->line("");
        $this->line("Task2* (SQL): Departments where minimum salary > 1000. Same as Task2, but also employee with highest salary displayed.");
        $this->table(['id','department_name',  'max', 'sum','max_salary_employee'],json_decode(json_encode($r2),TRUE));


        $this->line("");
        $this->line("Task3 (SQL)");

        $r3=Department::sqlGetMedian();
        $this->table(['median'],json_decode(json_encode($r3),TRUE));


        $this->line("");
        $this->line("Task3 (Eloquent)");

        $employees=Employee::all(['salary']);
        $this->line("Median is: ".$employees->median('salary'));

        $this->line("");
        $this->line("Task2+Task2*+Task3* (Eloquent)");
        $r4=Department::eloquentGetDepartmentsWithAllData(1);
        $this->table(['id','department_name',  'max', 'sum','avg','median','max_salary_employee'],json_decode(json_encode($r4),TRUE));


        $this->line("");
        $this->line("Task3* (SQL). Median grouped by department");

        $r5=Department::sqlGetDepartmentsWithAllData();
        $this->table(['id','department_name',  'sum', 'avg', 'median'],json_decode(json_encode($r5),TRUE));
    }
}
