<?php

namespace Ml\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceUser
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ml\ServiceBundle\Entity\CouchSurfingUserRepository")
 */
class CouchSurfingUser
{
    /**
	  * @ORM\Id
	  * @ORM\ManyToOne(targetEntity="Ml\ServiceBundle\Entity\CouchSurfing")
	  */
	private $couchsurfing;

	/**
	  * @ORM\Id
	  * @ORM\ManyToOne(targetEntity="Ml\UserBundle\Entity\User")
	  */
	private $applicant;
	
	/**
     * @var \DateTime
     *
     * @ORM\Column(name="dateReservation", type="datetime")
     */
    private $dateReservation;
	
	public function __construct() {
		$this->dateReservation = date_create(date('Y-m-d'));
	}
	

    /**
     * Set dateReservation
     *
     * @param \DateTime $dateReservation
     * @return CouchSurfingUser
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

    /**
     * Set couchsurfing
     *
     * @param \Ml\ServiceBundle\Entity\CouchSurfing $couchsurfing
     * @return CouchSurfingUser
     */
    public function setCouchsurfing(\Ml\ServiceBundle\Entity\CouchSurfing $couchsurfing)
    {
        $this->couchsurfing = $couchsurfing;
    
        return $this;
    }

    /**
     * Get couchsurfing
     *
     * @return \Ml\ServiceBundle\Entity\CouchSurfing 
     */
    public function getCouchsurfing()
    {
        return $this->couchsurfing;
    }

    /**
     * Set applicant
     *
     * @param \Ml\UserBundle\Entity\User $applicant
     * @return CouchSurfingUser
     */
    public function setApplicant(\Ml\UserBundle\Entity\User $applicant)
    {
        $this->applicant = $applicant;
    
        return $this;
    }

    /**
     * Get applicant
     *
     * @return \Ml\UserBundle\Entity\User 
     */
    public function getApplicant()
    {
        return $this->applicant;
    }
}
