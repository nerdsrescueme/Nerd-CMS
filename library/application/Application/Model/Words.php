<?php

/**
 * Nerd-CMS Model namespace
 *
 * @package Nerd-CMS
 * @subpackage Models
 */
namespace Application\Model;

// Aliasing rules
use Nerd\Str\Compare;

/**
 * Nerd-CMS Word Model
 *
 * @package Nerd-CMS
 * @subpackage Models
 */
class Words extends \Nerd\Model
{
    protected static $table = 'nerd_words';

    private static $sql = 'SELECT `word` FROM `nerd_words` WHERE `word` LIKE ?';

    /**
     * Find words beginning with...
     *
     * @param    string          Word part
     * @return   this
     */
    public static function beginningWith($part)
    {
        return static::findAll(static::$sql, strtolower("{$part}%"));
    }

    /**
     * Find words ending with...
     *
     * @param    string          Word part
     * @return   this
     */
    public static function endingWith($part)
    {
        return static::findAll(static::$sql, strtolower("%{$part}"));
    }

    /**
     * Find words containing...
     *
     * @param    string          Word part
     * @return   this
     */
    public static function containing($part)
    {
        return static::findAll(static::$sql, strtolower("%{$part}%"));
    }

    public function similar()
    {
        return static::findAll(static::$sql, $this->word);
    }

    /**
     * Calculates the levenshtein distance between this word and a given one
     *
     * @param    string|array    Word(s) to compare against
     * @return   integer|array   Levenshtein distance(s)
     */
    public function difference($words)
    {
        if (!is_array($words)) {
            return Compare::levenshtein($this->word, $words);
        }

        $differences = [];

        foreach($words as $word) {
            $differences[$word] = Compare::levenshtein($this->word, $word);
        }

        return $differences;
    }
}
