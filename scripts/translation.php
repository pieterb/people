<?php

/*·************************************************************************
 * Copyright © 2005-2008 by Pieter van Beek                               *
 * pieter@djinnit.com                                                     *
 *                                                                        *
 * This program may be distributed under the terms of the Q Public        *
 * License as defined by Trolltech AS of Norway and appearing in the file *
 * LICENSE.QPL included in the packaging of this file.                    *
 *                                                                        *
 * This program is distributed in the hope that it will be useful, but    *
 * WITHOUT ANY WARRANTY; without even the implied warranty of             *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                   *
 **************************************************************************/

$NOT_SINGLE_QUOTE_CHAR = "(?:\\\\\\\\|\\\\'|[^'])";
$NOT_DOUBLE_QUOTE_CHAR = "(?:\\\\.|[^\"\$])";
$SINGLE_QUOTED_STRING = "'{$NOT_SINGLE_QUOTE_CHAR}*'";
$DOUBLE_QUOTED_STRING = "\"{$NOT_DOUBLE_QUOTE_CHAR}*\"";
$QUOTED_STRING = "(?:$SINGLE_QUOTED_STRING|$DOUBLE_QUOTED_STRING)";

chdir ( dirname( dirname( __FILE__ ) ) );
exec('find inc \\( -iname \\*.tpl -or -iname \\*.php -or -iname \\*.inc \\) -type f', $filenames);

$strings2translate = array();

foreach ($filenames as $filename) {
  echo "Processing file '$filename'...";
  $file = file_get_contents($filename);
  preg_match_all("/[^\\w]tr\\s*\\(\\s*($QUOTED_STRING(?:\\s*\\.\\s*$QUOTED_STRING)*)\\s*\\)/s", $file, $matches, PREG_PATTERN_ORDER);
  foreach ($matches[1] as $match)
    eval("\$strings2translate[] = $match;");
  preg_match_all("/\\/\\*tr\\*\\/\\s*($QUOTED_STRING(?:\\s*\\.\\s*$QUOTED_STRING)*)/s", $file, $matches, PREG_PATTERN_ORDER);
  foreach ($matches[1] as $match)
    eval("\$strings2translate[] = $match;");
  preg_match_all("/($QUOTED_STRING(?:\\s*\\.\\s*$QUOTED_STRING)*)\\s*\\/\\*tr\\*\\//s", $file, $matches, PREG_PATTERN_ORDER);
  foreach ($matches[1] as $match)
    eval("\$strings2translate[] = $match;");
  preg_match_all("/\\{\\{\\s*tr\\s+string\\s*=\\s*($QUOTED_STRING)/s", $file, $matches, PREG_PATTERN_ORDER);
  foreach ($matches[1] as $match)
    eval("\$strings2translate[] = $match;");
  echo " done.\n";
}

unset($filenames);
exec('find i18n -iname \\*.i18n -type f', $filenames);

foreach ($filenames as $filename) {
  echo "Updating translation '$filename'...";
  $TRANSLATION = array();
  include $filename;
  foreach ($strings2translate as $string)
    if (!isset($TRANSLATION[$string]))
      $TRANSLATION[$string] = $string;
  $file = fopen($filename, 'w');
  fwrite($file, <<<EOS
<?php

/*·************************************************************************
 * Copyright © 2005-2008 by Pieter van Beek                               *
 * pieter@djinnit.com                                                     *
 *                                                                        *
 * This program may be distributed under the terms of the Q Public        *
 * License as defined by Trolltech AS of Norway and appearing in the file *
 * LICENSE.QPL included in the packaging of this file.                    *
 *                                                                        *
 * This program is distributed in the hope that it will be useful, but    *
 * WITHOUT ANY WARRANTY; without even the implied warranty of             *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.                   *
 **************************************************************************/


EOS
  );
  foreach ($TRANSLATION as $key => $value) {
    if ( !in_array( $key, $strings2translate ) )
      fwrite( $file, "// Unused:\n" );
    fwrite($file, '$TRANSLATION["' .
           str_replace(array('\\', '"'),
                       array('\\\\', '\\"'), $key) .
           "\"] =\n             \"" .
           str_replace(array('\\', '"'),
                       array('\\\\', '\\"'), $value) .
           "\";\n");
  }
  fwrite($file, "\n?>\n");
  fclose($file);
  echo " done.\n";
}

?>
