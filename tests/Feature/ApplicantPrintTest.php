<?php

namespace Tests\Feature;

use App\Models\Applicant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicantPrintTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_admin_can_print_an_applicant(): void
    {
        $user = User::factory()->create();
        $applicant = Applicant::create(['full_name' => 'Jane Doe']);

        $this->actingAs($user)
            ->get(route('applicants.print', $applicant))
            ->assertOk()
            ->assertHeader('Content-Type', 'application/pdf')
            ->assertSee('/Subtype /Image', false);
    }

    public function test_guest_cannot_print_an_applicant(): void
    {
        $applicant = Applicant::create(['full_name' => 'Jane Doe']);

        $this->get(route('applicants.print', $applicant))
            ->assertRedirect('/login');
    }
}
