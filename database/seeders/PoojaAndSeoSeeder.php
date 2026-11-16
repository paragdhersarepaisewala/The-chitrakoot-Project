<?php

namespace Database\Seeders;

use App\Models\PageSeoSetting;
use App\Models\PoojaService;
use Illuminate\Database\Seeder;

class PoojaAndSeoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Sacred Poojas
        $poojas = [
            [
                'title_hi' => 'श्री कामदगिरि संध्या महाआरती एवं परिक्रमा संकल्प',
                'title_en' => 'Shri Kamadgiri Sandhya Maha Aarti & Parikrama Sankalp',
                'slug' => 'kamadgiri-parikrama-sankalp',
                'summary_hi' => 'कामदगिरि पर्वत की पावन परिक्रमा का विशेष संकल्प, जिसमें पुरोहितों द्वारा आपके नाम व गोत्र से नित्य पूजन व दीपदान किया जाता है।',
                'description_hi' => 'चित्रकूट के हृदय भगवान कामतानाथ जी की पावन परिक्रमा से सभी मनोकामनाएं पूर्ण होती हैं। दूरस्थ भक्तजन जो स्वयं उपस्थित नहीं हो सकते, उनके लिए पीठाधीश्वर जी के पावन सान्निध्य में पुरोहितों द्वारा विधिवत नाम, गोत्र व कुलदेवता के स्मरण के साथ यह अनुष्ठान संपन्न किया जाता है।',
                'suggested_donation_amount' => 501.00,
                'duration_minutes' => 60,
                'timings_display' => 'प्रातः 06:30 AM व सायं 05:30 PM',
                'benefits_hi' => ['मनोकामना सिद्धि', 'पारिवारिक सुख व शांति', 'पापों का शमन व पुण्य संचय', 'प्रसाद डाक द्वारा प्रेषित'],
                'featured_image' => 'https://images.unsplash.com/photo-1609342122563-a43ac8917a3a?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 1,
            ],
            [
                'title_hi' => 'दिव्य मंदाकिनी दीपदान एवं महाआरती संकल्प',
                'title_en' => 'Divine Mandakini Deepdaan & Maha Aarti Sankalp',
                'slug' => 'mandakini-deepdaan-maha-aarti',
                'summary_hi' => 'रामघाट पर मां मंदाकिनी की संध्या महाआरती में 108 दीपों का पावन दीपदान व विशेष पितृ/शांति संकल्प।',
                'description_hi' => 'भगवान श्रीराम की पावन चरणरज से पवित्र मां मंदाकिनी के रामघाट पर संध्या आरती के समय आपके नाम से 108 दीप प्रज्वलित कर प्रवाहित किए जाते हैं।',
                'suggested_donation_amount' => 1100.00,
                'duration_minutes' => 45,
                'timings_display' => 'नित्य सायं 06:30 PM (रामघाट)',
                'benefits_hi' => ['पितृ शांति व कुल तर्पण', 'जीवन में प्रकाश व सकारात्मक ऊर्जा', 'आरती वीडियो लिंक व संकल्प प्रमाण'],
                'featured_image' => 'https://images.unsplash.com/photo-1548013146-72479768bada?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 2,
            ],
            [
                'title_hi' => 'श्री कामतानाथ जी एकादश रुद्राभिषेक महापूजन',
                'title_en' => 'Shri Kamtanath Ekadash Rudrabhishek Mahapooja',
                'slug' => 'kamtanath-ekadash-rudrabhishek',
                'summary_hi' => 'भगवान भोलेनाथ व कामतानाथ जी का पंचामृत, गन्ने का रस व दिव्य औषधियों द्वारा वेदोक्त रुद्राभिषेक।',
                'description_hi' => 'वेदपाठी ब्राह्मण बटुकों द्वारा रुद्री पाठ के 11 आवर्तनों के साथ संपन्न होने वाला परम कल्याणकारी अभिषेक अनुष्ठान।',
                'suggested_donation_amount' => 2100.00,
                'duration_minutes' => 90,
                'timings_display' => 'प्रातः 07:00 AM से 08:30 AM',
                'benefits_hi' => ['दीर्घायु व आरोग्यता', 'कालसर्प व ग्रह दोष निवारण', 'विशेष भस्म व रुद्राक्ष रक्षा सूत्र'],
                'featured_image' => 'https://images.unsplash.com/photo-1582510003544-4d00b7f74220?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 3,
            ],
            [
                'title_hi' => 'कामधेनु गौमाता नित्य सेवा व गौ-पूजन संकल्प',
                'title_en' => 'Kamdhenu Gau Mata Seva & Pooja Sankalp',
                'slug' => 'kamdhenu-gau-pooja-sankalp',
                'summary_hi' => 'ट्रस्ट की पावन गौशाला में देशी नंदी व गौमाताओं को हरा चारा, गुड़-चना अर्पण व सवत्स गौ-पूजन।',
                'description_hi' => '33 कोटि देवी-देवताओं की अधिष्ठात्री गौमाता की सपरिवार विशेष पूजा, आरती व गोग्रास सेवा।',
                'suggested_donation_amount' => 751.00,
                'duration_minutes' => 30,
                'timings_display' => 'प्रातः 08:00 AM व सायं 04:30 PM',
                'benefits_hi' => ['सर्वदेव कृपा व पुण्य प्राप्ति', 'संतान सुख व संकट निवारण', 'गौ सेवा प्रमाण पत्र'],
                'featured_image' => 'https://images.unsplash.com/photo-1570042225831-d98fa7577f1e?auto=format&fit=crop&w=800&q=80',
                'sort_order' => 4,
            ],
        ];

        foreach ($poojas as $p) {
            PoojaService::updateOrCreate(['slug' => $p['slug']], $p);
        }

        // 2. Default Page SEO Settings
        $pages = [
            [
                'page_key' => 'home',
                'page_title' => 'Home Page (मुख्य पृष्ठ)',
                'meta_title' => 'श्री कामदगिरि पीठम् सेवा ट्रस्ट | चित्रकूट धाम — Official Spiritual Portal',
                'meta_description' => 'चित्रकूट धाम स्थित श्री कामदगिरि पीठम् सेवा ट्रस्ट का आधिकारिक पोर्टल। कामदगिरि परिक्रमा, गौशाला सेवा, गुरुकुल शिक्षा, रामघाट आरती एवं ऑनलाइन पूजा व दान।',
                'meta_keywords' => 'Chitrakoot, Kamadgiri, Kamtanath, Ramghat, Seva Trust, Gaushala, Gurukul, Pooja Booking, Donation',
            ],
            [
                'page_key' => 'about',
                'page_title' => 'About Trust (ट्रस्ट परिचय)',
                'meta_title' => 'ट्रस्ट के बारे में | श्री कामदगिरि पीठम् सेवा ट्रस्ट, चित्रकूट धाम',
                'meta_description' => 'सनातन धर्म, शिक्षा, गौ सेवा, पर्यावरण संरक्षण और समाज कल्याण हेतु समर्पित श्री कामदगिरि पीठम् सेवा ट्रस्ट का इतिहास, विज़न एवं मिशन।',
                'meta_keywords' => 'About Kamadgiri Peetham, Chitrakoot Trust, Trustees, Sanatan Seva',
            ],
            [
                'page_key' => 'leadership',
                'page_title' => 'Leadership & Trustees (मार्गदर्शक मण्डल)',
                'meta_title' => 'Leadership & Trustees | श्री कामदगिरि पीठम् सेवा ट्रस्ट',
                'meta_description' => 'परम पूज्य पीठाधीश्वर जी महाराज का दिव्य मार्गदर्शन, न्यासी परिषद एवं वैधानिक मान्यता विवरण।',
                'meta_keywords' => 'Trustees, Peethadhishwar, Governance, 80G Tax Exemption, Chitrakoot Dham',
            ],
            [
                'page_key' => 'seva',
                'page_title' => 'Seva Projects (सेवा प्रकल्प)',
                'meta_title' => 'सेवा प्रकल्प | वैदिक गुरुकुल, गौशाला एवं अन्नक्षेत्र | चित्रकूट धाम',
                'meta_description' => 'श्री कामदगिरि पीठम् सेवा ट्रस्ट द्वारा संचालित पावन सेवा प्रकल्प: वैदिक संस्कृत विद्यालय, नंदी व गौ संवर्धन गौशाला, नित्य अन्नक्षेत्र।',
                'meta_keywords' => 'Seva Projects, Vedic Gurukul, Gaushala Protection, Anna Kshetra, Donation for Cow',
            ],
            [
                'page_key' => 'chitrakoot',
                'page_title' => 'Chitrakoot Guide (तीर्थ चित्रकूट दर्शन)',
                'meta_title' => 'चित्रकूट धाम तीर्थ दर्शन व दर्शनीय स्थल गाइड | कामदगिरि, रामघाट, गुप्त गोदावरी',
                'meta_description' => 'चित्रकूट धाम के पावन तीर्थ स्थलों की विस्तृत गाइड: कामदगिरि परिक्रमा, रामघाट, गुप्त गोदावरी, सती अनुसूया, भरत कूप एवं स्फटिक शिला का आध्यात्मिक महत्व व समय।',
                'meta_keywords' => 'Chitrakoot Guide, Kamadgiri Parikrama, Ramghat Aarti, Gupt Godavari, Sati Anusuya',
            ],
            [
                'page_key' => 'pooja',
                'page_title' => 'Pooja & Aarti Booking (ऑनलाइन पूजा व संकल्प)',
                'meta_title' => 'ऑनलाइन पूजा, रुद्राभिषेक व दीपदान संकल्प बुकिंग | श्री कामदगिरि पीठम्',
                'meta_description' => 'चित्रकूट कामतानाथ जी की पावन परिक्रमा संकल्प, एकादश रुद्राभिषेक, मंदाकिनी दीपदान एवं गौ-पूजन हेतु घर बैठे ऑनलाइन संकल्प बुक करें। प्रसाद डाक द्वारा प्राप्त करें।',
                'meta_keywords' => 'Online Pooja Booking, Kamadgiri Sankalp, Rudrabhishek Chitrakoot, Mandakini Deepdaan',
            ],
            [
                'page_key' => 'events',
                'page_title' => 'Events & Festivals (उत्सव व आयोजन)',
                'meta_title' => 'आगामी उत्सव व धार्मिक आयोजन | श्री कामदगिरि पीठम् सेवा ट्रस्ट',
                'meta_description' => 'चित्रकूट धाम में आयोजित होने वाले आगामी धार्मिक उत्सव, रामनवमी, गुरु पूर्णिमा, दीपोत्सव एवं विशेष सत्संग कथाओं की तिथियां व विवरण।',
                'meta_keywords' => 'Chitrakoot Festivals, Ramnavami, Guru Purnima, Deepotsav, Amavasya Mela',
            ],
            [
                'page_key' => 'blog',
                'page_title' => 'Articles & News (धार्मिक लेख व समाचार)',
                'meta_title' => 'सनातन संस्कृति व चित्रकूट महिमा लेख | श्री कामदगिरि पीठम् जर्नल',
                'meta_description' => 'चित्रकूट का आध्यात्मिक इतिहास, रामायण प्रसंग, संतों के पावन विचार एवं धर्म-दर्शन से जुड़े प्रामाणिक लेख पढ़ें।',
                'meta_keywords' => 'Spiritual Articles, Sanatan Blog, Chitrakoot History, Ramayana Katha',
            ],
            [
                'page_key' => 'gallery',
                'page_title' => 'Photo Gallery (चित्रकूट छायाचित्र दीर्घा)',
                'meta_title' => 'चित्रकूट धाम पावन छायाचित्र दीर्घा | कामदगिरि, मंदाकिनी व गौशाला दर्शन',
                'meta_description' => 'चित्रकूट धाम के मनोहारी दृश्यों, प्रातःकालीन आरती, गुरुकुल बटुकों एवं गौसेवा के उच्च-गुणवत्ता छायाचित्रों का दिव्य संग्रह।',
                'meta_keywords' => 'Chitrakoot Photos, Kamadgiri Temple Wallpapers, Ramghat Mandakini Gallery',
            ],
            [
                'page_key' => 'contact',
                'page_title' => 'Contact Us (संपर्क सूत्र)',
                'meta_title' => 'संपर्क करें | श्री कामदगिरि पीठम् सेवा ट्रस्ट, चित्रकूट धाम',
                'meta_description' => 'श्री कामदगिरि पीठम् सेवा ट्रस्ट से संपर्क करें: कार्यालय पता, हेल्पलाइन फोन नंबर, ईमेल एवं यात्रा मार्गदर्शन।',
                'meta_keywords' => 'Contact Kamadgiri Peetham, Phone Number, Chitrakoot Address, Enquiry',
            ],
            [
                'page_key' => 'donate',
                'page_title' => 'Donate & Support (सहयोग व दान)',
                'meta_title' => 'दान व सहयोग करें | 80G आयकर छूट रसीद सहित | श्री कामदगिरि पीठम्',
                'meta_description' => 'गौसेवा, गुरुकुल एवं अन्नक्षेत्र हेतु सुरक्षित ऑनलाइन दान करें। तुरंत 80G आयकर छूट रसीद प्राप्त करें।',
                'meta_keywords' => 'Donate for Cow, Sanskrit Gurukul Donation, 80G Tax Exemption, Chitrakoot Trust',
            ],
            [
                'page_key' => 'volunteer',
                'page_title' => 'Volunteer Registration (सेवादार / स्वयंसेवक पंजीकरण)',
                'meta_title' => 'सेवादार स्वयंसेवक बनें | श्री कामदगिरि पीठम् सेवा ट्रस्ट, चित्रकूट धाम',
                'meta_description' => 'सनातन धर्म, गौसेवा, गुरुकुल एवं तीर्थयात्री सेवा में अपना अमूल्य समय व कौशल समर्पित करने हेतु स्वयंसेवक पंजीकरण करें।',
                'meta_keywords' => 'Volunteer in Chitrakoot, Seva Sewak, Sanatan Volunteer, Gaushala Seva',
            ],
        ];

        foreach ($pages as $p) {
            PageSeoSetting::updateOrCreate(['page_key' => $p['page_key']], $p);
        }
    }
}
