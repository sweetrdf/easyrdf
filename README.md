# EasyRdf (Fork)

---

For more information about this fork, scroll at the end of this document.

---

![CI](https://github.com/sweetrdf/easyrdf/workflows/Tests/badge.svg)

EasyRdf is a PHP library designed to make it easy to consume and produce [RDF].
It was designed for use in mixed teams of experienced and inexperienced RDF
developers. It is written in Object Oriented PHP and has been tested
extensively using PHPUnit.

After parsing EasyRdf builds up a graph of PHP objects that can then be walked
around to get the data to be placed on the page. Dump methods are available to
inspect what data is available during development.

Data is typically loaded into an [`EasyRdf\Graph`] object from source RDF
documents, loaded from the web via HTTP. The [`EasyRdf\GraphStore`] class
simplifies loading and saving data to a SPARQL 1.1 Graph Store.

SPARQL queries can be made over HTTP to a Triplestore using the
[`EasyRdf\Sparql\Client`] class. `SELECT` and `ASK` queries will return an
[`EasyRdf\Sparql\Result`] object and `CONSTRUCT` and `DESCRIBE` queries will return
an [`EasyRdf\Graph`] object.

### Example ###

```php
$foaf = new \EasyRdf\Graph("http://njh.me/foaf.rdf");
$foaf->load();
$me = $foaf->primaryTopic();
echo "My name is: ".$me->get('foaf:name')."\n";
```

Downloads
---------

The latest _stable_ version of EasyRdf can be [downloaded from the EasyRdf website].


Links
-----

* [EasyRdf Homepage](https://www.easyrdf.org/)
* [API documentation](https://www.easyrdf.org/docs/api)
* [Change Log](https://github.com/easyrdf/easyrdf/blob/master/CHANGELOG.md)
* [Source Code](https://github.com/easyrdf/easyrdf)
* [Issue Tracker](https://github.com/easyrdf/easyrdf/issues)


Requirements
------------

* PHP 7.1 or higher


Features
--------

* API documentation written in `phpdoc`
* Extensive unit tests written using `phpunit`
* Built-in parsers and serialisers: RDF/JSON, N-Triples, RDF/XML, Turtle
* Optional parsing support for: [ARC2], [rapper]
* Optional support for [`Zend\Http\Client`]
* No required external dependencies upon other libraries (PEAR, Zend, etc...)
* Complies with Zend Framework coding style.
* Type mapper - resources of type `foaf:Person` can be mapped into PHP object of class `Foaf_Person`
* Support for visualisation of graphs using [GraphViz]
* Comes with a number of examples


List of Examples
----------------

* [`basic.php`](/examples/basic.php#slider) - Basic "Hello World" type example
* [`basic_sparql.php`](/examples/basic_sparql.php#slider) - Example of making a SPARQL `SELECT` query
* [`converter.php`](/examples/converter.php#slider) - Convert RDF from one format to another
* [`dump.php`](/examples/dump.php#slider) - Display the contents of a graph
* [`foafinfo.php`](/examples/foafinfo.php#slider) - Display the basic information in a FOAF document
* [`foafmaker.php`](/examples/foafmaker.php#slider) - Construct a FOAF document with a choice of serialisations
* [`graph_direct.php`](/examples/graph_direct.php#slider) - Example of using `EasyRdf\Graph` directly without `EasyRdf\Resource`
* [`graphstore.php`](/examples/graphstore.php#slider) - Store and retrieve data from a SPARQL 1.1 Graph Store
* [`graphviz.php`](/examples/graphviz.php#slider) - GraphViz rendering example
* [`html_tag_helpers.php`](/examples/html_tag_helpers.php#slider) - Rails Style html tag helpers to make the EasyRdf examples simpler
* [`httpget.php`](/examples/httpget.php#slider) - No RDF, just test `EasyRdf\Http\Client`
* [`open_graph_protocol.php`](/examples/open_graph_protocol.php#slider) - Extract Open Graph Protocol metadata from a webpage
* [`serialise.php`](/examples/serialise.php#slider) - Basic serialisation example
* [`sparql_queryform.php`](/examples/sparql_queryform.php#slider) - Form to submit SPARQL queries and display the result
* [`uk_postcode.php`](/examples/uk_postcode.php#slider) - Example of resolving UK postcodes using uk-postcodes.com
* [`wikidata_villages.php`](/examples/wikidata_villages.php#slider) - Fetch and information about villages in Fife from Wikidata
* [`zend_framework.php`](/examples/zend_framework.php#slider) - Example of using `Zend\Http\Client` with EasyRdf


Contributing
------------

We welcome any contributions. For further information please read [CONTRIBUTING.md](CONTRIBUTING.md).

For further information about extending / hack EasyRdf please read [DEVELOPER.md](DEVELOPER.md).

Running Examples
----------------

The easiest way of trying out some of the examples is to use the PHP command to
run a local web server on your computer.

```
php -S localhost:8080 -t examples
```

Then open the following URL in your browser: http://localhost:8080/

## Why this fork?

EasyRdf was in maintenance mode since 2017 ([link](https://github.com/easyrdf/easyrdf/issues/282)) and not actively maintained since. There were 6+ pull requests pending at that time with fixes and new features. Its sad to see another RDF PHP project die slowly, so i decided to clean house and give the code a new home ([old fork](https://github.com/sweetyrdf/easyrdf), [further info](https://github.com/easyrdf/easyrdf/issues/320)). A few months in late 2020 EasyRdf was actively improved (me being a co-maintainer for a while), but that stopped and decay began again.

I decided to abondon my [old fork](https://github.com/sweetyrdf/easyrdf) to make use of latest EasyRdf improvements. Therefore this Github repository was created.

#### What can you expect as a user?

This fork aims to be a drop-in replacement for the `easyrdf/easyrdf` package, which means, you can use it without changing your code. But you should still read the notes of the latest release, to make sure nothing unexpected happens after an update.

#### What can you expect as an EasyRdf developer?

This repository is set up in a way to lower the maintenance overhead in comparison to the original version.

Furthermore, this repository is held by an organization instead of a user. This allows more flexible maintenance like add further maintainer or helper.

**Contributions are welcome!** Please read [CONTRIBUTING.md](https://github.com/sweetyrdf/easyrdf/blob/master/CONTRIBUTING.md) for further information.

Further mainainers are possible, please send an email to [@k00ni](https://github.com/k00ni).

## Licensing

The EasyRdf library and tests are licensed under the [BSD-3-Clause] license.
The examples are in the public domain, for more information see [UNLICENSE].



[`EasyRdf\Graph`]:https://www.easyrdf.org/docs/api/EasyRdf\Graph.html
[`EasyRdf\GraphStore`]:https://www.easyrdf.org/docs/api/EasyRdf\GraphStore.html
[`EasyRdf\Sparql\Client`]:https://www.easyrdf.org/docs/api/EasyRdf\Sparql\Client.html
[`EasyRdf\Sparql\Result`]:https://www.easyrdf.org/docs/api/EasyRdf\Sparql\Result.html

[ARC2]:https://github.com/semsol/arc2/
[BSD-3-Clause]:https://www.opensource.org/licenses/BSD-3-Clause
[downloaded from the EasyRdf website]:https://www.easyrdf.org/downloads
[GraphViz]:https://www.graphviz.org/
[rapper]:http://librdf.org/raptor/rapper.html
[RDF]:https://en.wikipedia.org/wiki/Resource_Description_Framework
[SPARQL 1.1 query language]:https://www.w3.org/TR/sparql11-query/
[UNLICENSE]:https://unlicense.org/
[`Zend\Http\Client`]:https://docs.zendframework.com/zend-http/client/intro/
