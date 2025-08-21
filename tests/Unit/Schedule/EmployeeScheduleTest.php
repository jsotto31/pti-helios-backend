<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Schedule\EmployeeSchedule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class EmployeeScheduleTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_filters_by_employee_id()
    {
        $employeeA = EmployeeSchedule::factory()->create(['employee_id' => 1]);
        $employeeB = EmployeeSchedule::factory()->create(['employee_id' => 2]);

        $results = EmployeeSchedule::forEmployee(1)->get();

        $this->assertTrue($results->contains($employeeA));
        $this->assertFalse($results->contains($employeeB));
    }
}
