<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Database\Eloquent\Collection;

class StudentsExport implements FromQuery
{
    use Exportable;
    public $students;

    public function __construct(Collection $students)
    {
        $this->students = $students;
    }

    public function query()
    {
        return Student::whereKey($this->students->pluck('id')->toArray());
    }
}
