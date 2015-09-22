<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Breadcrumbs
* 
* Author:  Benjamin Carrera
* Created:  9.24.2014 
* 
* Description:  Class to create dynamic breadcrumbs according to the page setup in the database
* 
*/

set_include_path(get_include_path() . PATH_SEPARATOR . realpath(dirname(__FILE__) . '/../third_party/google/src'));
@session_start();
require_once ('Google/autoload.php');

class google{
	
	public $client_id;
	public $client_secret;
	public $redirect_uri;
	public $client;
	public $service;
	
	function google(){
		
		/************************************************
		ATTENTION: Fill in these values! Make sure
		the redirect URI is to this page, e.g:
		http://localhost:8080/user-example.php
		************************************************/
 		$this->client_id		= '879668237544-g4c839a4m83osnot21kdah1ogqndd5k3.apps.googleusercontent.com';
 		$this->client_secret 	= 'DvnUsiY0MOTAjljeEoirTij4';
 		$this->redirect_uri 	= 'http://reparadores.mx/oauth2callback';
		
		$this->client = new Google_Client();
		
		$this->client->setClientId($this->client_id);
		$this->client->setClientSecret($this->client_secret);
		$this->client->setRedirectUri($this->redirect_uri);
		
		$this->client->setScopes(array(
		    	'https://www.google.com/m8/feeds',
		    	'https://www.googleapis.com/auth/contacts.readonly'
			)
		);
		
		if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
			$this->client->setAccessToken($_SESSION['access_token']);
		}
			
	}
	
	function oauth2(){

		if(isset($_GET['code'])) {
			// Handle step 2 of the OAuth 2.0 dance - code exchange
			$this->client->authenticate($_GET['code']);
			$access_token = $this->client->getAccessToken();
			
			$_SESSION['access_token'] = $access_token;
			$this->client->setAccessToken($access_token);
		} elseif(!isset($_SESSION['access_token']) || empty($_SESSION['access_token'])){
			// Handle step 1 of the OAuth 2.0 dance - redirect to Google
		  	header('Location: ' . $this->client->createAuthUrl());
			exit;
		}

	}
	
	function logout(){
		
		unset($_SESSION['access_token']);
		
	}
	
	function mirrorService(){
		
		$service = $this->service = new Google_Service_Mirror($this->client);
		return $service;

	}
	
	function printAllContacts(){
		
		$return = '';
		
		$access_token = json_decode($this->client->getAccessToken())->access_token;
		$url = 'https://www.google.com/m8/feeds/contacts/default/full?alt=json&v=3.0&oauth_token='.$access_token;
		
		$ch = curl_init();  
 
	    curl_setopt($ch,CURLOPT_URL,$url);
	    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	 
	    $output=curl_exec($ch);
	 
	    curl_close($ch);
		$j = json_decode($output);
		if(!isset($j->feed)){
			redirect('oauth2callback');
			exit;
		}
  		//echo print_r($j);

		$return .= '<form method="post" action="' . base_url() . 'compartir/enviarGoogle"><ul>';
		$return .= '<input type="hidden" value="' . $j->feed->author[0]->email->{'$t'} . '" name="invito" />';
		
		foreach($j->feed->entry as $contact){
			
			$return .= '<li>';
			$return .= '<input type="hidden" value="' . $contact->title->{'$t'} . '" name="nombre[]" />';
			$return .= '<input type="checkbox" value="' . $contact->{'gd$email'}[0]->address . '" name="email[]" />' . $contact->{'gd$email'}[0]->address;
			$return .= '</li>';
			
		}
		
		$return .= '</ul>
			<input type="image" src="http://reparadores.mx/assets/graphics/enviarCompartir.png">
		</form>';
		
		return $return;
		
	}
	

}
