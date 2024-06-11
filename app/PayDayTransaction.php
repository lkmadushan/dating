<?php

namespace App;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;

class PayDayTransaction
{
    public function __construct(protected EmployeRepository $repository)
    {
    }

    public function execute(): Collection
    {
        $errors = new Collection;

        $employees = $this->repository->fetchAll();

        foreach ($employees as $employee) {
            try {
                if ($employee->isPayDay(Date::now())) {
                    // Calculate paycheck / check its invariants
                    $paycheck = $employee->calculatePaycheck();

                    // Send paycheck process / check its invariants
                    $employee->sendPay($paycheck);

                    $this->repository->persist($employee);
                }
            } catch (Exception $e) {
                $errors->add([
                    'employee' => $employee,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $errors;
    }
}
