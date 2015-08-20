Getting Started With JfdlFormBundle
==================================

## Prerequisites

This version of the bundle requires:

1. Symfony 2.2+
2. [jQuery & jQueryUi](http://jquery.com)
3. [jquery.select2](http://ivaynberg.github.io/select2/)

## Installation

Installation :

1. Download JfdlFormBundle
2. Enable the Bundle


### Step 1: Download JfdlFormBundle using `composer.json`

If you're using Symfony versions > 2.2.x and < 2.6.x you must use v2.3.x

For Symfony 2.6.x you must use v2.6.x

For Symfony 2.7.x you must use v2.7.x

For version > 2.7.x

``` php
<?php
// composer.json
in your require section
"jfdl/form-bundle": "~2.7"

```

For version < 2.7.x
``` php
<?php
// composer.json
in your require section
"jfdl/form-bundle": "2.3.2"

in your repositories section
"repositories": {
    "jfdl/form-bundle": {
        "type": "package",
        "package": {
            "version": "v2.3.2",
            "name": "jfdl/form-bundle",
            "source": {
               "url": "https://github.com/jfdl/JfdlFormBundle.git",
               "type": "git",
               "reference": "v2.3.2"
            },
            "autoload": {
                "psr-0": { "Jfdl\\Bundle\\FormBundle": "" }
            },
            "target-dir": "Jfdl/Bundle/FormBundle"
        }
    }
},
```

### Step 2: Enable the Bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Jfdl\FormBundle\JfdlFormBundle(),
    );
}
```

### Step 3: Use `jfdl_select2_ajax_entity`

In your form class :

``` php
<?php

public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder->add('my_field', 'jfdl_select2_ajax_entity', array(
        'class' => 'My\MyBundle\Entity\Myentity',
        'route' => 'my_route_to_ajax_autocomplete',
        'minimumInputLength' => 4,
        'multiple' => false
    ));
}
```

### Step 4: In your Template

Add jquery.select2 js file

``` php
{% block javascripts %}
    {% javascripts
        '@MyBundle/Resources/public/js/jquery.select2/current/select2.min.js'
    %}
    <script src="{{ asset_url }}" type="text/javascript"></script>
    {% endjavascripts %}
{% endblock %}
```

Add jquery.select2 css file

``` php
{% block stylesheets %}
    {% stylesheets filter='css_url_rewrite,less'
        '@MyBundle/Resources/public/js/jquery.select2/current/select2.css'
    %}
    <link href="{{ asset_url }}" type="text/css" rel="stylesheet" />
    {% endstylesheets %}
{% endblock %}
```

### Step 5: In your `config.yml`

Setup bundle configuration in your `app/config/config.yml`.

```
jfdl_form:
    form_types:
        select2_ajax_entity: true
```

### Step 6: Create Controller Action

In order to fetch entities, create a controller action.

```
/**
 * Returns a JSON list of entities.
 * 
 * @Route("/ajax_autocomplete")
 * @Method("POST")
 */
public function ajaxAutocompleteAction(Request $request)
{
    $q = $request->get('q');

    $response = new Response();

    // Fetch your entities with a query of your own
    $entities = findEntities($q);

    $jsonResults = array();

    foreach($entities as $entity)
    {
        $jsonResults[] = array(
            'id' => $entity->getId(),
            'text' => $entity->getLabel()
            );
    }

    $response->setContent(json_encode($jsonResults));

    $response->headers->set('Content-Type', 'application/json');
    return $response;
}
```

### Configuration Options in your Form Type

- `placeholder`: Default text to display (Default : Choose an option)
- `route`: Route select2 have to send datas request (Default: null)
- `quietMillis`: Delay before select2 will send an ajax request (Default: 300 ms)
- `jsonText`: json response should be like this [{'id':1, 'text':MyFirstValue}] but you may want to change de text key with this value (Default: null)
- `minimumInputLength`: Minimum input length before ajax call (Default: 3)

That's all... for the moment
