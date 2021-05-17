<?php

// On parcourt tous nos fichiers php
echo("yes1");
echo ('<br>');
$res = nl2br(file_get_contents( "./test.php" ));
if($res==false){
  echo "error";
}else{
  echo "ouverture réussie";
  echo ('<br>');
}

echo ('<br>');
 foreach (glob('./test.php') as $file) {
   echo("yes2");
   echo ('<br>');
    $content = nl2br(file_get_contents($file)); // On récupère le contenu

    echo ('<br>');
    $tokens = token_get_all($content);   // On récupère la séquence de tokens


    foreach ($tokens as $token) {
      echo("----");
      echo("<br>");
      if (is_array($token)) {
          echo "Line {$token[2]}: ", token_name($token[0]), " ('{$token[1]}')";
      }
      echo("<br>");
    }

    // On parcourt les tokens
    foreach ($tokens as $index => $token) {
      echo("entrée deuxième boucle");
      echo("<br>");
        if (!is_array($token)) continue; // On ignore les tokens string

        if (is_array($token)) {
            echo "Line {$token[2]}: ", token_name($token[0]), " ('{$token[1]}')";
        }

        // Il ne faut surtout pas se fier à la valeur du type de token
        // car elle peut changer entre 2 versions de PHP.
        // Heureusement, token_name() permet de récupérer le nom correspondant.
        // Ici, on n’est intéressé que par les déclarations de classe.
        if (token_name($token[0]) !== 'T_CLASS') continue;

        // On sait qu’un whitespace sépare obligatoirement
        // le mot-clé class du nom de la classe.
        $classNameToken = $tokens[$index + 2];

        $className = $classNameToken[1];
        $line = $classNameToken[2];

        if (!isStudlyCaps($className)) {
            echo sprintf(
                'Le fichier %s définit sur la ligne %s, la classe "%s" dont le nom n\' est pas en StudlyCaps',
                $file,
                $line,
                $className
            );

        }
    }
}

echo("yes");
?>
