<?php
    
    $pageTitle = 'Tableau de bord';

    session_start();

    if (isset($_SESSION['email']))
    {
        $pageTitle = 'Tableau de bord';
        include 'init.php';
?>

    <div class="container home-stats text-center ">
        <h1 class="text-center">Tableau de bord</h1>
        <div class="row">
            <div class="col-md-3" >
                <div class="stat st-member" >
                    Total des membres
                    <span><?php echo countData('id', 'User') ?> </span>
                </div>
            </div>
            <div class="col-md-3" >
                <div class="stat st-pending" >
                    Membres en attente
                    <span>1</span>
                </div>
            </div>
            <div class="col-md-3" >
                <div class="stat st-livre" >
                    Total des livres
                    <span>10</span>
                </div>
            </div>
        </div>
    </div>

 <?php
        include $tpl . 'footer.php';
    }
    else
    {
        echo 'you can\'t acces';
    }

?>

