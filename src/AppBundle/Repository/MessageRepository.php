<?php

namespace AppBundle\Repository;

use AppBundle\Entity\EventData;
use AppBundle\Entity\Message;

class MessageRepository
{
    /**
     * @param EventStorage $eventStorage
     */
    public function __construct(EventStorage $eventStorage)
    {
        $this->eventStorage = $eventStorage;
    }

    /**
     * @param string $uuid
     *
     * @return Message
     */
    public function find($uuid)
    {
        $eventData = $this->eventStorage->find($uuid);

        return Message::fromEvents($eventData->getData());
    }

    /**
     * @return Message[]
     */
    public function findAll()
    {
        return array_map(function(EventData $eventData) {
            return Message::fromEvents($eventData->getData());
        }, $this->eventStorage->findAll());
    }

    /**
     * @param Message $message
     */
    public function save(Message $message)
    {
        if (!$eventData = $this->eventStorage->find(['uuid' => $message->getUuid()])) {
            $eventData = (new EventData())
                ->setUuid($message->getUuid());
        }

        $eventData->setData($message->getEvents());

        $this->eventStorage->save($eventData);
    }

    /**
     * @param Message $message
     */
    public function delete(Message $message)
    {
        if ($eventData = $this->eventStorage->find(['uuid' => $message->getUuid()])) {
            $this->eventStorage->delete($eventData);
        }
    }
}
