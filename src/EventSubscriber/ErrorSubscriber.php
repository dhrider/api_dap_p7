<?php

namespace App\EventSubscriber;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

Class ErrorSubscriber implements EventSubscriberInterface
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function processException(ExceptionEvent $event)
    {
        $result = $this->serializer->normalize(FlattenException::createFromThrowable($event->getThrowable()));

        $body = $this->serializer->serialize($result['detail'], 'json');

        $event->setResponse(new Response($body, 'Access Denied.' === $result['detail'] ? 403 : $result['status']));
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [['processException', 255]]
        ];
    }
}
