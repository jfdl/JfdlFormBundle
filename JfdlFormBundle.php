<?php

namespace Jfdl\Bundle\FormBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Jfdl\Bundle\FormBundle\DependencyInjection\Compiler\FormPass;

class JfdlFormBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new FormPass());
    }
}
