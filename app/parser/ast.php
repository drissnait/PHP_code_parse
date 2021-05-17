<?php
require_once "../../vendor/autoload.php";
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;
/**** Import for the traverser ****/
use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\PrettyPrinter;
header('Content-type: text/plain');

final class AstApp extends NodeVisitorAbstract{
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

    function browsFile(){
      $fp=fopen("fileRes.txt","r");
      while(!feof($fp)){
        echo(fgets($fp)."\n");
      }
      fclose($fp);
    }

    /****
    Convert the AST to a PHP Code
    ****/
    function astToCode($ast){
      $prettyPrinter = new PrettyPrinter\Standard;
      echo $prettyPrinter->prettyPrintFile($ast);
    }

    public function enterNode(Node $node)
   {
       if (! $node instanceof ClassMethod) {
           return false;
       }

       $node->name = new Name('newName');

       // return node to tell parser to modify it
       return $node;
   }


    // getAst("test.php");
    // $ast = (new ParserFactory)->create(ParserFactory::PREFER_PHP7)->parse(file_get_contents("test.php"));
    // astToCode($ast);
    #browsFile();

    // $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7); # or PREFER_PHP5, if your code is older
    // $nodes = $parser->parse(file_get_contents(__DIR__ . '/test.php'));
    // $nodeTraverser = new PhpParser\NodeTraverser;
    // //$traversedNodes->addVisitor(new ChangeMethodNameNodeVisitor);
    // $traversedNodes = $nodeTraverser->traverse($nodes);
}
$astApp=new AstApp();
$astApp->getAst("test.php");
$ast = (new ParserFactory)->create(ParserFactory::PREFER_PHP7)->parse(file_get_contents("test.php"));
$astApp->astToCode($ast);
