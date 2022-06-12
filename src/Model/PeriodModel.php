<?php

namespace App\Model;

use App\Entity\Period;
use App\Repository\PeriodRepository;

class PeriodModel
{
    private PeriodRepository $periodRepository;

    public function __construct(PeriodRepository $periodRepository)
    {
        $this->periodRepository = $periodRepository;
    }

    public function create($type, $startdate, $enddate, $employee, $monthlyPeriod = 0)
    {
        $period = new Period();
        $period->setType($type)
            ->setStartdate($startdate)
            ->setEnddate($enddate)
            ->setEmployee($employee)
            ->setMonthlyPeriod($monthlyPeriod)
        ;
        return $period;
    }

    public function checkIfPeriodIsOnOneMonth(Period $period): bool
    {
        // it is assumed that the period of use corresponds to a calendar month
        return $period->getStartdate()->format('Y') === $period->getEnddate()->format('Y') && $period->getStartdate()->format('m') === $period->getEnddate()->format('m');
    }

    public function cutPeriodIntoCalendarMonths(Period $period): array
    {
        $periodType = $period->getType();
        $employee = $period->getEmployee();
        $periodStartdate = $period->getStartdate();
        $enddate = $period->getEnddate();
        $monthlyPeriod = $periodStartdate->format("m-Y");
        $enddateMonthlyPeriod = $enddate->format('m-Y');
        $periods = [];

        while ($monthlyPeriod !== $enddateMonthlyPeriod) {
            $periodEnddate = new \DateTimeImmutable($periodStartdate->format("Y-m-t"));
            $periodPart = $this->create($periodType, $periodStartdate, $periodEnddate, $employee, $monthlyPeriod);
            $periods[] = $periodPart;
            $periodStartdate = $periodEnddate->add(new \DateInterval('P1D'));

            $monthlyPeriod = $periodStartdate->format("m-Y");
        }
        $lastPeriod = $this->create($periodType, $periodStartdate, $enddate, $employee, $monthlyPeriod);

        $periods[] = $lastPeriod;
        return $periods;
    }

    public function register($period): void
    {
        $this->periodRepository->add($period, true);
    }

    public function getPeriods(): array
    {
        return $this->periodRepository->findAll();
    }
}
