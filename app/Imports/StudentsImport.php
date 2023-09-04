<?php

namespace App\Imports;

use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    public function model(array $row)
    {
        return new Student([
            'section_id' => self::getSectionId($row['class'], $row['section']),
            'class_id' => self::getClassId($row['class']),
            'name' => $row['name'],
            'email' => $row['email'],
            'address' => $row['address'],
            'phone_number' => $row['phone_number'],
        ]);
    }

    public static function getClassId($class)
    {
        $class = Classes::where('name', $class)->first();

        return $class->id;
    }

    public static function getSectionId($class, $section)
    {
        $class_id = self::getClassId($class);

        $section_model = Section::where([
            'class_id' => $class_id,
            'name' => $section
        ])->first();

        return $section_model->id;
    }
}
