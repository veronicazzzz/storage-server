<?php

namespace App\Controller;

use App\Service\EnvService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HealthController extends AbstractController
{
    /**
     * @var EnvService
     */
    private $envService;

    public function __construct(EnvService $envService)
    {
        $this->envService = $envService;
    }

    /**
     * @return JsonResponse
     */
    public function getAction(): JsonResponse
    {
        return new JsonResponse(
            array(
                'APP_ENV' => $this->envService->getAppEnv()
            ),
            Response::HTTP_OK
        );
    }
}
