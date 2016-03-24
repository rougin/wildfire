<?php

namespace Rougin\Wildfire;

/**
 * Inflector
 *
 * Changes words to plural, singular, camel case, etc.
 * 
 * @package Wildfire
 * @author  Rougin Royce Gutib <rougingutib@gmail.com>
 */
class Inflector
{
    /**
     * Takes a plural word and makes it singular.
     * 
     * @param  string $string
     * @return string
     */
    public static function singular($string)
    {
        $result = strval($string);

        if ( ! self::isCountable($result)) {
            return $result;
        }

        $singular_rules = [
            '/(matr)ices$/'     => '\1ix',
            '/(vert|ind)ices$/' => '\1ex',
            '/^(ox)en/'     => '\1',
            '/(alias)es$/'      => '\1',
            '/([octop|vir])i$/' => '\1us',
            '/(cris|ax|test)es$/'   => '\1is',
            '/(shoe)s$/'        => '\1',
            '/(o)es$/'      => '\1',
            '/(bus|campus)es$/' => '\1',
            '/([m|l])ice$/'     => '\1ouse',
            '/(x|ch|ss|sh)es$/' => '\1',
            '/(m)ovies$/'       => '\1\2ovie',
            '/(s)eries$/'       => '\1\2eries',
            '/([^aeiouy]|qu)ies$/'  => '\1y',
            '/([lr])ves$/'      => '\1f',
            '/(tive)s$/'        => '\1',
            '/(hive)s$/'        => '\1',
            '/([^f])ves$/'      => '\1fe',
            '/(^analy)ses$/'    => '\1sis',
            '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/' => '\1\2sis',
            '/([ti])a$/'        => '\1um',
            '/(p)eople$/'       => '\1\2erson',
            '/(m)en$/'      => '\1an',
            '/(s)tatuses$/'     => '\1\2tatus',
            '/(c)hildren$/'     => '\1\2hild',
            '/(n)ews$/'     => '\1\2ews',
            '/([^us])s$/'       => '\1'
        ];

        foreach ($singular_rules as $rule => $replacement) {
            if (preg_match($rule, $result)) {
                $result = preg_replace($rule, $replacement, $result);

                break;
            }
        }

        return $result;
    }

    /**
     * Takes multiple words separated by spaces or underscores and camelizes them.
     *
     * @param  string $string
     * @return string
     */
    public static function camelize($string)
    {
        $ucwords = ucwords(preg_replace('/[\s_]+/', ' ', $string));
        $strReplace = str_replace(' ', '', $ucwords);
        $subString = substr($strReplace, 1);

        return strtolower($string[0]) . $subString;
    }

    /**
     * Checks if the given word has a plural version.
     * 
     * @param  string  $word
     * @return boolean
     */
    protected static function isCountable($word)
    {
        return ! in_array(
            strtolower($word),
            [
                'equipment', 'information', 'rice', 'money',
                'species', 'series', 'fish', 'meta'
            ]
        );
    }
}
