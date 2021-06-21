<?php

    $pageTitle = 'Members';

    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do']: 'Manage';
?>





    <div class="container">
                    <h1 class="text-center">S'inscrire</h1>
                    <form class="form-horizontal" action="?do=Insert" method="POST">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Nom</label>
                            <input class="col-sm-10  form-control col-md-4" type="text" name="nom" placeholder="Nom" required="required" />
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" >Prenom</label>
                            <input class="col-sm-10 form-control col-md-4" type="text" name="prenom" placeholder="Prenom" required="required" />
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">email</label>
                            <input class="col-sm-10 form-control col-md-4" type="text" name="email" placeholder="Email" required="required" />
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" >Mot de passe</label>
                            <input class="col-sm-10 form-control col-md-4" type="password" name="pass" placeholder="Mot de passe" required="required" />
                        </div>
                        <div>
                            <input type="submit" value="Enregistrer">
                        </div>
                    </form>
                </div>
<?php

            if ($do == 'Insert')
            {
              //  echo "<h1 class='text-center' >Update Page</h1>";

                if ($_SERVER['REQUEST_METHOD'] == 'POST')
                {
                    $nom = $_POST['nom'];
                    $prenom = $_POST['prenom'];
                    $email = $_POST['email'];
                    $pass = sha1($_POST['pass']);

                    $stmt = $bdd->prepare('SELECT * from User where email = ?');
                    $stmt->execute(array($email));
                    $count = $stmt->rowCount();
                    
                    if ($count > 0)
                    {
                        echo "<div class = 'alert alert-danger text-center'>Cet Email a etait deja enregister</div>";
                        echo '';
                    }
                    else
                    {
                        $stmt = $bdd->prepare('INSERT INTO User SET nom = ?, prenom = ?, email = ?, password = ?,regStatus = ?, date = now()');
                        $stmt->execute(array($nom, $prenom, $email, $pass, 0));
                        echo "<div class = 'alert alert-success text-center'>" . $stmt->rowCount() . ' records Added</div>';
                    }

                }

                else
                {
                    echo 'You can\'t access';
                }
            }

            include $tpl . 'footer.php';
?>