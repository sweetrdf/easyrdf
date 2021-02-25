<?php

require_once realpath(__DIR__.'/..').'/vendor/autoload.php';

$parsers = [
    'Arc',
    'Json',
    'Ntriples',
    'RdfXml',
    'Rapper',
    'Turtle',
];

$documents = [
    'foaf.rdf' => 'rdfxml',
    'foaf.ttl' => 'turtle',
    'foaf.nt' => 'ntriples',
    'foaf.json' => 'json',
    'dundee.rdf' => 'rdfxml',
    'dundee.ttl' => 'turtle',
    'dundee.nt' => 'ntriples',
    'dundee.json' => 'json',
    'london.rdf' => 'rdfxml',
    'london.ttl' => 'turtle',
    'london.nt' => 'ntriples',
    'london.json' => 'json',
];

foreach ($documents as $filename => $type) {
    echo "Input file: $filename\n";
    $filepath = __DIR__."/performance/$filename";
    if (!file_exists($filepath)) {
        echo "Error: File does not exist.\n";
        continue;
    }

    $url = "http://www.example.com/$filename";
    $data = file_get_contents($filepath);
    echo 'File size: '.strlen($data)." bytes\n";

    foreach ($parsers as $parser_name) {
        $class = "EasyRdf\\Parser\\{$parser_name}";
        echo "  Parsing using: {$class}\n";

        try {
            $parser = new $class();
            $graph = new \EasyRdf\Graph();

            $start = microtime(true);
            $parser->parse($graph, $data, $type, $url);
            $duration = microtime(true) - $start;
            echo "  Parse time: $duration seconds\n";
            echo '  Triple count: '.$graph->countTriples()."\n";
        } catch (Exception $e) {
            echo 'Parsing failed: '.$e->getMessage()."\n";
        }
        echo "\n";

        unset($graph);
    }
}
