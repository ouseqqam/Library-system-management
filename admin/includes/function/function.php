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

    function countData($data, $table)
    {
        global $bdd;

        $stmt = $bdd->prepare("SELECT COUNT($data) from $table");
        $stmt->execute();

        return ($stmt->fetchColumn() - 1);
    }


?>