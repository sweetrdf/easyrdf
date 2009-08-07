<?php
  set_include_path(get_include_path() . PATH_SEPARATOR . '../lib/');
  require_once "EasyRdf/Graph.php";
  require_once "EasyRdf/Namespace.php";
  EasyRdf_Namespace::add('mo', 'http://purl.org/ontology/mo/');
  $url = $_GET['url'];
?>
<html>
<head><title>Artist Info</title></head>
<body>
<h1>Artist Info</h1>
<form method="get">
<input name="url" type="text" size="48" value="<?= empty($url) ? 'http://www.bbc.co.uk/music/artists/beff21d3-88c7-4ee0-8b7a-40b6db22c6d7.rdf' : $url ?>" />
<input type="submit" />
</form>
<?php
    if ($url) {
        $data = file_get_contents( $url );
        $graph = new EasyRdf_Graph( $url, $data );
        if ($graph) $artist = $graph->primaryTopic();
    }
  
    if ($artist) {
?>

<dl>
  <dt>Artist Name:</dt><dd><?= $artist->first('foaf_name') ?></dd>
  <dt>Type:</dt><dd><?= $artist->type() ?></dd>
  <dt>Homepage:</dt><dd><?= $artist->first('foaf_homepage') ?></dd>
  <dt>Wikipedia page:</dt><dd><?= $artist->first('mo_wikipedia') ?></dd>
</dl>

<?php
      if ($artist->type() == 'mo_MusicGroup') {
      
      
      }

    }
    
    if ($graph) {
        echo "<hr />";
        echo $graph->dump();
    }
?>
</body>
</html>