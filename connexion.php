<?php 


require_once "inc/init.php";

       //RESTRICTION D'ACCES
      


    //Gestion de la connection
        if (isset($_POST['connect'])) {
            //securisation des données
                dataEscape();
            //
            //Verification des données
                if(empty($_POST['pseudo']) || empty($_POST['mdp'])){
                    $_SESSION['error']['connect']['general']="Merci d'indiquer un pseudo et un mot de passe";
                }else {
                    
                    $membre=getMembreByPseudo($_POST['pseudo']);
                    debug ($membre);
                    

                    if (password_verify($_POST['mdp'], $membre['mdp'])){
                        $_SESSION['membre']= $membre;

                        $_SESSION['success']['connect'] = "Bravo vous êtes connecté";
                        header("location:profil.php");
                        exit;
                    }else{
                        $_SESSION['error']['connect']['general']="identifiants incorrects";
                        


                    }
                    
                }
                debug ($_SESSION);
        }

    //
    


$title = "Connexion";
require_once RACINE_SITE."inc/header.php";


if ( isset($_SESSION['success']['subscribe'])) {
    echo '<div class="alert alert-success col-md-6 mx-auto text-center">';
        echo $_SESSION['success']['subscribe'];
        unset($_SESSION['success']);
    echo '</div>';
}

if ( isset($_SESSION['error']['connect'])) {
    echo '<div class="alert alert-danger col-md-6 mx-auto text-center">';
        echo $_SESSION['error']['connect']['general'];
        unset($_SESSION['error']);
    echo '</div>';
}


?>

<h5 class="text-center">Connexion</h5>


<form class="col-7 mx-auto" action="" method="POST">
    <input class="form-control my-3" type="text" name="pseudo" id="pseudo" placeholder="Votre Pseudo">

    <input class="form-control my-3" type="password" name="mdp" id="mdp" placeholder="Votre mot de passe">

    <button class="d-block mx-auto btn btn-primary" name="connect">Connexion</button>
</form>

<?php require_once RACINE_SITE."inc/footer.php";?>