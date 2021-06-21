<?php 

    
    $getTitle = 'Categorie';
    session_start();
    include 'init.php';
    
    ?>

    <div class="container">
        <h1 class="text-center"><?php echo $_GET['catname'] ?></h1>
        <?php
            $cat_id = $_GET['catId'];
            $livre = getLivre($cat_id);
            setLivre($livre);
        ?>
    </div>

<?php include $tpl . 'footer.php'; ?>