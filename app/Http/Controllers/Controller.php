<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
#require_once "../../../vendor/autoload.php";
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
use App\parser\MyNodeVisitor;
header('Content-type: text/plain');

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(){
            return view('app');
    }

    public function import(){
            return view('import');
    }
    public function importApi(){
            return view('importApi');
    }

    function getAst(){
      $file=request()->input('fichierimport');
      $fileRes=fopen("fileRes.txt","w");
      if(file_exists($file)){
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
      else{
        //echo("Le fichier n'existe pas, veuillez réssayer");
        return view('importError');
      }
     }

    function getListeVariables(){
       $fp=fopen("fileRes.txt","r");
       $varRegex = "/VarLikeIdentifier|Expr_Variable/";
       $arrayVariables=array();
       $i=0;
       echo("liste des Variables { \n\n");
       while(!feof($fp)){
         if(preg_match($varRegex,fgets($fp))==true){
           $ligne=trim(fgets($fp));
           $variable=explode(":",$ligne)[1];
           if (!in_array($variable, $arrayVariables)){
             array_push($arrayVariables,$variable);
             echo("      Variable ".$i." => ".$variable."\n");
             $i++;
           }
         }
       }
       echo("\n}");
       fclose($fp);
     }

     function getDetailsCode(){
       $traverser = new PhpParser\NodeTraverser;
       #$traverser->addVisitor(new MyNodeVisitor);
       $traverser->addVisitor(new class extends NodeVisitorAbstract {
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
       }
       });
       $stmts =  (new ParserFactory)->create(ParserFactory::PREFER_PHP7)->parse(file_get_contents("test.php"));
       #print_r($stmts);
       $modifiedStmts = $traverser->traverse($stmts);
     }

}
