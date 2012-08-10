<?php
namespace Application\Form\Type;

class LocationPostalType extends LocationType
{
    protected $_extension = array(
        'name' => '_postal',
        'label' => ' Postal'
    );
}