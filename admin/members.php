<?php

    $pageTitle = 'Members';

    ob_start();
    session_start();

    if (isset($_SESSION['email']))
    {

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do']: 'Manage';

        if ($do == 'Manage')
        {
            $stmt = $bdd->prepare('SELECT * from User where groupId != 1 ORDER BY regStatus ASC');
            $stmt->execute();
            $rows = $stmt->fetchAll();
        ?>
        
            <h1 class= "text-center">Page des Adherents</h1>
            <div class=" container table-responsive ">
                <table class="main table table-bordered">
                    <tr>
                        <td>Id</td>
                        <td>Nom</td>
                        <td>Prenom</td>
                        <td>Email</td>
                        <td>Date d'enregistrement</td>
                        <td>Controle</td>
                    </tr>
                    <?php
                        foreach ($rows as $row)
                        {
                            echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['nom'] . "</td>";
                                echo "<td>" . $row['prenom'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['date'] . "</td>";
                                echo "<td>
                                    <a href='members.php?do=Edit&id= " . $row['id'] .  "' class='btn btn-success'><i  class = 'fa fa-edit' ></i>Editer</a>
                                    <a href='members.php?do=Delete&id= " . $row['id'] .  "' class='btn btn-danger'><i  class = 'fa fa-close' ></i>Supprimer</a>";
                                if ($row['regStatus'] == 0)
                                {
                                    echo "<a href='members.php?do=Accepte&id= " . $row['id'] .  "' class='btn btn-info accepte'><i  class = 'fa fa-activate' ></i>Accepter </a>";
                                }
                                echo "</td>";
                                
                            echo "</tr>";
                        }
                        ?>
                </table>
                <a href="members.php?do=Add" class="btn btn-primary"><i class="fa fa-plus" ></i>Ajouter un nouveau membre</a>
            </div>
        <?php
        }
        
        else if ($do == 'Add')
        {
        ?>
            <div class="container">
                <h1 class="text-center">Ajouter un nouveau membre</h1>
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
                        <input class="btn btn-primary" type="submit" value="Enregistrer">
                    </div>
                </form>
            </div>
        <?php
        }

        else if ($do == 'Insert')
        {
            echo "<h1 class='text-center' >Update Page</h1>";

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
                    $stmt = $bdd->prepare('INSERT INTO User SET nom = ?, prenom = ?, email = ?, password = ?, date = now()');
                    $stmt->execute(array($nom, $prenom, $email, $pass));
                    echo "<div class = 'alert alert-success text-center'>" . $stmt->rowCount() . ' records Added</div>';
                }

            }

            else
            {
                echo 'You can\'t accesss';
            }
        }

        else if ($do == 'Edit')
        {
            $id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;
            $stmt = $bdd->prepare('select * from User where id = ?');
            $stmt->execute(array($id));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            if ($count > 0)
            {
            ?>
                <div class="container">
                <h1 class="text-center">Page d'Editer les informations</h1>
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="id" value="<?php echo $id ?>" />
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
                    $pass = sha1($_POST['new_pass']);

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

        else if ($do == 'Delete')
        {
            echo "<h1 class='text-center'>Page de supression</h1>";

            $id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;
            $stmt = $bdd->prepare('SELECT * from User where id = ?');
            $stmt->execute(array($id));
            $count = $stmt->rowCount();

            if ($count > 0)
            {
                $stmt = $bdd->prepare('DELETE from User WHERE id = ?');
                $stmt->execute(array($id));
                echo "<div class = 'alert alert-success text-center'>" . $count . ' records updated</div>';
            }
        }
        else if ($do == 'Accepte')
        {
            $id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;
            $stmt = $bdd->prepare('SELECT * from User where id = ?');
            $stmt->execute(array($id));
            $count = $stmt->rowCount();
            if ($count > 0)
            {
                $stmt = $bdd->prepare('Update User SET regStatus = 1 where id = ?');
                $stmt->execute(array($id));
                echo "<div class = 'alert alert-success text-center'>" . $count . ' records updated</div>';
            }

        }

        include $tpl . 'footer.php';
    }

    else
    {
        echo 'You can\'t access';
    }
    
    ob_end_flush();

?>