<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

public function __construct()
{
	parent::__construct();
	require_once APPPATH.'third_party/src/Google_Client.php';
	require_once APPPATH.'third_party/src/contrib/Google_Oauth2Service.php';
}

	public function index()
	{
		$this->load->view('login');
	}

	public function google_login()
	{
		$clientId = '813257418181-raeku7fc4jubvdvf2v7cs15bleoe0nsm.apps.googleusercontent.com'; //Google client ID
		$clientSecret = '0N9wEP0q-aWa2rnM4edcy_xS'; //Google client secret
		$redirectURL = base_url() . 'Login/google_login/';

		//Call Google API
		$gClient = new Google_Client();
		$gClient->setApplicationName('Login');
		$gClient->setClientId($clientId);
		$gClient->setClientSecret($clientSecret);
		$gClient->setRedirectUri($redirectURL);
		$google_oauthV2 = new Google_Oauth2Service($gClient);

		if(isset($_GET['code']))
		{
			$gClient->authenticate($_GET['code']);
			$_SESSION['token'] = $gClient->getAccessToken();
			header('Location: ' . filter_var($redirectURL, FILTER_SANITIZE_URL));
		}

		if (isset($_SESSION['token']))
		{
			$gClient->setAccessToken($_SESSION['token']);
		}

		if ($gClient->getAccessToken()) {
      $userProfile = $google_oauthV2->userinfo->get();
			echo "<pre>";
			print_r($userProfile);
			die;
        }
		else
		{
        $url = $gClient->createAuthUrl();
		    header("Location: $url");
        exit;
    }
	}
}
