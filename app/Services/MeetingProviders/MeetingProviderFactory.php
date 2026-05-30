<?php

namespace App\Services\MeetingProviders;

use InvalidArgumentException;

class MeetingProviderFactory
{
    /**
     * Instantiate the correct provider based on the provider key.
     * 
     * @param string $provider
     * @return MeetingProviderContract
     */
    public static function make(string $provider): MeetingProviderContract
    {
        return match ($provider) {
            'manual_zoom', 'manual_meet', 'manual_teams' => new ManualLinkProvider(),
            // Future providers like 'api_zoom' will be added here
            default => new ManualLinkProvider(), // fallback
        };
    }
}
