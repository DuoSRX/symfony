<?php

namespace Symfony\Tests\Component\Config\Definition;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Definition\NodeInterface;

class FinalizationTest extends \PHPUnit_Framework_TestCase
{

    public function testUnsetKeyWithDeepHierarchy()
    {
        $tb = new TreeBuilder();
        $tree = $tb
            ->root('config', 'array')
                ->node('level1', 'array')
                    ->canBeUnset()
                    ->node('level2', 'array')
                        ->canBeUnset()
                        ->node('somevalue', 'scalar')->end()
                        ->node('anothervalue', 'scalar')->end()
                    ->end()
                    ->node('level1_scalar', 'scalar')->end()
                ->end()
            ->end()
            ->buildTree()
        ;

        $a = array(
            'level1' => array(
                'level2' => array(
                    'somevalue' => 'foo',
                    'anothervalue' => 'bar',
                ),
                'level1_scalar' => 'foo',
            ),
        );

        $b = array(
            'level1' => array(
                'level2' => false,
            ),
        );

        $this->assertEquals(array(
            'level1' => array(
                'level1_scalar' => 'foo',
            ),
        ), $this->process($tree, array($a, $b)));
    }

    protected function process(NodeInterface $tree, array $configs)
    {
        $processor = new Processor();

        return $processor->process($tree, $configs);
    }
}