<?php

session_start();
require_once 'vendor/autoload.php';

$client = new Google_Client();
$client->setClientId('1063662202942-dsok6pt0aq4sgu50ov0u0pse73tgvv1a.apps.googleusercontent.com');
$client->setClientSecret('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'); 
$client->setRedirectUri('http://localhost/tms/google-callback.php');
$client->addScope("email");
$client->addScope("profile");

$login_url = $client->createAuthUrl();

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);

        $google_oauth = new Google_Service_Oauth2($client);
        $user_info = $google_oauth->userinfo->get();

        $_SESSION['signin'] = $user_info->email;
        $_SESSION['name'] = $user_info->name;

        echo "<script type='text/javascript'> document.location = 'package-list.php'; </script>";
        exit();
    }
}

//if(isset($_POST['signin']))
//{
//$email=$_POST['email'];
//$password=md5($_POST['password']);
//$sql ="SELECT EmailId,Password FROM tblusers WHERE EmailId=:email and Password=:password";
//$query= $dbh -> prepare($sql);
//$query-> bindParam(':email', $email, PDO::PARAM_STR);
//$query-> bindParam(':password', $password, PDO::PARAM_STR);
//$query-> execute();
//$results=$query->fetchAll(PDO::FETCH_OBJ);
//if($query->rowCount() > 0)
//{
//$_SESSION['login']=$_POST['email'];
//echo "<script type='text/javascript'> document.location = 'package-list.php'; </script>";
//} else{
	
//	echo "<script>alert('Invalid Details');</script>";

//}

//}

if(isset($_POST['signin']))
{
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql ="SELECT EmailId,Password FROM admin WHERE EmailId=:email";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result && password_verify($_POST['password'], $result['Password'])) 
	//login successful
	{
        $_SESSION['alogin'] = $email;
        echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
    } else {
        echo "<script>alert('Invalid Details');</script>";
    }
}


?>

<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content modal-info">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>						
						</div>
						<div class="modal-body modal-spa">
							<div class="login-grids">
								<div class="login">
										<div class="login-left">
												<ul>
													<li><a class="fb" href="#"><i></i>Facebook</a></li>
													<li><a class="goog" href="<?php echo htmlspecialchars($login_url); ?>"><i></i>Google</a></li>

													
												</ul>
											</div>
									<div class="login-right">
										<form method="post">
											<h3>Signin with your account </h3>
	<input type="text" name="email" id="email" placeholder="Enter your Email"  required="">	
	<input type="password" name="password" id="password" placeholder="Password" value="" required="">	
											<h4><a href="forgot-password.php">Forgot password</a></h4>
											
											<input type="submit" name="signin" value="SIGNIN">
										</form>
									</div>
									<div class="clearfix"></div>								
								</div>
								<p>By logging in you agree to our <a href="page.php?type=terms">Terms and Conditions</a> and <a href="page.php?type=privacy">Privacy Policy</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>