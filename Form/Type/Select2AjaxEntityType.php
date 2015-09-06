<?php
namespace Jfdl\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Router;
use Jfdl\FormBundle\Form\DataTransformer\AjaxEntityTransformer;
use Symfony\Component\Translation\TranslatorInterface;


/**
 * Description of JfdlSelect2Entity
 *
 * @author JF
 */
class Select2AjaxEntityType extends AbstractType
{
    protected $registry;
    protected $router;
    protected $translator;

    public function __construct(ManagerRegistry $registry, Router $router, TranslatorInterface $translator)
    {
        $this->registry = $registry;
        $this->router = $router;
        $this->translator = $translator;

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new AjaxEntityTransformer(
            $this->registry,
            $options['class'],
            $options['multiple'],
            $options['property']
        );
        $builder->setAttribute('placeholder', $options['placeholder']);
        $builder->setAttribute('multiple', $options['multiple']);
        $builder->setAttribute('route', $options['route']);
        $builder->setAttribute('quietMillis', $options['quietMillis']);
        $builder->setAttribute('jsonText', $options['jsonText']);
        $builder->setAttribute('minimumInputLength', $options['minimumInputLength']);
        $builder->setAttribute('attr', $options['attr']);
        $builder->addViewTransformer($transformer);

    }


    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // Construct ChoiceView
        $choices = array();
        foreach($view->vars['value'] as $k => $v) {
            $choices[] = new ChoiceView($k, $k, $v, array('selected' => 'selected'));
        }

        $multiple = $options['multiple'];

        if (true == $multiple) {
            $view->vars['full_name'] .= '[]';
        }

        $view->vars['attr']['data-placeholder'] = $this->translator->trans($options['placeholder']);
        if ($form->getConfig()->getAttribute('route')) {
            $view->vars['route'] = $this->router->generate($form->getConfig()->getAttribute('route'));
        }

        $view->vars['expanded'] = false;
        $view->vars['preferred_choices'] = false;
        $view->vars['choice_translation_domain'] = false;
        $view->vars['choices'] = $choices;
        $view->vars['placeholder'] = $form->getConfig()->getAttribute('placeholder');
        $view->vars['multiple'] = $form->getConfig()->getAttribute('multiple');
        $view->vars['quietMillis'] = $form->getConfig()->getAttribute('quietMillis');
        $view->vars['jsonText'] = $form->getConfig()->getAttribute('jsonText');
        $view->vars['minimumInputLength'] = $form->getConfig()->getAttribute('minimumInputLength');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('class'));
        $resolver->setRequired(array('route'));
        $resolver->setDefaults(array(
                'placeholder'   => 'Choose an option',
                'choices' => array(),
                'repo_method'   => null,
                'property'      => null,
                'multiple'      => false,
                'quietMillis' => '300',
                'jsonText' => null,
                'minimumInputLength' => 3
            ));

    }

    public function getDefaultOptions(array $options) {
        parent::getDefaultOptions($options);
    }

    public function getParent()
    {
        return 'text';
    }

    public function getName()
    {
        return 'jfdl_select2_ajax_entity';
    }

}

?>
