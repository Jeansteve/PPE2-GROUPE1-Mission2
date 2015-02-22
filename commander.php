<?php
if(isset($_POST) && isset($_GET['numC']))
{
    $numCommand = $_GET['numC'];

    $nom = $_POST['nom'];
    $rue = $_POST['rue'];
    $cp = $_POST['cp'];
    $ville = $_POST['ville'];
    $email = $_POST['mail'];
    $tel = $_POST['tel'];

    $bdd = new PDO("mysql:host=localhost;dbname=BOCCO-GROUPE1","root","root");

    $req = $bdd->prepare("
        INSERT INTO CLIENT(nom_client, ad_rue_client, cp_client, ville_client, mail_client, tel_client)
        VALUES  (:nom, :rue, :cp, :ville, :mail, : tel)
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

    $req2 = $bdd->prepare("
        INSERT INTO COMMANDE(code_client, date_commande, heure_commande)
        VALUES (:code, DATE, TIME)
    ");

    $req2->bindValue(":code", $id);
    $req2->execute();
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
        <label for="nom">Nom </label><input type="text" name="nom" id="nom"/><br/>
        <label for="rue">Adresse </label><input type="text" name="rue" id="rue"/><br/>
        <label for="cp">Code postal</label><input type="text" name="cp" id="cp"/><br/>
        <label for="ville">Ville </label><input type="text" name="ville" id="ville"/><br/>
        <label for="mail">Email </label><input type="email" name="mail" id="mail"/><br/>
        <label for="tel">Telephone </label><input type="tel" name="tel" id="tel"/><br/>
        <input type="submit" name="commander"/>
    </form>
</div>
</body>
</html>