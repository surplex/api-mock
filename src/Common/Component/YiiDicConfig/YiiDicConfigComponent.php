<?php
namespace SrplxBoiler\Common\Component\YiiDicConfig;

use SrplxBoiler\Common\Component\YiiDicConfig\Exception\ContainerConfigException;
use yii\base\BaseObject;

/**
 * This class takes care of app container config
 * To see which format you have to use take a lot at:
 * @see https://www.yiiframework.com/doc/guide/2.0/en/concept-di-container
 */
class YiiDicConfigComponent extends BaseObject
{
    /** @var array */
    public $singletons = [];

    /** @var array */
    public $nonSingletons = [];

    /**
     * Initializes the object.
     */
    public function init()
    {
        parent::init();

        $this->configureSingletons($this->singletons);
        $this->configureNonSingletons($this->nonSingletons);
    }

    /**
     * sets all singletons at once
     * @param array $singletons
     */
    private function configureSingletons(array $singletons)
    {
        if (!empty($singletons)) {
            \Yii::$container->setSingletons($singletons);
        }
    }

    /**
     * Walks through the array of non singleton service configurations and registers them.
     *
     * @param array $nonSingletons
     * @throws ContainerConfigException
     */
    private function configureNonSingletons(array $nonSingletons)
    {
        if (count($nonSingletons) > 0) {
            foreach ($nonSingletons as $class => $definition) {
                if (is_callable($definition)) {
                    \Yii::$container->set($class, $definition);
                } elseif (isset($definition[0]) && is_array($definition[0]) &&
                    isset($definition[1]) && is_array($definition[1])
                ) {
                    \Yii::$container->set($class, $definition[0], $definition[1]);
                } else {
                    throw new ContainerConfigException('Definition is not a type of an array or callable');
                }
            }
        }
    }
}
