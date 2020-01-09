<?php


function connection()
{
    $user="root";
    $password="0000";
    return new PDO ("mysql:host=localhost;dbname=activites",$user,$password);
}


function selectall(){
    $dbCo=connection();
    $query="select * from activites";
    $preparedQuery=$dbCo->prepare($query);
    $preparedQuery->execute();
    $activites=$preparedQuery->fetchAll();
    echo json_encode($activites);
}

function selectallorder($order){
    $dbCo=connection();
    $query='select * from activites order by '.$order;
    $preparedQuery=$dbCo->prepare($query);
    $preparedQuery->execute();
    $activites=$preparedQuery->fetchAll();
    echo json_encode($activites);}
//select one by id
/*function selectbyid() {
    $dbCo=connection();
    $query='select * from activites where id="'.$_GET["id"].'"';
    $preparedQuery=$dbCo->prepare($query);
    $preparedQuery->execute();
    $activites=$preparedQuery->fetch();
    var_dump($activites);
}*/
//select random row
function selectrandom(){
    $dbCo=connection();
    $query='select * from activites order by rand() limit 1';
    $preparedQuery=$dbCo->prepare($query);
    $preparedQuery->execute();
    $activites=$preparedQuery->fetchAll();
    echo json_encode($activites);}

//ajout activite

//if (!empty($_GET['type'])&&!empty($_GET['nb_participants'])&&!empty($_GET['prix'])&&!empty($_GET['accessibilite'])&&!empty($_GET['nom']))
function addactivity()
{
    $dbCo=connection();
    $reqq = "INSERT INTO activites (type, nb_participants, prix, accessibilite, nom) VALUES (:type,:nb_participants,:prix,:accessibilite,:nom)";
    $preparerequet3 = $dbCo->prepare($reqq);
    $preparerequet3->bindparam(":type", $_GET['type']);
    $preparerequet3->bindparam(":nb_participants", $_GET['nb_participants']);
    $preparerequet3->bindparam(":prix", $_GET['prix']);
    $preparerequet3->bindparam(":accessibilite", $_GET['accessibilite']);
    $preparerequet3->bindparam(":nom", $_GET['nom']);
    $test=$preparerequet3->execute();
    if ($test==true)
    {
        echo json_encode("add succesful");
    }
    else
    {
        echo json_encode("add fail");
    }

    //affichage de controle http://192.168.33.43/exos/projet_api/?action=add&type=typetest&nb_participants=666&prix=0.999&accessibilite=0.666&nom=nomtest

}
//delete
//if(!empty($_GET['id']))
function deleteactivity()
    {
        $dbCo=connection();
        $req4 = "DELETE FROM activites WHERE id = :id";
        $preparerequet4 = $dbCo->prepare($req4);
        $preparerequet4->bindparam(":id", $_GET['id']);
        $test=$preparerequet4->execute();
        if ($test==true)
        {
            echo json_encode("delete succesful (doesn't mean that you delete something that was existing)");
        }
        else
        {
            echo json_encode("delete fail");
        }

    }
//update
//if (!empty($_GET['type'])&&!empty($_GET['nb_participants'])&&!empty($_GET['prix'])&&!empty($_GET['accessibilite'])&&!empty($_GET['nom'])&&!empty($_GET['id'])) {
   function updateactivity(){
       $dbCo=connection();
    $reqq = "UPDATE activites SET type =:type,
nb_participants = :nb_participants,
prix = :prix,
accessibilite = :accessibilite,
nom= :nom
WHERE id = :id";

    $preparerequet3 = $dbCo->prepare($reqq);
    $preparerequet3->bindparam(":type", $_GET['type']);
    $preparerequet3->bindparam(":nb_participants", $_GET['nb_participants']);
    $preparerequet3->bindparam(":prix", $_GET['prix']);
    $preparerequet3->bindparam(":accessibilite", $_GET['accessibilite']);
    $preparerequet3->bindparam(":nom", $_GET['nom']);
    $preparerequet3->bindparam(":id", $_GET['id']);
    $test=$preparerequet3->execute();
    // test http://192.168.33.43/exos/projet_api/?action=update&type=update&nb_participants=87&prix=0.999999&accessibilite=0.666&nom=nomtest2&id=10
       if ($test==true)
       {
           echo json_encode("update succesful");
       }
       else
       {
           echo json_encode("update fail");
       }

}



//echo json_encode($activites);


if (!empty($_GET["action"])) //si action pas vide
{
    if ($_GET["action"]=="display"){ //si action == display
        if (!empty($_GET["type"])) // si type de display défini
        {
            if ($_GET["type"]=="random") //si type de display == random
            {
                selectrandom(); // on affiche un act random
            }
            elseif ($_GET["type"]=="all") // si type == all
            {
                selectall(); // on affiche toutes les activités
            }
            elseif ($_GET["type"]=="order") // si type == order
            {
                if (!empty($_GET["orderby"])) //et si orderby est défini
                {
                    if ($_GET["orderby"]=="price") //si orderby == price
                    {
                        selectallorder("prix"); // on affiche tout en triant par prix
                    }
                    elseif ($_GET["orderby"]=="type") //si orderby == type
                    {
                        selectallorder("type"); // on affiche tout en triant par type
                    }
                    elseif ($_GET["orderby"]=="access") //si orderby == acces
                    {
                        selectallorder("accessibilite");// on affiche tout en triant par accessibilite
                    }
                    elseif ($_GET["orderby"]=="nb_par") //si orderby == nb_par
                    {
                        selectallorder("nb_participants"); // on affiche tout en triant par nb_participants
                    }
                    else
                    {
                        echo json_encode("orderby incorrect value");
                    }
                }

            }
        }
    }
    elseif ($_GET["action"]=="update")// si action = update
    {
        if (!empty($_GET['type'])&&!empty($_GET['nb_participants'])&&!empty($_GET['prix'])&&!empty($_GET['accessibilite'])&&!empty($_GET['nom'])&&!empty($_GET['id'])) // verifier si tt les champs sont pas vide
        {
            updateactivity();
        }

    }
    elseif ($_GET["action"]=="delete") // si action = delete
    {
        if(!empty($_GET['id'])) // si id is not empty
        {
        deleteactivity();
        }
        else
        {
            echo json_encode("parametres incorrects");
        }
    }
    elseif ($_GET["action"]=="add") // si action = add pour ajouter
    {
        if (!empty($_GET['type'])&&!empty($_GET['nb_participants'])&&!empty($_GET['prix'])&&!empty($_GET['accessibilite'])&&!empty($_GET['nom'])) // on verifie si tt les champs sont pas vide
        {
            addactivity();
        }

    }

}
else
{
    selectrandom();
}
include "footer.html"; ?>
