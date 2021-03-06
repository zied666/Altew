<?php

namespace Front\GlobalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Room
 *
 * @ORM\Table(name="wt_room")
 * @ORM\Entity
 */
class Room
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ocupation", type="string", length=255)
     */
    private $ocupation;

    /**
     * @var integer
     *
     * @ORM\Column(name="bed", type="integer")
     */
    private $bed;

    /**
     * @ORM\ManyToOne(targetEntity="Reservation", inversedBy="rooms")
     * @ORM\JoinColumn(name="id_reservation", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $reservation;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return Room
     */
    public function setFirstName($firstName)
    {
        $this->firstName=$firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return Room
     */
    public function setLastName($lastName)
    {
        $this->lastName=$lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set bed
     *
     * @param integer $bed
     * @return Room
     */
    public function setBed($bed)
    {
        $this->bed=$bed;

        return $this;
    }

    /**
     * Get bed
     *
     * @return integer 
     */
    public function getBed()
    {
        return $this->bed;
    }


    /**
     * Set reservation
     *
     * @param \Front\GlobalBundle\Entity\Reservation $reservation
     * @return Room
     */
    public function setReservation(\Front\GlobalBundle\Entity\Reservation $reservation = null)
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * Get reservation
     *
     * @return \Front\GlobalBundle\Entity\Reservation 
     */
    public function getReservation()
    {
        return $this->reservation;
    }
    
    
    public function __toString()
    {
        return $this->firstName." ".$this->lastName;
    }

    /**
     * Set ocupation
     *
     * @param string $ocupation
     * @return Room
     */
    public function setOcupation($ocupation)
    {
        $this->ocupation = $ocupation;

        return $this;
    }

    /**
     * Get ocupation
     *
     * @return string 
     */
    public function getOcupation()
    {
        return $this->ocupation;
    }
}
