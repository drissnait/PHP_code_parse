<?php
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
use PhpParser\Node\Expr\BinaryOp;

header('Content-type: text/plain');
class api{
  function detailsCode($file){
    $traverser = new NodeTraverser;

    $traverser->addVisitor(new class extends NodeVisitorAbstract {
    public $variablesDansFonctions = [];
    public $fonction="";
    public function enterNode(Node $node) {
        $fonction="";
        $variablesDansFonction = array();
        $i=0;
        $fonctionsArray=array();
        $verifVariableFonction=0;
        if ($node instanceof PhpParser\Node\Stmt\Use_) {
            #print_r(($node->uses[0]->name->parts));
            for ($k=0; $k<count($node->uses);$k++){
              echo("Bibliothèque  ===>  "  );
              for ($k2=0;$k2<count($node->uses[$k]->name->parts);$k2++){
                echo ("/".$node->uses[$k]->name->parts[$k2]);
              }
              #$tab="attributes:protected";
              #echo($node->uses[$k]->attributes->startLine);
              echo("\n");
            }
            #print_r($node);
            #$numligne+=1;
        }
        if ($node instanceof PhpParser\Node\Stmt\Class_) {
            echo("\n\n");
            print_r("Nom de la classe "."  ========>   ".$node->name ."\n");
            #print_r($node);
            #$numligne+=1;
        }
        else if ($node instanceof PhpParser\Node\Stmt\ClassMethod) {
            echo("\n");
            print_r("   Fonction ==========> ".$node->name."\n");
            $fonction=(string)$node->name;
            for ($k=0;$k<count($node->params);$k++){
              $numParametre=$k+1;
              print_r("       Paramètre " . $numParametre ." de la fonction  ========>  ". $node->params[$k]->var->name ."\n");
            }
            #print_r($node->params);
            if (!in_array($fonction, $fonctionsArray)){
              array_push($fonctionsArray,$fonction);
              $i++;
            }
            $verifVariableFonction=1;
            #echo($verifVariableFonction);

        }
        else if ($node instanceof PhpParser\Node\Stmt\For_){
          print_r("       Boucle For              ======>  ".$node->init[0]->var->name);
          if(isset($node->init[0]->expr->value)){
            print_r(" = ".$node->init[0]->expr->value );
          }
          else if(!isset($node->init[0]->expr->value) and isset($node->init[0]->expr->name)){
            print_r(" = ". $node->init[0]->expr->name);
          }
          if (isset($node->cond)){
            print_r(" ; " . $node->cond[0]->left->name . "  op  " . $node->cond[0]->right->value."\n");
            #print_r($node->cond[0]);
          }
          echo("\n");
          #print_r($node);
        }
        // else if ($node instanceof PhpParser\Node\Expr\Variable) {
        //   print_r($node);
        //     print_r("   Variable dans la fonction ".$fonction."  ========>   ".$node->name. "\n");
        //     #echo($verifVariableFonction);
        // }
        else if ($node instanceof PhpParser\Node\Stmt\Echo_) {
            print_r("       Affichage avec un echo  =====>  ". $node->exprs[0]->value .  "\n");
        }
        else if ($node instanceof PhpParser\Node\Stmt\Expression) {
            #print_r($node);
            /************Constante******************/
            if (isset($node->expr->args)){
              print_r("       Constante                   ========>  ". $node->expr->args[0]->value->value . " = ".$node->expr->args[1]->value->value .   "\n");
            }
            /************Variable isntancié******************/
            if (isset($node->expr->var)){
              if(isset($node->expr->expr->value)){
                print_r("       Variable dans la fonction "."  ========>   ".$node->expr->var->name." = ". $node->expr->expr->value."\n");
              }
              /*********tableau déclaré sous la forme : tab=array()*********/
              else if(isset($node->expr->expr)){
                print_r("       Tableau dans la fonction    ========>  ".$node->expr->var->name."\n");
              }
              /*********tableau déclaré sous la forme : $tab[]**********/
              else if(isset($node->expr->var)){
                /********Si la taille est indique************/
                if(isset($node->expr->dim->value)){
                  print_r("       Tableau dans la fonction    ========>  ".$node->expr->var->name." de taille : ".$node->expr->dim->value. "\n");
                }
                /********Si la taille n'est pas indiqueé*******/
                else if(!isset($node->expr->dim->value)){
                  print_r("       Tableau dans la fonction    ========>  ".$node->expr->var->name." de taille : (non spécifié)" . "\n");
                }
              }
            }
            else if ($node instanceof PhpParser\Node\Expr\ArrayDimFetch){
              print_r($node);
            }
            /************Variable non isntancié******************/
            if(isset($node->expr->name) and $node->expr->name !="define"){
              print_r("       Variable dans la fonction "."  ========>   ".$node->expr->name."\n");
            }
        }
        else if ($node instanceof PhpParser\Node\Stmt\Property) {
            echo("\n");
            print_r("   Variable déclarée dans aucune fonction ==========>  ");
            if(isset($node->type)){
              if(isset($node->flags)){
                switch($node->flags){
                  case 1:
                    print_r(" Public ");
                    break;
                  case 2:
                    print_r(" Protected ");
                    break;
                  case 4:
                    print_r(" Private ");
                    break;
                }

              }
              if(isset($node->type->parts[0])){
                print_r( $node->type->parts[0] . "  " .$node->props[0]->name->name);
              }
              else if(isset($node->type->name)){
                print_r( $node->type->name ."  " .$node->props[0]->name->name);
              }
            }
            else if (!isset($node->type)){
              print_r($node->props[0]->name->name);
            }
            if (isset($node->props[0]->default->value)){
              print_r(" = " . $node->props[0]->default->value);
            }

        }
        // else if ($node instanceof PhpParser\Node\Stmt\PropertyProperty) {
        //     echo("\n");
        //     //print_r($node);
        //     print_r("Variable déclarée dans aucune fonction ==========>  ".$node->name." = ". $node->default->value."\n");
        // }

        else if ($node instanceof PhpParser\Node\Expr\MethodCall) {
            echo("\n\n--------------------------a voir---------------\n");
            print_r("   Variable qui permet de faire l'appel de la fonction  ====>  ". $node->var->name."\n");
            print_r("   Fonction appellée ==========>  ". $node->name->name. "\n");
            for ($x=0;$x<count($node->args);$x++){
                  $numArgument=$x+1;
                  print_r("   Argument ".$numArgument."  de l'appel de la fonction =========>  ".$node->args[$x]->value->name. "\n");
            }
            $fonction=(string)$node->name;
        }
        else if ($node instanceof PhpParser\Node\Stmt\Return_) {
            print_r("       Retour de la fonction       ========>   ".$node->expr->name. "\n");
        }


    }
    });

    $stmts =  (new ParserFactory)->create(ParserFactory::PREFER_PHP7)->parse(file_get_contents($file));
    $modifiedStmts = $traverser->traverse($stmts);
  }


