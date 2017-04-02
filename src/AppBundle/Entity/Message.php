<?php

namespace AppBundle\Entity;

use AppBundle\Event\EventInterface;
use AppBundle\Event\MessageCreated;
use AppBundle\Event\MessageArchived;
use AppBundle\Event\MessageRead;
use AppBundle\Event\MessageSent;
use AppBundle\Util\EventsCapability;

final class Message
{
    const STATUS_CREATED = 'created';
    const STATUS_SENT = 'sent';
    const STATUS_READ = 'read';
    const STATUS_ARCHIVED = 'archived';

    use EventsCapability;

    /**
     * @var string
     */
    private $uuid;

    /**
     * @var \DateTime
     */
    private $datetime;

    /**
     * @var string
     */
    private $sender;

    /**
     * @var string
     */
    private $recipient;

    /**
     * @var string
     */
    private $status;

    /**
     * @var bool
     */
    private $sent = false;

    /**
     * @var bool
     */
    private $read = false;

    /**
     * @var bool
     */
    private $archived = false;

    private function __construct()
    {
        // Private constructor, do nothing
    }

    /**
     * @param string $uuid
     * @param string $sender
     * @param string $recipient
     *
     * @return Message
     */
    public static function create($uuid, $sender, $recipient)
    {
        $creationEvent = new MessageCreated($uuid, $sender, $recipient, new \DateTime());

        return self::fromEvents([$creationEvent]);
    }

    /**
     * @param string $uuid
     *
     * @return Message
     */
    public function send($uuid)
    {
        if ($this->sent === true) {
            throw new \RuntimeException('Message already sent');
        }

        $sendEvent = new MessageSent($uuid, new \DateTime());

        $this->addEvent($sendEvent);
        $this->apply($sendEvent);
    }

    /**
     * @param string $uuid
     *
     * @return Message
     */
    public function read($uuid)
    {
        if ($this->sent === false) {
            throw new \RuntimeException('Message not sent');
        }

        if ($this->archived === true) {
            throw new \RuntimeException('Message already archived');
        }

        if ($this->read === true) {
            throw new \RuntimeException('Message already read');
        }

        $readEvent = new MessageRead($uuid, new \DateTime());

        $this->addEvent($readEvent);
        $this->apply($readEvent);
    }

    /**
     * @param string $uuid
     *
     * @return Message
     */
    public function archive($uuid)
    {
        if ($this->sent === false) {
            throw new \RuntimeException('Message not sent');
        }

        if ($this->archived === true) {
            throw new \RuntimeException('Message already archived');
        }

        $deletionEvent = new MessageArchived($uuid, new \DateTime());

        $this->addEvent($deletionEvent);
        $this->apply($deletionEvent);
    }

    /**
     * @param EventInterface[] $events
     *
     * @return Message
     */
    public static function fromEvents(array $events)
    {
        $message = new self();

        foreach ($events as $event) {
            $message->addEvent($event);
            $message->apply($event);
        }

        return $message;
    }

    /**
     * @param EventInterface $event
     */
    private function apply(EventInterface $event)
    {
        if ($event instanceof MessageCreated) {
            $this->uuid = $event->getUuid();
            $this->datetime = $event->getDateTime();
            $this->sender = $event->getSender();
            $this->recipient = $event->getRecipient();
            $this->status = self::STATUS_CREATED;
        }

        if ($event instanceof MessageSent) {
            $this->datetime = $event->getDateTime();
            $this->status = self::STATUS_SENT;
            $this->sent = true;
        }

        if ($event instanceof MessageRead) {
            $this->datetime = $event->getDateTime();
            $this->status = self::STATUS_READ;
            $this->read = true;
        }

        if ($event instanceof MessageArchived) {
            $this->datetime = $event->getDateTime();
            $this->status = self::STATUS_ARCHIVED;
            $this->archived = true;
        }
    }

    /**
     * @return int
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->datetime;
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return bool
     */
    public function isCreated()
    {
        return $this->status === self::STATUS_CREATED;
    }

    /**
     * @return bool
     */
    public function isSent()
    {
        return $this->status === self::STATUS_SENT;
    }

    /**
     * @return bool
     */
    public function isRead()
    {
        return $this->status === self::STATUS_READ;
    }

    /**
     * @return bool
     */
    public function isArchived()
    {
        return $this->status === self::STATUS_ARCHIVED;
    }
}
