<?php

namespace AppBundle\Event;

interface EventInterface
{
    /**
     * @return string
     */
    public function getUuid();

    /**
     * @return \DateTime
     */
    public function getDateTime();
}
