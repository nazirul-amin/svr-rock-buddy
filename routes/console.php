<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use function Laravel\Prompts\form;
use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;

Schedule::command('telescope:prune')->daily();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('create:admin', function () {
    $responses = form()
    ->text('What is your name?', required: true, name: 'name', validate: ['name' => 'string|max:255'])
    ->text('What is your email?', required: true, name: 'email', validate: ['email' => 'email|max:255|unique:' . User::class])
    ->password(
        label: 'What is your password?',
        validate: ['password' => 'min:8'],
        name: 'password'
    )
    ->confirm('Do you accept the terms?')
    ->submit();

    spin(
        message: 'Creating admin user...',
        callback: fn () => User::create([
            'name' => $responses['name'],
            'email' => $responses['email'],
            'password' => $responses['password'],
        ])
    );

    info('Admin user created successfully.');
});