  /***
  Get the AST of the file passed as an argument
  ****/
  function getAst($file){
    $fileRes=fopen("fileRes.txt","w");
    $code=file_get_contents($file);
    $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
    try {
        $ast = $parser->parse($code);
    } catch (Error $error) {
        echo "Parse error: {$error->getMessage()}\n";
        return;
    }
    $dumper = new NodeDumper;
    echo $dumper->dump($ast) . "\n";
    fwrite($fileRes, $dumper->dump($ast) . "\n");
    fclose($fileRes);
  }

  /****
  Convert the AST to a PHP Code
  ****/
  function astToCode($ast){
    $prettyPrinter = new PrettyPrinter\Standard;
    echo $prettyPrinter->prettyPrintFile($ast);
  }
}

$test=new api;
#$test->getAst("test2.php");
#echo("---------------------------DETAILS CODE ---------------------------------\n\n");
$test->detailsCode("test2.php");
#echo("---------------------------CONVERSION AST EN CODE ------------------------\n\n");
#$ast = (new ParserFactory)->create(ParserFactory::PREFER_PHP7)->parse(file_get_contents("test.php"));
#$test->astToCode($ast);





$traverser = new NodeTraverser;

class ExtractVars extends NodeVisitorAbstract {
    private $prettyPrinter = null;

