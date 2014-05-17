<?php

namespace Ml\libServices;

class ReservationTested {
	/*
	Vérifie si l'utilisateur peut encore réservé.
	*/
	
	/**
	 * Return true only if the $user can reserve the $service
	 */
	public function canReserve($user,$service,$em) {	    	    
	    return $this->canPay($user,$service->getPrice(),$em); 
	}
	
	public function canPay($user,$amount,$em) {	    
	    $basicReserved = $em->getRepository('MlServiceBundle:BasicUser')
						   ->findByApplicant($user);
		
		$carpoolingsReserved = $em->getRepository('MlServiceBundle:CarpoolingUser')
						   ->findByApplicant($user);
							   
	    $couchsurfingsReserved = $em->getRepository('MlServiceBundle:CouchSurfingUser')
						     ->findByApplicant($user);	

		$salesReserved = $em->getRepository('MlServiceBundle:SaleUser')
				     ->findByApplicant($user);
				     
	    $sum = 0;
	    
	    if($basicReserved != null) {
	        foreach ($basicReserved as $reservation) {
	            $sum += $reservation->getBasic()->getPrice();
	        }
	    }
	    	    
	    if($carpoolingsReserved != null) {
	        foreach ($carpoolingsReserved as $reservation) {
	            $sum += $reservation->getCarpooling()->getPrice();
	        }
	    }
	    if($couchsurfingsReserved != null) {
	        foreach ($couchsurfingsReserved as $reservation) {
	            $sum += $reservation->getCouchsurfing()->getPrice();
	        }
	    }
	    if($salesReserved != null) {
	        foreach ($salesReserved as $reservation) {
	            $sum += $reservation->getSale()->getPrice();
	        }
	    }
	    	    
	    $sum += $amount;
	    
	    return $sum <= $user->getAccount()->getAmount(); 
	}
}
