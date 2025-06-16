<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

uses(RefreshDatabase::class);

it('redirects unauthenticated users from admin panel', function () {
    $response = $this->get('/admin'); // @phpstan-ignore-line

    $response->assertStatus(Response::HTTP_FOUND);
    $response->assertRedirect('/admin/login');
});

it('allows authenticated users to access admin panel', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/admin'); // @phpstan-ignore-line

    $response->assertStatus(Response::HTTP_OK);
});
