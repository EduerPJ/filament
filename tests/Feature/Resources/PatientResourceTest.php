<?php

use App\Filament\Resources\PatientResource;
use App\Models\Owner;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\User;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Patient::query()->delete();
    Owner::query()->delete();
    Treatment::query()->delete();
});

describe('Patient Resource Listing', function () {
    it('displays patients list page', function () {
        Owner::factory()->count(3)->create()->each(function ($owner) {
            Patient::factory()->count(2)->create(['owner_id' => $owner->id]);
        });

        $response = $this->get('/admin/patients');
        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee('Patients');
    });
});

describe('Patient Resource Creation', function () {
    it('can access create patient page', function () {
        $response = $this->get('/admin/patients/create');
        $response->assertStatus(Response::HTTP_OK);
    });

    it('can create a new patient', function () {
        $owner = Owner::factory()->create();

        Livewire::test(PatientResource\Pages\CreatePatient::class)
            ->fillForm([
                'name' => 'Fluffy',
                'type' => 'cat',
                'date_of_birth' => '2022-01-15',
                'owner_id' => $owner->id,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('patients', [
            'name' => 'Fluffy',
            'type' => 'cat',
            'owner_id' => $owner->id,
        ]);
    });
});

describe('Patient Resource Editing', function () {
    it('can access edit patient page', function () {
        $owner = Owner::factory()->create();
        $patient = Patient::factory()->create(['owner_id' => $owner->id]);

        $response = $this->get("/admin/patients/{$patient->id}/edit");
        $response->assertStatus(Response::HTTP_OK);
    });

    it('can update patient data', function () {
        $owner = Owner::factory()->create();
        $patient = Patient::factory()->create(['owner_id' => $owner->id]);

        Livewire::test(PatientResource\Pages\EditPatient::class, ['record' => $patient->id])
            ->fillForm([
                'name' => 'Updated Name',
                'type' => 'dog',
                'date_of_birth' => '2022-01-15',
                'owner_id' => $owner->id,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'name' => 'Updated Name',
            'type' => 'dog',
        ]);
    });
});

describe('Patient Resource Deletion', function () {
    it('can delete a patient', function () {
        $owner = Owner::factory()->create();
        $patient = Patient::factory()->create(['owner_id' => $owner->id]);

        Livewire::test(PatientResource\Pages\EditPatient::class, ['record' => $patient->id])
            ->callAction('delete');

        $this->assertDatabaseMissing('patients', [
            'id' => $patient->id,
        ]);
    });
});

describe('Patient Treatments Relation', function () {
    it('displays patient treatments in relation manager', function () {
        $owner = Owner::factory()->create();
        $patient = Patient::factory()->create(['owner_id' => $owner->id]);

        Treatment::factory()->count(3)->create(['patient_id' => $patient->id]);

        $response = $this->get("/admin/patients/{$patient->id}/edit");
        $response->assertStatus(Response::HTTP_OK);
    });
});
