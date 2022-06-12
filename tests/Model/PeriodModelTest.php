<?php

namespace App\Tests\Model;

use App\Entity\Period;
use App\Model\PeriodModel;
use App\Repository\PeriodRepository;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;

class PeriodModelTest extends TestCase
{
    private PeriodModel $periodModel;

    public function setUp(): void
    {
        $periodRepository = $this->getMockBuilder(PeriodRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $this->periodModel = new PeriodModel($periodRepository);
    }

    /**
    * @dataProvider singleOrMultiplePeriodProvider
    */
    public function testCheckIfPeriodIsOnOneMonth($period, $expected): void
    {
        $result = $this->periodModel->checkIfPeriodIsOnOneMonth($period);
        $this->assertSame($expected, $result);
    }

    public function singleOrMultiplePeriodProvider(): array
    {
        $period_1 = new Period();
        $period_1->setEmployee(1)
            ->setStartdate(new \DateTimeImmutable("2022-01-15"))
            ->setEnddate(new \DateTimeImmutable("2022-02-10"))
            ->setType("vacation")
        ;
        $period_2 = new Period();
        $period_2->setEmployee(1)
            ->setStartdate(new \DateTimeImmutable("2022-01-15"))
            ->setEnddate(new \DateTimeImmutable("2022-01-31"))
            ->setType("vacation")
        ;

        return [
            "several_months" => [$period_1, false],
            "one_month" => [$period_2, true]
        ];
    }

    /**
    * @dataProvider multiplePeriodsProvider
    */
    public function testIfPeriodIsCutInGoodNumberOfPeriods($period, $expected): void
    {
        $result = $this->periodModel->cutPeriodIntoCalendarMonths($period);
        $this->assertCount(count($expected), $result);
    }

    /**
    * @dataProvider multiplePeriodsProvider
    */
    public function testEnddateAndStartdateOfEachPeriod($period, $expected): void
    {
        $result = $this->periodModel->cutPeriodIntoCalendarMonths($period);

        foreach ($result as $index=>$cutPeriod) {
            $this->assertSame($cutPeriod->getStartdate()->format('Y-m-d H:i:s'), $expected[$index]->getStartdate()->format('Y-m-d H:i:s'));
            $this->assertSame($cutPeriod->getEnddate()->format('Y-m-d H:i:s'), $expected[$index]->getEnddate()->format('Y-m-d H:i:s'));
        }
    }


    public function multiplePeriodsProvider(): array
    {
        $periods_3 = new Period();
        $periods_3->setEmployee(1)
            ->setStartdate(new \DateTimeImmutable("2022-01-15"))
            ->setEnddate(new \DateTimeImmutable("2022-03-10"))
            ->setType("vacation")
        ;
        $periods_2 = new Period();
        $periods_2->setEmployee(1)
            ->setStartdate(new \DateTimeImmutable("2021-12-24"))
            ->setEnddate(new \DateTimeImmutable("2022-01-02"))
            ->setType("vacation")
        ;

        $period_3_1 = new Period();
        $period_3_1->setEmployee(1)
            ->setStartdate(new \DateTimeImmutable("2022-01-15"))
            ->setEnddate(new \DateTimeImmutable("2022-01-31"))
            ->setType("vacation")
            ->setMonthlyPeriod("01-2022")
        ;
        $period_3_2 = new Period();
        $period_3_2->setEmployee(1)
            ->setStartdate(new \DateTimeImmutable("2022-02-01"))
            ->setEnddate(new \DateTimeImmutable("2022-02-28"))
            ->setType("vacation")
            ->setMonthlyPeriod("02-2022")
        ;
        $period_3_3 = new Period();
        $period_3_3->setEmployee(1)
            ->setStartdate(new \DateTimeImmutable("2022-03-01"))
            ->setEnddate(new \DateTimeImmutable("2022-03-10"))
            ->setType("vacation")
            ->setMonthlyPeriod("03-2022")
        ;

        $expected_1 = [
            $period_3_1,
            $period_3_2,
            $period_3_3
        ];

        $period_2_1 = new Period();
        $period_2_1->setEmployee(1)
            ->setStartdate(new \DateTimeImmutable("2021-12-24"))
            ->setEnddate(new \DateTimeImmutable("2021-12-31"))
            ->setType("vacation")
            ->setMonthlyPeriod("12-2021")
        ;
        $period_2_2 = new Period();
        $period_2_2->setEmployee(1)
            ->setStartdate(new \DateTimeImmutable("2022-01-01"))
            ->setEnddate(new \DateTimeImmutable("2022-01-02"))
            ->setType("vacation")
            ->setMonthlyPeriod("01-2022")
        ;

        $expected_2 = [
            $period_2_1,
            $period_2_2
        ];

        return [
            "periods_3" => [$periods_3, $expected_1],
            "periods_2" => [$periods_2, $expected_2]
        ];
    }
}
