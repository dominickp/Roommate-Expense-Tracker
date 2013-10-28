<?php

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Parameter;

/*

$container->setDefinition(
    'dominick_roommate.example',
    new Definition(
        'Dominick\Roommate\RoommateBundle\Example',
        array(
            new Reference('service_id'),
            "plain_value",
            new Parameter('parameter_name'),
        )
    )
);

*/

$container->setParameter(
    'dominick.controller.expense.class',
    'Dominick\RoommateBundle\Controller\ExpenseController'
);

$container->setDefinition('dominick.expense.controller', new Definition(
    '%dominick.controller.expense.class%'
));

