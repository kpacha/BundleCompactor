#BundleCompactor

[![Build Status](https://secure.travis-ci.org/kpacha/BundleCompactor.png?branch=master)](https://travis-ci.org/kpacha/BundleCompactor) [![Coverage Status](https://coveralls.io/repos/kpacha/BundleCompactor/badge.png)](https://coveralls.io/r/kpacha/BundleCompactor)

compact your libs in a single php file and create a classmap for the autoloader

##Set up

    git clone https://github.com/kpacha/BundleCompactor.git
    cd BundleCompactor
    curl -s http://getcomposer.org/installer | php
    php composer.phar install --dev --no-interaction


##Example

###Create a Compactor with the CompactorBuilder

Create your example_with_builder.php file inside the bin folder.

Include the bootstrap file, declare your source and destination path and the name of your bundle

    <?php
    
    require_once __DIR__ . '/../bootstrap.php';
    $sourcePath = realpath(__DIR__ . '/path/to/your/library');
    $destination = realpath(__DIR__ . '/path/where/place/the/results');
    $name = 'amazingBundle';


Create the compactor builder with the default parameters, get the compactor and run it!

    $compactorBuilder = new Kpacha\BundleCompactor\CompactorBuilder;

    $compactorBuilder->setBundleName($name)
            ->setSourcePath($sourcePath)
            ->setDestination($destination)
            ->build()
            ->compact();


###Direct instantiation

Create your example.php file inside the bin folder.

Include the bootstrap file, declare your source and destination path and the name of your bundle

    <?php
    
    require_once __DIR__ . '/../bootstrap.php';
    $sourcePath = realpath(__DIR__ . '/path/to/your/library');
    $destination = realpath(__DIR__ . '/path/where/place/the/results');
    $name = 'amazingBundle';
    

Declare the extensions

    // The extension for the compacted file
    $compactedExtension = Kpacha\BundleCompactor\CompactatorConstants::COMPACTED_SOURCE_EXTENSION;
    // The extension for the classmap file
    $classmapExtension = Kpacha\BundleCompactor\CompactatorConstants::CLASSMAP_EXTENSION;

Create the SourceReader

    $sourceReader = new Kpacha\BundleCompactor\Helpers\SourceReader($sourcePath);

Create the CompactedWriter

    $compactedWriter = new \Kpacha\BundleCompactor\Helpers\CompactedWriter($destination, $name, $compactedExtension);

Create the ClassMapGenerator

    $classMapGenerator = new Kpacha\BundleCompactor\Helpers\ClassMapGenerators\DeclaredParserClassMapGenerator($destination, $name, $classmapExtension, $compactedExtension);
    // or
    $classMapGenerator = new Kpacha\BundleCompactor\Helpers\ClassMapGenerators\TokenizerClassMapGenerator($destination, $name, $classmapExtension, $compactedExtension);
    // or
    $broker = new TokenReflection\Broker(new \TokenReflection\Broker\Backend\Memory());
    $classMapGenerator = new Kpacha\BundleCompactor\Helpers\ClassMapGenerators\TokenReflectorClassMapGenerator($destination, $name, $classmapExtension, $compactedExtension, $broker);

Create the compactor and run it!

    $compactor = new Kpacha\BundleCompactor\Compactor($sourceReader, $compactedWriter, $classMapGenerator);
    $compactor->compact();

##TODO

* fix the sort of the sources to compact


