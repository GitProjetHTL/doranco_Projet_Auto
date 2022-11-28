<?php require_once "inc/init.php";?>

<?php 
    //GESTION DE L'INSCRIPTION

        if (isset($_POST['subscribe'])) {
            debug($_POST);

            //Sécurisation des données

                dataEscape();
            }

            //Verification des données
                if (empty($_POST['pseudo']) || iconv_strlen($_POST['pseudo']) <4 || iconv_strlen($_POST['pseudo']) > 20 ) {
                    $_SESSION['error']['subscribe']['pseudo']="Vous devez indiquer un pseudo entre 4 et 20 caractères";
                }
                if (empty($_POST['prenom']) || iconv_strlen($_POST['prenom'] )>20){
                    $_SESSION['error']['subscribe']['prenom']="Merci d'indiquer votre prénom (20 caractères max)";
                }    
                if (empty($_POST['nom']) || iconv_strlen($_POST['nom'])>20 ){
                    $_SESSION['error']['subscribe']['nom']="Merci d'indiquer votre nom (20 caractères max)";
                }   
                if (empty($_POST['mdp']) || iconv_strlen($_POST['mdp'] )<8){
                    $_SESSION['error']['subscribe']['mdp']="le Mot de Passe doit contenir minimum 8 caractères";
                }   
                if(empty($_POST['email']) || !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
                    $_SESSION['error']['subscribe']['email']="Merci d'indiquer un email valide";
                }
                if(empty($_POST['civilite']) || !in_array($_POST['civilite'],['m','f'])){
                    $_SESSION['error']['subscribe']['civilite']="Merci de sélectionner une civilité";
                }
                
            //
            
            //Envoie des données
                if (!isset($_SESSION['erreur']['subscribe'])) { //Si l'indice n'existe pas alors je n'ai pas de message d'erreur

                    //verification de BDD si le pseudo existe déja

                    $requete= $bdd->prepare ("SELECT * FROM membre WHERE pseudo = :pseudo");    

                    $requete->execute(['pseudo' => $_POST['pseudo']]);

                    $membre = $requete->fetch();

                    if (!empty($membre)) {
                        $_SESSION['erreur']['subscribe']['pseudo'] = "Ce pseudo est deja utiliser";
                    }else{
                    
                $requete= $bdd->prepare ("INSERT INTO membre VALUES(NULL,:pseudo, :mdp ,:prenom ,:nom ,:email ,:civilite , 'user', NOW())");
                $success = $requete -> execute([
                    "pseudo" => $_POST['pseudo'],
                    "mdp" => password_hash($_POST['mdp'],PASSWORD_DEFAULT),
                    "nom"=> $_POST['nom'],
                    "prenom" => $_POST['prenom'],
                    "email" => $_POST['email'],
                    "civilite" => $_POST['civilite'],
                    
                ]);

                if ($success) {
                    $_SESSION['success']['subscribe']="Inscription validée";
                    header("location:connection.php");
                    exit;

                }

                }

        }
    




    //
    $title = "inscription";
?>

<?php require_once RACINE_SITE."inc/header.php";?>

<h5 class="text-center">Inscription</h5>

<form class="col-7 mx-auto" action="" method="POST">
    <input 
           class="form-control mt-4 <?= isset($_SESSION['error']['subscribe']['pseudo']) ? "is-invalid" : "" ?>" 
           type="text" 
           name="pseudo" 
           id="pseudo" 
           placeholder="Votre Pseudo"
           value="<?= $_POST['pseudo'] ?? "" ?>"
           >
    <div class="invalid-feedback alert-inscription"><?= ($_SESSION['error']['subscribe']['pseudo'] ?? ""  ) ?></div>

    <input 
           class="form-control mt-4 <?= isset($_SESSION['error']['subscribe']['mdp']) ? "is-invalid" : "" ?>" 
           type="password" 
           name="mdp" 
           id="mdp" 
           placeholder="Votre mot de passe">
    <div class="invalid-feedback alert-inscription"> <?= ($_SESSION['error']['subscribe']['mdp'] ?? ""  ) ?></div>

    <input 
           class="form-control mt-4 <?= isset($_SESSION['error']['subscribe']['prenom']) ? "is-invalid" : "" ?>" 
           type="text" 
           name="prenom" 
           id="prenom" 
           placeholder="Votre Prenom"
           value="<?= $_POST['prenom'] ?? "" ?>"
           ><div class="invalid-feedback alert-inscription"><?= ($_SESSION['error']['subscribe']['prenom'] ?? ""  ) ?></div>

    <input 
           class="form-control mt-4 <?= isset($_SESSION['error']['subscribe']['nom']) ? "is-invalid" : "" ?>" 
           type="text" 
           name="nom" 
           id="nom" 
           placeholder="Votre Nom"
           value="<?= $_POST['nom'] ?? "" ?>"
           >
    <div class="invalid-feedback alert-inscription"><?= ($_SESSION['error']['subscribe']['nom'] ?? ""  ) ?></div>

    <input 
           class="form-control mt-4 <?= isset($_SESSION['error']['subscribe']['email']) ? "is-invalid" : "" ?> " 
           type="email" 
           name="email" 
           id="email" 
           placeholder="Votre Email"
           value="<?= $_POST['email'] ?? "" ?>"
           >
    <div class="invalid-feedback alert-inscription"> <?= ($_SESSION['error']['subscribe']['email'] ?? ""  ) ?> </div>

    <select class="form-select mt-4 <?= isset($_SESSION['error']['subscribe']['civilite']) ? "is-invalid" : "" ?>" name="civilite" id="civilite">
        <option disabled selected>Civilite</option>
        <option <?= isset($_POST['civilite']) && $_POST['civilite'] == 'm' ? "selected" : ""?> value="m">Homme</option>
        <option <?= isset($_POST['civilite']) && $_POST['civilite'] == 'f' ? "selected" : ""?> value="f">Femme</option>
    </select>
    <div class="invalid-feedback alert-inscription"><?= ($_SESSION['error']['subscribe']['civilite'] ?? ""  ) ?></div>



    <button class="d-block mx-auto btn btn-primary mt-3" name="subscribe">Inscription</button>
</form>

<?php unset($_SESSION['error'])?>



<?php require_once RACINE_SITE."inc/footer.php";?>