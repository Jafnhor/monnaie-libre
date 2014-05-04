<?php

namespace Ml\ServiceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServiceUser
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ml\ServiceBundle\Entity\SaleUserRepository")
 */
class SaleUser
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
	  * @ORM\ManyToOne(targetEntity="Ml\ServiceBundle\Entity\Sale")
	  */
	private $sale;

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
	
	// Getter et setter pour l'entité Sale
	  public function setSale(\Ml\ServiceBundle\Entity\Sale $sale) {
		$this->sale = $sale;
	  }
	  public function getSale() {
		return $this->sale;
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
