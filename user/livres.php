<?php


    ob_start();
    session_start();
    $pageTitle = 'Livre';
    include 'init.php';

    $livre_id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
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
                    Livre.id =?"
    );
    $stmt->execute(array($livre_id));
    $count = $stmt->rowCount();

    if ($count > 0)
    {
        $livre = $stmt->fetch();

?>
    <h1 class="text-center"><?php echo $livre['livre'] ?></h1>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <?php 
                    echo '<img class="img-responsive img-thumbnail" src="../admin/img/' . $livre['image'] . '">';
                    if (isset($_SESSION['user']))
                    {
                        $email = $_SESSION['user'];
                        if (getRegstatus($email, $livre_id) == 0)
                        {
                            echo '<a href="livres.php?do=Reserver&idi=' . $livre_id . '" class="btn btn-primary" style ="padding-right: 90px;
                            padding-left: 95px;">Reserver</a>';
                        }
                        else
                        {
                            echo '<a href="livres.php?do=Annuler&idi=' . $livre_id . '" class="btn btn-primary" style ="padding-right: 95px;
                            padding-left: 100px;">Annuler</a>';
                        }
                    }
                    else
                    {
                        echo "<div class = 'alert alert-success text-center'>Vous devez vous connecter à votre profil pour faire une reservation</div>";
                    }
                 ?>
            </div>
            <div class="col-md-9 item-info">
                <h3>Nom: <?php echo $livre['livre'] ?></h3>
                <div>
                    <i class="fa fa-tags fa-fw"></i>
                    <strong><span>Categorie: </span><?php echo $livre['categorie'] ?></strong>
                </div>
                <p><strong><?php echo $livre['description'] ?></strong></p>
            </div>
            
        </div>     
    </div>
    

<?php

    }


    if (isset($_SESSION['user']))
    {
        if (isset($_GET['do']))
        {
            $do = $_GET['do'];
            if ($do == 'Reserver')
            {
                $livre_id = isset($_GET['idi']) && is_numeric($_GET['idi']) ? intval($_GET['idi']) : 0;
                $email = $_SESSION['user'];
                $stmt = $bdd->prepare('SELECT id from User WHERE email = ?');
                $stmt->execute(array($email));
                $user = $stmt->fetch();
                $user_id = $user['id'];
                $stmt = $bdd->prepare('INSERT INTO Reservation SET user_id = ?, livre_id = ?, regStatus = ?');
                $stmt->execute(array($user_id, $livre_id, 1));
                echo "<div class = 'alert alert-success text-center'>La reservation a etait effectuer</div>";
            }
            else if ($do == 'Annuler')
            {
                $livre_id = isset($_GET['idi']) && is_numeric($_GET['idi']) ? intval($_GET['idi']) : 0;
                $email = $_SESSION['user'];
                $stmt = $bdd->prepare('SELECT id from User WHERE email = ?');
                $stmt->execute(array($email));
                $user = $stmt->fetch();
                $user_id = $user['id'];
                $stmt = $bdd->prepare('DELETE FROM Reservation WHERE user_id = ? AND livre_id = ?');
                $stmt->execute(array($user_id, $livre_id));
                echo "<div class = 'alert alert-danger text-center'>La reservation a etait Annuler</div>";
            }
        }
    }

    include $tpl . 'footer.php';
    ob_end_flush();

    
?>