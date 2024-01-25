<?php

namespace App\EventSubscriber;

use App\CQRS\Exception\InternalException;
use App\CQRS\Exception\UserFacingException;
use App\EventSubscriber\DTO\ErrorResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final readonly class ExceptionTransformationSubscriber implements EventSubscriberInterface
{
    private const GENERIC_ERROR_MESSAGE = 'Es ist ein Fehler aufgetreten, das Entwicklungsteam wurde darüber informiert. Bitte versuche es später noch einmal!';

    public function __construct(
        private bool $isExceptionTransformationEnabledForAllExceptions,
    ) {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $errorResponse = null;

        if ($exception instanceof UserFacingException) {
            $errorResponse = new ErrorResponse(
                $exception->getMessage(),
                $exception->statusCode,
            );
        } elseif ($exception instanceof InternalException) {
            $errorResponse = new ErrorResponse(
                self::GENERIC_ERROR_MESSAGE,
                $exception->statusCode,
            );
        } elseif ($this->isExceptionTransformationEnabledForAllExceptions) {
            $errorResponse = new ErrorResponse(
                self::GENERIC_ERROR_MESSAGE,
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        if ($errorResponse !== null) {
            $response = new JsonResponse(
                [
                    'message' => $errorResponse->message,
                ],
                $errorResponse->statusCode,
            );

            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }
}
