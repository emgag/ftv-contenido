<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php71\Rector\Assign\AssignArrayToStringRector;
use Rector\Php71\Rector\BinaryOp\BinaryOpBetweenNumberAndStringRector;
use Rector\Php71\Rector\FuncCall\CountOnNullRector;
use Rector\Php71\Rector\FuncCall\RemoveExtraParametersRector;
use Rector\Php71\Rector\List_\ListToArrayDestructRector;
use Rector\Php71\Rector\TryCatch\MultiExceptionCatchRector;
use Rector\Php73\Rector\FuncCall\ArrayKeyFirstLastRector;
use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;
use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;
use Rector\Php74\Rector\FuncCall\ArraySpreadInsteadOfArrayMergeRector;
use Rector\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::PHP_71);
    $containerConfigurator->import(SetList::PHP_72);
    $containerConfigurator->import(SetList::PHP_73);
    $containerConfigurator->import(SetList::PHP_74);

    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::PATHS, [
        __DIR__.'/cms',
        __DIR__.'/contenido',
        __DIR__.'/data',
        __DIR__.'/setup',
        __DIR__.'/test',
    ]);

    $parameters->set(Option::SKIP, [
        // PHP 7.1
        AssignArrayToStringRector::class => [
            __DIR__.'/contenido/classes/class.autoload.php',
            __DIR__.'/contenido/classes/class.registry.php',
            __DIR__.'/contenido/classes/class.rights.php',
            __DIR__.'/contenido/includes/functions.upl.php',
            __DIR__.'/contenido/plugins/mod_rewrite/classes/class.modrewrite.php',
        ],
        BinaryOpBetweenNumberAndStringRector::class,
        CountOnNullRector::class,
        ListToArrayDestructRector::class,
        MultiExceptionCatchRector::class,
        RemoveExtraParametersRector::class => [
            __DIR__.'/contenido/classes/class.mailer.php',
            __DIR__.'/contenido/classes/uri/class.uri.php',
            __DIR__.'/contenido/classes/uri/Sample.php',
            __DIR__.'/contenido/includes/include.mod_import_export.php',
            __DIR__.'/contenido/plugins/mod_rewrite/includes/functions.mod_rewrite.php',
        ],

        // PHP 7.3
        ArrayKeyFirstLastRector::class,
        JsonThrowOnErrorRector::class,

        // PHP 7.4
        AddLiteralSeparatorToNumberRector::class,
        ArraySpreadInsteadOfArrayMergeRector::class,
        ClosureToArrowFunctionRector::class,
        TypedPropertyRector::class,

        '*/contenido/classes/swiftmailer/*',
        '*/contenido/plugins/smarty/*',
        '*/contenido/plugins/linkchecker/*',
        '*/setup/data/examples/*',
    ]);

    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_74);
};