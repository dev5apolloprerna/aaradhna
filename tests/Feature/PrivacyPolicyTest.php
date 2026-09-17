<?php

namespace Tests\Feature;

use Tests\TestCase;

class PrivacyPolicyTest extends TestCase
{
    /** @dataProvider privacyPolicyUrls */
    public function testPrivacyPolicyIsPubliclyAvailable(string $url): void
    {
        $this->withoutExceptionHandling();

        $this->get($url)
            ->assertOk()
            ->assertSee('Privacy Policy')
            ->assertSee('Information we collect')
            ->assertSee('Contact us');
    }

    public function privacyPolicyUrls(): array
    {
        return [
            'website' => ['/privacy-policy'],
            'app API' => ['/api/privacy-policy'],
        ];
    }
}
