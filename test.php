<?php

require_once 'Token.php';
require_once 'Lexer.php';

$input = <<<PAS
program example1;

var a,b c: integer;
begin
a:= 1;
b:= 2;
c:= a+b;
end.
PAS;

$lexer = new Lexer($input);

while (($token = $lexer->getNextToken()) && null !== $token->getValue()) {
  echo sprintf("type=%s\tvalue=%s\n", $token->getType(), $token->getValue());
}
