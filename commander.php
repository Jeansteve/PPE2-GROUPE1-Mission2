<?php
if(!empty($_GET))
{
    $numPlateau = $_GET['numP'];
    $qte_commandee = $_GET['quantitee'];
}
if(!empty($_POST))
{
    $nom = $_POST['nom'];
    $rue = $_POST['rue'];
    $cp = $_POST['cp'];
    $ville = $_POST['ville'];
    $email = $_POST['mail'];
    $tel = $_POST['tel'];

    $bdd = new PDO("mysql:host=localhost;dbname=BOCCO-GROUPE1","root","root");
    /*
     * Remplissage de la table CLIENT
     */
    $req = $bdd->prepare("
        INSERT INTO CLIENT(nom_client, ad_rue_client, cp_client, ville_client, mail_client, tel_client)
        VALUES  (:nom, :rue, :cp, :ville, :mail, :tel)
    ");
    $req->bindValue(":nom",$nom);
    $req->bindValue(":rue",$rue);
    $req->bindValue(":cp",$cp);
    $req->bindValue(":ville",$ville);
    $req->bindValue(":mail",$email);
    $req->bindValue(":tel",$tel);

    $req->execute();

    $id = $bdd->lastInsertId();
    $req->closeCursor();
    /*
     * Remplissage de la table COMMANDE
     */
    $req = $bdd->prepare("
        INSERT INTO COMMANDE(code_client, date_heure)
        VALUES (:code, NOW())
    ");

    $req->bindValue(":code", $id);
    $req->execute();

    $numCommande = $bdd->lastInsertId();

    $req->closeCursor();
    //echo print_r($req->errorInfo());

    /*
     * Remplissage de la table CONTENIR
     */
    $req = $bdd->prepare("
        INSERT INTO CONTENIR(num_commande, num_plateau, qte_commandee)
        VALUES (:numC, :numP, :qte)
    ");
    $req->bindValue(":numC",$numCommande);
    $req->bindValue(":numP",$numPlateau);
    $req->bindValue(":qte",$qte_commandee);

    $req->execute();
    $req->closeCursor();

    /*
     * Remplissage de la table LIVRAISON
     */
    $req = $bdd->prepare("
        INSERT INTO LIVRAISON(num_commande, rue_livraison, ville_livraison, cp_livraison)
        VALUES (:numC, :rue, :ville, :cp)
    ");
    $req->bindValue(":numC",$numCommande);
    $req->bindValue(":rue",$rue);
    $req->bindValue(":ville",$ville);
    $req->bindValue(":cp",$cp);

    $req->execute();
    $req->closeCursor();

    header("location:index.php");
}

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Commander</title>
</head>
<body>
<div id="container">
    <form  method="post">
        <label for="nom">Nom </label><input type="text" name="nom" id="nom" required/><br/>
        <label for="tel">Telephone </label><input type="tel" name="tel" id="tel" required/><br/>
        <h3>Lieu de Livraison</h3>
        <label for="rue">Adresse </label><input type="text" name="rue" id="rue" required/><br/>
        <label for="cp">Code postal</label><input type="text" name="cp" id="cp" required/><br/>
        <label for="ville">Ville </label><input type="text" name="ville" id="ville" required/><br/>
        <label for="mail">Email </label><input type="email" name="mail" id="mail" required/><br/>
        <input type="submit" name="commander"/>
    </form>
</div>
</body>
</html>