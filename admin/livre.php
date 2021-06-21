<?php

    $pageTitle = 'Livre';

    session_start();

    if (isset($_SESSION['email']))
    {

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do']: 'Manage';

        if ($do == 'Manage')
        {
            $stmt = $bdd->prepare(
                                    "SELECT 
                                                Livre.*, Categorie.categorie AS categorie
                                    from 
                                                Livre
                                    INNER JOIN
                                                Categorie
                                    ON
                                                Categorie.id = Livre.cat_id
                                    ORDER BY
                                        Livre.id
                                    ASC"
                                );
            $stmt->execute();
            $rows = $stmt->fetchAll();
        ?>
        
            <h1 class= "text-center">Page de livres</h1>
            <div class=" container table-responsive ">
                <table class="main  manage-livre table table-bordered">
                    <tr>
                        <td>Id</td>
                        <td>Image</td>
                        <td>Livre</td>
                        <td>Categorie</td>
                        <td>Description</td>

                        <td>Controle</td>
                    </tr>
                    <?php

                        foreach ($rows as $row)
                        {
                            echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td><img src='img/" . $row['image'] . "'></td>";
                                echo "<td>" . $row['livre'] . "</td>";
                                echo "<td>" . $row['categorie'] . "</td>";
                                echo "<td>" . $row['description'] . "</td>";
                                echo "<td>
                                    <a href='livre.php?do=Edit&id= " . $row['id'] .  "' class='btn btn-success'><i  class = 'fa fa-edit' ></i>Editer</a>
                                    <a href='livre.php?do=Delete&id= " . $row['id'] .  "' class='btn btn-danger'><i  class = 'fa fa-close' ></i>Supprimer</a>
                                </td>";
                                
                            echo "</tr>";
                        }
                        ?>
                </table>
                <a href="livre.php?do=Add" class="btn btn-primary"><i class="fa fa-plus" ></i>Ajouter un nouveau livre</a>
            </div>
        <?php
        }
        
        else if ($do == 'Add')
        {
        ?>
            <div class="container">
                <h1 class="text-center">Ajouter un livre</h1>
                <form class="form-horizontal" action="?do=Insert" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Nom de livre</label>
                        <input class="col-sm-10  form-control col-md-4" type="text" name="livre" placeholder="Nom de livre" required="required" />
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >Description</label>
                        <textarea class="col-sm-10 form-control col-md-4" name="desc" rows="8" cols="45" placeholder="text here"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >Image</label>
                        <input class="col-sm-10 form-control col-md-4" type="file" name="img" required="required" />
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Categorie</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="col-sm-10  form-control col-md-8" name="cat" required="required">
                                <option value="0">...</option>
                                <?php
                                        $stmt = $bdd->prepare('SELECT * from Categorie');
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll();
                                        foreach ($rows as $row)
                                            echo "<option value='" . $row['id'] . "'>" . $row['categorie'] . "</option></br>";
                                ?>
                            </select>
                        </div>
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
            if ($_SERVER['REQUEST_METHOD'] == 'POST')
            {
                echo "<h1 class='text-center' >Inserer un livre</h1>";

                $imgName = $_FILES['img']['name'];
                $imgTmp = $_FILES['img']['tmp_name'];
                $imgSize = $_FILES['img']['size']; 
                $imgAllowedExtension = array("png", "jpeg", "jpg");
                $imgExtension = explode('.', $imgName);
                $imgExtension = strtolower(end($imgExtension));
                $formErrors = array();

                echo $imgExtension;


            
                $livre = $_POST['livre'];
                $description = $_POST['desc'];
                $cat_id = $_POST['cat'];

                $stmt = $bdd->prepare('SELECT id from Livre where livre = ?');
                $stmt->execute(array($livre));
                $count = $stmt->rowCount();

                if (!in_array($imgExtension, $imgAllowedExtension))
                    $formErrors[] = 'extension not allowed';
                else if ($imgSize > 8388608)
                    $formErrors[] = 'Image superieur de 8 mega';
                if ($count > 0)
                    $formErrors[] = 'Ce livre a etait deja enregister';
                
                if (!empty($formErrors))
                    foreach ($formErrors as $e)
                        echo "<div class = 'alert alert-danger text-center'>" . $e .  "</div>";
                else
                {
                    $img = rand(0, 100000) . '_' . $imgName;
                    move_uploaded_file($imgTmp, "img//" . $img);
                    $stmt = $bdd->prepare('INSERT INTO Livre SET livre = ?, description = ?, cat_id = ?, image = ?');
                    $stmt->execute(array($livre, $description, $cat_id, $img));
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
            $stmt = $bdd->prepare(
                "SELECT 
                            Livre.*, Categorie.categorie AS categorie
                from 
                            Livre
                INNER JOIN
                            Categorie
                ON
                            Categorie.id = Livre.cat_id
                WHERE
                            Livre.id = ?"
            );
            $stmt->execute(array($id));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            if ($count > 0)
            {
            ?>
                <div class="container">
                <h1 class="text-center">Editer un livre</h1>
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="id" value="<?php echo $id ?>" />
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Livre</label>
                        <input class="col-sm-10  form-control col-md-4" type="text" name="livre" value="<?php echo $row['livre'] ?>" />
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" >Description</label>
                        <textarea class="col-sm-10 form-control col-md-4" name="desc" rows="8" cols="45" placeholder="text here" >value="<?php echo $row['description'] ?>"</textarea>
                    </div>
                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-label">Categorie</label>
                        <div class="col-sm-10 col-md-6">
                            <select class="col-sm-10  form-control col-md-8" name="cat" required="required">
                            <?php
                                        echo "<option value='" . $row['cat_id'] . "'>" . $row['categorie'] . "</option></br>";
                                        $stmt = $bdd->prepare('SELECT * from Categorie');
                                        $stmt->execute();
                                        $rows = $stmt->fetchAll();
                                        foreach ($rows as $livre)
                                        {
                                            if ($row['categorie'] != $livre['categorie'])
                                            echo "<option value='" . $livre['id'] . "'>" . $livre['categorie'] . "</option></br>";
                                        }
                                ?>
                            </select>
                        </div>
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
                $livre = $_POST['livre'];
                $description = $_POST['desc'];
                $cat_id = $_POST['cat'];


                
                if (empty($livre))
                    echo 'Le champ Categorie ne doit pas etre vide';

                
                else
                {
                    $stmt = $bdd->prepare('UPDATE Livre SET livre = ?, description = ?, cat_id = ? where id = ?');
                    $stmt->execute(array($livre, $description,$cat_id, $id));
                    echo "<div class = 'alert alert-success text-center'>Le livre est editer avec succes";
                }

               
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
            $stmt = $bdd->prepare('SELECT livre from Livre where id = ?');
            $stmt->execute(array($id));
            $count = $stmt->rowCount();

            if ($count > 0)
            {
                $stmt = $bdd->prepare('DELETE from Livre WHERE id = ?');
                $stmt->execute(array($id));
                echo "<div class = 'alert alert-success text-center'>" . $count . ' records deleted</div>';
            }
        }
        else if ($do == 'Reservation')
        {
            $stmt = $bdd->prepare(
                "SELECT 
                            Reservation.*, livre, prenom, nom
                from 
                            Reservation
                INNER JOIN
                            Livre
                ON
                            Livre.id = Reservation.livre_id
                INNER JOIN
                            User
                ON
                            User.id = Reservation.user_id"
            );

            $stmt->execute();
            $rows = $stmt->fetchAll();

            
            ?>
            <h1 class= "text-center">Page de reservation des livres</h1>
            <div class=" container table-responsive ">
                <table class="main  manage-livre table table-bordered">
                    <tr>
                        <td>Id</td>
                        <td>Adherent</td>
                        <td>Livre</td>
                        <td>Controle</td>
                    </tr>
                    <?php

                        foreach ($rows as $row)
                        {
                            echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['prenom'] . " " . $row['nom'] . "</td>";
                                echo "<td>" . $row['livre'] . "</td>";
                                if ($row['emprunte'] == 0)
                                {
                                echo "<td>
                                    <a href='livre.php?do=Accept&id= " . $row['id'] .  "' class='btn btn-success'><i  class = 'fa fa-edit' ></i>Accepter</a>
                                    <a href='livre.php?do=Annuller&id= " . $row['id'] .  "' class='btn btn-danger'><i  class = 'fa fa-close' ></i>Refuser</a>
                                </td>";
                                }
                                else
                                {
                                    echo "<td>
                                        <a href='livre.php?do=Emprunte&id= " . $row['id'] .  "' class='btn btn-primary'>Emprunte</a>
                                    </td>";
                                }
                                
                            echo "</tr>";
                        }
                        ?>
                </table>
            </div>
        <?php
        }
        else if ($do == 'Accept')
        {
            $id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;
            $stmt = $bdd->prepare('SELECT * from Reservation where id = ?');
            $stmt->execute(array($id));
            $count = $stmt->rowCount();
            if ($count > 0)
            {
                $stmt = $bdd->prepare('UPDATE Reservation SET emprunte = ? where id = ?');
                $stmt->execute(array(1, $id));
                echo "<div class = 'alert alert-success text-center'>" . $count . ' records updated</div>';
            }
        }
        else if($do == 'Annuller')
        {
            $id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? intval($_GET['id']) : 0;
            $stmt = $bdd->prepare('SELECT * from Reservation where id = ?');
            $stmt->execute(array($id));
            $count = $stmt->rowCount();
            if ($count > 0)
            {
                $stmt = $bdd->prepare('DELETE FROM Reservation where id = ?');
                $stmt->execute(array($id));
                echo "<div class = 'alert alert-danger text-center'>La reservation a etait annulee</div>";
            }
            
        }

        include $tpl . 'footer.php';
    }
    else
    {
        echo 'You can\'t access';
    }
?>