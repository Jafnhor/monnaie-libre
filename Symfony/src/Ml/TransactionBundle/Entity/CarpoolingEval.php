<?php

namespace Ml\TransactionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarpoolingEval
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ml\TransactionBundle\Entity\CarpoolingEvalRepository")
 */
class CarpoolingEval extends Evaluation
{

    /**
	  * @ORM\ManyToOne(targetEntity="Ml\ServiceBundle\Entity\Carpooling")
	  */
    private $service;
    

    /**
     * Set service
     *
     * @param string $service
     * @return CarpoolingEval
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

}
