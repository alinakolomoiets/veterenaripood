<?php
require_once ('connect2.php');

global $yhendus;

if (isset($_REQUEST['lisamisvorm']) && !empty($_REQUEST["nimi"])){
    $paring=$yhendus->prepare(
        "INSERT INTO loomad(loomanimi,Vanus,Tervist)Value (?,?,?)"
    );
    $paring->bind_param("sis",$_REQUEST["nimi"],$_REQUEST["vanus"],$_REQUEST["tervist"]);
    $paring->execute();

}

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Veterenaripood</title>
</head>
<body>
<h1>Veterenaripood </h1>
<link rel="stylesheet" type="text/css"  href="loomadstyle2.css">
<div id="menu">
    <ul>
        <?php
        //näitab loomade loetelu tabelist loomad
        $paring=$yhendus->prepare("SELECT loomadID, loomanimi  FROM loomad");
        $paring->bind_result($id,$nimi);
        $paring->execute();

        while ( $paring->fetch()){
            echo "<li><a href='?id=$id'>$nimi</a></li>";

        }
        echo"</ul>";
        echo"<a href='?lisaloom=jah'>Lisa Loom</a>"
        ?>

</div>
<div id="sisu">
    <h4>Siia tuleb loomade info:</h4>
    <?php

    if(isset($_REQUEST['kustuta'])){

        $paring=$yhendus->prepare("DELETE FROM loomad WHERE loomadID=?");
        $paring->bind_param('i',$_REQUEST['kustuta']);
        $paring->execute();
    }
    if(isset($_REQUEST["id"]))
    {
        $paring=$yhendus->prepare("SELECT loomadID,loomanimi , Vanus ,Tervist  FROM loomad WHERE loomadID=?");
        $paring->bind_param("i",$_REQUEST["id"]);
        //küsimärki asemel aadressiribalt tuleb id
        $paring->bind_result($id,$nimi,$vanus,$tervist);
        $paring->execute();
        if($paring->fetch()){
            echo "<div><strong>".htmlspecialchars($nimi)."</strong> ";
            echo  htmlspecialchars($vanus)." aastat.";
            echo  htmlspecialchars($tervist)." tervist.";
            echo"<a href='?kustuta=$id'>Kustuta</a>";
            echo"</div>";
        }
    }
    if(isset($_REQUEST['lisaloom'])){
        ?>
        <h2>Uue looma lisamine</h2>
        <form name="uusloom" method="post" action="?">
            <input type="hidden" name="lisamisvorm" value="jah">
            <input type="text" name="nimi" placeholder="Looma nimi">
            <br>
            <input type="number" name="vanus" max="90" placeholder="Looma vanus">
            <br>
            <input type="text" name="tervist" placeholder="Looma tervist">
            <br>
            <input type="submit" value="OK">

        </form>
        <?php
    }
    else {
        echo"<h5>loomade info</h5>";
    }
    ?>

</div>



</body>
</html>
