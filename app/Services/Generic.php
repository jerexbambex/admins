<?php

define('PAGINATE_SIZE', 10);

define('LEVELS', [1 => 'ND 1', 2 => 'ND 2', 5 => 'ND 3', 3 => 'HND 1', 4 => 'HND 2', 6 => 'HND 3']);
define('PROGRAMMES', [1 => 'ND', 2 => 'HND']);
define('ND_LEVELS', [1 => 'ND 1', 2 => 'ND 2']);
define('HND_LEVELS', [3 => 'HND 1', 4 => 'HND 2']);

define('FROM', [2000, 4000, 6000, 8000, 10000, 12000, 14000, 16000, 18000]);
define('TO', [2000, 4000, 6000, 8000, 10000, 12000, 14000, 16000, 18000]);

define('PROG_TYPES', [1 => 'FULL TIME', 2 => 'CEC', 3 => 'DPP']);

define('PROG_TYPE_SLUGS', [1 => 'FT', 2 => 'CEC', 3 => 'DPP']);

define('PROG_TYPE_ENUM', [1 => 'F', 2 => 'C', 3 => 'D']);

define('ADM_STATUS', [0 => 'NOT ADMITTED', 1 => 'ADMITTED']);

define('SUBMIT_STATUS', [0 => 'NOT SUBMITTED', 1 => 'SUBMITTED']);

define('SESSION_TEMP', [2020 => '2020/2021', 2021 => '2021/2022']);

define('EXAM_HOURS', [
    'first'     =>  [11, 17],
    'other'     =>  [8, 17],
    'friday'    =>  [8, 13]
]);

define('EXAM_SEATS', 400);

define('SEMESTERS', [
    1   =>  'First Semester',
    2   =>  'Second Semester',
    3   =>  'Third Semester'
]);

define('SEMESTER_KEYS', [
    'First Semester'    =>  1,
    'Second Semester'   =>  2,
    'Third Semester'    =>  3
]);

define('GRADES', [
    'A' =>  range(75, 100),
    'AB' =>  range(70, 74),
    'B' =>  range(65, 69),
    'BC' =>  range(60, 64),
    'C' =>  range(55, 59),
    'CD' =>  range(50, 54),
    'D' =>  range(45, 49),
    'DE' =>  range(40, 44),
    'F' =>  range(0, 39),
    'NS' => -1,
    'EM' => -2
]);

define('POINTS', [
    'A' =>  4.00,
    'AB' =>  3.50,
    'B' =>  3.25,
    'BC' =>  3.00,
    'C' =>  2.75,
    'CD' =>  2.50,
    'D' =>  2.25,
    'DE' =>  2.00,
    'F' =>  0,
    'NS' =>  0,
    'EM' =>  0
]);

function grade(Int $value): string
{
    if ($value == -1) return 'NS';
    if ($value == -2) return 'EM';
    if (in_array($value, GRADES['A'])) return 'A';
    if (in_array($value, GRADES['AB'])) return 'AB';
    if (in_array($value, GRADES['B'])) return 'B';
    if (in_array($value, GRADES['BC'])) return 'BC';
    if (in_array($value, GRADES['C'])) return 'C';
    if (in_array($value, GRADES['CD'])) return 'CD';
    if (in_array($value, GRADES['D'])) return 'D';
    if (in_array($value, GRADES['DE'])) return 'DE';
    return 'F';
}

function grade_points($point): string
{
    if ($point >= 3.5 && $point <= 4.0) return 'Distinction';
    if ($point >= 3.0 && $point <= 3.49) return 'Upper Credit';
    if ($point >= 2.5 && $point <= 2.99) return 'Lower Credit';
    if ($point >= 2.0 && $point <= 2.49) return 'Pass';
    return 'Fail';
}

function modifiedDate($value)
{
    return gmdate('jS F, Y - h:i:s a', strtotime($value));
}

function mapSemesters($sem)
{
    if ($sem) return SEMESTERS[$sem];
    return '';
}
