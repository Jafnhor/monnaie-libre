<?php

namespace Ml\libServices;

/**
 * Check if a session is set (user is authenticated)
 */
class SessionTested{

	/**
	 * Check if a session is set (user is authenticated)
	 * @param array $req
	 * @return string $login
	 */
	public function sessionExist($req){
		// On récupère la requête
		$session = $req->getSession();		
		$login = $session->get('login');

		/* Si on est pas logger -> redirection vers la page d'inscription */
		if ($login == NULL) {
			throw new \Exception("Sorry, you're not logged yet...");
		}
		
		return $login;

	}
}
