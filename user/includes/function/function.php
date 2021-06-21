<?php


    function getTitle()
    {
        global $pageTitle;

        if (isset($pageTitle))
        {
            echo $pageTitle;
        }
        else
        {
            echo 'Default';
        }
    }

    function getCat()
    {
        global $bdd;

        $stmt = $bdd->prepare('SELECT id, categorie from Categorie ORDER BY categorie ASC');

        $stmt->execute();
        $rows = $stmt->fetchAll();

        return ($rows);
    }

    function setCat($rows)
    {
        foreach($rows as $row)
            echo
             '<li class="nav-item">
                <a class="nav-link" href="categorie.php?catId=' . $row['id'] . '&catname=' . $row['categorie'] . '">' . $row['categorie'] . '</a></li>';
    }

    function getLivre($cat_id)
    {
        global $bdd;

        $stmt = $bdd->prepare('SELECT * from Livre WHERE cat_id = ? ORDER BY id DESC');
        $stmt->execute(array($cat_id));
        $rows = $stmt->fetchAll();

        return ($rows);
    }

    function setLivre($livres)
    {

        echo '<div class="flex-container">';
    
            foreach ($livres as $livre)
            {
                echo '<div class= "zone">';
                echo '<a href="livres.php?id=' . $livre['id'] . '"><img src="../admin/img/' . $livre['image'] . '"></a>';
                echo '</div>';
    
            }
    
        echo '</div>';
    }

    function putUser($email)
    {
        global $bdd;

        $stmt = $bdd->prepare('SELECT prenom from User WHERE email = ?');
        $stmt->execute(array($email));
        $rows = $stmt->fetch();

        echo $rows['prenom'];
    }

    function getRegstatus($email, $livre_id)
    {
        global $bdd;

        $stmt = $bdd->prepare('SELECT id from User WHERE email = ?');
        $stmt->execute(array($email));
        $user = $stmt->fetch();
        $user_id = $user['id'];
        $stmt = $bdd->prepare('SELECT regStatus from Reservation WHERE user_id = ? AND livre_id = ?');
        $stmt->execute(array($user_id, $livre_id));
        $row = $stmt->fetch();

        return $row['regStatus'] ?? 'default value';
    }

    function getUserId($email)
    {
        global $bdd;

        $stmt = $bdd->prepare('SELECT id from User WHERE email = ?');
        $stmt->execute(array($email));
        $rows = $stmt->fetch();

        return $rows['id'];
    }

?>