<?php
#__DIR__.
namespace App\parser;

require_once __DIR__."/../../vendor/autoload.php";
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;
/**** Import for the traverser ****/
use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use PhpParser\NodeFinder;
use PhpParser\NodeVisitorAbstract;
use PhpParser\PrettyPrinter;

header('Content-type: text/plain');
class api {
  function detailsCode(){
    $traverser = new NodeTraverser;
    $traverser->addVisitor(new class extends NodeVisitorAbstract {
    public $variablesDansFonctions = [];
    public $fonction="";


    public function enterNode(Node $node) {
        $classes=array();
        $fonction="";
        $variablesDansFonction = array();
        $i=0;
        $fonctionsArray=array();
        $verifVariableFonction=0;
        $tabRes=array();
        if ($node instanceof PhpParser\Node\Stmt\Class_) {
            print_r("Nom de la classe "."  ========>   ".$node->name."\n");
            $nomClasse= (string)$node->name;
            $tabRes+=["nom classe"=>$nomClasse];
        }

        else if ($node instanceof PhpParser\Node\Stmt\ClassMethod) {
            echo("\n");
            print_r("Fonction ==========> ".$node->name."\n");
            $fonction=(string)$node->name;
            $tabRes+=["nom Fonction"=>$fonction];
            #print_r($node->params);
            if (!in_array($fonction, $fonctionsArray)){
              array_push($fonctionsArray,$fonction);
              $i++;
            }
            $verifVariableFonction=1;
            echo($verifVariableFonction);
        }
        else if ($node instanceof PhpParser\Node\Expr\Variable) {
            print_r("   Variable de la fonction ".$fonction."  ========>   ".$node->name."\n");
            echo($verifVariableFonction);
            $variable=(string)$node->name;
            $tabRes+=["nom Variable"=>$variable];
        }
        else if ($node instanceof PhpParser\Node\Stmt\PropertyProperty) {
            print_r("Variable déclarée dans aucune fonction ==========>  ".$node->name."\n");
        }

        else if ($node instanceof PhpParser\Node\Expr\MethodCall) {
            echo("\n\n--------------------------a voir---------------\n");
            print_r("Variable qui permet de faire l'appel de la fonction  ====>  ". $node->var->name."\n");
            print_r("Fonction appellée ==========>  ". $node->name->name."\n");
            for ($x=0;$x<count($node->args);$x++){
                  $numArgument=$x+1;
                  print_r("Argument ".$numArgument."  de l'appel de la fonction =========>  ".$node->args[$x]->value->name."\n");
            }
            $fonction=(string)$node->name;
        }
        else if ($node instanceof PhpParser\Node\Stmt\Return_) {
            print_r("   Retour de la fonction     ========>   ".$node->expr->name."\n");
            $variableRetour=(string)$node->expr->name;
            $tabRes+=["RetourFonction"=>$variableRetour];
        }
    }

    });
    // $file=request()->input('fichierimportAPI');
    // $stmts =  (new ParserFactory)->create(ParserFactory::PREFER_PHP7)->parse(file_get_contents($file));
    $stmts =  (new ParserFactory)->create(ParserFactory::PREFER_PHP7)->parse(file_get_contents("C:/Wamp/www/stage/app/parser/test.php"));
    $modifiedStmts = $traverser->traverse($stmts);
  }
}

$test=new api;
$test->detailsCode();
?>
