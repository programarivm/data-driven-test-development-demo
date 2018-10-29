<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * Class ExceptionListener.
 */
class ExceptionListener
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        $response = new Response();
        $response->setStatusCode($exception->getStatusCode());
        $response->headers->set('Content-Type', 'application/json');

        if ($exception instanceof BadRequestHttpException) {
            $message = 'Bad Request';
        } elseif ($exception instanceof NotFoundHttpException) {
            $message = 'Not Found';
        } elseif ($exception instanceof UnauthorizedHttpException) {
            $message = 'Unauthorized';
        } else {
            $message = 'Internal Server Error';
        }

        $response->setContent(
            json_encode([
                'status' => $exception->getStatusCode(),
                'message' => $message
            ])
        );

        $event->setResponse($response);
    }
}
