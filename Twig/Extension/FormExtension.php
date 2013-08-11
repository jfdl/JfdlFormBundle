<?php
namespace Jfdl\FormBundle\Twig\Extension;

use Symfony\Component\Form\FormView;
use Symfony\Bridge\Twig\Form\TwigRendererInterface;

/**
 * Description of FormExtension
 *
 * @author JF
 */
class FormExtension extends \Twig_Extension
{
    /**
    * This property is public so that it can be accessed directly from compiled
    * templates without having to call a getter, which slightly decreases performance.
    *
    * @var \Symfony\Component\Form\FormRendererInterface
    */
    public $renderer;

    public function __construct(TwigRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
    * {@inheritdoc}
    */
    public function getFunctions()
    {
        return array(
            'jfdl_form_javascript' => new \Twig_Function_Method($this, 'renderJavascript', array('is_safe' => array('html'))),
            'jfdl_form_stylesheet' => new \Twig_Function_Node('Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', array('is_safe' => array('html'))),
        );
    }

    /**
    * Render Function Form Javascript
    *
    * @param FormView $view
    * @param bool $prototype
    *
    * @return string
    */
    public function renderJavascript(FormView $view, $prototype = false)
    {
        $block = $prototype ? 'javascript_prototype' : 'javascript';

        return $this->renderer->searchAndRenderBlock($view, $block);
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return 'jfdl.twig.extension.form';
    }

}

?>
