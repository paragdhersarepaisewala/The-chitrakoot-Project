<?php

namespace Tests\Feature;

use App\Models\LeadershipProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadershipSocialsAndPlaylistTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->adminUser = User::where('role', 'super_admin')->first() ?? User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_manage_maharaj_socials_and_youtube_playlist_and_public_page_respects_toggles(): void
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.leadership.store'), [
            'name_hi' => 'पूज्य स्वामी रामभद्राचार्य जी महाराज',
            'name_en' => 'Pujya Swami Maharaj Ji',
            'title_hi' => 'पीठाधीश्वर एवं संरक्षक',
            'role_category' => 'peethadhishwar',
            'sort_order' => 1,
            'is_active' => '1',
            'socials' => [
                'youtube' => ['enabled' => '1', 'url' => 'https://www.youtube.com/@maharajji'],
                'facebook' => ['url' => 'https://www.facebook.com/maharajji'],
                'instagram' => ['enabled' => '1', 'url' => 'https://www.instagram.com/maharajji'],
                'x' => ['enabled' => '1', 'url' => 'https://x.com/maharajji'],
            ],
            'is_youtube_playlist_enabled' => '1',
            'youtube_playlist_title' => 'कामदगिरि पावन प्रवचन श्रृंखला',
            'youtube_playlist_url' => 'https://www.youtube.com/playlist?list=PLMaharajDiscourses123',
        ]);

        $response->assertRedirect(route('admin.leadership'));

        $profile = LeadershipProfile::where('name_hi', 'पूज्य स्वामी रामभद्राचार्य जी महाराज')->firstOrFail();
        $this->assertTrue($profile->is_youtube_playlist_enabled);
        $this->assertSame('PLMaharajDiscourses123', $profile->getYoutubePlaylistId());

        $publicResponse = $this->get(route('leadership'));
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('https://www.youtube.com/@maharajji');
        $publicResponse->assertSee('https://www.instagram.com/maharajji');
        $publicResponse->assertSee('https://x.com/maharajji');
        $publicResponse->assertDontSee('https://www.facebook.com/maharajji');
        $publicResponse->assertSee('https://www.youtube-nocookie.com/embed/videoseries?list=PLMaharajDiscourses123');
        $publicResponse->assertSee('कामदगिरि पावन प्रवचन श्रृंखला');

        // Toggle YouTube playlist off and disable X, while enabling Facebook
        $updateResponse = $this->actingAs($this->adminUser)->put(route('admin.leadership.update', $profile->id), [
            'name_hi' => $profile->name_hi,
            'title_hi' => $profile->title_hi,
            'role_category' => 'peethadhishwar',
            'sort_order' => 1,
            'is_active' => '1',
            'socials' => [
                'youtube' => ['enabled' => '1', 'url' => 'https://www.youtube.com/@maharajji'],
                'facebook' => ['enabled' => '1', 'url' => 'https://www.facebook.com/maharajji'],
                'instagram' => ['enabled' => '1', 'url' => 'https://www.instagram.com/maharajji'],
                'x' => ['url' => 'https://x.com/maharajji'],
            ],
            'youtube_playlist_title' => 'कामदगिरि पावन प्रवचन श्रृंखला',
            'youtube_playlist_url' => 'https://www.youtube.com/playlist?list=PLMaharajDiscourses123',
        ]);

        $updateResponse->assertRedirect(route('admin.leadership'));

        $afterUpdate = $this->get(route('leadership'));
        $afterUpdate->assertStatus(200);
        $afterUpdate->assertSee('https://www.facebook.com/maharajji');
        $afterUpdate->assertDontSee('https://x.com/maharajji');
        $afterUpdate->assertDontSee('videoseries?list=PLMaharajDiscourses123');
    }
}
