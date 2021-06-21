<?php  

	$pageTitle = 'Login';

	include 'init.php';
	include $tpl . 'header.php';

	$nonavbar = '';
	

	session_start();

	if (isset($_SESSION['email']))
	{
		header('Location: dashbord.php');
	}

	if ($_SERVER['REQUEST_METHOD'] == 'POST')
	{
		$email = $_POST['email'];
		$pass = $_POST['pass'];

		$stmt = $bdd->prepare('select email, password from User where email = ? and password = ? and groupId = 1');
		$stmt->execute(array($email, $pass));
		$count = $stmt->rowcount();
		if ($count > 0)
		{
			$_SESSION['email'] = $email;
			header('Location: dashbord.php');

		}
	}
?>
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<h4 class="text-center">Login page</h4>
		<input class="form-control" type="email" name="email" placeholder="Email" autocomplete="off">
		<input class="form-control" type="password" name="pass" placeholder="Mot de passe" autocomplete="new-password">
		<input  class="form-control btn btn-primary" type="submit" value="Login">
	</form>

<?php include $tpl . 'footer.php'; ?>

