<?php

namespace Auth\Form;

use Laminas\Form\Form;
use Laminas\Form\Element;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator;
use Laminas\Filter;

class LoginForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('login');
        
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'needs-validation');
        $this->setAttribute('novalidate', true);
        
        $this->addElements();
        $this->addInputFilter();
    }
    
    public function addElements()
    {
        // Username field
        $username = new Element\Text('username');
        $username->setLabel('Usuario')
                 ->setAttributes([
                     'id' => 'username',
                     'class' => 'form-control',
                     'required' => true,
                     'placeholder' => 'Ingrese su usuario'
                 ]);
        $this->add($username);
        
        // Password field
        $password = new Element\Password('password');
        $password->setLabel('Contraseña')
                 ->setAttributes([
                     'id' => 'password',
                     'class' => 'form-control',
                     'required' => true,
                     'placeholder' => 'Ingrese su contraseña'
                 ]);
        $this->add($password);
        
        // Remember me checkbox
        $remember = new Element\Checkbox('remember');
        $remember->setLabel('Recordarme')
                 ->setAttributes([
                     'id' => 'remember',
                     'class' => 'form-check-input'
                 ])
                 ->setCheckedValue('1')
                 ->setUncheckedValue('0');
        $this->add($remember);
        
        // Submit button
        $submit = new Element\Submit('submit');
        $submit->setValue('Ingresar')
               ->setAttributes([
                   'class' => 'btn btn-primary w-100'
               ]);
        $this->add($submit);
        
        // CSRF protection
        $csrf = new Element\Csrf('csrf');
        $csrf->getCsrfValidator()->setTimeout(600); // 10 minutes
        $this->add($csrf);
    }
    
    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        
        // Username validation
        $inputFilter->add([
            'name' => 'username',
            'required' => true,
            'filters' => [
                ['name' => Filter\StripTags::class],
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => Validator\NotEmpty::class,
                    'options' => [
                        'messages' => [
                            Validator\NotEmpty::IS_EMPTY => 'El usuario es requerido'
                        ]
                    ]
                ],
                [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'min' => 3,
                        'max' => 50,
                        'messages' => [
                            Validator\StringLength::TOO_SHORT => 'El usuario debe tener al menos 3 caracteres',
                            Validator\StringLength::TOO_LONG => 'El usuario no puede tener más de 50 caracteres'
                        ]
                    ]
                ]
            ]
        ]);
        
        // Password validation
        $inputFilter->add([
            'name' => 'password',
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => Validator\NotEmpty::class,
                    'options' => [
                        'messages' => [
                            Validator\NotEmpty::IS_EMPTY => 'La contraseña es requerida'
                        ]
                    ]
                ],
                [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'min' => 4,
                        'messages' => [
                            Validator\StringLength::TOO_SHORT => 'La contraseña debe tener al menos 4 caracteres'
                        ]
                    ]
                ]
            ]
        ]);
        
        // Remember checkbox validation
        $inputFilter->add([
            'name' => 'remember',
            'required' => false,
        ]);
        
        $this->setInputFilter($inputFilter);
    }
}