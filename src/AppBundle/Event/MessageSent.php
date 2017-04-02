<?php

namespace AppBundle\Event;

final class MessageSent implements EventInterface
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
     * @param string $uuid
     * @param \DateTime $datetime
     */
    public function __construct($uuid, \DateTime $datetime)
    {
        $this->uuid = $uuid;
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
}
