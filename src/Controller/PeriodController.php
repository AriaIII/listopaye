<?php

namespace App\Controller;

use App\Model\PeriodModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\isEmpty;

class PeriodController extends AbstractController
{
    #[Route('/period/declare', name: 'declare_period', methods:["POST"])]
    public function declareAction(Request $request, PeriodModel $periodModel): JsonResponse
    {
        $data = json_decode($request->getContent());

        try {
            $this->checkData($data);
        } catch (\UnexpectedValueException $e) {
            return $this->json($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }

        $type = $this->cleanData($data->type);
        $startdate = new \DateTimeImmutable($this->cleanData($data->startdate));
        $enddate = new \DateTimeImmutable($this->cleanData($data->enddate));
        $employee = $this->cleanData($data->employee);

        $period = $periodModel->create($type, $startdate, $enddate, $employee);

        if ($periodModel->checkIfPeriodIsOnOneMonth($period)) {
            $periodModel->register($period);
        } else {
            $periods = $periodModel->cutPeriodIntoCalendarMonths($period);
            foreach ($periods as $period) {
                $periodModel->register($period);
            }
        }
        return $this->json("Votre période de congés a été enregistrée.", Response::HTTP_OK);
    }

    #[Route('/period', name: 'index_period', methods:["GET"])]
    public function indexAction(PeriodModel $periodModel): JsonResponse
    {
        $periods = $periodModel->getPeriods();
        return $this->json($periods, Response::HTTP_OK);
    }

    private function checkData(mixed $data): void
    {
        if (empty($data->type) || empty($data->startdate) || empty($data->enddate) || empty($data->employee)) {
            throw new \UnexpectedValueException("the data should not be empty");
        }
        $enddate = $data->enddate;
        $startdate = $data->startdate;
        if ((date('d-m-Y', strtotime($enddate)) !== $enddate) || (date('d-m-Y', strtotime($startdate)) !== $startdate)) {
            throw new \UnexpectedValueException("The dates should be formatted like d-m-Y");
        }
    }

    private function cleanData(mixed $data): bool
    {
            $data = trim($data);
            $data = stripslashes($data);
            return htmlspecialchars($data);
    }

}
