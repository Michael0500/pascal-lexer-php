<?php


/**
 * Lexer of a language.
 *
 * @class
 * @since 1.0.0
 */
class Lexer
{
  private string $input;
  private int $position;
  private ?string $currentChar;
  private int $length;
  public array $RESERVED_WORDS;

  /**
   * Creates a new instance of a lexer.
   * When instance created, you need to call {@link Lexer#getNextToken} for get a Token::
   * Each time you call {@link Lexer#getNextToken} it returns next token from specified input.
   *
   * @param {String} input Source code of a program
   * @example
   * const lexer = new Lexer('2 + 5');
   */
  public function __construct(string $input)
  {
    $this->input = $input;
    $this->position = 0;
    $this->currentChar = $this->input[$this->position];
    $this->length = strlen($this->input);
    $this->RESERVED_WORDS = [
      'PROGRAM' => Token::create(Token::TOK_PROGRAM(), 'PROGRAM'),
      'VAR' => Token::create(Token::TOK_VAR(), 'VAR'),
      'DIV' => Token::create(Token::TOK_INTEGER_DIV(), 'DIV'),
      'INTEGER' => Token::create(Token::TOK_INTEGER_TYPE(), 'INTEGER_TYPE'),
      'REAL' => Token::create(Token::TOK_REAL_TYPE(), 'REAL_TYPE'),
      'BEGIN' => Token::create(Token::TOK_BEGIN(), 'BEGIN'),
      'END' => Token::create(Token::TOK_END(), 'END'),
      'PROCEDURE' => Token::create(Token::TOK_PROCEDURE(), 'PROCEDURE'),
    ];
  }

  /**
   * Lexer has a pointer that specifies where we are located right now in input.
   * This method moves this pointer by one, incrementing its value.
   * Afterwards, it reads a new char at new pointer's location and stores in `currentChar`.
   * In case, pointer is out-of-range (end of input) it assigns `null` to `currentChar`.
   *
   * @returns {Lexer} Returns current instance of the lexer
   * @example
   * const lexer = new Lexer('2 + 5'); // position = 0, currentChar = '2'
   *
   * lexer
   *  .advance() // position = 1, currentChar = ' '
   *  .advance() // position = 2, currentChar = '+'
   *  .advance() // position = 3, currentChar = ' '
   *  .advance() // position = 4, currentChar = '5'
   *  .advance() // position = 5, currentChar = null
   *  .advance() // position = 6, currentChar = null
   */
  public function advance()
  {
    $this->position += 1;

    if ($this->position > $this->length - 1) {
      $this->currentChar = null;
    } else {
      $this->currentChar = $this->input[$this->position];
    }

    return $this;
  }

  /**
   * Peeks a following character from the input without modifying the pointer.
   * The difference here between {@link Lexer#advance} is that this method is pure.
   * It helps differentiate between different tokens that start with the same character.
   * I.e. ':' and ':=' are different tokens, but we can't say that for sure until we see the next char.
   *
   * @returns {String}
   * @example
   * const lexer = new Lexer('2 + 5'); // pointer = 0, currentChar = '2'
   *
   * lexer
   *  .peek() // pointer = 0, currentChar = '2', returns ' '
   *  .advance() // pointer = 1, currentChar = ' '
   *  .peek() // pointer = 1, currentChar = ' ', returns '+'
   */
  public function peek()
  {
    $position = $this->position + 1;

    if ($position > $this->length - 1) {
      return null;
    }

    return $this->input[$position];
  }

  /**
   * Skips whitespaces in a source code.
   * While `currentChar` is a whitespace do {@link Lexer#advance}.
   * That way, we literally skips any char that doesn't make sense to us.
   *
   * @returns {Lexer} Returns current instance of the lexer
   */
  public function skipWhitespace()
  {
    while (!is_null($this->currentChar) && ctype_space($this->currentChar)) {
      $this->advance();
    }

    return $this;
  }

  /**
   * Skips all the comments in a source code.
   * While `currentChar` is not a closing comments block, we advance the pointer.
   * The last one advance is for eating a curly brace itself.
   *
   * @returns {Lexer}
   */
  public function skipComment()
  {
    while (!is_null($this->currentChar) && $this->currentChar !== '}') {
      $this->advance();
    }

    return $this->advance();
  }

