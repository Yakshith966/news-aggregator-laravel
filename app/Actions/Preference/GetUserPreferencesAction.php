<?php

namespace App\Actions\Preference;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class GetUserPreferencesAction
{
    public function execute(): Collection
    {
        return Auth::user()->preferences()->with('preferencable')->get();
    }
}
