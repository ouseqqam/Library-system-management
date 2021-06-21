<?php

    session_start();
    $pageTitle = 'Profile';
    
    if (isset($_SESSION['user']))
    {
        include 'init.php';
        $do = isset($_GET['do']) ? $_GET['do']: 'Edit';

        if ($do == 'Edit')
        {
            $email = $_SESSION['user'];
            $stmt = $bdd->prepare('select * from User where email = ?');
            $stmt->execute(array($email));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            if ($count > 0)
            {
            ?>
                <div class="container">
                <h1 class="text-center">Page d'Editer les informations</h1>
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="id" value="<?php echo $row['id'] ?>" />
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Nom</label>
                        <input class="col-sm-10  form-control col-md-4" type="text" name="nom" value="<?php echo $row['nom'] ?>" />
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >Prenom</label>
                        <input class="col-sm-10 form-control col-md-4" type="text" name="prenom" value="<?php echo $row['prenom'] ?>" />
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">email</label>
                        <input class="col-sm-10 form-control col-md-4" type="text" name="email" value="<?php echo $row['email'] ?>" />
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >Mot de passe</label>
                        <input type="hidden" name="old_pass" value= "<?php echo $row['password'] ?>" >
                        <input class="col-sm-10 form-control col-md-4" type="password" name="new_pass" placeholder="Mot de passe" />
                    </div>
                    <div>
                        <input class="btn btn-primary" type="submit" value="Enregistrer">
                    </div>
                </form>
            </div>
             <?php
            }

            else
            {
                echo 'You can\'t access to this page from here';
            }

        }

        else if ($do == 'Update')
        {
            echo "<h1 class='text-center' >Update Page</h1>";
            echo "<div class='container' >";

            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                $id = $_POST['id'];
                $nom = $_POST['nom'];
                $prenom = $_POST['prenom'];
                $email = $_POST['email'];
                $formErrors = array();


                if (empty($_POST['new_pass']))
                {
                    $pass = $_POST['old_pass'];
                }
                else
                {
                    $pass = $_POST['new_pass'];

                }

                if (empty($nom))
                    $formErrors[] = 'Le champ Nom ne doit pas etre vide';

                if (empty($prenom))
                    $formErrors[] = 'Le champ Prenom ne doit pas etre vide';

                if (empty($email))
                    $formErrors[] = 'Le champ Email ne doit pas etre vide';

                if (empty($formErrors))
                {
                    $stmt = $bdd->prepare('UPDATE User SET nom = ?, prenom = ?, password = ?, email = ? where id = ?');
                    $stmt->execute(array($nom, $prenom, $pass, $email, $id));
                    echo "<div class = 'alert alert-success text-center'>" . $stmt->rowCount() . ' records updated</div>';
                }

                else
                    foreach ($formErrors as $e)
                        echo "<div class = 'alert alert-danger'>" . $e . '</div>' ;
            }

            else
            {
                echo 'You can\'t accesss';
            }

            echo "</div>";
        }
        include $tpl . 'footer.php';
    }
    else
    {
        header('Location: login.php');
        exit();
    }

?>