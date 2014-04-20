<?php

namespace Ml\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ml\ServiceBundle\Entity\Service;

/**
 * CouchSurfing
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ml\ServiceBundle\Entity\CouchSurfingRepository")
 */
class CouchSurfing extends Service
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
     * @ORM\Column(name="location", type="string", length=255)
     */
    protected $location;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateStart", type="date")
     */
    protected $dateStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEnd", type="date")
     */
    protected $dateEnd;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hourStart", type="time")
     */
    protected $hourStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hourEnd", type="time")
     */
    protected $hourEnd;

    /**
     * @var boolean
     *
     * @ORM\Column(name="limitGuest", type="boolean")
     */
    protected $limitGuest;

    /**
     * @var integer
     *
     * @ORM\Column(name="limitNumberOfGuest", type="integer", nullable=true)
     */
    protected $limitNumberOfGuest;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $type;

    public function __construct() {
        parent::__construct();
        $this->type = "CouchSurfing";
        $this->dateStart = date_create(date('Y-m-d'));
        $this->dateEnd = date_create(date('Y-m-d'));
		$this->limitNumberOfGuest = NULL;
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
     * Set location
     *
     * @param string $location
     * @return CouchSurfing
     */
    public function setLocation($location)
    {
        $this->location = $location;
    
        return $this;
    }

    /**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     * @return CouchSurfing
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;
    
        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime 
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     * @return CouchSurfing
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;
    
        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime 
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set hourStart
     *
     * @param \DateTime $hourStart
     * @return CouchSurfing
     */
    public function setHourStart($hourStart)
    {
        $this->hourStart = $hourStart;
    
        return $this;
    }

    /**
     * Get hourStart
     *
     * @return \DateTime 
     */
    public function getHourStart()
    {
        return $this->hourStart;
    }

    /**
     * Set hourEnd
     *
     * @param \DateTime $hourEnd
     * @return CouchSurfing
     */
    public function setHourEnd($hourEnd)
    {
        $this->hourEnd = $hourEnd;
    
        return $this;
    }

    /**
     * Get hourEnd
     *
     * @return \DateTime 
     */
    public function getHourEnd()
    {
        return $this->hourEnd;
    }

    /**
     * Set limitGuest
     *
     * @param boolean $limitGuest
     * @return CouchSurfing
     */
    public function setLimitGuest($limitGuest)
    {
        $this->limitGuest = $limitGuest;
    
        return $this;
    }

    /**
     * Get limitGuest
     *
     * @return boolean 
     */
    public function getLimitGuest()
    {
        return $this->limitGuest;
    }

    /**
     * Set limitNumberOfGuest
     *
     * @param integer $limitNumberOfGuest
     * @return CouchSurfing
     */
    public function setLimitNumberOfGuest($limitNumberOfGuest)
    {
        $this->limitNumberOfGuest = $limitNumberOfGuest;
    
        return $this;
    }

    /**
     * Get limitNumberOfGuest
     *
     * @return integer 
     */
    public function getLimitNumberOfGuest()
    {
        return $this->limitNumberOfGuest;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return CouchSurfing
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
