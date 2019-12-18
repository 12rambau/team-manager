<?php

namespace App\tests\Form;

use App\Form\ContactType;
use Symfony\Component\Form\Test\TypeTestCase;

class ContactTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $this->markTestIncomplete('Waiting for information on stack for construct a type with services');
        /*
        $formData = [
            'subject'   => 'Subject',
            'name'      => 'Pierrick',
            'email'     => 'pierrick@domaine.com',
            'message'   => 'Team-Manager Rocks !'
        ];
        
        $form = $this->factory->create(ContactType::class, null);

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
        */
    }
}
