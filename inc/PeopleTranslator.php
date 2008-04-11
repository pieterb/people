<?php

/*·****************************************************************************
 * Copyright © 2005-2006 by Pieter van Beek                                   *
 * pieter@djinnit.com                                                         *
 *                                                                            *
 * This program may be distributed under the terms of the Q Public License as *
 * defined  by  Trolltech AS  of Norway and appearing in the file LICENSE.QPL *
 * included in the packaging of this file.                                    *
 *                                                                            *
 * This  program  is  distributed  in the  hope  that it will  be useful, but *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY *
 * or FITNESS FOR A PARTICULAR PURPOSE.                                       *
 ******************************************************************************/

/**
 * @package People
 * @subpackage Templates
 * @author Pieter van Beek <pieter@djinnit.com>
 */

/**
 * A translator object.
 * This object will translate any string into any language! Here's how it
 * works:<ul>
 * <li>First, you surround every literal string in the code with People::People::tr(),
 *   e.g. <code>echo People::tr('This is a literal string.');</code>
 *   I repeat: <b>literal strings!!!</b>. People::People::tr() has no effect for
 *   other expressions, like <code>
 *   echo People::People::tr("This is a not too literal string: $string");
 *   echo People::People::tr("And this won't work " . 'either');
 *   </code></li>
 * <li>If there isn't a *.i18n file (AKA translation table) for your language
 *   yet, then create it as $PATH/layer1/i18n/<your locale>.i18n</li>
 * <li>Run script 'translate.php' that's included in the distribution.
 *   This script will scan all source files for literal strings, and put
 *   these strings in all translation tables. Existing translations will be
 *   preserved, so don't be affraid to run this script: it won't delete
 *   anything.</li>
 * <li>Edit your translation table file.</li>
 * <li>Edit the constructor below, to include your locale.</li></ul>
 *
 * The constructor determines the language to use, through inspection of
 * <pre>$_SERVER['HTTP_ACCEPT_LANGUAGE']</pre>
 *
 * All strings, all files, yes, <b>every text in digital form</b> in this
 * platform has UTF-8 encoding. If you don't know what that is, go sit in
 * a corner and feel ashamed! (And then read up on encodings, of course)
 *
 * For those who don't know: i18n is shorthand for "internationalization",
 * where the middle 18 characters ("nternationalizatio") have been replaced
 * by "18", because it's such a rediculously long word.
 * @package People
 * @subpackage Templates
 * @author Pieter van Beek <pieter@djinnit.com>
 */
class PeopleTranslator extends PeopleSingleton {


private $i_translations;
private $i_current_locale;


/**
 * Protected singleton constructor
 */
protected function __construct() {
  parent::__construct();
  $current_locale = NULL;
  if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $pref=array();
    foreach(split(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) as $lang) {
      $lang = strtolower(trim($lang));
      if (preg_match('/^([a-z\\-]+).*?;\\s*q\\s*=\\s*([0-9.]+)/i',
                      strtolower(trim($lang)) . ';q=1.0', $split))
        $pref[sprintf ('%06.2f', $split[2])] = strtolower ($split[1]);
    }
    if (!count($pref)) return;
    krsort($pref);
    foreach ($pref as $value) {
      switch ($value) {
      case 'en':
      case 'en-us':
        $current_locale = 'en_US';
        break;
      case 'en-br':
        $current_locale = 'en_BR';
        break;
      case 'nl':
      case 'nl-nl':
        $current_locale = 'nl_NL';
        break;
      case 'nl-be':
        $current_locale = 'nl_BE';
        break;
      case 'fr':
      case 'fr-fr':
        $current_locale = 'fr_FR';
        break;
      case 'fr-be':
        $current_locale = 'fr_BE';
        break;
      default:
        PeopleDebugger::inst()->debug
          ("PeopleTranslator::__construct recieved HTTP_ACCEPT_LANGUAGE '$value'",
          E_USER_WARNING);
      }
      if ($current_locale !== NULL) break;
    } // foreach ($pref as $value)
  } // if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
  if ( is_null( $current_locale ) )
    $current_locale = 'en_US';
  $this->setLocale($current_locale);
} // end of member function __construct


/** @var PeopleTranslator */
private static $si_inst = NULL;


/**
 * Singleton factory
 * @return object PeopleTranslator the single instance of this class.
 */
public static function inst() {
  if (is_null(self::$si_inst))
    self::$si_inst = new PeopleTranslator();
  return self::$si_inst;
} // end of member function inst


/**
 * Translates the given string.
 * @param string $p_original the string to be translated.
 * @return string the translation.
 */
public function tr( $p_original ) {
  return isset( $this->i_translations[$p_original] ) ?
    $this->i_translations[$p_original] : $p_original;
}


/**
 * Set the current locale to use.
 * @return object PeopleTranslator $this.
 * @param string $locale the locale to use.
 */
public function setLocale($locale) {
  $this->i_current_locale = $locale;
  $translation_file = dirname(__FILE__) . '/../i18n/' .
    strtolower( $this->i_current_locale ) . '.i18n';
  if ( file_exists( $translation_file ) )
    require_once $translation_file;
  $this->i_translations =& $TRANSLATION;
  setlocale(LC_MONETARY, $locale);
  setlocale(LC_TIME, $locale);
  return $this;
}


/**
 * @return string the current locale.
 */
public function currentLocale() { return $this->i_current_locale; }


} // class 

?>