<?php

    $pageTitle = 'Categorie';

    session_start();

    if (isset($_SESSION['email']))
    {

        include 'init.php';

        $do = isset($_GET['do']) ? $_GET['do']: 'Categorie';

        if ($do == 'Categorie')
        {
            $stmt = $bdd->prepare('SELECT * from Categorie');
            $stmt->execute();
            $rows = $stmt->fetchAll();
        ?>
        
            <h1 class= "text-center">Page de categorie</h1>
            <div class=" container table-responsive ">
                <table class="main table table-bordered">
                    <tr>
                        <td>Id</td>
                        <td>Categorie</td>
                        <td>Controle</td>
                    </tr>
                    <?php
                        foreach ($rows as $row)
                        {
                            echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['categorie'] . "</td>";
                                echo "<td>
                                    <a href='categories.php?do=Edit&id= " . $row['id'] .  "' class='btn btn-success'><i  class = 'fa fa-edit' ></i>Editer</a>
                                    <a href='categories.php?do=Delete&id= " . $row['id'] .  "' class='btn btn-danger'><i  class = 'fa fa-close' ></i>Supprimer</a>
                                </td>";
                                
                            echo "</tr>";
                        }
                        ?>
                </table>
                <a href="categories.php?do=Add" class="btn btn-primary"><i class="fa fa-plus" ></i>Ajouter une nouvelle categorie</a>
            </div>
        <?php
        }
        
        else if ($do == 'Add')
        {
        ?>
            <div class="container">
                <h1 class="text-center">Ajouter une categorie</h1>
                <form class="form-horizontal" action="?do=Insert" method="POST">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Nom de categorie</label>
                        <input class="col-sm-10  form-control col-md-4" type="text" name="cat" placeholder="Nom" required="required" />
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
                $cat = $_POST['cat'];

                $stmt = $bdd->prepare('SELECT * from Categorie where categorie = ?');
                $stmt->execute(array($cat));
                $count = $stmt->rowCount();
                
                if ($count > 0)
                {
                    echo "<div class = 'alert alert-danger text-center'>Cet categorie a etait deja enregister</div>";
                }
                else
                {
                    $stmt = $bdd->prepare('INSERT INTO Categorie SET categorie = ?');
                    $stmt->execute(array($cat));
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
            $stmt = $bdd->prepare('select * from Categorie where id = ?');
            $stmt->execute(array($id));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            if ($count > 0)
            {
            ?>
                <div class="container">
                <h1 class="text-center">Editer une categorie</h1>
                <form class="form-horizontal" action="?do=Update" method="POST">
                    <input type="hidden" name="id" value="<?php echo $id ?>" />
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Categorie</label>
                        <input class="col-sm-10  form-control col-md-4" type="text" name="cat" value="<?php echo $row['categorie'] ?>" />
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
                $cat = $_POST['cat'];
                $description = $_POST['desc'];


                
                if (empty($cat))
                    echo 'Le champ Categorie ne doit pas etre vide';

                
                else
                {
                    $stmt = $bdd->prepare('UPDATE Categorie SET categorie = ? where id = ?');
                    $stmt->execute(array($cat, $id));
                    echo "<div class = 'alert alert-success text-center'>La categorie est editer avec succes";
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
            $stmt = $bdd->prepare('SELECT * from Categorie where id = ?');
            $stmt->execute(array($id));
            $count = $stmt->rowCount();

            if ($count > 0)
            {
                $stmt = $bdd->prepare('DELETE from Categorie WHERE id = ?');
                $stmt->execute(array($id));
                echo "<div class = 'alert alert-success text-center'>" . $count . ' records deleted</div>';
            }
        }
        include $tpl . 'footer.php';
    }
    else
    {
        echo 'You can\'t access';
    }
?>