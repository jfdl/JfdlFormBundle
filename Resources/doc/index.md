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


### Step 1: Download JfdlFormBundle


### Step 2: Enable the bundle

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

In your config.yml add the following lines:

``` yml
jfdl_form:
    form_types:
      select2_ajax_entity: true
```


### Step 3: Use jfdl_select2_ajax_entity

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

### Step 4: In your template

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

That's all... for the moment
