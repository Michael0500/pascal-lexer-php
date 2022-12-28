<?php

/**
 * Class Token
 */
class Token
{
  private string $type;
  private ?string $value;

  /**
   * Creates a new Token instance.
   *
   * @param {String} type Token type from {@link Token} static dictionary
   * @param {String} value Value of a token
   * @example
   * new Token(Token.INTEGER, '1234');
   * new Token(Token.PLUS, '+');
   * new Token(Token.INTEGER, '5678');
   */
  public function __construct(string $type, ?string $value)
  {
    $this->type = $type;
    $this->value = $value;
  }

  /**
   * Returns a type of a token.
   *
   * @returns {String}
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   * Returns a value of a token.
   *
   * @returns {String}
   */
  public function getValue()
  {
    return $this->value;
  }

  /**
   * Check if specified token type is this token.
   *
   * @param {String} tokenType Token type from {@link Token} static dictionary
   * @returns {Boolean} Returns true if provided type is equal to type of this token
   * @example
   * const token = Token.create(Token.INTEGER, '234');
   *
   * token.is(Token.INTEGER); // true
   * token.is(Token.ASTERISK); // false
   */
  public function is($tokenType)
  {
    return $this->getType() === $tokenType;
  }

  /**
   * Converts a token into string representation.
   * It useful when you need to debug some tokens.
   * Instead of printing a token as object, it will print as a string.
   * Format of this string is following: Token(<type>, <value>).
   *
   * @returns {String} Returns a string in format Token(<type>, <value>)
   * @example
   * const token = Token.create(Token.INTEGER, '1234');
   *
   * console.log(token); // Token(INTEGER, 1234)
   */
  public function toString()
  {
    return "Token({$this->getType()}, {$this->getValue()})";
  }

  /**
   * Creates a new Token instance.
   *
   * @static
   * @param {String} type Token type from {@link Token} static dictionary
   * @param {String} value Value of a token
   * @returns {Token} Returns instantiated instance of a Token
   * @example
   * Token.create(Token.INTEGER, 1234);
   * Token.create(Token.PLUS, '+');
   * Token.create(Token.INTEGER, 5678);
   */
  public static function create($type, $value)
  {
    return new self($type, $value);
  }

  /**
   * Returns a Token type for a plus symbol (+).
   *
   * @static
   * @returns {String}
   */
  public static function TOK_PLUS()
  {
    return 'PLUS';
  }

  /**
   * Returns a Token type for a minus symbol (-).
   *
   * @static
   * @returns {String}
   */
  public static function TOK_MINUS()
  {
    return 'MINUS';
  }

  /**
   * Returns a Token type for an asterisk symbol (*).
   *
   * @static
   * @returns {String}
   */
  public static function TOK_ASTERISK()
  {
    return 'ASTERISK';
  }

  /**
   * Returns a Token type for a slash sign (/).
   *
   * @static
   * @returns {String}
   */
  public static function TOK_SLASH()
  {
    return 'SLASH';
  }

  /**
   * Returns a Token type for a backslash sign (\).
   *
   * @static
   * @returns {String}
   */
  public static function TOK_BACKSLASH()
  {
    return 'BACKSLASH';
  }

  /**
   * Returns a Token type for a comma symbol (,).
   *
   * @static
   * @returns {String}
   */
  public static function TOK_COMMA()
  {
    return 'COMMA';
  }

  /**
   * Returns a Token type for a dot symbol (.).
   *
   * @static
   * @returns {String}
   */
  public static function TOK_DOT()
  {
    return 'DOT';
  }

  /**
   * Returns a Token type for a colon symbol (:).
   *
   * @static
   * @returns {String}
   */
  public static function TOK_COLON()
  {
    return 'COLON';
  }

  /**
   * Returns a Token type for a semicolon symbol (;).
   *
   * @static
   * @returns {String}
   */
  public static function TOK_SEMICOLON()
  {
    return 'SEMICOLON';
  }

  /**
   * Returns a Token type for a left parenthesis "(".
   *
   * @static
   * @returns {String}
   */
  public static function TOK_LEFT_PARENTHESIS()
  {
    return 'LEFT_PARENTHESIS';
  }

  /**
   * Returns a Token type for a right parenthesis ")".
   *
   * @static
   * @returns {String}
   */
  public static function TOK_RIGHT_PARENTHESIS()
  {
    return 'RIGHT_PARENTHESIS';
  }

  /**
   * Returns a Token type for an ASSIGN sequence of chars (:=).
   *
   * @static
   * @returns {String}
   */
  public static function TOK_ASSIGN()
  {
    return 'ASSIGN';
  }

  /**
   * Returns a Token type for end-of-file.
   *
   * @static
   * @returns {String}
   */
  public static function TOK_EOF()
  {
    return 'EOF';
  }

  /**
   * Returns a Token type for a BEGIN keyword.
   * This token marks a beginning of a compound statement.
   *
   * @static
   * @returns {String}
   */
  public static function TOK_BEGIN()
  {
    return 'BEGIN';
  }

  /**
   * Returns a Token type for an END keyword.
   * This token marks the end of a compound statement.
   *
   * @static
   * @returns {String}
   */
  public static function TOK_END()
  {
    return 'END';
  }

  /**
   * Returns a Token type for identifiers in a program.
   * Valid identifier starts with an alphabetical character.
   *
   * @static
   * @returns {String}
   */
  public static function TOK_IDENTIFIER()
  {
    return 'IDENTIFIER';
  }

  /**
   * Returns a Token type for a PROGRAM keyword.
   *
   * @static
   * @returns {String}
   */
  public static function TOK_PROGRAM()
  {
    return 'PROGRAM';
  }

  /**
   * Returns a Token type for a VAR keyword.
   *
   * @static
   * @returns {String}
   */
  public static function TOK_VAR()
  {
    return 'VAR';
  }

  /**
   * Returns a Token type for an INTEGER type.
   *
   * @static
   * @returns {String}
   */
  public static function TOK_INTEGER_TYPE()
  {
    return 'INTEGER_TYPE';
  }

  /**
   * Returns a Token type for a REAL type.
   *
   * @static
   * @returns {String}
   */
  public static function TOK_REAL_TYPE()
  {
    return 'REAL_TYPE';
  }

  /**
   * Returns a Token type for an integer literals.
   *
   * @static
   * @returns {String}
   */
  public static function TOK_INTEGER_LITERAL()
  {
    return 'INTEGER_LITERAL';
  }

  /**
   * Returns a Token type for a real literals.
   *
   * @static
   * @returns {String}
   */
  public static function TOK_REAL_LITERAL()
  {
    return 'REAL_LITERAL';
  }

  /**
   * Returns a Token type for integer division (DIV).
   *
   * @static
   * @returns {String}
   */
  public static function TOK_INTEGER_DIV()
  {
    return 'INTEGER_DIV';
  }

  /**
   * Returns a Token type for float division (/).
   *
   * @static
   * @returns {String}
   */
  public static function TOK_REAL_DIV()
  {
    return 'REAL_DIV';
  }

  /**
   * Returns a Token type for PROCEDURE keyword (PROCEDURE).
   *
   * @static
   * @returns {String}
   */
  public static function TOK_PROCEDURE()
  {
    return 'PROCEDURE';
  }
}
