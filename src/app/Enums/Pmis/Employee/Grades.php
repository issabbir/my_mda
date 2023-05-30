<?php
/**
 * Created by PhpStorm.
 * User: rokan
 * Date: 14/10/21
 * Time: 11:32 AM
 */

namespace App\Enums\Pmis\Employee;

/**
 * Employee Grades. We should use it in doing query.
 * USAGE: \App\Enums\Pmis\Employee\Grades::ON_SSAEN_GRADE.
 *
 * Class Grades
 * @package App\Enums\Pmis\Employee
 */
abstract class Grades
{
    public const ON_SSAEN_GRADE = 10;
    public const ON_SAEN_GRADE = 11;
}
