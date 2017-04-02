<?php

namespace AppBundle\Event;

final class MessageCreated implements EventInterface
{
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
     * @param string $uuid
     * @param string $sender
     * @param string $recipient
     * @param \DateTime $datetime
     */
    public function __construct($uuid, $sender, $recipient, \DateTime $datetime)
    {
        $this->uuid = $uuid;
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->datetime = $datetime;
    }

    /**
     * @return string
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
}