  /**
   * Parses an number from a source code.
   * While `currentChar` is a digit [0-9], add a char into the string stack.
   * Afterwards, when `currentChar` is not a digit anymore, parses an number from the stack.
   *
   * @returns {Number}
   */
  public function number()
  {
    $number = '';

    while (!is_null($this->currentChar) && ctype_digit($this->currentChar)) {
      $number .= $this->currentChar;
      $this->advance();
    }

    if ($this->currentChar === '.') {
      $number .= $this->currentChar;
      $this->advance();

      while (!is_null($this->currentChar) && ctype_digit($this->currentChar)) {
        $number .= $this->currentChar;
        $this->advance();
      }

      return Token::create(Token::TOK_REAL_LITERAL(), floatval($number));
    }

    return Token::create(Token::TOK_INTEGER_LITERAL(), intval($number));
  }

  /**
   * Parses a sequence of alphanumeric characters and returns a Token::
   * In case, the sequence is reserved word, it returns a predefined token for this word.
   * Otherwise, it returns an IDENTIFIER Token::
   *
   * @returns {Token}
   * @example
   * const lexer = new Lexer('BEGIN x END');
   *
   * lexer.identifier(); // Token(BEGIN, BEGIN)
   * lexer.identifier(); // Token(IDENTIFIER, x)
   * lexer.identifier(); // Token(END, END)
   */
  public function identifier()
  {
    $identifier = '';

    while (!is_null($this->currentChar) && ctype_alnum($this->currentChar)) {
      $identifier .= $this->currentChar;
      $this->advance();
    }

    return $this->RESERVED_WORDS[mb_strtoupper($identifier)] ?? Token::create(Token::TOK_IDENTIFIER(), $identifier);
  }

  /**
   * Returns a next token in a source program.
   * Each time it sees a match from the source program, it wraps info into a {@link Token}.
   * It means, that it doesn't return all the tokens at once.
   * You need to call this method each time, you need to get next token from the input program.
   *
   * @returns {Token}
   * @example
   * const lexer = new Lexer('2 + 5');
   *
   * lexer.getNextToken(); // Token(INTEGER, 2)
   * lexer.getNextToken(); // Token(PLUS, +)
   * lexer.getNextToken(); // Token(INTEGER, 5)
   * lexer.getNextToken(); // Token(EOF, null)
   * lexer.getNextToken(); // Token(EOF, null)
   */
  public function getNextToken()
  {
    while (!is_null($this->currentChar)) {
      if (ctype_space($this->currentChar)) {
        $this->skipWhitespace();
        continue;
      }

      if ($this->currentChar === '{') {
        $this->advance();
        $this->skipComment();
        continue;
      }

      if (ctype_digit($this->currentChar)) {
        return $this->number();
      }

      if (ctype_alpha($this->currentChar)) {
        return $this->identifier();
      }

      if ($this->currentChar === ':' && $this->peek() === '=') {
        $this->advance()->advance();
        return Token::create(Token::TOK_ASSIGN(), ':=');
      }

      if ($this->currentChar === ':') {
        $this->advance();
        return Token::create(Token::TOK_COLON(), ':');
      }

      if ($this->currentChar === ',') {
        $this->advance();
        return Token::create(Token::TOK_COMMA(), ',');
      }

      if ($this->currentChar === ';') {
        $this->advance();
        return Token::create(Token::TOK_SEMICOLON(), ';');
      }

      if ($this->currentChar === '.') {
        $this->advance();
        return Token::create(Token::TOK_DOT(), '.');
      }

      if ($this->currentChar === '+') {
        $this->advance();
        return Token::create(Token::TOK_PLUS(), '+');
      }

      if ($this->currentChar === '-') {
        $this->advance();
        return Token::create(Token::TOK_MINUS(), '-');
      }

      if ($this->currentChar === '*') {
        $this->advance();
        return Token::create(Token::TOK_ASTERISK(), '*');
      }

      if ($this->currentChar === '/') {
        $this->advance();
        return Token::create(Token::TOK_SLASH(), '/');
      }

      if ($this->currentChar === '(') {
        $this->advance();
        return Token::create(Token::TOK_LEFT_PARENTHESIS(), '(');
      }

      if ($this->currentChar === ')') {
        $this->advance();
        return Token::create(Token::TOK_RIGHT_PARENTHESIS(), ')');
      }

      self::error("Unexpected character: {$this->currentChar}");
    }

    return Token::create(Token::TOK_EOF(), null);
  }

  /**
   * @param string $msg
   * @throws Exception
   */
  public static function error(string $msg)
  {
    throw new Exception("[Lexer]\n $msg");
  }
}
