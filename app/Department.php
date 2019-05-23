<?php

namespace App;

use App\Libraries\DataImport\Interfaces\IImportable;

use App\Libraries\DataImport\Traits\DSImporter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Department extends Model implements IImportable
{
    use DSImporter;
    //
    protected $fillable=['id', 'name'];


    static public function sqlGetDepartmentsWithMinMaxSalary($minSalary=1000)
    {
        $sql="SELECT d.id AS department_id, d.name AS `department_name`,   MAX(salary) as `max`, SUM(salary) AS `sum` FROM employees AS e
            JOIN departments AS d ON d.id=e.`department_id`
            GROUP BY e.department_id  ";
        if(intval($minSalary)) {
            $sql.=' HAVING  MIN(salary)>'.intval($minSalary);
        }

        return DB::select($sql);
    }

    static public function sqlGetDepartmentsWithMinMaxSalaryAndTopEmployee($minSalary=1000)
    {
        $sql="SELECT d.id AS `department_id`, d.name AS `department_name`,  MAX(salary) as `max`, SUM(salary) as `sum`, 
          (SELECT   full_name FROM employees WHERE department_id=e.department_id ORDER BY salary DESC LIMIT 1) as max_salary_employee
          FROM employees AS e JOIN departments AS d ON d.id=e.`department_id`
          WHERE 1 GROUP BY department_id ";

        if(intval($minSalary)) {
            $sql.=' HAVING MIN(salary)>'.intval($minSalary);
        }
        return DB::select($sql);

    }
    public function employees()
    {
        return $this->hasMany('App\\Employee');
    }
    static public function eloquentGetDepartmentsWithAllData($minSalary=1000)
    {
        $departments=Department::whereHas('employees',
            function ($query) use ($minSalary) {
                $query->groupBy('department_id')->havingRaw("MIN(salary)>$minSalary")->orderby('salary','DESC');
            })->get();

        $ret=[];
        $i=0;
        foreach($departments as $department){
            $ret[$i]['department_id']=$department->id;
            $ret[$i]['department_name']=$department->name;

            $employees=$department->employees()->orderby('salary','DESC')->get();
            $ret[$i]['max']=$employees->max('salary');
            $ret[$i]['sum']=$employees->sum('salary');
            $ret[$i]['avg']=$employees->avg('salary');
            $ret[$i]['med']=$employees->median('salary');



            if(count($employees)){
                $ret[$i]['max_salary_owner']=$employees[0]->full_name;
            }
            else $ret[$i]['max_salary_owner']=null;
            $i++;
        }
        return $ret;
    }

    static public function sqlGetDepartmentsWithAllData() {

        $sql="SELECT   @dep_id:=department_id AS department_id, departments.name AS department_name, 
SUM(salary) AS sum, AVG(salary) AS avg, (
        SELECT   AVG(salary) as median
        FROM
        (
            SELECT department_id, salary,
              (SELECT count(*) FROM employees t2 WHERE t2.department_id = t3.department_id) as ct,
              seq,
              (SELECT count(*) FROM employees t2 WHERE t2.department_id < t3.department_id) as delta
            FROM (SELECT department_id, salary, @rownum := @rownum + 1 as seq
                  FROM (SELECT * FROM employees ORDER BY department_id, salary) t1 
                  ORDER BY department_id, seq
                ) t3 CROSS JOIN (SELECT @rownum := 0) x
            HAVING (ct%2 = 0 and seq-delta between floor((ct+1)/2) and floor((ct+1)/2) +1)
              or (ct%2 <> 0 and seq-delta = (ct+1)/2)
        ) T
        WHERE department_id=@dep_id
        GROUP BY department_id
        ORDER BY department_id) AS median

FROM employees
JOIN departments ON employees.department_id=departments.id
 WHERE 1 GROUP BY department_id";

        return DB::select($sql);
    }

    static function sqlGetMedian()
    {
        return DB::select("SELECT AVG(dd.salary) as median_val
            FROM (
                SELECT d.salary, @rownum:=@rownum+1 as `row_number`, @total_rows:=@rownum
              FROM employees d, (SELECT @rownum:=0) r
            
            
              ORDER BY d.salary
            ) as dd
            WHERE dd.row_number IN ( FLOOR((@total_rows+1)/2), FLOOR((@total_rows+2)/2) )");

    }

}
