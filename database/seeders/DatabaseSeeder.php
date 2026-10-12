<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Cause;
use App\Models\SevaProject;
use App\Models\Destination;
use App\Models\Event;
use App\Models\HeroSlide;
use App\Models\HomepageBlock;
use App\Models\OrganizationSetting;
use App\Models\EmailTemplate;
use App\Models\LeadershipProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        User::firstOrCreate(
            ['email' => 'admin@kamadgiripeetham.org'],
            [
                'name' => 'Shri Kamadgiri Peetham Admin',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'approval_status' => 'approved',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. Organization Settings
        OrganizationSetting::getSettings()->update([
            'org_name' => 'Shri Kamadgiri Peetham Sewa Trust',
            'address' => 'Kamadgiri Parikrama Marg, Chitrakoot Dham, Madhya Pradesh / Uttar Pradesh, India',
            'phone' => '+91 94150 00000',
            'email' => 'info@kamadgiripeetham.org',
            'reg_number' => 'REG/CKT/2012/80G',
            'receipt_header_title' => 'Shri Kamadgiri Peetham Sewa Trust — Official Receipt',
            'receipt_legal_note' => 'Donations are eligible for tax relief under Section 80G of Income Tax Act. Thank you for your support.',
            'show_donation_totals' => true,
        ]);

        // 3. Homepage Blocks
        $blocks = [
            ['key' => 'hero', 'name' => 'Hero Banner Header', 'sort_order' => 1],
            ['key' => 'about_summary', 'name' => 'About Organization Summary', 'sort_order' => 2],
            ['key' => 'seva', 'name' => 'Featured Seva Projects', 'sort_order' => 3],
            ['key' => 'chitrakoot', 'name' => 'Chitrakoot Sacred Places', 'sort_order' => 4],
            ['key' => 'events', 'name' => 'Upcoming Events', 'sort_order' => 5],
            ['key' => 'donate_section', 'name' => 'Donation CTA Showcase', 'sort_order' => 6],
        ];

        foreach ($blocks as $block) {
            HomepageBlock::firstOrCreate(['key' => $block['key']], $block);
        }

        // 4. Hero Slides
        HeroSlide::firstOrCreate(
            ['title' => 'सेवा से समृद्धि · संस्कार से सशक्त · प्रकृति से सुरक्षित'],
            [
                'subtitle' => 'श्री कामदगिरि पीठम् सेवा ट्रस्ट — सनातन संस्कृति, शिक्षा, गौसेवा, पर्यावरण संरक्षण और मानव कल्याण के लिए समर्पित एक सेवा परिवार।',
                'image_path' => 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=2400&q=90',
                'button_text' => 'हमारी सेवाएँ',
                'button_link' => '#seva',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // 5. Causes
        $gaushalaCause = Cause::firstOrCreate(
            ['slug' => 'gaushala-seva'],
            [
                'title' => 'गौ सेवा एवं गौ संरक्षण',
                'description' => 'चित्रकूट धाम में निराश्रित गौवंश के लिए चारा, चिकित्सा और संरक्षण सेवा।',
                'target_amount' => 500000.00,
                'minimum_amount' => 501.00,
                'image_path' => 'https://images.unsplash.com/photo-1560493676-04071c5f467b?auto=format&fit=crop&w=1500&q=85',
                'status' => 'active',
            ]
        );

        $vidyalayaCause = Cause::firstOrCreate(
            ['slug' => 'sanskrit-vidyalaya'],
            [
                'title' => 'गुरुकुल एवं संस्कृत विद्यालय',
                'description' => 'वैदिक विद्वानों और बालकों हेतु संस्कृत शिक्षा, भोजन और आवास व्यवस्था।',
                'target_amount' => 300000.00,
                'minimum_amount' => 501.00,
                'image_path' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&w=1200&q=85',
                'status' => 'active',
            ]
        );

        $eventCause = Cause::firstOrCreate(
            ['slug' => 'religious-events-annakshetra'],
            [
                'title' => 'धार्मिक आयोजन एवं अन्नक्षेत्र',
                'description' => 'परिक्रमा मार्ग पर संतों एवं भक्तों हेतु निःशुल्क अन्नक्षेत्र एवं सत्संग आयोजन।',
                'target_amount' => 200000.00,
                'minimum_amount' => 501.00,
                'image_path' => 'https://images.unsplash.com/photo-1604608672516-f1b9e1e3b7aa?auto=format&fit=crop&w=1200&q=85',
                'status' => 'active',
            ]
        );

        // 6. Seva Projects
        SevaProject::firstOrCreate(
            ['slug' => 'gaushala-protection'],
            [
                'title_hi' => 'गौ सेवा, गौ संरक्षण',
                'title_en' => 'Gaushala & Cow Protection',
                'category' => 'seva',
                'summary_hi' => 'गौवंश के लिए देखभाल, आश्रय और सेवा — करुणा को कर्म में बदलने का हमारा संकल्प।',
                'summary_en' => 'Compassionate care, shelter, and medical nutrition for revered cows in Chitrakoot.',
                'description_hi' => 'चित्रकूट की पवित्र भूमि पर कामदगिरि पीठम् सेवा ट्रस्ट द्वारा संचालित गौशाला में सैंकड़ों गौवंश की नित्य सेवा, हरा चारा, सुव्यवस्थित शेड और पशु चिकित्सकों द्वारा चिकित्सा उपलब्ध कराई जाती है।',
                'description_en' => 'Our Gaushala provides daily nutrition, green fodder, clean dynamic shelter, and veterinary assistance to hundreds of sacred cows.',
                'featured_image' => 'https://images.unsplash.com/photo-1560493676-04071c5f467b?auto=format&fit=crop&w=1500&q=85',
                'cause_id' => $gaushalaCause->id,
                'sort_order' => 1,
                'is_published' => true,
            ]
        );

        SevaProject::firstOrCreate(
            ['slug' => 'sanskrit-gurukul'],
            [
                'title_hi' => 'संस्कृत विद्यालय',
                'title_en' => 'Sanskrit Vidyalaya & Gurukul',
                'category' => 'education',
                'summary_hi' => 'वैदिक ज्ञान और संस्कारों को आने वाली पीढ़ियों तक पहुँचाने का प्रयास।',
                'summary_en' => 'Preserving traditional Vedic wisdom, Sanskrit literature, and spiritual ethics for future generations.',
                'description_hi' => 'कामदगिरि पीठम् संस्कृत विद्यालय में बालकों को निःशुल्क आवास, सात्विक भोजन और वेद, व्याकरण, ज्योतिष तथा नैतिक शिक्षा दी जाती है।',
                'description_en' => 'Providing free residence, pure nutrition, and comprehensive Sanskrit education to young scholars in Chitrakoot.',
                'featured_image' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&w=1200&q=85',
                'cause_id' => $vidyalayaCause->id,
                'sort_order' => 2,
                'is_published' => true,
            ]
        );

        SevaProject::firstOrCreate(
            ['slug' => 'religious-events'],
            [
                'title_hi' => 'धार्मिक आयोजन एवं सत्संग',
                'title_en' => 'Religious Ceremonies & Cultural Events',
                'category' => 'culture',
                'summary_hi' => 'उत्सव, सत्संग और सांस्कृतिक आयोजनों के माध्यम से समाज को जोड़ना।',
                'summary_en' => 'Uniting the community through sacred celebrations, satsang discourses, and cultural heritage festivals.',
                'description_hi' => 'कामदगिरि परिक्रमा एवं रामघाट तट पर भव्य धार्मिक अनुष्ठान, कथा, संत समागम और दीपदान का आयोजन किया जाता है।',
                'description_en' => 'Organizing grand spiritual discourses, holy recitations, Sant Samagam, and evening Mandakini Deepotsav.',
                'featured_image' => 'https://images.unsplash.com/photo-1518005020951-eccb494ad742?auto=format&fit=crop&w=1200&q=85',
                'cause_id' => $eventCause->id,
                'sort_order' => 3,
                'is_published' => true,
            ]
        );

        // 7. Sacred Destinations of Chitrakoot
        $destinations = [
            [
                'name_hi' => 'कामदगिरि परिक्रमा',
                'name_en' => 'Kamadgiri Parikrama',
                'slug' => 'kamadgiri-parikrama',
                'tagline_hi' => 'मनोकामना पूर्ण करने वाले पवित्र पर्वत की ५ किमी परिक्रमा',
                'tagline_en' => 'Sacred 5km circumambulation around the wish-fulfilling Kamadgiri hill',
                'summary_hi' => 'कामदगिरि पर्वत चित्रकूट का मुख्य तीर्थ स्थान है। मान्यता है कि यहाँ प्रभु श्री राम ने अपने वनवास के 11 वर्ष व्यतीत किए थे।',
                'summary_en' => 'The prime holy hill of Chitrakoot where Lord Rama spent 11 years of exile.',
                'featured_image' => 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 1,
            ],
            [
                'name_hi' => 'रामघाट',
                'name_en' => 'Ramghat',
                'slug' => 'ramghat',
                'tagline_hi' => 'मंदाकिनी नदी का पावन तट एवं संध्या महाआरती',
                'tagline_en' => 'Holy Mandakini riverbank famous for evening lamps & Ganga-style Aarti',
                'summary_hi' => 'रामघाट मंदाकिनी नदी के तट पर स्थित है जहाँ गोस्वामी तुलसीदास जी को प्रभु श्री राम और लक्ष्मण जी के दर्शन हुए थे।',
                'summary_en' => 'The holy bank of River Mandakini where Goswami Tulsidas had divine darshan of Lord Rama.',
                'featured_image' => 'https://images.unsplash.com/photo-1599661046289-e31897846e41?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 2,
            ],
            [
                'name_hi' => 'भरत मिलाप',
                'name_en' => 'Bharat Milap',
                'slug' => 'bharat-milap',
                'tagline_hi' => 'श्री राम और भरत जी के भ्रातृ स्नेह का अमर प्रतीक',
                'tagline_en' => 'Eternal memorial of fraternal devotion between Lord Rama and Bharat',
                'summary_hi' => 'कामदगिरि परिक्रमा मार्ग पर स्थित वह स्थान जहाँ राजा भरत श्री राम को वापस अयोध्या ले जाने हेतु मिले थे।',
                'summary_en' => 'The historic meeting spot on Kamadgiri circuit where Bharat requested Rama to return to Ayodhya.',
                'featured_image' => 'https://images.unsplash.com/photo-1606298855672-3efb63017be8?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 3,
            ],
            [
                'name_hi' => 'हनुमान धारा',
                'name_en' => 'Hanuman Dhara',
                'slug' => 'hanuman-dhara',
                'tagline_hi' => 'पर्वत शिखर पर स्थित पवनपुत्र हनुमान जी का सिद्ध पीठ',
                'tagline_en' => 'Hilltop shrine with a continuous natural spring bathing Lord Hanuman',
                'summary_hi' => 'पहाड़ी की चोटी पर स्थित जहाँ हनुमान जी की विशाल प्रतिमा पर अविरल जलधारा गिरती है।',
                'summary_en' => 'Perched atop a scenic hill where natural mountain water cools the revered deity of Hanuman.',
                'featured_image' => 'https://images.unsplash.com/photo-1587135991058-8816b0287f1d?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 4,
            ],
        ];

        foreach ($destinations as $dest) {
            Destination::firstOrCreate(['slug' => $dest['slug']], array_merge($dest, [
                'description_hi' => $dest['summary_hi'] . ' यह चित्रकूट धाम का अत्यंत प्राचीन एवं पूजनीय स्थान है।',
                'description_en' => $dest['summary_en'] . ' A revered heritage destination in Chitrakoot Dham.',
                'is_published' => true,
            ]));
        }

        // 8. Upcoming Events
        Event::firstOrCreate(
            ['slug' => 'ram-navami-mahotsav-2027'],
            [
                'title_hi' => 'राम नवमी जन्मोत्सव एवं महाआरती',
                'title_en' => 'Ram Navami Celebration & Grand Aarti',
                'event_date' => '2027-04-12 09:00:00',
                'location_hi' => 'रामघाट एवं कामदगिरि पीठम्, चित्रकूट',
                'location_en' => 'Ramghat & Kamadgiri Peetham, Chitrakoot',
                'summary_hi' => 'प्रभु श्री राम के जन्मोत्सव पर भव्य शोभायात्रा, अन्नक्षेत्र एवं मंदाकिनी महाआरती।',
                'summary_en' => 'Grand procession, mass Annakshetra feast, and evening Mandakini celebration.',
                'is_published' => true,
            ]
        );

        Event::firstOrCreate(
            ['slug' => 'vriksharopan-abhiyan-2027'],
            [
                'title_hi' => 'चित्रकूट हरित पर्यावरण एवं वृक्षारोपण अभियान',
                'title_en' => 'Chitrakoot Green Plantation Drive',
                'event_date' => '2027-05-05 07:00:00',
                'location_hi' => 'कामदगिरि परिक्रमा क्षेत्र, चित्रकूट',
                'location_en' => 'Kamadgiri Parikrama Zone, Chitrakoot',
                'summary_hi' => 'कामदगिरि क्षेत्र में १०,००० फलदार एवं औषधीय पौधों के रोपण का पावन संकल्प।',
                'summary_en' => 'Environmental initiative to plant 10,000 medicinal and shade trees.',
                'is_published' => true,
            ]
        );

        Event::firstOrCreate(
            ['slug' => 'sanskrit-vidyalaya-varshikotsav-2027'],
            [
                'title_hi' => 'संस्कृत विद्यालय वार्षिकोत्सव एवं वेद संगोष्ठी',
                'title_en' => 'Sanskrit Vidyalaya Annual Day & Vedic Seminar',
                'event_date' => '2027-05-21 10:00:00',
                'location_hi' => 'श्री कामदगिरि पीठम् सभागार, चित्रकूट',
                'location_en' => 'Shri Kamadgiri Peetham Auditorium, Chitrakoot',
                'summary_hi' => 'छात्रों द्वारा वेद पाठ, संस्कृत नाटक एवं विद्वानों का सम्मान समारोह।',
                'summary_en' => 'Vedic recitations, Sanskrit drama performances, and scholar felicitations.',
                'is_published' => true,
            ]
        );

        // 9. Leadership Profiles (Placeholders as instructed in agent.md)
        LeadershipProfile::firstOrCreate(
            ['name_en' => 'Pujya Peethadhishwar Swami Ji'],
            [
                'name_hi' => 'पूज्य पीठाधीश्वर स्वामी जी',
                'title_hi' => 'पीठाधीश्वर, श्री कामदगिरि पीठम्',
                'title_en' => 'Peethadhishwar, Shri Kamadgiri Peetham',
                'role_category' => 'peethadhishwar',
                'bio_hi' => 'श्री कामदगिरि पीठम् के पूज्य पीठाधीश्वर जी के पावन मार्गदर्शन में ट्रस्ट द्वारा सनातन धर्म, शिक्षा एवं समाज सेवा के प्रकल्प संचालित हो रहे हैं।',
                'bio_en' => 'Guiding the spiritual vision, cultural preservation, and charitable missions of Shri Kamadgiri Peetham Sewa Trust.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // 10. Email Templates
        EmailTemplate::firstOrCreate(
            ['slug' => 'donation-receipt'],
            [
                'name' => 'Donation Confirmation & Receipt Email',
                'subject' => 'Thank you for your donation — Shri Kamadgiri Peetham Sewa Trust',
                'content_html' => '<h2>Pranam {{donor_name}},</h2><p>Thank you for your generous donation of <strong>₹{{amount}}</strong> towards <strong>{{cause_title}}</strong>.</p><p>Receipt Number: <strong>{{receipt_number}}</strong></p><p>You can download your official tax receipt from your account dashboard.</p>',
                'is_active' => true,
            ]
        );

        // 11. Blog Posts
        \App\Models\Post::firstOrCreate(
            ['slug' => 'chitrakoot-mahatmya-aur-kamadgiri-parikrama'],
            [
                'title_hi' => 'चित्रकूट माहात्म्य: कामदगिरि परिक्रमा का आध्यात्मिक एवं वैज्ञानिक महत्व',
                'title_en' => 'Chitrakoot Mahatmya: Spiritual & Scientific Significance of Kamadgiri Parikrama',
                'category' => 'Spiritual',
                'excerpt' => 'कामदगिरि पर्वत की परिक्रमा केवल एक धार्मिक अनुष्ठान नहीं, अपितु मन, शरीर और आत्मा के शुद्धिकरण का दिव्य अनुभव है।',
                'content_hi' => 'चित्रकूट धाम सनातन संस्कृति की परम पवित्र स्थली है। जब भगवान श्री राम ने अपने 14 वर्षों के वनवास काल में से 11 वर्ष से अधिक समय चित्रकूट की पावन धरा पर व्यतीत किया, तब यह भूमि ऋषियों, संतों और साधकों की तपोभूमि बन गई। कामदगिरि पर्वत स्वयं ब्रह्म स्वरूप माना जाता है। इसकी ५ किलोमीटर की परिक्रमा करने से समस्त मनोकामनाएँ पूर्ण होती हैं।',
                'content_en' => 'Chitrakoot is one of the most sacred lands of Vedic tradition. Lord Rama spent over 11 years of his exile here, making this land an epicenter of divine energy and meditation.',
                'featured_image' => 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=1400&q=90',
                'author_name' => 'स्वामी रामभद्राचार्य पीठम्',
                'likes_count' => 48,
                'views_count' => 312,
                'status' => 'published',
            ]
        );

        \App\Models\Post::firstOrCreate(
            ['slug' => 'gaushala-protection-and-sanatan-sanskriti'],
            [
                'title_hi' => 'गौ सेवा और भारतीय वैदिक संस्कृति: करुणा को कर्म में बदलने का मार्ग',
                'title_en' => 'Cow Protection & Vedic Tradition: Transforming Compassion into Action',
                'category' => 'Gaushala',
                'excerpt' => 'गौमाता की सेवा सनातन धर्म का मुख्य स्तंभ है। श्री कामदगिरि पीठम् गौशाला में नित्य सैंकड़ों निराश्रित गौवंश की देखभाल की जा रही है।',
                'content_hi' => 'भारतीय संस्कृति में गौमाता को समस्त देवी-देवताओं का निवास स्थान माना गया है। गौ सेवा केवल एक धार्मिक कर्तव्य नहीं, बल्कि पर्यावरण, कृषि और स्वास्थ्य की दृष्टि से भी समाज के लिए अत्यंत कल्याणकारी है। कामदगिरि पीठम् सेवा ट्रस्ट द्वारा संचालित गौशाला में असहाय, वृद्ध एवं निराश्रित गौवंश को चिकित्सा, हरा चारा तथा प्राकृतिक आश्रय प्रदान किया जाता है।',
                'content_en' => 'In Indian heritage, the cow is revered as a motherly force of nurture. Our Gaushala initiative provides daily sustenance and medical help to abandoned cows.',
                'featured_image' => 'https://images.unsplash.com/photo-1560493676-04071c5f467b?auto=format&fit=crop&w=1400&q=90',
                'author_name' => 'गौ सेवा समिति',
                'likes_count' => 62,
                'views_count' => 450,
                'status' => 'published',
            ]
        );

        \App\Models\Post::firstOrCreate(
            ['slug' => 'gurukul-education-and-sanskrit-revival'],
            [
                'title_hi' => 'गुरुकुल शिक्षा पद्धति और संस्कृत भाषा का पुनरुत्थान',
                'title_en' => 'Gurukul System & The Revival of Ancient Sanskrit Knowledge',
                'category' => 'Education',
                'excerpt' => 'आधुनिक युग में प्राचीन गुरुकुल शिक्षा प्रणाली का महत्व और बालकों के चरित्र निर्माण में संस्कृत विद्या की भूमिका।',
                'content_hi' => 'संस्कृत केवल एक भाषा नहीं, बल्कि भारत की ज्ञान-परंपरा का आधार स्तंभ है। श्री कामदगिरि पीठम् संस्कृत विद्यालय में बालकों को वेद, व्याकरण, वेदांत और नैतिक मूल्यों का अध्ययन कराया जाता है, जिससे वे भविष्य में विद्वान और चरित्रवान नागरिक बन सकें।',
                'content_en' => 'Sanskrit is the mother of languages and the vessel of ancient scientific and philosophical knowledge. Our Sanskrit Vidyalaya nurtures young minds with Vedic wisdom.',
                'featured_image' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&w=1400&q=90',
                'author_name' => 'विद्यापीठ आचार्य संकुल',
                'likes_count' => 39,
                'views_count' => 280,
                'status' => 'published',
            ]
        );

        // 12. Galleries & Items
        $gallery = \App\Models\Gallery::firstOrCreate(
            ['slug' => 'chitrakoot-dham-heritage-gallery'],
            [
                'title_hi' => 'चित्रकूट धाम पवित्र दर्शनीय स्थल',
                'title_en' => 'Chitrakoot Sacred Heritage Gallery',
                'description' => 'कामदगिरि, रामघाट, मंदाकिनी तट और ऐतिहासिक मंदिरों की मनमोहक झलकियाँ।',
                'cover_image' => 'https://images.unsplash.com/photo-1582510003544-4d00b7f74220?auto=format&fit=crop&w=1200&q=85',
                'type' => 'photo',
                'is_published' => true,
            ]
        );

        $photos = [
            ['title_hi' => 'कामदगिरि शिखर दर्शन', 'category' => 'Kamadgiri', 'image_url' => 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=1200&q=85', 'caption' => 'कामदगिरि पर्वत का दिव्य एवं शांत दृश्य'],
            ['title_hi' => 'मंदाकिनी आरती रामघाट', 'category' => 'Ramghat', 'image_url' => 'https://images.unsplash.com/photo-1599661046289-e31897846e41?auto=format&fit=crop&w=1200&q=85', 'caption' => 'संध्या समय मंदाकिनी नदी के तट पर महाआरती'],
            ['title_hi' => 'गौशाला प्रकल्प सेवा', 'category' => 'Gaushala', 'image_url' => 'https://images.unsplash.com/photo-1560493676-04071c5f467b?auto=format&fit=crop&w=1200&q=85', 'caption' => 'गौ संवर्धन एवं सेवा स्थल'],
            ['title_hi' => 'संस्कृत गुरुकुल छात्र', 'category' => 'Gurukul', 'image_url' => 'https://images.unsplash.com/photo-1542810634-71277d95dcbb?auto=format&fit=crop&w=1200&q=85', 'caption' => 'वैदिक संध्या एवं पाठरत बटुक'],
            ['title_hi' => 'हनुमान धारा जलप्रपात', 'category' => 'Hanuman Dhara', 'image_url' => 'https://images.unsplash.com/photo-1587135991058-8816b0287f1d?auto=format&fit=crop&w=1200&q=85', 'caption' => 'पर्वत से निकलती अविरल जलधारा'],
            ['title_hi' => 'प्राकृतिक सौंदर्य एवं परिक्रमा', 'category' => 'Parikrama', 'image_url' => 'https://images.unsplash.com/photo-1606298855672-3efb63017be8?auto=format&fit=crop&w=1200&q=85', 'caption' => 'परिक्रमा मार्ग का हरा-भरा वातावरण'],
        ];

        foreach ($photos as $idx => $photo) {
            \App\Models\GalleryItem::firstOrCreate([
                'gallery_id' => $gallery->id,
                'media_path' => $photo['image_url'],
            ], [
                'item_type' => 'image',
                'caption_hi' => $photo['title_hi'] . ' - ' . $photo['caption'],
                'caption_en' => $photo['category'],
                'sort_order' => $idx + 1,
            ]);
        }
    }
}
