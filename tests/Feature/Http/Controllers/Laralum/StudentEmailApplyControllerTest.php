<?php

namespace Tests\Feature\Http\Controllers\Laralum;

use App\AdmissionSession;
use App\Department;
use App\Program;
use App\StudentEmailApply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Laralum\StudentEmailApplyController
 */
class StudentEmailApplyControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_displays_view()
    {
        $studentEmailApplies = factory(StudentEmailApply::class, 3)->create();

        $response = $this->get(route('student-email-apply.index'));

        $response->assertOk();
        $response->assertViewIs('laralum.student-domain-apply.index');
        $response->assertViewHas('applyEmails');
    }


    /**
     * @test
     */
    public function create_displays_view()
    {
        $response = $this->get(route('student-email-apply.create'));

        $response->assertOk();
        $response->assertViewIs('laralum.student-domain-apply.create');
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\Laralum\StudentEmailApplyController::class,
            'store',
            \App\Http\Requests\Laralum\StudentEmailApplyStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves_and_redirects()
    {
        $admission_session = factory(AdmissionSession::class)->create();
        $department = factory(Department::class)->create();
        $program = factory(Program::class)->create();
        $regestration_number = $this->faker->word;
        $name = $this->faker->name;
        $contact_phone = $this->faker->word;

        $response = $this->post(route('student-email-apply.store'), [
            'admission_session_id' => $admission_session->id,
            'department_id' => $department->id,
            'program_id' => $program->id,
            'regestration_number' => $regestration_number,
            'name' => $name,
            'contact_phone' => $contact_phone,
        ]);

        $studentEmailApplies = StudentEmailApply::query()
            ->where('admission_session_id', $admission_session->id)
            ->where('department_id', $department->id)
            ->where('program_id', $program->id)
            ->where('regestration_number', $regestration_number)
            ->where('name', $name)
            ->where('contact_phone', $contact_phone)
            ->get();
        $this->assertCount(1, $studentEmailApplies);
        $studentEmailApply = $studentEmailApplies->first();

        $response->assertRedirect(route('StudentEmailApply.index'));
    }
}
