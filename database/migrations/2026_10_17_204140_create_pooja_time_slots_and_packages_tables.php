<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add package category / service type to pooja_services
        if (Schema::hasTable('pooja_services')) {
            Schema::table('pooja_services', function (Blueprint $table) {
                if (! Schema::hasColumn('pooja_services', 'service_type')) {
                    $table->string('service_type')->default('pooja')->after('slug'); // pooja, hawan, anushthan
                }
                if (! Schema::hasColumn('pooja_services', 'samagri_included')) {
                    $table->text('samagri_included')->nullable()->after('benefits_hi');
                }
                if (! Schema::hasColumn('pooja_services', 'priest_assigned')) {
                    $table->string('priest_assigned')->nullable()->after('samagri_included');
                }
            });
        }

        // 2. Create Mandir Available Time Slots table managed by Admin
        if (! Schema::hasTable('pooja_time_slots')) {
            Schema::create('pooja_time_slots', function (Blueprint $table) {
                $table->id();
                $table->string('slot_name');
                $table->string('start_time', 10); // "06:30"
                $table->string('end_time', 10);   // "08:00"
                $table->string('service_type')->default('all'); // all, pooja, hawan
                $table->integer('max_capacity')->default(5);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });

            // Seed authentic Chitrakoot Mandir daily slots
            $defaultSlots = [
                [
                    'slot_name' => 'प्रातः कालीन मंगला आरती व महायज्ञ (Morning Mangala Aarti & Yagya)',
                    'start_time' => '06:30',
                    'end_time' => '08:00',
                    'service_type' => 'all',
                    'max_capacity' => 8,
                    'is_active' => true,
                    'sort_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slot_name' => 'प्रातः कालीन विशेष वैदिक पूजन व रुद्राभिषेक (Morning Vedic Pooja)',
                    'start_time' => '08:30',
                    'end_time' => '10:30',
                    'service_type' => 'pooja',
                    'max_capacity' => 6,
                    'is_active' => true,
                    'sort_order' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slot_name' => 'मध्याह्न भोग, परिक्रमा एवं कामदगिरि संकल्प (Midday Bhog & Parikrama)',
                    'start_time' => '11:00',
                    'end_time' => '12:30',
                    'service_type' => 'all',
                    'max_capacity' => 6,
                    'is_active' => true,
                    'sort_order' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slot_name' => 'अपराह्न नवग्रह शांति हवन व गायत्री महायज्ञ (Afternoon Hawan & Yagya)',
                    'start_time' => '15:30',
                    'end_time' => '17:30',
                    'service_type' => 'hawan',
                    'max_capacity' => 5,
                    'is_active' => true,
                    'sort_order' => 4,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'slot_name' => 'सायंकालीन मंदाकिनी दीपदान, महाआरती व पूर्णाहुति (Evening Maha Aarti)',
                    'start_time' => '18:30',
                    'end_time' => '20:00',
                    'service_type' => 'all',
                    'max_capacity' => 10,
                    'is_active' => true,
                    'sort_order' => 5,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];

            DB::table('pooja_time_slots')->insert($defaultSlots);
        }

        // 3. Ensure a default Hawan package exists in pooja_services if not already present
        if (Schema::hasTable('pooja_services')) {
            $hawanExists = DB::table('pooja_services')->where('service_type', 'hawan')->exists();
            if (! $hawanExists) {
                DB::table('pooja_services')->insert([
                    'title_hi' => 'श्री नवग्रह शांति महायज्ञ एवं वैदिक हवन',
                    'title_en' => 'Navgrah Shanti Maha Yagya & Hawan Package',
                    'slug' => 'navgrah-shanti-mahayagya-hawan',
                    'service_type' => 'hawan',
                    'summary_hi' => 'समस्त ग्रहों की अनुकूलता, वास्तुदोष निवारण एवं पारिवारिक सुख-शांति हेतु चित्रकूट के पवित्र तीर्थ पर वैदिक आचार्यों द्वारा संपन्न संपूर्ण हवनात्मक अनुष्ठान।',
                    'description_hi' => 'भगवान श्री कामतानाथ जी की पावन तपोभूमि में नवग्रह शांति व गायत्री महायज्ञ समस्त मनोरथों को सिद्ध करने वाला है। इसमें समिधा, हवन सामग्री, पंचामृत एवं वैदिक आचार्यों का सानिध्य न्यास द्वारा प्रदान किया जाता है।',
                    'suggested_donation_amount' => 2100.00,
                    'duration_minutes' => 90,
                    'timings_display' => 'प्रातः 06:30 AM व अपराह्न 03:30 PM',
                    'benefits_hi' => json_encode(['नौ ग्रहों की शांति व अनुकूलता', 'समस्त बाधाओं व कष्टों का निवारण', 'गंगाजल, रोली, चंदन व महाप्रसाद डाक द्वारा प्रेषित']),
                    'samagri_included' => 'शुद्ध देशी घी, नवग्रह समिधा, हवन कुण्ड, रोली, अक्षत, धूप, पंचमेवा व रक्षासूत्र न्यास द्वारा समर्पित',
                    'priest_assigned' => 'पीठम् के वरिष्ठ वैदिक आचार्यों द्वारा संकल्पित',
                    'featured_image' => '/images/chitrakoot-hero.webp',
                    'sort_order' => 2,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pooja_time_slots');

        if (Schema::hasTable('pooja_services')) {
            Schema::table('pooja_services', function (Blueprint $table) {
                if (Schema::hasColumn('pooja_services', 'service_type')) {
                    $table->dropColumn('service_type');
                }
                if (Schema::hasColumn('pooja_services', 'samagri_included')) {
                    $table->dropColumn('samagri_included');
                }
                if (Schema::hasColumn('pooja_services', 'priest_assigned')) {
                    $table->dropColumn('priest_assigned');
                }
            });
        }
    }
};
