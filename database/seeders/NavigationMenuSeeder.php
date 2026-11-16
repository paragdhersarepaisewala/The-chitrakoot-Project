<?php

namespace Database\Seeders;

use App\Models\NavigationMenuItem;
use Illuminate\Database\Seeder;

class NavigationMenuSeeder extends Seeder
{
    public function run(): void
    {
        if (NavigationMenuItem::count() > 0) {
            return;
        }

        $items = [
            [
                'label_en' => 'Home',
                'label_hi' => 'मुख्य पृष्ठ',
                'url' => '/',
                'sort_order' => 1,
            ],
            [
                'label_en' => 'About',
                'label_hi' => 'ट्रस्ट परिचय',
                'url' => '/about',
                'sort_order' => 2,
                'children' => [
                    ['label_en' => 'About the Trust', 'label_hi' => 'ट्रस्ट के विषय में', 'url' => '/about', 'sort_order' => 1],
                    ['label_en' => 'Leadership & Trustees', 'label_hi' => 'संरक्षक व न्यासी मंडल', 'url' => '/leadership', 'sort_order' => 2],
                ],
            ],
            [
                'label_en' => 'Seva & Gurukul',
                'label_hi' => 'सेवा व गुरुकुल',
                'url' => '/seva',
                'sort_order' => 3,
                'children' => [
                    ['label_en' => 'Vedic Sanskrit Gurukul', 'label_hi' => 'ऋषिकुल संस्कृत विद्यालय', 'url' => '/seva/sanskrit-gurukul', 'sort_order' => 1],
                    ['label_en' => 'Gaushala & Cow Care', 'label_hi' => 'कामदगिरि गौशाला', 'url' => '/seva/gaushala-protection', 'sort_order' => 2],
                    ['label_en' => 'Religious & Cultural Seva', 'label_hi' => 'धार्मिक एवं सांस्कृतिक सेवा', 'url' => '/seva/religious-events', 'sort_order' => 3],
                    ['label_en' => 'All Seva Projects', 'label_hi' => 'समस्त सेवा प्रकल्प', 'url' => '/seva', 'sort_order' => 4],
                ],
            ],
            [
                'label_en' => 'Chitrakoot',
                'label_hi' => 'चित्रकूट दर्शन',
                'url' => '/chitrakoot',
                'sort_order' => 4,
                'children' => [
                    ['label_en' => 'Sacred Places Guide', 'label_hi' => 'समस्त तीर्थ स्थल', 'url' => '/chitrakoot', 'sort_order' => 1],
                    ['label_en' => 'Kamadgiri Parikrama', 'label_hi' => 'कामदगिरि परिक्रमा', 'url' => '/chitrakoot/kamadgiri-parikrama', 'sort_order' => 2],
                    ['label_en' => 'Ramghat Mandakini', 'label_hi' => 'रामघाट एवं मंदाकिनी', 'url' => '/chitrakoot/ramghat', 'sort_order' => 3],
                ],
            ],
            [
                'label_en' => 'Pooja & Aarti',
                'label_hi' => 'पूजा व आरती',
                'url' => '/pooja/book',
                'sort_order' => 5,
            ],
            [
                'label_en' => 'Events',
                'label_hi' => 'उत्सव व कार्यक्रम',
                'url' => '/events',
                'sort_order' => 6,
            ],
            [
                'label_en' => 'Blog',
                'label_hi' => 'समाचार व आलेख',
                'url' => '/blog',
                'sort_order' => 7,
            ],
            [
                'label_en' => 'Gallery',
                'label_hi' => 'चित्र दीर्घा',
                'url' => '/gallery',
                'sort_order' => 8,
            ],
            [
                'label_en' => 'Contact',
                'label_hi' => 'संपर्क',
                'url' => '/contact',
                'sort_order' => 9,
            ],
        ];

        foreach ($items as $itemData) {
            $children = $itemData['children'] ?? [];
            unset($itemData['children']);

            $parent = NavigationMenuItem::create($itemData);

            foreach ($children as $childData) {
                $childData['parent_id'] = $parent->id;
                NavigationMenuItem::create($childData);
            }
        }
    }
}
