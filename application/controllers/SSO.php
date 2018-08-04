<?php
require_once APPPATH.'third_party//vendor/autoload.php';
use Auth0\SDK\Auth0;
class SSO extends CI_Controller {

    protected $_data = array('div_alert' => 'container','type' => null,'url' => null,'content' => null);

    protected $_uid = '';

    protected $_pwd = '';

		// Hàm khởi tạo
		function __construct() {
				// Gọi đến hàm khởi tạo của cha
				parent::__construct();
        $this->auth0 = new Auth0([
          'domain' => 'ngthuc.auth0.com',
          'client_id' => 'rYuzanR_2s25Wpy6wXqgnJvgHLy-njY0',
          'client_secret' => 'wMmawuSq2b_scPCBBIhOUsJYThRZzKGqITr_J4WPvYG4Cgznacaef1ETl1mkIKCQ',
          'redirect_uri' => 'http://localhost/DCIS/sso/auth',
          'audience' => 'https://ngthuc.auth0.com/userinfo',
          'responseType' => 'code',
          'scope' => 'openid email profile',
          'persist_id_token' => true,
          'persist_access_token' => true,
          'persist_refresh_token' => true,
          // 'prompt' => none,
        ]);

        $this->_data['url'] = base_url();
		}

		public function index()
		{
        // $this->_data['subview'] = 'alert/load_alert_view';
        // $this->_data['titlePage'] = 'Xác thực';
        // $this->_data['type'] = 'warning';
        // $this->_data['url'] = base_url();
        // $this->_data['content'] = 'Access Denied';
        // $this->load->view('main.php', $this->_data);
        if(!isset($_SESSION['user'])) {
          echo '<a href="http://localhost/DCIS/sso/login/">Login</a>';
        } else {
          echo '
          Username: '.$_SESSION['user']['nickname'].'<br />
          Name: '.$_SESSION['user']['name'].'<br />
          <img src="'.$_SESSION['user']['picture'].'" width="150px" /><br />
          Email: '.$_SESSION['user']['email'].'<br />
          Last login: '.date($_SESSION['user']['updated_at']).'<br />
          <a href="http://localhost/DCIS/sso/logout/">Logout</a>
          ';
        }
		}

    public function auth(){
      $userInfo = $this->auth0->getUser();
      // var_dump($userInfo);
      $this->session->set_userdata('user', $userInfo);
      $redirectTo = 'http://localhost/DCIS';
      header('Location: ' . $redirectTo);
      // if (!$userInfo) {
      //     // We have no user info
      //     // redirect to Login
      //     $loginTo = 'http://localhost/itc';
      //     header('Location: ' . $loginTo);
      // } else {
      //     // User is authenticated
      //     // Say hello to $userInfo['name']
      //     // print logout button
      //     $_SESSION['userinfo'] = $userInfo;
      //     // var_dump($_SESSION);
      //     echo '<body onload="window.history.go(-3);"></body>';
      // }
    }

    public function login()
		{
        $this->auth0->login();
		}

    public function logout()
		{
        $this->auth0->logout();
        $this->session->unset_userdata('user');	// Unset session of user
        $this->session->unset_userdata('access');	// Unset session of user
        $logoutTo = 'http://localhost/DCIS';
        header('Location: ' . $logoutTo);
		}
}
