<?php

$configFolder = './testi';
$configFilePattern = '.*\.txt$/i';

function myscandir($dir, $exp, $how='name', $desc=0)
{
  // print "myscandir > dir: $dir, exp: $exp";

    $r = array();
    $dh = @opendir($dir);
    if ($dh) {
        while (($fname = readdir($dh)) !== false) {
          // print "myscandir > fname: $fname";
            if (preg_match($exp, $fname)) {
                $stat = stat("$dir/$fname");
                $r[$fname] = ($how == 'name')? $fname: $stat[$how];
            }
        }
        closedir($dh);
        if ($desc) {
            arsort($r);
        }
        else {
            asort($r);
        }
    }
    return(array_keys($r));
}

function parsefile($dir, $filename, $letter) {

  $strOut = '';

  $filenamestr = preg_replace('/[^a-zA-Z]/', '-', $filename);

  $handle = fopen($dir . '/' . $filename, "r");
  if ($handle) {

    // get title
    if( ($line = fgets($handle)) !== false) {
	$title = preg_replace('/^.*# */', '', $line);
        $strOut = $strOut . '<a id="heading-' . $filenamestr . '" class="list-group-item list-group-item-action flex-column text-primary" aria-current="true" href="#heading-'. $filenamestr . '" data-toggle="collapse" data-target="#' . $filenamestr . '" aria-expanded="false" aria-controls="' . $filenamestr . '" >' . trim($title) . '</a>' . PHP_EOL;
    }

    $strOut = $strOut . '<div class="list-group-item-content collapse" id="' . $filenamestr . '" aria-labelledby="heading-' . $filenamestr . '" data-parent="#canti-' . $letter . '">' . PHP_EOL;

    $text = '';
    while (($line = fgets($handle)) !== false) { 
        // process the line read.
       $text = $text . trim($line) . '<br>' . PHP_EOL;
    }

    $strOut = $strOut . $text . PHP_EOL;
    $strOut = $strOut . '</div>' . PHP_EOL;

    fclose($handle);
} else {
    // error opening the file.
} 
  return array( $title => $strOut );
}

function printHeader( ) {
echo '
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
    integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.0/font/bootstrap-icons.css">

  <title>Canti</title>

  <style>
    .navbar button {
      width: 1.5rem;
      padding: 0;
      margin: .15rem;
    }
    a.list-group-item {
      padding: .2rem;
      padding-left: 1rem;
    }
    .list-group-item-content {
      padding: .5rem;
      padding-left: 1rem;

    }
  </style>
</head>
';    
}

function printNavbar() {

    echo '<nav class="navbar d-flex justify-content-center">' , PHP_EOL;
    
    foreach(range('A','Z') as $letter) {
        $v = strtolower($letter);
        echo '<button type="button" class="btn btn-outline-primary" data-toggle="collapse" data-target="#canti-' . $v . '" aria-controls="canti-' . $v . '" aria-expanded="false" aria-label="Toggle canti ' . $v . '">' . $letter . '</button>' , PHP_EOL;
    }

    echo '</nav>' , PHP_EOL;
    
}

echo ' 
<!doctype html>
<html lang="en">
';

printHeader();

echo '<body>' , PHP_EOL;

echo '<div id="canti-all">' , PHP_EOL;

printNavbar();

foreach(range('a','z') as $v){
    $filenames = myscandir($configFolder, '/^' . $v . $configFilePattern);
 
    echo '<div id="canti-' . $v . '" class="collapse" data-parent="#canti-all">', PHP_EOL;
    echo '<div class="list-group list-group-flush">' , PHP_EOL;

    $canti = array();

    foreach( $filenames as $f ) {
        // printf("%s\n", $f);
        $canti = $canti + parsefile($configFolder, $f, $v);
    }

    ksort($canti);
    foreach ($canti as $key => $val) {
      echo $val;
    }

    echo '</div>', PHP_EOL;
    echo '</div>', PHP_EOL;
}

printFooter();

function printFooter() {

    echo '
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
    integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
    integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"
    integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF"
    crossorigin="anonymous"></script>
    ';
}

echo '</div>' , PHP_EOL;
echo '</body>' , PHP_EOL;
echo '</html>' , PHP_EOL;


?>

