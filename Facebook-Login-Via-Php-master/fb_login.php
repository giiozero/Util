<?PHP
//Facebook Class
require_once __DIR__ . '/facebook/autoload.php'; // change path as needed

$fb = new Facebook\Facebook([
  'app_id' => '{app-id}', 
  'app_secret' => '{secret-id}',
  'default_graph_version' => 'v2.10',
  'persistent_data_handler'=>'session'
  ]);

$helper = $fb->getRedirectLoginHelper();
if(!ISSET($_SESSION['FBRLH_state'])) {
	$_SESSION['FBRLH_state']=$_GET['state'];
}
try {
	if(ISSET($_SESSION['face_access_token'])) {
		$accessToken = $_SESSION['face_access_token'];
		echo 'Tem';
	} else {
		$accessToken = $helper->getAccessToken();
		echo 'Não tem';
	}
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'Graph returned an error: ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'Facebook SDK returned an error: ' . $e->getMessage();
  exit;
}

if (isset($_GET['state'])) {
    $helper->getPersistentDataHandler()->set('state', $_GET['state']);
}
if (!isset($accessToken)) {//Usuário Deslogado
	$permissions = ['email']; // Optional permissions
	$loginUrl = $helper->getLoginUrl('http://ppadeiro.sytes.net/backup/write/3/index.php', $permissions);
	echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
} else {
	if(isset($_SESSION['face_access_token'])) {
		$fb->setDefaultAccessToken($_SESSION['face_access_token']);
	} else {
		$_SESSION['face_access_token'] = (string) $accessToken;
		$oAuth2Client = $fb->getOAuth2Client();
		$_SESSION['face_access_token'] = $oAuth2Client->getLongLivedAccessToken($_SESSION['face_access_token']);
	}
	try {
	  $response = $fb->get('/me?fields=name,picture, email');
	  $user = $response->getGraphUser();
	  echo $user->getName().' - <img src="'.$user->getPicture()->getUrl().'">';
	  //unset($_SESSION['face_access_token']);
	  

	} catch(Facebook\Exceptions\FacebookResponseException $e) {
	  echo 'Graph returned an error: ' . $e->getMessage();
	  
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
	  //echo 'Facebook SDK returned an error: ' . $e->getMessage();
	  echo '<meta HTTP-EQUIV="Refresh" CONTENT="0; URL=http://ppadeiro.sytes.net/backup/write/3/index.php">';
	}
}
?>