    private $classes=[];
    private $variables = [];
    private $fonctions=[];
    private $expressions = [];
    private $operateurs=[];
    private $occurenceOperateurs=[];



    public function __construct() {
        $this->prettyPrinter = new PhpParser\PrettyPrinter\Standard;
    }

    public function leaveNode(Node $node) {
          $indiceOperateurs=0;
          if ($node instanceof PhpParser\Node\Stmt\Class_){
            $this->classes[]=(string)$node->name;
          }

        if ( $node instanceof PhpParser\Node\Expr\Variable )   {
            $variable = $this->prettyPrinter->prettyPrintExpr($node);
            if(in_array($variable,$this->variables)==FALSE){
              $this->variables[] = $variable;
            }
            $variableList[] = $variable;
        }
        else if($node instanceof PhpParser\Node\Stmt\Property){
          $variable =$node->props[0]->name->name;
          if(in_array($variable,$this->variables)==FALSE){
            $this->variables[] = $variable;
          }
          $variableList[] = $variable;
        }
        else if ($node instanceof PhpParser\Node\Stmt\ClassMethod) {
            #$fonction=$this->prettyPrinter->prettyPrintExpr($node->name);
            $this->fonctions[]=(string)$node->name;
        }
        else if($node instanceof PhpParser\Node\Expr\BinaryOp){
          #print_r($node);
          $this->operateurs[]=$node->getOperatorSigil();
        }
        else if($node instanceof PhpParser\Node\Expr\BinaryOp){
          #print_r($node);
          $this->operateurs[]=$node->getOperatorSigil();
        }
    }

    public function getClasses(){
      echo("\n\nListe des classes{\n");
      for ($k=0;$k<sizeof($this->classes);$k++){
        print_r("   " . $this->classes[$k] ."\n");
      }
      echo("}");
    }

    public function getFonctions(){
      echo("\n\n");
      echo("Liste des fonctions { \n");
      for ($k=0;$k<sizeof($this->fonctions);$k++){
        print_r("   Fonction $k ===> " . $this->fonctions[$k]."\n");
      }
      echo("}");
    }

    public function getVariables(){
      echo("\n\n");
      echo("Liste des variables (sans répétition) {\n");
      for ($k=0;$k<sizeof($this->variables);$k++){
        print_r("   Variable $k ===> " . $this->variables[$k] ."\n");
      }
      echo("}");
    }
    public function getOperateurs(){
      $operateursFin=array();
      $nbrOccurences=array();
      $indiceOperateurs=0;
      echo("\n\n");
      echo("Liste des operateurs{\n");
      for ($k=0;$k<sizeof($this->operateurs);$k++){
        $occurence=0;
        $operateur=$this->operateurs[$k];
        if(!in_array($operateur,$operateursFin)){
          $operateursFin[]=$operateur;
          $indice=sizeof($operateursFin)-1;
          for($j=0;$j<sizeof($this->operateurs);$j++){
            if($this->operateurs[$j]==$operateur){
              $occurence++;
              $nbrOccurences[$indice]=$occurence;
            }
          }
        }
      }

      for($i=0;$i<sizeof($operateursFin);$i++){
        print_r("   ".$operateursFin[$i]."   =======>   ".$nbrOccurences[$i]." fois. \n");
      }
      echo("}\n");
    }

    public function getExpressions() : array    {
        return $this->expressions;
    }

}
echo("\n\n\n\n------------------------------RESUME DATA CODE--------------------------------\n");
$varExtract = new ExtractVars();
$traverser->addVisitor ($varExtract);
$ast = (new ParserFactory)->create(ParserFactory::PREFER_PHP7)->parse(file_get_contents("test2.php"));
$traverser->traverse($ast);

$varExtract->getClasses();
$varExtract->getFonctions();
$varExtract->getVariables();
$varExtract->getOperateurs();


#print_r($varExtract->getExpressions());
?>
