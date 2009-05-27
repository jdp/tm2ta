#!/usr/bin/php -q
<?php
$program = basename($argv[0]);

/* Show an error message */
function error($msg, $exitcode = 1) {
  global $program;
  echo "{$program}: $msg\n";
  exit($exitcode);
}

/* Map of TextMate to TextAdept identifiers */
$ident_map = array(
  'text'                 => 'default',
  'comment'              => 'comment',
  'comment_block'        => 'comment',
  'constant'             => 'constant',
  'keyword'              => 'keyword',
  'variable'             => 'variable',
  'entity_name_function' => 'function',
  'string'               => 'string',
  'entity_name_type'     => 'type',
  'other_preprocessor'   => 'preprocessor'
);

/* Converts a TextMate color to a TextAdept color */
function ta_color($tmcolor) {
  preg_match('/^#?(\X\X)(\X\X)(\X\X)/i', $tmcolor, $rgb);
  return sprintf("color('%s','%s','%s')", $rgb[1], $rgb[2], $rgb[3]);
}

/* Compiles XML from $filename into an array to pass to build() */
function compile($filename) {
  global $ident_map;
  $style = array();
  $tmxml = simplexml_load_string(file_get_contents($filename));
  foreach ($tmxml->dict->array->dict as $item) {
    if ($item->key[0] == "name") {
      $tm_property = str_replace(".", "_", "{$item->string[1]}");
      if (!array_key_exists($tm_property, $ident_map)) {
        continue;
      }
      $ta_property = $ident_map[$tm_property];
      for ($i = 0; $i < count($item->dict->key); $i++) {
        switch ($item->dict->key[$i]) {
          case 'background':
            $style[$ta_property]['back'] = ta_color($item->dict->string[$i]);
            break;
          case 'foreground':
            $style[$ta_property]['fore'] = ta_color($item->dict->string[$i]);
            break;
          case 'fontStyle':
            if (!empty($item->dict->string[$i])) {
              $fontstyles = explode(" ", $item->dict->string[$i]);
              foreach($fontstyles as $fontstyle) {
                $style[$ta_property][$fontstyle] = 'true';
              }
            }
            break;
        }
      }
    }
  }
  return $style;
}

/* Converts $style XML to Lua and outputs it to $outfile */
function build($style, $outfile) {
  $fp = fopen($outfile, 'w');
  foreach ($style as $property => $attrs) {
    fputs($fp, "style_{$property} = style { ");
    $pairs = array();
    foreach ($attrs as $attr => $value) {
      array_push($pairs, "{$attr} = {$value}");
    }
    fputs($fp, implode(", ", $pairs));
    fputs($fp, " }\n");
  }
  fclose($fp);
  return true;
}

/* DO WORK */
$options = getopt('f:o:h');

/* Handle -h switch, for help */
if (array_key_exists('h', $options) || ($argc == 1)) {
?>
Usage: <?php echo $program; ?> -f<file> [-ho<file>]
  -f<file>   TextMate theme to convert
  -o<file>   Name of the output file
  -h         Display this message
<?php
  exit(0);
}

/* Handle -f switch, for input file */
if (!array_key_exists('f', $options)) {
  error('No input file provided, specify with -f switch');
}
$infile = $options['f'];
if (!file_exists($infile)) {
  error("Could not find `{$infile}'");
}

/* Handle -o switch, for output file */
$outfile = array_key_exists('o', $options) ? $options['o'] : 'php://stdout';

/* Build and output the TextAdept theme partial */
$intermediate = compile($infile);
$result = build($intermediate, $outfile);
if (!$result) {
  exit(1);
}
?>
