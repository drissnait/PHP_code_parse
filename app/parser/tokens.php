<?php
$a = 1;
$code=file_get_contents("./test.php");
$file= nl2br(file_get_contents("./test.php"));

$tokens =  token_get_all($code);

$listeClasses=array();
$listeVariable=array();
$listeFunctions=array();

echo("Analyse Code");
echo("<br>");
echo("{");
echo("<br>");
$verificationFonction1=false;
$verificationFonction2=false;
$verificationClasse1=false;
$verificationClasse2=false;
$verificationClasseAccolade=false;
$verificationFonctionAccolade=false;
$nombreAccolade=0;
$nombreAccoladeFonction=0;
$tabInfoFonctions=array();
$listeVariableFonctions=array();
$listeOperateurs=array( "+", "*", "/", "%", "-");
foreach ($tokens as $token) {
    if (is_array($token)) {
        //echo("<br>");
        /*Pour les classes*/
        if (strcmp(token_name($token[0]), "T_CLASS")==0){
          $verificationClasse1=true;
        }
        else if (strcmp(token_name($token[0]), "T_WHITESPACE")==0 and $verificationClasse1==true){
          $verificationClasse2=true;
        }
        else if($verificationClasse2==true){
          array_push($listeClasses,$token[1]);
          $nomClasse=$token[1];
          echo("le nom de la classe est " . $nomClasse);
          echo("<br>");
          $verificationClasse1=false;
          $verificationClasse2=false;
          $verificationClasseAccolade = true;
        }
        /*Pour les fonctions*/
        if (strcmp(token_name($token[0]), "T_FUNCTION")==0){
          $verificationFonction1=true;
        }
        else if (strcmp(token_name($token[0]), "T_WHITESPACE")==0 and $verificationFonction1==true){
          $verificationFonction2=true;
        }
        else if($verificationFonction2==true){
          array_push($listeFunctions,$token[1] . "()");
          $nomFonction=$token[1];
          $verificationFonction1=false;
          $verificationFonction2=false;
          $verificationFonctionAccolade=true;
        }

        if (strcmp(token_name($token[0]), "T_VARIABLE")==0){
          array_push($listeVariable,$token[1]);
        }
        if(strcmp(token_name($token[0]),"T_WHITESPACE")!=0){
          echo "\t-\tLigne : {$token[2]}  :: ", token_name($token[0]), " = (' {$token[1]} ')", PHP_EOL;
          $ligne=$token[2];
          echo("<br>");
        }

        if (strcmp(token_name($token[0]), "T_VARIABLE")==0 && $nombreAccoladeFonction>0){
          if (!in_array ($token[1], $listeVariableFonctions) && !in_array($token[1],$listeOperateurs)){
            array_push($listeVariableFonctions,$token[1]);
          }
        }
    }
    else {
        //var_dump($token);
        //$ligneActu=$ligne+1;
        // echo "\t-\t :: STRING  =>  (' {$token} ')", PHP_EOL;
        // echo("<br>");
        if (strcmp($token, "}")!=0 && $verificationFonctionAccolade==true){
          echo "\t-\t Ligne : $ligne :: STRING  =>  (' {$token} ')", PHP_EOL;
          echo("<br>");
        }
        else if (strcmp($token, "}")==0 && $verificationFonctionAccolade==true){
          $ligneActu=$ligne+1;
          echo "\t-\t Ligne : $ligneActu :: STRING  =>  (' {$token} ')", PHP_EOL;
          echo("<br>");

        }

         if (!in_array ($token, $listeVariableFonctions) && in_array($token, $listeOperateurs)){
           array_push($listeVariableFonctions, $token);
         }
        /*****************************FONCTIONS***********************************/
        if (strcmp($token, "{")==0 && $verificationFonctionAccolade==true){
          $nombreAccoladeFonction+=1;
          echo("----------DEBUT DE LA FONCTION----------" . $nomFonction);
          echo("<br>");
        }
        if (strcmp($token, "}")==0 && $verificationFonctionAccolade==true){
          $nombreAccoladeFonction-=1;
          if ($nombreAccoladeFonction==0){
            echo("----------FIN DE LA FONCTION----------" . $nomFonction);
            echo("<br>");
            $tabInfoFonctions+=[$nomFonction =>$listeVariableFonctions];
            $verificationClasseAccolade=false;
            $listeVariableFonctions=array();
          }
          //echo("echoladeeee dans la classe : " . $nomClasse);
          //echo("<br>");
        }


        /***************************CLASSE*******************************/

        if (strcmp($token, "{")==0 && $verificationClasseAccolade==true){
          $nombreAccolade+=1;
          //echo("echoladeeee dans la classe : " . $nomClasse);
          //echo("<br>");
        }
        if (strcmp($token, "}")==0 && $verificationClasseAccolade==true){
          $nombreAccolade-=1;
          if ($nombreAccolade==0){
            echo("----------FIN DE LA CLASSE----------" . $nomClasse);
            echo("<br>");
          }
        }
    }
}


echo("}");
echo("<br>");
echo("<br>");
//print_r($tabInfoFonctions);
function affichageFonctionsDetails($tab){
  foreach ($tab as $key=>$value){
    foreach($value as $variable){
      echo "\t{$key} => $variable" . "<br>";
    }
  echo("<br>");
  }
}

function affichageVariables($listeVariables){
  $lengthVariable=count($listeVariables);
  for ($i = 0; $i < $lengthVariable; $i++) {
    echo ("Variables " . $i . " => " . $listeVariables[$i]);
    echo "<br>";
  }
}

function affichageFonctions($listeFonctions){
  $lengthFunctions=count($listeFonctions);
  for ($i = 0; $i < $lengthFunctions; $i++) {
    $cpt=$i+1;
    echo ("fonction " . $cpt . " => " . $listeFonctions[$i]);
    echo "<br>";
  }
}

affichageFonctionsDetails($tabInfoFonctions);
affichageVariables($listeVariable);
echo("<br>");
affichageFonctions($listeFunctions);
echo("<br>");
echo("<br>");
echo("<br>");
echo ($file);
?>
