<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class CORSResponseSubscriber implements EventSubscriberInterface
{
    private const ALLOWED_METHODS = [
        Request::METHOD_OPTIONS,
        Request::METHOD_GET,
        Request::METHOD_POST,
        Request::METHOD_PATCH,
        Request::METHOD_PUT,
        Request::METHOD_DELETE,
    ];

    private const ALLOWED_HEADERS = [
        'Authorization',
        'Accept-Language',
        'Connection',
        'Content-Type',
        'Content-Length',
        'Content-Disposition',
        'Host',
        'Origin',
        'User-Agent',
        'X-MOBILE-APP-VERSION',
        'X-TRIAL-RUN',
    ];

    /** All requests with method OPTIONS are immediately returned with an empty 200 response. */
    public function onKernelRequest(RequestEvent $event): void
    {
        // Don't do anything if it's not the main request.
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $method = $request->getRealMethod();

        if ($method === Request::METHOD_OPTIONS) {
            $response = $this->attachRelevantHeaders(new Response());
            $event->setResponse($response);
        }
    }

    /** CORS headers are attached to all normal responses. */
    public function onKernelResponse(ResponseEvent $event): void
    {
        // Don't do anything if it's not the master request.
        if (!$event->isMainRequest()) {
            return;
        }

        $response = $event->getResponse();
        $event->setResponse(
            $this->attachRelevantHeaders($response),
        );
    }

    /** CORS headers are attached to all exception responses. */
    public function onKernelException(ExceptionEvent $event): void
    {
        $response = $event->getResponse();
        if ($response) {
            $event->setResponse(
                $this->attachRelevantHeaders($response),
            );
        }
    }

    private function attachRelevantHeaders(
        Response $response,
    ): Response {
        // Add allowed methods
        $response->headers->set('Access-Control-Allow-Methods', implode(',', self::ALLOWED_METHODS));

        // Add allowed headers
        $response->headers->set('Access-Control-Allow-Headers', implode(',', self::ALLOWED_HEADERS));

        $response->headers->set('Access-Control-Allow-Origin', '*');

        // Add vary origin header to signal the browser that different requests can result in different Origin response headers
        $response->headers->set('Vary', 'Origin');

        // Only try a CORS request every two hours (maximum Firefox allows)
        $response->headers->set('Access-Control-Max-Age', '7200');

        return $response;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 9999],
            KernelEvents::RESPONSE => ['onKernelResponse', 9999],
            KernelEvents::EXCEPTION => ['onKernelException', 9999],
        ];
    }
}
