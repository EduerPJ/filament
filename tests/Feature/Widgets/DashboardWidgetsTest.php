<?php

use App\Filament\Widgets\PatientTypeOverview;
use App\Filament\Widgets\TreatmentsChart;
use App\Models\Owner;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\User;

beforeEach(function () {
    /** @var \Tests\TestCase $this */
    $user = User::factory()->create();
    $this->actingAs($user);

    Patient::query()->delete();
    Owner::query()->delete();
    Treatment::query()->delete();
});

describe('PatientTypeOverview Widget', function () {
    it('displays correct patient counts by type', function () {
        /** @var Owner $owner */
        $owner = Owner::factory()->create();
        $ownerId = $owner->id;

        Patient::factory()->create(['owner_id' => $ownerId, 'type' => 'cat']);
        Patient::factory()->create(['owner_id' => $ownerId, 'type' => 'cat']);
        Patient::factory()->create(['owner_id' => $ownerId, 'type' => 'dog']);
        Patient::factory()->create(['owner_id' => $ownerId, 'type' => 'rabbit']);

        $widget = new PatientTypeOverview;

        $reflection = new ReflectionClass($widget);
        $method = $reflection->getMethod('getStats');
        $method->setAccessible(true);
        $stats = $method->invoke($widget);

        expect($stats)->toHaveCount(3);
        expect($stats[0]->getLabel())->toBe('Cats');
        expect($stats[0]->getValue())->toBe(2);
        expect($stats[1]->getLabel())->toBe('Dogs');
        expect($stats[1]->getValue())->toBe(1);
        expect($stats[2]->getLabel())->toBe('Rabbits');
        expect($stats[2]->getValue())->toBe(1);
    });

    it('shows zero counts when no patients exist', function () {
        $widget = new PatientTypeOverview;

        $reflection = new ReflectionClass($widget);
        $method = $reflection->getMethod('getStats');
        $method->setAccessible(true);
        $stats = $method->invoke($widget);

        expect($stats)->toHaveCount(3);
        expect($stats[0]->getValue())->toBe(0);
        expect($stats[1]->getValue())->toBe(0);
        expect($stats[2]->getValue())->toBe(0);
    });
});

describe('TreatmentsChart Widget', function () {
    it('generates chart data structure correctly', function () {
        /** @var Owner $owner */
        $owner = Owner::factory()->create();
        $ownerId = $owner->id;

        /** @var Patient $patient */
        $patient = Patient::factory()->create(['owner_id' => $ownerId]);
        $patientId = $patient->id;

        Treatment::factory()->create([
            'patient_id' => $patientId,
            'created_at' => now()->subMonths(2),
        ]);
        Treatment::factory()->create([
            'patient_id' => $patientId,
            'created_at' => now()->subMonth(),
        ]);

        $widget = new TreatmentsChart;

        $reflection = new ReflectionClass($widget);
        $method = $reflection->getMethod('getData');
        $method->setAccessible(true);
        $data = $method->invoke($widget);

        expect($data)->toHaveKeys(['datasets', 'labels']);
        expect($data['datasets'])->toHaveCount(1);
        expect($data['datasets'][0])->toHaveKeys(['label', 'data']);
        expect($data['datasets'][0]['label'])->toBe('Treatments');
        expect($data['labels']->toArray())->toBeArray();
    });

    it('returns line chart type', function () {
        $widget = new TreatmentsChart;

        $reflection = new ReflectionClass($widget);
        $method = $reflection->getMethod('getType');
        $method->setAccessible(true);
        $type = $method->invoke($widget);

        expect($type)->toBe('line');
    });

    it('has correct heading', function () {
        $widget = new TreatmentsChart;

        $reflection = new ReflectionClass($widget);
        $property = $reflection->getProperty('heading');
        $property->setAccessible(true);
        $heading = $property->getValue($widget);

        expect($heading)->toBe('Chart');
    });
});
