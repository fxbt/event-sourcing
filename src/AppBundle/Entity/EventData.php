<?php

namespace AppBundle\Entity;

use AppBundle\Event\EventInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventStorage")
 */
class EventData
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=23, unique=true)
     */
    protected $uuid;

    /**
     * @ORM\Column(type="object")
     */
    protected $data;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $uuid
     *
     * @return EventData
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param EventInterface[] $data
     *
     * @return EventData
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return EventInterface[]
     */
    public function getData()
    {
        return $this->data;
    }
}