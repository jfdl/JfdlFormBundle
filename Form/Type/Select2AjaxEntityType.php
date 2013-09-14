<?php
namespace Jfdl\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;
use Jfdl\Bundle\FormBundle\Form\DataTransformer\AjaxEntityTransformer;


/**
 * Description of JfdlSelect2Entity
 *
 * @author JF
 */
class Select2AjaxEntityType extends AbstractType
{
    protected $registry;
    protected $router;

    public function __construct(ManagerRegistry $registry, Router $router)
    {
        $this->registry = $registry;
        $this->router = $router;

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new AjaxEntityTransformer(
            $this->registry,
            $options['class'],
            $options['multiple'],
            $options['property']
        );
        $this->options = $options;

        $builder->setAttribute('multiple', $options['multiple']);
        $builder->setAttribute('route', $options['route']);
        $builder->setAttribute('quietMillis', $options['quietMillis']);
        $builder->setAttribute('jsonText', $options['jsonText']);
        $builder->setAttribute('minimumInputLength', $options['minimumInputLength']);
        $builder->addViewTransformer($transformer);
    }


    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $value = $view->vars['value'];
        $multiple = $options['multiple'];

        if ($value) {
            if ($multiple) {
                // build id string
                $ids = array();
                foreach ($value as $entity) {
                    $ids[] = $entity['id'];
                }
                $view->vars['value'] = implode(',', $ids);
            } else {
                $view->vars['value'] = $value['id'];
            }

            $view->vars['attr']['data-initial'] = json_encode($value);
        }

        $view->vars['attr']['data-placeholder'] = $options['placeholder'];
        if ($form->getConfig()->getAttribute('route')) {
            $view->set('route', $this->router->generate($form->getConfig()->getAttribute('route')));
        }

        $view->set('multiple', $form->getConfig()->getAttribute('multiple'));
        $view->set('quietMillis', $form->getConfig()->getAttribute('quietMillis'));
        $view->set('jsonText', $form->getConfig()->getAttribute('jsonText'));
        $view->set('minimumInputLength', $form->getConfig()->getAttribute('minimumInputLength'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('class'));
        $resolver->setDefaults(array(
                'placeholder'   => 'Choose an option',
                'route'           => null,
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
