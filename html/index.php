<?php
/**
 *
 * "Script to read information from audio stream"
 * http://www.script-tutorials.com/script-to-read-information-from-audio-stream/
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2014, Script Tutorials
 * http://www.script-tutorials.com/
 */

error_reporting(0);
ini_set('user_agent', 'Mozilla');

$result = '';

if ($_POST && $_POST['url']) {

  // get a stream URL and type
  $url = $_POST['url'];
  $type = $_POST['type'];

  // http://php.net/manual/en/function.filter-var.php
  if (filter_var($url, FILTER_VALIDATE_URL) !== false) {

    $result .= '<h2>Audiostream Information:</h2>';

    // Stage 1. Get general information:
    $result .= '<h3>General Information:</h3>';

    if ($type == 's') {
      // get the stream content
      $html = file_get_contents(rtrim($url, '/').'/index.html');

      // create a new domDocument and load the stream response
      $dom = new domDocument;
      $dom->loadHTML($html);

      // parse the result
      $tables = $dom->getElementsByTagName('table');
      $rows = $tables->item(3)->getElementsByTagName('tr');
      foreach ($rows as $row) {
        $cols = $row->getElementsByTagName('td');

        if (!strstr($cols->item(0)->nodeValue,'@')) {
          $result .= '<div><strong>' . $cols->item(0)->nodeValue . '</strong> ' . $cols->item(1)->nodeValue;

          if ($cols->item(2)->nodeValue)
              $result .= ' *'.$cols->item(2)->nodeValue.'*';

          $result .= '</div>';
        }
      }

      // Stage 2. Get song history information:
      $result .= '<h3>Song History Information:</h3>';

      // get the stream content
      $html = file_get_contents(rtrim($url, '/').'/played.html'); // playlist info

      // create a new domDocument and load the stream response
      $dom = new domDocument;
      $dom->loadHTML($html);

      // parse the result
      $tables = $dom->getElementsByTagName('table');
      $rows = $tables->item(2)->getElementsByTagName('tr');
      foreach ($rows as $row) {
        $cols = $row->getElementsByTagName('td');

        if (!strstr($cols->item(0)->nodeValue,'@')) {
          $result .= '<div><strong>' . $cols->item(0)->nodeValue . '</strong> ' . $cols->item(1)->nodeValue;

          if ($cols->item(2)->nodeValue)
              $result .= ' *'.$cols->item(2)->nodeValue.'*';

          $result .= '</div>';
        }
      }
    } elseif ($type == 'i') {

      // use the IceCast class to obtain the stream details
      require_once('icecast.php');
      $oIceCast = new IceCast();
      $oIceCast->setUrl(rtrim($url, '/'));
      $status = $oIceCast->getStatus();

      $result .= <<<EOF
<div><strong>Server</strong> {$status['server']}</div>
<div><strong>Title</strong> {$status['title']}</div>
<div><strong>Description</strong> {$status['description']}</div>
<div><strong>Content type</strong> {$status['content_type']}</div>
<div><strong>Mount start</strong> {$status['mount_start']}</div>
<div><strong>Bitrate</strong> {$status['bit_rate']}</div>
<div><strong>Listeners</strong> {$status['listeners']}</div>
<div><strong>Most listeners</strong> {$status['most_listeners']}</div>
<div><strong>Genre</strong> {$status['genre']}</div>
<div><strong>Url</strong> {$status['url']}</div>
<div><strong>Current artist</strong> {$status['now_playing']['artist']}</div>
<div><strong>Current track</strong> {$status['now_playing']['track']}</div>
EOF;
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta property="og:site_name" content="Script Tutorials" />
  <meta property="og:title" content="Script to read information from audio stream | Script Tutorials" />
  <meta property="og:image" content="http://www.script-tutorials.com/demos/398/thumb.png" />
  <meta property="og:type" content="website" />
  <meta name="description" content="Script to read information from audio stream - Script Tutorials">
  <title>Script to read information from audio stream | Script Tutorials</title>
  <link href="css/style.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <form method="post">
      <h1>Script to read information from audio stream
          <span>Here you can put a stream URL to get it's details</span>
          <span>e.g. http://81.173.3.250:80 (Shoutcast) or http://50-7-66-170.webnow.net.br:80 (Icecast)</span>
      </h1>
      <label><span>Stream URL:</span><input type="url" name="url" placeholder="stream url" /></label>
      <label><span>Stream type:</span>
        <select name="type">
          <option value="s">Shoutcast</option>
          <option value="i">Icecast</option>
        </select>
      </label>
      <label><span>&nbsp;</span><input type="submit" class="button" value="Display info" /></label>
    </form>
    <?= $result ?>
  </div><!--/.container-->
</body>
</html>