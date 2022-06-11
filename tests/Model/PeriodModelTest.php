<?php

namespace App\Tests\Model;

use App\Entity\Period;
use App\Model\PeriodModel;
use App\Repository\PeriodRepository;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;

class PeriodModelTest extends TestCase
{
    /**
    * @dataProvider periodProvider
    */
    public function testCheckIfPeriodIsOnOneMonth($period, $expected): void
    {
        $periodRepository = $this->getMockBuilder(PeriodRepository::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $periodModel = new PeriodModel($periodRepository);
        $result = $periodModel->checkIfPeriodIsOnOneMonth($period);
        $this->assertSame($expected, $result);
    }

    public function periodProvider(): array
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
}
