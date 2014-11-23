<?php
namespace Opine;

use PHPUnit_Framework_TestCase;
use Opine\Config\Service as Config;
use Opine\Container\Service as Container;

class HandlebarsTest extends PHPUnit_Framework_TestCase {
    private $handlebars;
    private $layout;

    public function setup () {
        $root = __DIR__ . '/../public';
        $config = new Config($root);
        $config->cacheSet();
        $container = new Container($root, $config, $root . '/../container.yml');
        $this->handlebars = $container->get('handlebarService');
        $this->handlebars->quiet();
        $this->layout = $container->get('layout');
    }

    private function normalizeResponse ($input) {
        return str_replace(['    ', "\n"], '', $input);
    }

    public function testSample () {
        $this->assertTrue($this->handlebars->build());
    }

    public function testCachedApp () {
        ob_start();
        $this->layout->
            app('app/test')->
            layout('layout')->
            data('test', ['test' => 'ABC'])->
            write();
        $response = ob_get_clean();
        $this->assertTrue($this->normalizeResponse($response) == '<html><head><title></title></head><body><div><div>ABC</div></div></body></html>');
    }
}