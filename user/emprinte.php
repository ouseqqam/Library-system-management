<?php

    $pageTitle = 'Reservation';

    session_start();

    if (isset($_SESSION['user']))
    {

        include 'init.php';

        $id = getUserId($_SESSION['user']);
        $stmt = $bdd->prepare('SELECT * FROM Reservation WHERE user_id = ?');
        $stmt->execute(array($id));
        $ids = $stmt->fetchAll();
       
        ?>

        <h1 class= "text-center">Page des reservations</h1>
        <div class=" container table-responsive ">
            <table class="main manage-reservation table table-bordered">
                <tr>
                    <td>Id</td>
                    <td>Image</td>
                    <td>Livre</td>
                    <td>Controle</td>
                    <td>Etat</td>
                </tr>
        <?php
                foreach ($ids as $id)
                {
                    $stmt = $bdd->prepare('SELECT * FROM Livre WHERE id = ?');
                    $stmt->execute(array($id['livre_id']));
                    $rows = $stmt->fetchAll();

                    foreach ($rows as $row)
                    {
                        echo "<tr>";
                            echo "<td>" . $row['id'] . "</td>";
                            echo "<td><img src='../admin/img/" . $row['image'] . "'></td>";
                            echo "<td>" . $row['livre'] . "</td>";
                            if (getRegstatus($_SESSION['user'], $row['livre']) == 1)
                            {
                                echo '<td>
                                    <a href="livres.php?do=Reserver&idi=' . $row['id'] . '" class="btn btn-success"><i  class="fa fa-edit" ></i>Reserver</a></td>';
                            }
                            else
                            {
                                echo '<td>
                                <a href="livres.php?do=Annuler&idi=' . $row['id'] . '" class="btn btn-danger"><i  class="fa fa-close" ></i>Annuler</a></td>';
                            }    
                            if ($id['emprunte'] == 1)
                            {
                                echo '<td>La reservation a etait accepter</td>';
                            }
                            else
                            {
                                echo '<td>La reservation est en attente</td>';
                            }
                        echo "</tr>";
                    }
                }
                    ?>
            </table>
        </div>
            <?php
        include $tpl . 'footer.php';
    }
    else{
        header('Location: index.php');
    }

?>