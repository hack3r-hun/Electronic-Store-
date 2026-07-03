<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_saves_message(): void
    {
        $response = $this->post(route('contact.store'), [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Product inquiry',
            'message' => 'Do you have LED bulbs in stock?',
        ]);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'john@example.com',
            'subject' => 'Product inquiry',
        ]);
    }

    public function test_contact_form_validates_required_fields(): void
    {
        $response = $this->from(route('contact'))->post(route('contact.store'), []);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }
}
