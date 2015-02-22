<?php
if(isset($_POST['num']))
{
    $num = $_POST['num'];
    $bdd = new PDO("mysql:host=localhost;dbname=BOCCO-GROUPE1","root","root");
    $req = $bdd->prepare("
              SELECT PL.libelle_plateau, PL.prix_plateau, F.libelle_fromage, P.libelle_plat, E.libelle_entree, D.Libelle_Dessert
                FROM PLATEAU PL, FROMAGE F, PLAT P, ENTREE E, DESSERT D
                WHERE PL.num_plateau = :num
                AND PL.code_fromage = F.Code_fromage
                AND PL.code_plat = P.Code_plat
                AND PL.code_entree = E.Code_entree
                AND PL.code_dessert = D.Code_Dessert
    ") or die(print_r($bdd->errorInfo()));
    $req->bindParam(":num", $num);
    $req->execute();

    //echo print_r($bdd->errorInfo());
    $donnee = $req->fetch();

   json_encode($donnee);

    //echo $donnee['libelle_plateau'];

}
else
{
    echo "erreur";
}

?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Bocco-commande</title>
</head>
<body>
<div id="container">
    <h1>BOCCO - COMMANDE</h1>
    <ul id="list_plateau">
        <?php
        $bdd = new PDO("mysql:host=localhost;dbname=BOCCO-GROUPE1","root","root");
        $req = $bdd->query("SELECT num_plateau, libelle_plateau, prix_plateau from PLATEAU");
        $donnees = $req->fetchAll();

        json_encode($donnees);

        foreach($donnees as $plateau)
        {
            $num = $plateau['num_plateau'];
            //Quand il clicque j'appelle la fonction pour obtenir les détails
            echo "<li onclick=\"getDetails(event, ".$num.")\" ><span class=\"titre\" >".$plateau['libelle_plateau']."</span>   <span class=\"prix\">".$plateau['prix_plateau']."€</span></li>";
        }
        ?>
    </ul>
    <form method="post" name="formulaire">
        <input type="text" name="num" value= "" hidden="hidden"/>
    </form>
    <div id="details">
            <?php
            //si il a sélectionné un plateau pour afficher les détails
            if(isset($_POST['num']))
            {
                echo "<h2>Détails ".$donnee['libelle_plateau']."</h2>";
                echo "<ul>";
                foreach($donnee as $key => $elm)
                {
                    if(!is_integer($key)) // je vérifie si l'index n'est pas un entier car le résultat Json me retourne un index entier et une chaine
                    {
                        echo "<li>".utf8_encode($key)." = ".utf8_encode($elm)."</li>";
                    }
                }
                echo "</ul>";
            }
            ?>

    </div>
</div>
<script type="text/javascript">

    function getDetails(event, num){
    //je récupère le numéro du plateau contenue dans l'attribut value
    //var num = event.target.parentNode.getAttribute("value");
        document.getElementsByName("num")[0].setAttribute("value", num);
        document.formulaire.submit();
        //alert (event.target.parentNode.getAttribute("value"));
    }
</script>
</body>
</html>
