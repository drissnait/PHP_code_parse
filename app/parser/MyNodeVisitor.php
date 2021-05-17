<?php
namespace App\parser;
require_once "../../vendor/autoload.php";
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
use App\parser\NodeVisitor;
use Log;

final class MyNodeVisitor extends NodeVisitorAbstract
{
  public function enterNode(Node $node) { 
      $fonction="";
      $variablesDansFonction = array();
      $i=0;
      $fonctionsArray=array();
      $verifVariableFonction=0;
      if ($node instanceof PhpParser\Node\Stmt\Class_) {
          print_r("Nom de la classe "."  ========>   ".$node->name."\n");
      }
      else if ($node instanceof PhpParser\Node\Stmt\ClassMethod) {
          echo("\n");
          print_r("Fonction ==========> ".$node->name."\n");
          $fonction=(string)$node->name;
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
      }
      echo("passe 1");
  }
}
$stmts =  (new ParserFactory)->create(ParserFactory::PREFER_PHP7)->parse(file_get_contents("test.php"));
$traverser = new NodeTraverser;
$traverser->addVisitor(new MyNodeVisitor());
$modifiedStmts = $traverser->traverse($stmts);
echo("la");
?>
