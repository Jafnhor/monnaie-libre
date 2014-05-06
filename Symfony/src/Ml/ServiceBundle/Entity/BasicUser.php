<?php

namespace Ml\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceUser
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ml\ServiceBundle\Entity\BasicUserRepository")
 */
class BasicUser
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
	  * @ORM\ManyToOne(targetEntity="Ml\ServiceBundle\Entity\Basic")
	  */
	private $basic;

	/**
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
	
	// Getter et setter pour l'entité Basic
	  public function setBasic(\Ml\ServiceBundle\Entity\Basic $basic) {
		$this->basic = $basic;
	  }
	  public function getBasic() {
		return $this->basic;
	  }

    // Getter et setter pour l'entité User
	  public function setApplicant(\Ml\UserBundle\Entity\User $applicant) {
		$this->applicant = $applicant;
	  }
	  public function getApplicant() {
		return $this->applicant;
	  }

    /**
     * Set dateReservation
     *
     * @param \DateTime $dateReservation
     * @return DonnerAvis
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
