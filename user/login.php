<?php 

    $pageTitle = 'Login';

    include 'init.php';

    session_start();

    if (isset($_SESSION['user']))
    {
        header('Location: index.php');
    }
    else if (isset($_SESSION['email']))
    {
        header('Location: ../admin/dashbord.php');
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $email = $_POST['email'];
        $pass = sha1($_POST['pass']);

        $stmt = $bdd->prepare('select * from User where email = ? and password = ?');
        $stmt->execute(array($email, $pass));
        $count = $stmt->rowcount();
        $user = $stmt->fetch();
        if ($count > 0 && $user['regStatus'] == 1)
        {
            if ($user['groupId'] == 0)
            {
                $_SESSION['user'] = $email;
                header('Location: index.php');
            }
            else if ($user['groupId'] == 1)
            {
                $_SESSION['email'] = $email;
                header('Location: ../admin/dashbord.php');
            }

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