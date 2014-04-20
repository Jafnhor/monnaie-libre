<?php

namespace Ml\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ml\ServiceBundle\Entity\Service;

/**
 * Carpooling
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ml\ServiceBundle\Entity\CarpoolingRepository")
 */
class Carpooling extends Service
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="departure", type="string", length=255)
     */
    protected $departure;

    /**
     * @var string
     *
     * @ORM\Column(name="arrival", type="string", length=255)
     */
    protected $arrival;

    /**
     * @var string
     *
     * @ORM\Column(name="meetingPoint", type="string", length=255)
     */
    protected $meetingPoint;

    /**
     * @var string
     *
     * @ORM\Column(name="arrivalPoint", type="string", length=255)
     */
    protected $arrivalPoint;

    /**
     * @var string
     *
     * @ORM\Column(name="bends", type="string", length=255, nullable=true)
     */
    protected $bends;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="departureDate", type="datetime")
     */
    protected $departureDate;

    /**
     * @var time
     *
     * @ORM\Column(name="estimatedDuration", type="time")
     */
    protected $estimatedDuration;

    /**
     * @var integer
     *
     * @ORM\Column(name="estimatedDistance", type="integer")
     */
    protected $estimatedDistance;

    /**
     * @var string
     *
     * @ORM\Column(name="packageTransport", type="string", length=255, nullable=true)
     */
    protected $packageTransport;

    /**
     * @var integer
     *
     * @ORM\Column(name="packageSize", type="integer", nullable=true)
     */
    protected $packageSize;

    /**
     * @var string
     *
     * @ORM\Column(name="car", type="string", length=255)
     */
    protected $car;

    /**
     * @var boolean
     *
     * @ORM\Column(name="smoker", type="boolean")
     */
    protected $smoker;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pets", type="boolean")
     */
    protected $pets;

    /**
     * @var boolean
     *
     * @ORM\Column(name="music", type="boolean")
     */
    protected $music;
	
	/**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;
	
	public function __construct() {
		parent::__construct();
		$this->type = "Carpooling";
		$this->departureDate = date_create(date('Y-m-d'));
	}

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
     * Set departure
     *
     * @param string $departure
     * @return Carpooling
     */
    public function setDeparture($departure)
    {
        $this->departure = $departure;
    
        return $this;
    }

    /**
     * Get departure
     *
     * @return string 
     */
    public function getDeparture()
    {
        return $this->departure;
    }

    /**
     * Set arrival
     *
     * @param string $arrival
     * @return Carpooling
     */
    public function setArrival($arrival)
    {
        $this->arrival = $arrival;
    
        return $this;
    }

    /**
     * Get arrival
     *
     * @return string 
     */
    public function getArrival()
    {
        return $this->arrival;
    }

    /**
     * Set meetingPoint
     *
     * @param string $meetingPoint
     * @return Carpooling
     */
    public function setMeetingPoint($meetingPoint)
    {
        $this->meetingPoint = $meetingPoint;
    
        return $this;
    }

    /**
     * Get meetingPoint
     *
     * @return string 
     */
    public function getMeetingPoint()
    {
        return $this->meetingPoint;
    }

    /**
     * Set arrivalPoint
     *
     * @param string $arrivalPoint
     * @return Carpooling
     */
    public function setArrivalPoint($arrivalPoint)
    {
        $this->arrivalPoint = $arrivalPoint;
    
        return $this;
    }

    /**
     * Get arrivalPoint
     *
     * @return string 
     */
    public function getArrivalPoint()
    {
        return $this->arrivalPoint;
    }

    /**
     * Set bends
     *
     * @param string $bends
     * @return Carpooling
     */
    public function setBends($bends)
    {
        $this->bends = $bends;
    
        return $this;
    }

    /**
     * Get bends
     *
     * @return string 
     */
    public function getBends()
    {
        return $this->bends;
    }

    /**
     * Set departureDate
     *
     * @param \DateTime $departureDate
     * @return Carpooling
     */
    public function setDepartureDate($departureDate)
    {
        $this->departureDate = $departureDate;
    
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
     * Set estimatedDuration
     *
     * @param integer $estimatedDuration
     * @return Carpooling
     */
    public function setEstimatedDuration($estimatedDuration)
    {
        $this->estimatedDuration = $estimatedDuration;
    
        return $this;
    }

    /**
     * Get estimatedDuration
     *
     * @return integer 
     */
    public function getEstimatedDuration()
    {
        return $this->estimatedDuration;
    }

    /**
     * Set estimatedDistance
     *
     * @param integer $estimatedDistance
     * @return Carpooling
     */
    public function setEstimatedDistance($estimatedDistance)
    {
        $this->estimatedDistance = $estimatedDistance;
    
        return $this;
    }

    /**
     * Get estimatedDistance
     *
     * @return integer 
     */
    public function getEstimatedDistance()
    {
        return $this->estimatedDistance;
    }

    /**
     * Set packageTransport
     *
     * @param string $packageTransport
     * @return Carpooling
     */
    public function setPackageTransport($packageTransport)
    {
        $this->packageTransport = $packageTransport;
    
        return $this;
    }

    /**
     * Get packageTransport
     *
     * @return string 
     */
    public function getPackageTransport()
    {
        return $this->packageTransport;
    }

    /**
     * Set packageSize
     *
     * @param integer $packageSize
     * @return Carpooling
     */
    public function setPackageSize($packageSize)
    {
        $this->packageSize = $packageSize;
    
        return $this;
    }

    /**
     * Get packageSize
     *
     * @return integer 
     */
    public function getPackageSize()
    {
        return $this->packageSize;
    }

    /**
     * Set car
     *
     * @param string $car
     * @return Carpooling
     */
    public function setCar($car)
    {
        $this->car = $car;
    
        return $this;
    }

    /**
     * Get car
     *
     * @return string 
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * Set smoker
     *
     * @param boolean $smoker
     * @return Carpooling
     */
    public function setSmoker($smoker)
    {
        $this->smoker = $smoker;
    
        return $this;
    }

    /**
     * Get smoker
     *
     * @return boolean 
     */
    public function getSmoker()
    {
        return $this->smoker;
    }

    /**
     * Set pets
     *
     * @param boolean $pets
     * @return Carpooling
     */
    public function setPets($pets)
    {
        $this->pets = $pets;
    
        return $this;
    }

    /**
     * Get pets
     *
     * @return boolean 
     */
    public function getPets()
    {
        return $this->pets;
    }

    /**
     * Set music
     *
     * @param boolean $music
     * @return Carpooling
     */
    public function setMusic($music)
    {
        $this->music = $music;
    
        return $this;
    }

    /**
     * Get music
     *
     * @return boolean 
     */
    public function getMusic()
    {
        return $this->music;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Carpooling
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
}
