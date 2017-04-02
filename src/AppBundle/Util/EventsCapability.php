<?php

namespace AppBundle\Util;

use AppBundle\Event\EventInterface;

trait EventsCapability
{
    /**
     * @var array
     */
    private $events = [];

    /**
     * @param EventInterface $event
     *
     * @return $this
     */
    protected function addEvent(EventInterface $event)
    {
        $this->events[] = $event;

        return $this;
    }

    /**
     * @return EventInterface[]
     */
    public function getEvents()
    {
        return $this->events;
    }
}
