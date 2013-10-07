<?php
// src/Acme/AccountBundle/Form/Type/UserType.php

namespace Dominick\RoommateBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder->add('email', 'email');
    $builder->add('password', 'repeated', array(
    'first_name'  => 'password',
    'second_name' => 'confirm',
    'type'        => 'password',
    ));
    $builder->add('register', 'submit');
}

public function setDefaultOptions(OptionsResolverInterface $resolver)
{
$resolver->setDefaults(array(
'data_class' => 'Dominick\RoommateBundle\Entity\User'
));
}

public function getName()
{
return 'user';
}
}