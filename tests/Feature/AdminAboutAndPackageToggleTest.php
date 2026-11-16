<?php

namespace Tests\Feature;

use App\Models\HomepageSetting;
use App\Models\LeadershipProfile;
use App\Models\OrganizationSetting;
use App\Models\PoojaTimeSlot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAboutAndPackageToggleTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        HomepageSetting::clearCache();
        $this->seed();

        $this->adminUser = User::where('role', 'super_admin')->first() ?? User::factory()->create([
            'role' => 'super_admin',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_about_page_cms(): void
    {
        $response = $this->get(route('admin.about'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_can_access_about_page_cms_and_view_english_labels(): void
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.about'));
        $response->assertStatus(200);
        $response->assertSee('About Us Page CMS Editor');
        $response->assertSee('Trust Narrative History');
        $response->assertSee('Environmental Mission');
        $response->assertSee('Pilgrim Pledge Banner');
        $response->assertSee('Years of Seva');
    }

    public function test_admin_can_update_about_page_content_and_it_reflects_on_public_about(): void
    {
        $payload = [
            'trust_eyebrow' => 'श्री कामदगिरि दिव्य गाथा',
            'trust_heading' => 'चित्रकूट की पावन तपोभूमि पर अखण्ड सेवा',
            'trust_tagline' => 'भगवान राम की कर्मभूमि पर जन-कल्याण',
            'trust_lead' => 'चित्रकूट धाम में विगत 35 वर्षों से अनवरत गौसेवा एवं गुरुकुल परंपरा का संवर्धन।',
            'trust_desc' => 'परम पूज्य संतों के आशीर्वाद से संचालित कामदगिरि सेवा प्रकल्प।',
            'trust_p2' => 'संपूर्ण तीर्थ क्षेत्र में स्वच्छता, पर्यावरण और संस्कृति का अनुपम संगम।',
            'about_mission_eyebrow' => 'पावन संकल्प',
            'about_mission_heading' => 'पर्यावरण व कामदगिरि संवर्धन के 4 स्तम्भ',
            'about_mission_quote' => 'प्रकृति और परमात्मा की पावन भूमि का संरक्षण ही हमारा मूल धर्म है।',
            'mission_1_title' => 'अखण्ड कामदगिरि परिक्रमा पथ',
            'mission_1_desc' => 'परिक्रमा पथ पर निरंतर स्वच्छता एवं शुद्ध पेयजल व्यवस्था।',
            'mission_1_tag' => 'परिक्रमा सेवा',
            'stat_years' => '35+',
            'stat_years_title' => 'वर्षों की अखण्ड सेवा',
            'pledge_title' => 'श्री कामदगिरि तीर्थ शुचिता संकल्प',
            'pledge_subtitle' => 'पवित्र चित्रकूट धाम को प्लास्टिक मुक्त रखने का पावन प्रण लें।',
            'pledge_button_text' => 'संकल्प पत्र भरें',
            'pledge_button_url' => '/volunteer',
        ];

        $response = $this->actingAs($this->adminUser)->post(route('admin.updateAbout'), $payload);
        $response->assertRedirect(route('admin.about'));
        $response->assertSessionHas('success');

        // Verify public about page displays updated content
        $publicAbout = $this->get('/about');
        $publicAbout->assertStatus(200);
        $publicAbout->assertSee('श्री कामदगिरि दिव्य गाथा');
        $publicAbout->assertSee('चित्रकूट की पावन तपोभूमि पर अखण्ड सेवा');
        $publicAbout->assertSee('पर्यावरण व कामदगिरि संवर्धन के 4 स्तम्भ');
        $publicAbout->assertSee('श्री कामदगिरि तीर्थ शुचिता संकल्प');
    }

    public function test_admin_can_toggle_pooja_packages_feature(): void
    {
        $org = OrganizationSetting::firstOrCreate([]);
        $org->update(['is_pooja_packages_enabled' => false]);

        // Toggle ON
        $response = $this->actingAs($this->adminUser)->post(route('admin.poojas.togglePackages'), [
            'is_pooja_packages_enabled' => 1,
        ]);
        $response->assertRedirect(route('admin.poojas'));
        $this->assertTrue(OrganizationSetting::first()->is_pooja_packages_enabled);

        // When enabled, /pooja renders 200
        $this->get('/pooja')->assertStatus(200);

        // Toggle OFF
        $response = $this->actingAs($this->adminUser)->post(route('admin.poojas.togglePackages'), [
            'is_pooja_packages_enabled' => 0,
        ]);
        $response->assertRedirect(route('admin.poojas'));
        $this->assertFalse(OrganizationSetting::first()->is_pooja_packages_enabled);

        // When disabled, /pooja redirects to /pooja/book
        $this->get('/pooja')->assertRedirect(route('pooja.bookingForm'));
    }

    public function test_pooja_booking_blocks_today_and_tomorrow_and_accepts_two_days_advance(): void
    {
        $slot = PoojaTimeSlot::first() ?? PoojaTimeSlot::create([
            'slot_name' => 'प्रातः कालीन मंगला आरती व महायज्ञ',
            'start_time' => '06:00',
            'end_time' => '07:30',
            'is_active' => true,
        ]);

        // Test 1: Today date is rejected
        $todayPayload = [
            'devotee_name' => 'रमेश कुमार',
            'gotra' => 'कश्यप',
            'phone' => '9876543210',
            'email' => 'ramesh@example.com',
            'booking_date' => now()->format('Y-m-d'),
            'time_slot' => $slot->slot_name,
        ];
        $todayResponse = $this->post(route('pooja.book'), $todayPayload);
        $todayResponse->assertSessionHasErrors(['booking_date']);

        // Test 2: Tomorrow date is rejected
        $tomorrowPayload = [
            'devotee_name' => 'रमेश कुमार',
            'gotra' => 'कश्यप',
            'phone' => '9876543210',
            'email' => 'ramesh@example.com',
            'booking_date' => now()->addDay()->format('Y-m-d'),
            'time_slot' => $slot->slot_name,
        ];
        $tomorrowResponse = $this->post(route('pooja.book'), $tomorrowPayload);
        $tomorrowResponse->assertSessionHasErrors(['booking_date']);

        // Test 3: Day after tomorrow (2 days in advance) is valid and accepts any temple time slot
        $validDate = now()->addDays(2)->format('Y-m-d');
        $validPayload = [
            'devotee_name' => 'रमेश कुमार',
            'gotra' => 'कश्यप',
            'phone' => '9876543210',
            'email' => 'ramesh@example.com',
            'booking_date' => $validDate,
            'time_slot' => $slot->slot_name,
            'special_instructions' => 'सपरिवार कामदगिरि विशेष पूजा',
        ];
        $validResponse = $this->post(route('pooja.book'), $validPayload);
        $validResponse->assertSessionHasNoErrors();
        $validResponse->assertRedirect();
    }

    public function test_leadership_profiles_can_be_managed_in_admin_and_render_on_public_page(): void
    {
        // 1. Create a new leadership profile as admin
        $profileData = [
            'name_hi' => 'डॉ. देवेन्द्र नाथ शास्त्री',
            'name_en' => 'Dr. Devendra Nath Shastri',
            'title_hi' => 'वरिष्ठ प्रबंध न्यासी',
            'title_en' => 'Senior Managing Trustee',
            'role_category' => 'trustee',
            'bio_hi' => 'विगत 25 वर्षों से कामदगिरि पीठम् के संचालन एवं गुरुकुल सेवा में समर्पित।',
            'bio_en' => 'Dedicated to Kamadgiri Peetham administration and Gurukul traditions for 25 years.',
            'sort_order' => 1,
            'is_active' => 1,
        ];

        $createResponse = $this->actingAs($this->adminUser)->post(route('admin.leadership.store'), $profileData);
        $createResponse->assertRedirect(route('admin.leadership'));
        $createResponse->assertSessionHas('success');

        $this->assertDatabaseHas('leadership_profiles', [
            'name_hi' => 'डॉ. देवेन्द्र नाथ शास्त्री',
            'title_hi' => 'वरिष्ठ प्रबंध न्यासी',
        ]);

        // 2. Verify public /leadership page renders this leader
        $publicResponse = $this->get('/leadership');
        $publicResponse->assertStatus(200);
        $publicResponse->assertSee('डॉ. देवेन्द्र नाथ शास्त्री');
        $publicResponse->assertSee('वरिष्ठ प्रबंध न्यासी');
        $publicResponse->assertSee('कामदगिरि पीठम् के संचालन एवं गुरुकुल सेवा में समर्पित');

        // 3. Update the profile
        $profile = LeadershipProfile::where('name_hi', 'डॉ. देवेन्द्र नाथ शास्त्री')->first();
        $updateResponse = $this->actingAs($this->adminUser)->put(route('admin.leadership.update', $profile->id), array_merge($profileData, [
            'title_hi' => 'मुख्य अधिशासी न्यासी',
        ]));
        $updateResponse->assertRedirect(route('admin.leadership'));
        $this->assertDatabaseHas('leadership_profiles', [
            'id' => $profile->id,
            'title_hi' => 'मुख्य अधिशासी न्यासी',
        ]);

        // 4. Delete the profile
        $deleteResponse = $this->actingAs($this->adminUser)->delete(route('admin.leadership.delete', $profile->id));
        $deleteResponse->assertRedirect(route('admin.leadership'));
        $this->assertDatabaseMissing('leadership_profiles', [
            'id' => $profile->id,
        ]);
    }
}
