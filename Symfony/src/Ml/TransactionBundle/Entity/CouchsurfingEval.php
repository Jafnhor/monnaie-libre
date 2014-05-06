<?php

namespace Ml\TransactionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CouchsurfingEval
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ml\TransactionBundle\Entity\CouchsurfingEvalRepository")
 */
class CouchsurfingEval extends Evaluation
{

    /**
	  * @ORM\ManyToOne(targetEntity="Ml\ServiceBundle\Entity\CouchSurfing")
	  */
    private $service;

    /**
     * Set service
     *
     * @param string $service
     * @return CouchsurfingEval
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return string 
     */
    public function getService()
    {
        return $this->service;
    }
}
