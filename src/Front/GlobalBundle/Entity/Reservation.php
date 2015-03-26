<?php

namespace Front\GlobalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reservation
 *
 * @ORM\Table(name="wt_reservation")
 * @ORM\Entity(repositoryClass="Front\GlobalBundle\Entity\ReservationRepository")
 */
class Reservation
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
     * @ORM\Column(name="homePhone", type="string", length=255)
     */
    private $homePhone;

    /**
     * @var string
     *
     * @ORM\Column(name="workPhone", type="string", length=255)
     */
    private $workPhone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;

    /**
     * @var integer
     *
     * @ORM\Column(name="idhotel", type="integer")
     */
    private $idhotel;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="postalCode", type="integer")
     */
    private $postalCode;

    /**
     * @var string
     *
     * @ORM\Column(name="currency", type="string", length=5)
     */
    private $currency;
    
    /**
     * @var string
     *
     * @ORM\Column(name="countryCode", type="string", length=5)
     */
    private $countryCode;
    
    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=5)
     */
    private $city;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="arrivaleDate", type="date")
     */
    private $arrivaleDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="departureDate", type="date")
     */
    private $departureDate;

    /**
     * @var string
     *
     * @ORM\Column(name="rateKey", type="string", length=255)
     */
    private $rateKey;

    /**
     * @var string
     *
     * @ORM\Column(name="roomTypeCode", type="string", length=255)
     */
    private $roomTypeCode;

    /**
     * @var string
     *
     * @ORM\Column(name="rateCode", type="string", length=255)
     */
    private $rateCode;

    /**
     * @var string
     *
     * @ORM\Column(name="chargeableRate", type="decimal", precision=11, scale=3)
     */
    private $chargeableRate;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=11, scale=3,nullable=true)
     */
    private $montant;
    
    /**
     * @var string
     *
     * @ORM\Column(name="param", type="string", length=255, nullable=true)
     */
    private $param;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateReservation", type="datetime")
     */
    private $dateReservation;

    /**
     * @ORM\OneToMany(targetEntity="Room", mappedBy="reservation")
     */
    protected $rooms;

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
     * @return Reservation
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
     * @return Reservation
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
     * Set homePhone
     *
     * @param string $homePhone
     * @return Reservation
     */
    public function setHomePhone($homePhone)
    {
        $this->homePhone=$homePhone;

        return $this;
    }

    /**
     * Get homePhone
     *
     * @return string 
     */
    public function getHomePhone()
    {
        return $this->homePhone;
    }

    /**
     * Set workPhone
     *
     * @param string $workPhone
     * @return Reservation
     */
    public function setWorkPhone($workPhone)
    {
        $this->workPhone=$workPhone;

        return $this;
    }

    /**
     * Get workPhone
     *
     * @return string 
     */
    public function getWorkPhone()
    {
        return $this->workPhone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Reservation
     */
    public function setEmail($email)
    {
        $this->email=$email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Reservation
     */
    public function setAddress($address)
    {
        $this->address=$address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set idhotel
     *
     * @param integer $idhotel
     * @return Reservation
     */
    public function setIdhotel($idhotel)
    {
        $this->idhotel=$idhotel;

        return $this;
    }

    /**
     * Get idhotel
     *
     * @return integer 
     */
    public function getIdhotel()
    {
        return $this->idhotel;
    }

    /**
     * Set currency
     *
     * @param string $currency
     * @return Reservation
     */
    public function setCurrency($currency)
    {
        $this->currency=$currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return string 
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set arrivaleDate
     *
     * @param \DateTime $arrivaleDate
     * @return Reservation
     */
    public function setArrivaleDate($arrivaleDate)
    {
        $this->arrivaleDate=$arrivaleDate;

        return $this;
    }

    /**
     * Get arrivaleDate
     *
     * @return \DateTime 
     */
    public function getArrivaleDate()
    {
        return $this->arrivaleDate;
    }

    /**
     * Set departureDate
     *
     * @param \DateTime $departureDate
     * @return Reservation
     */
    public function setDepartureDate($departureDate)
    {
        $this->departureDate=$departureDate;

        return $this;
    }

    /**
     * Get departureDate
     *
     * @return \DateTime 
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * Set rateKey
     *
     * @param string $rateKey
     * @return Reservation
     */
    public function setRateKey($rateKey)
    {
        $this->rateKey=$rateKey;

        return $this;
    }

    /**
     * Get rateKey
     *
     * @return string 
     */
    public function getRateKey()
    {
        return $this->rateKey;
    }

    /**
     * Set roomTypeCode
     *
     * @param string $roomTypeCode
     * @return Reservation
     */
    public function setRoomTypeCode($roomTypeCode)
    {
        $this->roomTypeCode=$roomTypeCode;

        return $this;
    }

    /**
     * Get roomTypeCode
     *
     * @return string 
     */
    public function getRoomTypeCode()
    {
        return $this->roomTypeCode;
    }

    /**
     * Set rateCode
     *
     * @param string $rateCode
     * @return Reservation
     */
    public function setRateCode($rateCode)
    {
        $this->rateCode=$rateCode;

        return $this;
    }

    /**
     * Get rateCode
     *
     * @return string 
     */
    public function getRateCode()
    {
        return $this->rateCode;
    }

    /**
     * Set chargeableRate
     *
     * @param string $chargeableRate
     * @return Reservation
     */
    public function setChargeableRate($chargeableRate)
    {
        $this->chargeableRate=$chargeableRate;

        return $this;
    }

    /**
     * Get chargeableRate
     *
     * @return string 
     */
    public function getChargeableRate()
    {
        return $this->chargeableRate;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rooms=new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add rooms
     *
     * @param \Front\GlobalBundle\Entity\Room $rooms
     * @return Reservation
     */
    public function addRoom(\Front\GlobalBundle\Entity\Room $rooms)
    {
        $this->rooms[]=$rooms;

        return $this;
    }

    /**
     * Remove rooms
     *
     * @param \Front\GlobalBundle\Entity\Room $rooms
     */
    public function removeRoom(\Front\GlobalBundle\Entity\Room $rooms)
    {
        $this->rooms->removeElement($rooms);
    }

    /**
     * Get rooms
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Set montant
     *
     * @param string $montant
     * @return Reservation
     */
    public function setMontant($montant)
    {
        $this->montant=$montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string 
     */
    public function getMontant()
    {
        return $this->montant;
    }


    /**
     * Set postalCode
     *
     * @param integer $postalCode
     * @return Reservation
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return integer 
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     * @return Reservation
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string 
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Reservation
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set param
     *
     * @param string $param
     * @return Reservation
     */
    public function setParam($param)
    {
        $this->param = $param;

        return $this;
    }

    /**
     * Get param
     *
     * @return string 
     */
    public function getParam()
    {
        return $this->param;
    }

    /**
     * Set dateReservation
     *
     * @param \DateTime $dateReservation
     * @return Reservation
     */
    public function setDateReservation($dateReservation)
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    /**
     * Get dateReservation
     *
     * @return \DateTime 
     */
    public function getDateReservation()
    {
        return $this->dateReservation;
    }
}
