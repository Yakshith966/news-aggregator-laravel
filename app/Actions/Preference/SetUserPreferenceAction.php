<?php

namespace App\Actions\Preference;

use App\Models\Preference;
use Illuminate\Support\Facades\Auth;

class SetUserPreferenceAction
{
    /**
     * Set user preference
     *
     * @param  array  $data  [preferencable_id, preferencable_type]
     * @return void
     */
    public function execute(array $data): Preference
    {
        $user = Auth::user();

        return $user->preferences()->updateOrCreate(
            [
                'preferencable_id' => $data['preferencable_id'],
                'preferencable_type' => Preference::TYPE_MAP[$data['preferencable_type']],
            ]
        );
    }
}
