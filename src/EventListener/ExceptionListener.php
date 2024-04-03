<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class ExceptionListener implements EventSubscriberInterface {
  public static function getSubscribedEvents(): array
  {
    return [
      KernelEvents::EXCEPTION => 'onKernelException',
    ];
  }

  public function onKernelException(ExceptionEvent $event): void
  {
    $exception = $event->getThrowable();

    $response = new JsonResponse($exception->getMessage() . $exception::class, Response::HTTP_INTERNAL_SERVER_ERROR);

    switch (true) {
      case $exception instanceof HttpException:
        $response = new JsonResponse(['error' => $exception->getMessage()], $exception->getStatusCode());
        if ($exception->getPrevious() instanceof ValidationFailedException) {
          $response = $this->getValidationResponse($exception->getPrevious());
        }
        break;
      case $exception instanceof ValidationFailedException:
        $response = $this->getValidationResponse($exception);
        break;
    }

    $event->setResponse($response);
  }

  private function getValidationResponse(ValidationFailedException $exception): JsonResponse {
    $errors = [];

    foreach ($exception->getViolations() as $violation) {
      $errors[$violation->getPropertyPath()] = $violation->getMessage();
    }

    $responseData = [
      'errors' => $errors,
    ];

    return new JsonResponse($responseData, Response::HTTP_UNPROCESSABLE_ENTITY);
  }
}