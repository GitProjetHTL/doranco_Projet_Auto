<?php

/**
 * Fonction d'affichage des données
 */
function debug($value){
    echo "<pre>";
        print_r ($value);
    echo "</pre>";
}


function dataEscape(){
    foreach ($_POST as $key => $value) {
        $_POST[$key] =htmlspecialchars($value,ENT_QUOTES);//échappement des caractère
        $_POST[$key] =trim($value);//suppression des espaces avant et après chaine de caractères
}
}

function isConnected(){
    if(isset($_SESSION['membre'])){
        return TRUE;
    }

    return FALSE;
}

function isAdmin(){
    if (isConnected() && $_SESSION['membre']['statut'] == "admin") {
        return TRUE;
    }
    return FALSE;
}
//fonction MEMBRE
    function getMembreByPseudo($pseudo){

        global $bdd;

        $requete= $bdd->prepare ("SELECT * FROM membre WHERE pseudo = :pseudo");    

        $requete->execute(['pseudo' => $pseudo]);

        $membre = $requete->fetch();

        return $membre;
    }

    function getAllMembres(){

       
            global $bdd;

            $requete = $bdd->query("SELECT * FROM membre");
            $membres = $requete ->fetchAll();

            return $membres;

    }
    
    function deleteFrom($id,$table){

        // "DELETE FROM membre WHERE id_membre = $id";

        global $bdd;

        $requete = $bdd->prepare("DELETE FROM $table WHERE id_$table = :id_$table") ; //pour choisir si on utilise prepare on execute il faut regarder la requête SQL
                                                                                    //Si dans ma requête j'ai donneé qui peuvent être envoyer par l'utilisateur ($_POST ou $_GET)j'utilise prepare
                                                                                    //Si par contre ma requete n'a aucun paramètre dynamique alords je peux utiliser query()
        $result = $requete -> execute (["id_$table" => $id]);



        return $result;






    }

//fonction MEMBRE
function getMembreById($id_membre){

    global $bdd;

    $requete= $bdd->prepare ("SELECT * FROM membre WHERE id_membre = :id_membre");    

    $requete->execute(['id_membre' => $id_membre]);

    $membre = $requete->fetch();

    return $membre;
}


?>