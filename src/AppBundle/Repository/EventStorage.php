<?php

namespace AppBundle\Repository;

use AppBundle\Entity\EventData;
use Doctrine\ORM\EntityRepository;

class EventStorage extends EntityRepository
{
    /**
     * @param EventData $eventData
     */
    public function save(EventData $eventData)
    {
        $this->getEntityManager()->persist($eventData);
        $this->getEntityManager()->flush($eventData);
    }

    /**
     * @param EventData $eventData
     */
    public function delete(EventData $eventData)
    {
        $this->getEntityManager()->remove($eventData);
        $this->getEntityManager()->flush($eventData);
    }

    /**
     * @param mixed $uuid
     * @return EventData
     */
    public function find($uuid)
    {
        return $this->findOneBy(['uuid' => $uuid]);
    }
}
