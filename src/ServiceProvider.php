<?php

namespace Mducharme\DateFormatter;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * Date parser and formatter service providers.
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container Pimple DI Container.
     * @return void
     */
    public function register(Container $container)
    {
        /**
         *  The custom formats. None per default, extends in your own provider to pass more formats.
         *
         * @var array
         */
        $container['date/custom-formats'] = [];

        /**
         * Default format. Must be a string, other
         *
         * @var string|null
         */
        $container['date/default-format'] = 'atom';

        /**
         * @return Parser
         */
        $container['date/parser'] = function() {
            return new Parser();
        };

        /**
         * @param Container $container Pimple DI Container.
         * @return Formatter
         */
        $container['date/formatter'] = function(Container $container) {
            return new Formatter(
                $container['date/parser'],
                $container['date/custom-formats'],
                $container['date/default-format']
            );
        };
    }
}
