<?php

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    protected static string $view = 'filament.pages.auth.login';

    public function registerAction(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('register')
            ->link()
            ->label('Don\'t have an account? Register here')
            ->url(filament()->getRegistrationUrl());
    }
}
