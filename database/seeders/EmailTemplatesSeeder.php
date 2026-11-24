<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use App\Models\OrganizationSetting;
use Illuminate\Database\Seeder;

class EmailTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $settings = OrganizationSetting::getSettings();
        $siteName = $settings->org_name ?: 'Shri Kamadgiri Peetham Sewa Trust';
        $siteUrl = url('/');
        $phone = $settings->phone ?: '+91 94150 00000';
        $email = $settings->email ?: 'info@kamadgiripeetham.org';

        $templates = [
            // ==========================================
            // 1. DONATIONS & RECEIPTS (Every Cause + General)
            // ==========================================
            [
                'name' => 'General Donation & 80G Contribution Receipt',
                'slug' => 'donation_receipt',
                'category' => 'donations',
                'description' => 'Dispatched automatically upon successful general donation payment. Includes official receipt number and 80G tax exemption statement.',
                'subject' => 'Official Contribution Receipt #{receipt_id} — '.$siteName,
                'placeholders_hint' => '{name}, {amount}, {cause}, {receipt_id}, {receipt_url}, {payment_id}, {date}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'आधिकारिक दान पावती · OFFICIAL CONTRIBUTION RECEIPT',
                    'आपके पावन सहयोग हेतु हृदय से धन्यवाद',
                    'सादर प्रणाम, {name} जी!',
                    'भगवान श्री कामतानाथ जी के पावन सानिध्य में <strong>{cause}</strong> प्रकल्प हेतु आपका श्रद्धापूर्वक समर्पित सहयोग प्राप्त हो चुका है। आपके इस पुण्य योगदान से चित्रकूट की सनातन परंपरा एवं सेवा कार्यों को संबल प्राप्त होता है। आपकी आधिकारिक दान पावती नीचे प्रस्तुत है:',
                    '<div style="background-color: #fffdfa; border: 1.5px solid #d4ad7d; border-radius: 10px; padding: 22px; margin: 24px 0; box-shadow: 0 4px 16px rgba(185,133,80,0.08);">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font-size: 13.5px; line-height: 1.8;">
                            <tr style="border-bottom: 1px dashed #e8dfd3;">
                                <td style="padding: 8px 0; color: #705847;">रसीद संख्या (Receipt No):</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 700; color: #8f1719; font-family: monospace; font-size: 14px;">{receipt_id}</td>
                            </tr>
                            <tr style="border-bottom: 1px dashed #e8dfd3;">
                                <td style="padding: 8px 0; color: #705847;">समर्पण तिथि (Date):</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #16100d;">{date}</td>
                            </tr>
                            <tr style="border-bottom: 1px dashed #e8dfd3;">
                                <td style="padding: 8px 0; color: #705847;">सेवा प्रकल्प (Cause / Seva):</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #16100d;">{cause}</td>
                            </tr>
                            <tr style="border-bottom: 1px dashed #e8dfd3;">
                                <td style="padding: 8px 0; color: #705847;">भुगतान संदर्भ (Payment ID):</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 600; color: #555; font-family: monospace;">{payment_id}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px 0 6px; color: #16100d; font-size: 15px; font-weight: 700;">सहयोग राशि (Amount):</td>
                                <td style="padding: 12px 0 6px; text-align: right; font-size: 19px; font-weight: 800; color: #8f1719;">₹{amount}</td>
                            </tr>
                        </table>
                        <div style="margin-top: 14px; padding-top: 12px; border-top: 1px solid #e8dfd3; font-size: 11.5px; color: #8c725d; line-height: 1.6;">
                            * यह श्री कामदगिरि पीठम् सेवा ट्रस्ट द्वारा जारी डिजिटल रूप से सत्यापित आधिकारिक पावती है। आयकर अधिनियम 1961 की धारा 80G के अंतर्गत सभी दान 50% कर-मुक्त हैं।
                        </div>
                    </div>',
                    'डिजिटल पावती डाउनलोड करें (Download Receipt)',
                    '{receipt_url}'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'गौ सेवा एवं गौ संरक्षण प्रकल्प दान पावती (Gaushala Dedicated Receipt)',
                'slug' => 'donation_cause_gaushala',
                'category' => 'donations',
                'description' => 'Dedicated receipt sent when devotee donates specifically to Gaushala Seva & Cow Protection.',
                'subject' => 'गौ सेवा समर्पण पावती #{receipt_id} — '.$siteName,
                'placeholders_hint' => '{name}, {amount}, {cause}, {receipt_id}, {receipt_url}, {payment_id}, {date}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'गौ सेवा समर्पण पावती · GAUSHALA SEVA RECEIPT',
                    'कामदगिरि गौशाला सेवा में आपके पावन योगदान हेतु आभार',
                    'जय गोमाता, {name} जी!',
                    'भगवान कामतानाथ जी के पावन धाम में संचालित <strong>गौ सेवा एवं गौ संरक्षण प्रकल्प</strong> हेतु आपका ₹{amount} का पुण्य सहयोग प्राप्त हुआ है। आपके इस दान से असहाय, वृद्ध एवं निराश्रित गोवंश के पोषण, हरा चारा, चिकित्सा एवं संरक्षण का पवित्र कार्य संपन्न हो रहा है।',
                    '<div style="background-color: #fffdfa; border: 1.5px solid #c9a063; border-radius: 10px; padding: 22px; margin: 24px 0;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font-size: 13.5px; line-height: 1.8;">
                            <tr style="border-bottom: 1px dashed #e8dfd3;">
                                <td style="padding: 8px 0; color: #705847;">रसीद संख्या (Receipt No):</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 700; color: #8f1719; font-family: monospace;">{receipt_id}</td>
                            </tr>
                            <tr style="border-bottom: 1px dashed #e8dfd3;">
                                <td style="padding: 8px 0; color: #705847;">समर्पित प्रकल्प (Dedicated Seva):</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 700; color: #2e7d32;">गौ सेवा, आहार एवं संवर्धन</td>
                            </tr>
                            <tr style="border-bottom: 1px dashed #e8dfd3;">
                                <td style="padding: 8px 0; color: #705847;">तिथि (Date):</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 600;">{date}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px 0 6px; color: #16100d; font-size: 15px; font-weight: 700;">समर्पित राशि (Amount):</td>
                                <td style="padding: 12px 0 6px; text-align: right; font-size: 19px; font-weight: 800; color: #8f1719;">₹{amount}</td>
                            </tr>
                        </table>
                    </div>
                    <div style="background-color: #f6fbf6; border: 1px solid #c2e2c5; border-radius: 8px; padding: 14px 18px; margin-bottom: 20px; font-size: 13px; color: #2e6930; line-height: 1.7;">
                        <em>"गावो विश्वस्य मातरः"</em> — गोमाता की सेवा से सभी मनोरथ सिद्ध होते हैं। कामदगिरि गौशाला परिवार आपकी इस सहृदयता हेतु आपके एवं आपके समस्त परिवार के सुखी जीवन की कामना करता है।
                    </div>',
                    'आधिकारिक 80G रसीद देखें',
                    '{receipt_url}'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'गुरुकुल एवं संस्कृत विद्यालय प्रकल्प दान पावती (Gurukul Dedicated Receipt)',
                'slug' => 'donation_cause_gurukul',
                'category' => 'donations',
                'description' => 'Dedicated receipt sent when devotee donates specifically to Sanskrit Gurukul & Vedic Vidyalaya education.',
                'subject' => 'विद्यादान एवं गुरुकुल सहयोग पावती #{receipt_id} — '.$siteName,
                'placeholders_hint' => '{name}, {amount}, {cause}, {receipt_id}, {receipt_url}, {payment_id}, {date}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'विद्यादान पावती · GURUKUL EDUCATION SUPPORT RECEIPT',
                    'वैदिक संस्कृति एवं गुरुकुल विद्यार्थियों के सहयोग हेतु धन्यवाद',
                    'सादर प्रणाम, {name} जी!',
                    'चित्रकूट धाम में संचालित <strong>गुरुकुल एवं संस्कृत विद्यालय</strong> के आर्ष विद्यार्थियों की शिक्षा, वेदाध्ययन, आवास एवं भोजन व्यवस्था हेतु आपका ₹{amount} का सहयोग संदर्भ संख्या <strong>#{receipt_id}</strong> के साथ प्राप्त हो चुका है। विद्यादान को सभी दानों में श्रेष्ठ माना गया है।',
                    '<div style="background-color: #fffdfa; border: 1.5px solid #c9a063; border-radius: 10px; padding: 22px; margin: 24px 0;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font-size: 13.5px; line-height: 1.8;">
                            <tr style="border-bottom: 1px dashed #e8dfd3;">
                                <td style="padding: 8px 0; color: #705847;">रसीद संख्या (Receipt No):</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 700; color: #8f1719; font-family: monospace;">{receipt_id}</td>
                            </tr>
                            <tr style="border-bottom: 1px dashed #e8dfd3;">
                                <td style="padding: 8px 0; color: #705847;">प्रकल्प (Cause):</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 700; color: #b45309;">गुरुकुल, संस्कृत विद्यालय एवं छात्र सेवा</td>
                            </tr>
                            <tr style="border-bottom: 1px dashed #e8dfd3;">
                                <td style="padding: 8px 0; color: #705847;">तिथि (Date):</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 600;">{date}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px 0 6px; color: #16100d; font-size: 15px; font-weight: 700;">सहयोग राशि (Amount):</td>
                                <td style="padding: 12px 0 6px; text-align: right; font-size: 19px; font-weight: 800; color: #8f1719;">₹{amount}</td>
                            </tr>
                        </table>
                    </div>',
                    '80G दान पावती डाउनलोड करें',
                    '{receipt_url}'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'धार्मिक आयोजन एवं अन्नक्षेत्र प्रकल्प दान पावती (Annakshetra Dedicated Receipt)',
                'slug' => 'donation_cause_annakshetra',
                'category' => 'donations',
                'description' => 'Dedicated receipt sent when devotee donates specifically to Annakshetra, Sadhu Seva & Bhandara.',
                'subject' => 'अन्नक्षेत्र एवं भंडारा सेवा पावती #{receipt_id} — '.$siteName,
                'placeholders_hint' => '{name}, {amount}, {cause}, {receipt_id}, {receipt_url}, {payment_id}, {date}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'अन्नक्षेत्र सेवा पावती · ANNAKSHETRA CONTRIBUTION RECEIPT',
                    'साधु-संत एवं तीर्थयात्री अन्न सेवा हेतु आपका पावन सहयोग प्राप्त हुआ',
                    'जय सियाराम, {name} जी!',
                    'चित्रकूट की पावन तपोभूमि पर नित्य संचालित <strong>धार्मिक आयोजन, अन्नक्षेत्र एवं साधु-संत भंडारा सेवा</strong> हेतु आपका ₹{amount} का पुण्य योगदान प्राप्त हो चुका है। कामदगिरि परिक्रमा करने वाले श्रद्धालुओं एवं तपस्वियों की अन्न सेवा में आपका यह योगदान अत्यंत वंदनीय है।',
                    '<div style="background-color: #fffdfa; border: 1.5px solid #c9a063; border-radius: 10px; padding: 22px; margin: 24px 0;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font-size: 13.5px; line-height: 1.8;">
                            <tr style="border-bottom: 1px dashed #e8dfd3;">
                                <td style="padding: 8px 0; color: #705847;">रसीद संख्या (Receipt No):</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 700; color: #8f1719; font-family: monospace;">{receipt_id}</td>
                            </tr>
                            <tr style="border-bottom: 1px dashed #e8dfd3;">
                                <td style="padding: 8px 0; color: #705847;">सेवा प्रकल्प (Cause):</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 700; color: #8f1719;">नित्य अन्नक्षेत्र एवं संत सेवा भंडारा</td>
                            </tr>
                            <tr style="border-bottom: 1px dashed #e8dfd3;">
                                <td style="padding: 8px 0; color: #705847;">समर्पण तिथि (Date):</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 600;">{date}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px 0 6px; color: #16100d; font-size: 15px; font-weight: 700;">सहयोग राशि (Amount):</td>
                                <td style="padding: 12px 0 6px; text-align: right; font-size: 19px; font-weight: 800; color: #8f1719;">₹{amount}</td>
                            </tr>
                        </table>
                    </div>',
                    'प्रमाणित दान पावती देखें',
                    '{receipt_url}'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'मासिक संरक्षक सदस्यता पावती (Monthly Sustaining Membership Receipt)',
                'slug' => 'monthly_donation_active',
                'category' => 'donations',
                'description' => 'Sent to recurring monthly sustaining patrons upon successful monthly contribution pledge.',
                'subject' => 'मासिक संरक्षक सहयोग ₹{amount} पावती — '.$siteName,
                'placeholders_hint' => '{name}, {amount}, {receipt_id}, {receipt_url}, {month}, {date}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'मासिक संरक्षक पावती · MONTHLY SUSTAINING PATRON RECEIPT',
                    'मासिक सेवा संकल्प में आपके निरंतर सहयोग हेतु हार्दिक आभार',
                    'सादर प्रणाम, {name} जी!',
                    'श्री कामदगिरि पीठम् सेवा ट्रस्ट के मासिक संरक्षक परिवार के रूप में आपका <strong>{month}</strong> माह का ₹{amount} का सेवा संकल्प सफलतापूर्वक प्राप्त हुआ है। आपकी यह निरंतर सहभागिता हमारे आश्रम प्रकल्पों की आधारशिला है।',
                    '<div style="background-color: #fffdfa; border: 1.5px solid #d4ad7d; border-radius: 10px; padding: 22px; margin: 24px 0;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse; font-size: 13.5px; line-height: 1.8;">
                            <tr style="border-bottom: 1px dashed #e8dfd3;">
                                <td style="padding: 8px 0; color: #705847;">रसीद संख्या (Receipt No):</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 700; color: #8f1719; font-family: monospace;">{receipt_id}</td>
                            </tr>
                            <tr style="border-bottom: 1px dashed #e8dfd3;">
                                <td style="padding: 8px 0; color: #705847;">सदस्यता माह (Patron Period):</td>
                                <td style="padding: 8px 0; text-align: right; font-weight: 700; color: #16100d;">{month}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px 0 6px; color: #16100d; font-size: 15px; font-weight: 700;">मासिक सहयोग (Monthly Support):</td>
                                <td style="padding: 12px 0 6px; text-align: right; font-size: 19px; font-weight: 800; color: #8f1719;">₹{amount}</td>
                            </tr>
                        </table>
                    </div>',
                    'मासिक पावती डाउनलोड करें',
                    '{receipt_url}'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'मासिक सेवा संकल्प स्मरण पत्र (Monthly Patron Due Reminder)',
                'slug' => 'monthly_due_reminder',
                'category' => 'donations',
                'description' => 'Sent to registered sustaining members as a gentle reminder when their monthly pledge is due.',
                'subject' => 'मासिक सेवा संकल्प स्मरण: {month} — '.$siteName,
                'placeholders_hint' => '{name}, {amount}, {month}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'मासिक संकल्प स्मरण · PATRON RENEWAL NOTICE',
                    'आपके मासिक सेवा संकल्प के नवीनीकरण का समय',
                    'जय सियाराम, {name} जी!',
                    'श्री कामदगिरि पीठम् के मासिक संरक्षक के रूप में सेवा कार्यों को निरंतर गतिशील रखने में आपका सहयोग सर्वोपरि है। <strong>{month}</strong> माह हेतु आपका सेवा संकल्प ₹{amount} देय है। आप नीचे दिए गए लिंक द्वारा सुगमता से अपना मासिक संकल्प नवीनीकृत कर सकते हैं।',
                    '<div style="background-color: #faf7f2; border: 1px solid #e2dbce; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>संरक्षक नाम:</strong> {name}<br>
                        <strong>संकल्प माह:</strong> {month}<br>
                        <strong>संकल्पित राशि:</strong> ₹{amount}<br>
                        <em>आपके सहयोग से गौशाला, गुरुकुल एवं अन्नक्षेत्र निर्बाध रूप से संचालित होते हैं।</em>
                    </div>',
                    'मासिक संकल्प नवीनीकृत करें (Renew Pledge)',
                    '{site_url}/donate'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'दान भुगतान असफल सहायता पत्र (Donation Payment Failed Alert)',
                'slug' => 'donation_failed',
                'category' => 'donations',
                'description' => 'Sent automatically to donor if online payment transaction was dropped or failed, with helpful recovery link.',
                'subject' => 'सहयोग भुगतान स्थिति सूचना — '.$siteName,
                'placeholders_hint' => '{name}, {amount}, {cause}, {date}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'सहयोग भुगतान स्थिति · PAYMENT STATUS NOTICE',
                    'आपके सहयोग प्रयास के संबंध में आवश्यक सूचना',
                    'सादर प्रणाम, {name} जी!',
                    'श्री कामदगिरि पीठम् के <strong>{cause}</strong> प्रकल्प हेतु आपके द्वारा ₹{amount} का सहयोग अर्पित करने का प्रयास किया गया था। बैंकिंग गेटवे अथवा नेटवर्क विलंब के कारण यह भुगतान पूर्ण नहीं हो सका।',
                    '<div style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8; color: #991b1b;">
                        यदि आपके बैंक खाते से राशि कट गई है, तो बैंकिंग नियमों के अनुसार 24 से 48 घंटे में वह स्वतः आपके खाते में वापस आ जाएगी अथवा स्वतः सत्यापित हो जाएगी। किसी भी सहायता हेतु हमारे सेवा केंद्र पर संपर्क करें।
                    </div>',
                    'पुनः प्रयास करें (Retry Donation)',
                    '{site_url}/donate'
                ),
                'is_active' => true,
                'is_system' => true,
            ],

            // ==========================================
            // 2. ACCOUNTS & SECURITY (Auth)
            // ==========================================
            [
                'name' => 'पंजीकरण एवं लॉगिन ओटीपी कोड (Login / Registration Email OTP)',
                'slug' => 'login_otp',
                'category' => 'auth',
                'description' => 'Sent during registration or passwordless login. Contains 6-digit cryptographic security code.',
                'subject' => 'ईमेल सत्यापन सुरक्षा कोड: {otp} — '.$siteName,
                'placeholders_hint' => '{name}, {email}, {otp}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'सुरक्षा सत्यापन कोड · ONE TIME PASSWORD',
                    'श्री कामदगिरि पीठम् भक्त पोर्टल सत्यापन कोड',
                    'सादर प्रणाम, {name} जी!',
                    'श्री कामदगिरि पीठम् सेवा ट्रस्ट के डिजिटल भक्त पोर्टल में आपके खाते के प्रमाणीकरण हेतु एक-बारीय सुरक्षा कोड (OTP) अनुरोध किया गया है।',
                    '<div style="text-align: center; margin: 28px 0;">
                        <div style="display: inline-block; background-color: #faf7f0; border: 2px dashed #b98550; border-radius: 12px; padding: 18px 36px;">
                            <div style="font-size: 11px; font-weight: 800; letter-spacing: 2px; color: #8c725d; text-transform: uppercase; margin-bottom: 6px;">आपका 6-अंकीय सत्यापन कोड</div>
                            <div style="font-size: 32px; font-weight: 900; letter-spacing: 8px; color: #8f1719; font-family: monospace;">{otp}</div>
                        </div>
                        <div style="font-size: 12.5px; color: #b45309; margin-top: 14px;">
                            ⏳ यह सुरक्षा कोड केवल <strong>15 मिनट</strong> तक सक्रिय रहेगा। कृपया इसे किसी के साथ साझा न करें।
                        </div>
                    </div>',
                    'भक्त पोर्टल खोलें',
                    '{site_url}/login'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'सफल पंजीकरण स्वागत पत्र (Welcome on Registration)',
                'slug' => 'welcome_registration',
                'category' => 'auth',
                'description' => 'Sent immediately after devotee successfully verifies email and activates portal account.',
                'subject' => 'कामदगिरि परिवार में आपका सहर्ष स्वागत है, {name}! — '.$siteName,
                'placeholders_hint' => '{name}, {email}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'स्वागत पत्र · WELCOME TO KAMADGIRI FAMILY',
                    'श्री कामदगिरि पीठम् परिवार में आपका सहर्ष स्वागत है',
                    'जय कामतानाथ, {name} जी!',
                    'चित्रकूट धाम की पावन धरा से श्री कामदगिरि पीठम् सेवा ट्रस्ट परिवार आपका हार्दिक अभिनंदन करता है। आपका भक्त पोर्टल खाता सफलतापूर्वक सक्रिय हो चुका है। अब आप ऑनलाइन दान, रसीद डाउनलोड, पूजा संकल्प, अतिथि विश्राम एवं आश्रम गतिविधियों से सीधे जुड़े रह सकते हैं।',
                    '<div style="background-color: #faf7f0; border: 1px solid #e8dfd3; border-radius: 8px; padding: 20px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>पंजीकृत नाम:</strong> {name}<br>
                        <strong>पंजीकृत ईमेल:</strong> {email}<br>
                        <strong>पोर्टल सुविधाएं:</strong> 80G दान रसीदें, मासिक संकल्प, पूजा बुकिंग, सेवादार दल सहभागिता
                    </div>',
                    'अपने भक्त डैशबोर्ड में जाएँ',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'पासवर्ड पुनर्प्राप्ति सुरक्षा कोड (Password Reset Security Code)',
                'slug' => 'password_reset_otp',
                'category' => 'auth',
                'description' => 'Sent when a user requests password reset via forgot password screen.',
                'subject' => 'पासवर्ड पुनर्प्राप्ति सुरक्षा कोड: {otp} — '.$siteName,
                'placeholders_hint' => '{name}, {email}, {otp}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'पासवर्ड पुनर्प्राप्ति · PASSWORD RESET CODE',
                    'पासवर्ड रीसेट करने हेतु सुरक्षा कोड',
                    'सादर प्रणाम, {name} जी!',
                    'आपके श्री कामदगिरि पीठम् खाते का पासवर्ड बदलने हेतु अनुरोध प्राप्त हुआ है। अपने नए पासवर्ड को सेट करने हेतु नीचे दिए गए 6-अंकीय कोड का उपयोग करें:',
                    '<div style="text-align: center; margin: 26px 0;">
                        <div style="display: inline-block; background-color: #faf7f0; border: 2px dashed #b98550; border-radius: 10px; padding: 16px 32px;">
                            <div style="font-size: 30px; font-weight: 900; letter-spacing: 7px; color: #8f1719; font-family: monospace;">{otp}</div>
                        </div>
                        <div style="font-size: 12px; color: #8c725d; margin-top: 12px;">
                            यदि आपने यह अनुरोध नहीं किया था, तो कृपया इस ईमेल को अनदेखा करें। आपका खाता पूरी तरह सुरक्षित है।
                        </div>
                    </div>',
                    'पासवर्ड रीसेट पेज पर जाएँ',
                    '{site_url}/reset-password'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'पासवर्ड परिवर्तन सुरक्षा अलर्ट (Password Changed Security Alert)',
                'slug' => 'password_changed',
                'category' => 'auth',
                'description' => 'Sent automatically to user whenever their account password is successfully changed or reset.',
                'subject' => 'सुरक्षा अलर्ट: आपका पासवर्ड सफलतापूर्वक परिवर्तित हुआ — '.$siteName,
                'placeholders_hint' => '{name}, {email}, {date}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'सुरक्षा अलर्ट · SECURITY ALERT',
                    'आपके खाते का पासवर्ड सफलतापूर्वक बदला गया है',
                    'सादर प्रणाम, {name} जी!',
                    'यह सुरक्षा सूचना है कि आपके श्री कामदगिरि पीठम् भक्त पोर्टल खाते का पासवर्ड <strong>{date}</strong> को सफलतापूर्वक परिवर्तित कर दिया गया है।',
                    '<div style="background-color: #edf7ee; border: 1px solid #a3d9a5; border-radius: 8px; padding: 16px; margin: 20px 0; font-size: 13.5px; color: #1e4620; line-height: 1.7;">
                        यदि यह परिवर्तन आपने ही किया है, तो किसी अतिरिक्त कार्यवाही की आवश्यकता नहीं है। यदि आपने यह परिवर्तन नहीं किया था, तो कृपया तुरंत हमारे सेवा केंद्र से संपर्क करें।
                    </div>',
                    'डैशबोर्ड में लॉगिन करें',
                    '{site_url}/login'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'प्रबंधन सूचना: नया भक्त पंजीकरण (Admin Alert: New Registration)',
                'slug' => 'admin_new_user',
                'category' => 'auth',
                'description' => 'Sent to trust admin email when a new devotee registers on the portal.',
                'subject' => '[Admin Alert] New Member Registered: {name} ({email})',
                'placeholders_hint' => '{name}, {email}, {phone}, {date}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'प्रबंधन सूचना · ADMIN NEW USER ALERT',
                    'नया भक्त पंजीकरण प्राप्त हुआ',
                    'आदरणीय व्यवस्थापक जी,',
                    'श्री कामदगिरि पीठम् पोर्टल पर नया भक्त खाता पंजीकृत हुआ है। विवरण नीचे दिया गया है:',
                    '<div style="background-color: #fff8e1; border: 1px solid #ffe082; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>भक्त नाम:</strong> {name}<br>
                        <strong>ईमेल:</strong> {email}<br>
                        <strong>पंजीकरण तिथि:</strong> {date}
                    </div>',
                    'प्रबंधन डैशबोर्ड में देखें',
                    '{site_url}/admin/users'
                ),
                'is_active' => true,
                'is_system' => true,
            ],

            // ==========================================
            // 3. INQUIRIES & CONTACT
            // ==========================================
            [
                'name' => 'संपर्क पूछताछ पावती (Contact Form Auto-Acknowledgement)',
                'slug' => 'contact_inquiry_received',
                'category' => 'inquiries',
                'description' => 'Sent automatically to user when they submit the Contact Us form on the website.',
                'subject' => 'आपके पावन संदेश हेतु धन्यवाद — '.$siteName,
                'placeholders_hint' => '{name}, {email}, {subject}, {message}, {date}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'संपर्क पावती · CONTACT INQUIRY RECEIVED',
                    'आपका संदेश हमें प्राप्त हो चुका है',
                    'जय कामतानाथ, {name} जी!',
                    'श्री कामदगिरि पीठम् सेवा ट्रस्ट से संपर्क करने हेतु आपका सहृदय धन्यवाद। आपका संदेश हमारे कार्यालय में दर्ज कर लिया गया है। हमारे प्रतिनिधि शीघ्र ही आपके विषय की समीक्षा कर आपसे संपर्क करेंगे।',
                    '<div style="background-color: #faf7f0; border: 1px solid #e8dfd3; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>विषय (Subject):</strong> {subject}<br>
                        <strong>प्राप्ति तिथि:</strong> {date}<br>
                        <strong>आपका संदेश:</strong><br>
                        <span style="color: #665; font-style: italic;">"{message}"</span>
                    </div>',
                    'ट्रस्ट वेबसाइट पर जाएँ',
                    '{site_url}'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'संपर्क पूछताछ प्रत्युत्तर (Contact Inquiry Admin Reply)',
                'slug' => 'contact_inquiry_reply',
                'category' => 'inquiries',
                'description' => 'Sent to user when administrator writes an official response to their contact inquiry.',
                'subject' => 'आपके संदेश के संबंध में प्रत्युत्तर: {subject} — '.$siteName,
                'placeholders_hint' => '{name}, {email}, {subject}, {reply_message}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'आधिकारिक प्रत्युत्तर · OFFICIAL RESPONSE',
                    'श्री कामदगिरि पीठम् सेवा ट्रस्ट से आधिकारिक उत्तर',
                    'सादर प्रणाम, {name} जी!',
                    'आपके द्वारा प्रेषित संपर्क संदेश (विषय: <strong>{subject}</strong>) के संबंध में ट्रस्ट कार्यालय द्वारा यह आधिकारिक प्रत्युत्तर प्रेषित किया जा रहा है:',
                    '<div style="background-color: #fffdfa; border: 1.5px solid #d4ad7d; border-radius: 10px; padding: 22px; margin: 24px 0; font-size: 14px; line-height: 1.8; color: #2b231d;">
                        {reply_message}
                    </div>',
                    'वेबसाइट पर संपर्क विवरण देखें',
                    '{site_url}/contact'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'प्रबंधन सूचना: नई संपर्क पूछताछ (Admin Alert: New Contact Inquiry)',
                'slug' => 'contact_inquiry_admin_alert',
                'category' => 'inquiries',
                'description' => 'Sent to trust admin email whenever a new inquiry is submitted on the contact page.',
                'subject' => '[ADMIN ALERT] नई वेबसाइट संपर्क पूछताछ: {name} — {subject}',
                'placeholders_hint' => '{name}, {email}, {phone}, {subject}, {message}, {date}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'प्रबंधन सूचना · ADMIN CONTACT INQUIRY ALERT',
                    'नई संपर्क पूछताछ प्राप्त हुई',
                    'आदरणीय व्यवस्थापक जी,',
                    'वेबसाइट संपर्क पृष्ठ के माध्यम से नई पूछताछ प्राप्त हुई है:',
                    '<div style="background-color: #fff8e1; border: 1px solid #ffe082; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>नाम:</strong> {name}<br>
                        <strong>ईमेल:</strong> {email} &bull; <strong>फ़ोन:</strong> {phone}<br>
                        <strong>विषय:</strong> {subject}<br>
                        <strong>संदेश:</strong><br>{message}
                    </div>',
                    'एडमिन पैनल में उत्तर दें',
                    '{site_url}/admin/contact-inquiries'
                ),
                'is_active' => true,
                'is_system' => true,
            ],

            // ==========================================
            // 4. VOLUNTEERS (Seva Dal)
            // ==========================================
            [
                'name' => 'सेवादार पंजीकरण आवेदन पावती (Volunteer Application Received)',
                'slug' => 'volunteer_received',
                'category' => 'volunteers',
                'description' => 'Sent to volunteer applicant immediately upon submission of volunteer registration form.',
                'subject' => 'सेवादार पंजीकरण आवेदन प्राप्त हुआ: संदर्भ #{applicant_id} — '.$siteName,
                'placeholders_hint' => '{name}, {email}, {phone}, {city}, {seva_areas}, {applicant_id}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'सेवा संकल्प पावती · VOLUNTEER ENROLMENT RECEIVED',
                    'सेवादार पंजीकरण आवेदन सफलतापूर्वक प्राप्त हुआ',
                    'सादर प्रणाम, {name} जी!',
                    'चित्रकूट धाम सेवा प्रकल्पों में आपकी निःस्वार्थ सेवा भावना हेतु आपका आवेदन संदर्भ संख्या <strong>#{applicant_id}</strong> के साथ दर्ज कर लिया गया है। ट्रस्ट कार्यालय द्वारा विवरण की समीक्षा कर आपसे संपर्क किया जाएगा।',
                    '<div style="background-color: #faf7f0; border: 1px solid #b98550; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>संदर्भ संख्या:</strong> #{applicant_id}<br>
                        <strong>चयनित सेवा क्षेत्र:</strong> {seva_areas}<br>
                        <strong>स्थिति:</strong> समीक्षाधीन (Under Review)
                    </div>',
                    'भक्त पोर्टल खोलें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'सेवादार आवेदन स्वीकृति एवं दल प्रवेश (Volunteer Application Approved)',
                'slug' => 'volunteer_approved',
                'category' => 'volunteers',
                'description' => 'Sent when administrator approves volunteer application and enrolls them into Seva Dal.',
                'subject' => 'बधाई! आपका सेवादार आवेदन स्वीकृत हुआ — '.$siteName.' Sewa Dal',
                'placeholders_hint' => '{name}, {email}, {seva_areas}, {applicant_id}, {admin_notes}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'सेवादार अनुमोदन पत्र · SEVA DAL ENROLMENT APPROVED',
                    'श्री कामदगिरि सेवादार परिवार में आपका सहर्ष स्वागत है',
                    'जय सियाराम, {name} जी!',
                    'अत्यंत हर्ष के साथ सूचित किया जाता है कि श्री कामदगिरि पीठम् सेवा ट्रस्ट द्वारा आपका सेवादार पंजीकरण आवेदन आधिकारिक रूप से स्वीकृत (Approved) कर लिया गया है। आप अब हमारे सेवा दल के सम्मानित सदस्य हैं।',
                    '<div style="background-color: #edf7ee; border: 1px solid #a3d9a5; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong style="color: #2e7d32;">स्थिति: आधिकारिक रूप से स्वीकृत (APPROVED)</strong><br>
                        <strong>सेवादार नाम:</strong> {name}<br>
                        <strong>सेवादार क्रमांक:</strong> #{applicant_id}<br>
                        <strong>सेवा क्षेत्र:</strong> {seva_areas}<br>
                        <strong>व्यवस्थापक संदेश:</strong> {admin_notes}
                    </div>',
                    'सेवादार डैशबोर्ड देखें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'सेवादार आवेदन सूचना / अस्वीकृति (Volunteer Application Status Notice)',
                'slug' => 'volunteer_rejected',
                'category' => 'volunteers',
                'description' => 'Sent if volunteer application cannot be accepted in the current term.',
                'subject' => 'सेवादार आवेदन के संबंध में आवश्यक सूचना — '.$siteName,
                'placeholders_hint' => '{name}, {email}, {applicant_id}, {admin_notes}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'सेवादार आवेदन सूचना · APPLICATION STATUS',
                    'आपके सेवादार आवेदन के संबंध में सूचना',
                    'सादर प्रणाम, {name} जी!',
                    'श्री कामदगिरि पीठम् सेवा ट्रस्ट के सेवा प्रकल्पों में आपकी सहभागिता की भावना का हम सम्मान करते हैं। वर्तमान सत्र में सेवादार स्लॉट्स की सीमित उपलब्धता के कारण इस समय आपका आवेदन स्वीकृत नहीं किया जा सका है।',
                    '<div style="background-color: #faf7f0; border: 1px solid #e8dfd3; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>आवेदक नाम:</strong> {name}<br>
                        <strong>संदेश:</strong> {admin_notes}
                    </div>',
                    'ट्रस्ट के अन्य प्रकल्प देखें',
                    '{site_url}/seva'
                ),
                'is_active' => true,
                'is_system' => true,
            ],

            // ==========================================
            // 5. POOJA & RITUAL SERVICES
            // ==========================================
            [
                'name' => 'पूजा व अनुष्ठान संकल्प अनुरोध पावती (Pooja Booking Received)',
                'slug' => 'pooja_booking_received',
                'category' => 'services',
                'description' => 'Sent to devotee when they submit a ritual / pooja booking request.',
                'subject' => 'पूजा व अनुष्ठान संकल्प अनुरोध प्राप्त हुआ: #{booking_number} — '.$siteName,
                'placeholders_hint' => '{name}, {email}, {service_name}, {booking_number}, {booking_date}, {time_slot}, {amount}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'पूजा संकल्प पावती · POOJA BOOKING RECEIVED',
                    'पूजा व अनुष्ठान संकल्प अनुरोध दर्ज हुआ',
                    'जय कामतानाथ, {name} जी!',
                    'चित्रकूट धाम के पावन सानिध्य में <strong>{service_name}</strong> हेतु आपका संकल्प अनुरोध संदर्भ संख्या <strong>#{booking_number}</strong> के साथ प्राप्त हो चुका है।',
                    '<div style="background-color: #faf7f0; border: 1px solid #e8dfd3; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>बुकिंग संख्या:</strong> #{booking_number}<br>
                        <strong>अनुष्ठान:</strong> {service_name}<br>
                        <strong>तिथि व समय:</strong> {booking_date} ({time_slot})<br>
                        <strong>सहयोग राशि:</strong> ₹{amount}
                    </div>',
                    'संकल्प विवरण देखें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'पूजा दक्षिणा कोटेशन व ऑनलाइन भुगतान लिंक (Pooja Quotation Dispatched)',
                'slug' => 'pooja_quotation',
                'category' => 'services',
                'description' => 'Sent when admin prepares a tailored quotation with a secure payment link for custom ritual.',
                'subject' => 'पूजा व अनुष्ठान संकल्प दक्षिणा कोटेशन: #{booking_number} — '.$siteName,
                'placeholders_hint' => '{name}, {email}, {service_name}, {booking_number}, {quotation_amount}, {quotation_notes}, {booking_date}, {time_slot}, {expires_at}, {payment_url}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'पूजा दक्षिणा कोटेशन · POOJA DAKSHINA QUOTATION',
                    'आपके अनुष्ठान संकल्प हेतु आधिकारिक दक्षिणा पत्र',
                    'जय कामतानाथ, {name} जी!',
                    'श्री कामदगिरि पीठम् सेवा ट्रस्ट द्वारा आपके <strong>{service_name}</strong> के संकल्प अनुरोध (संदर्भ #{booking_number}) की समीक्षा उपरांत आचार्य जी से परामर्श कर यह दक्षिणा विवरण प्रेषित किया गया है।',
                    '<div style="background-color: #faf7f0; border: 1.5px solid #d4ad7d; border-radius: 10px; padding: 20px; margin: 20px 0; font-size: 14px; line-height: 1.9;">
                        <strong style="font-size: 16px; color: #8f1719; display: block; margin-bottom: 8px;">स्वीकृत दक्षिणा राशि: ₹{quotation_amount}</strong>
                        <strong>अनुष्ठान:</strong> {service_name}<br>
                        <strong>प्रस्तावित तिथि:</strong> {booking_date} ({time_slot})<br>
                        <strong>व्यवस्था विवरण:</strong> {quotation_notes}<br>
                        <span style="display: block; margin-top: 8px; font-size: 12.5px; color: #b98550;">⏳ यह पावन भुगतान लिंक केवल <strong>{expires_at}</strong> तक सक्रिय रहेगा। कृपया समय समाप्त होने से पूर्व दक्षिणा अर्पित कर अपना पूजा स्लॉट सुनिश्चित करें।</span>
                    </div>',
                    'ऑनलाइन दक्षिणा अर्पित करें (Pay Dakshina Online)',
                    '{payment_url}'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'पूजा व अनुष्ठान संकल्प पुष्टि पत्र (Pooja Booking Confirmed)',
                'slug' => 'pooja_booking_confirmed',
                'category' => 'services',
                'description' => 'Sent after devotee pays dakshina and ritual is scheduled with Vedic priests.',
                'subject' => 'पूजा व अनुष्ठान संकल्प पुष्ट हुआ: #{booking_number} — '.$siteName,
                'placeholders_hint' => '{name}, {email}, {service_name}, {booking_number}, {booking_date}, {time_slot}, {gotra}, {nakshatra}, {sankalp_purpose}, {admin_notes}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'अनुष्ठान पुष्टि पत्र · POOJA SANKALP CONFIRMED',
                    'आपका वैदिक पूजा व अनुष्ठान संकल्प सुनिश्चित हुआ',
                    'परम आदरणीय {name} जी!',
                    'चित्रकूट धाम में कामदगिरि महाराज के चरणों में आपके द्वारा समर्पित <strong>{service_name}</strong> का संकल्प पुष्ट कर दिया गया है।',
                    '<div style="background-color: #edf7ee; border: 1px solid #a3d9a5; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong style="color: #2e7d32;">स्थिति: अनुष्ठान पुष्ट (CONFIRMED)</strong><br>
                        <strong>बुकिंग संख्या:</strong> #{booking_number}<br>
                        <strong>अनुष्ठान:</strong> {service_name}<br>
                        <strong>तिथि व समय:</strong> {booking_date} ({time_slot})<br>
                        <strong>गोत्र / नक्षत्र:</strong> {gotra} / {nakshatra}<br>
                        <strong>संकल्प उद्देश्य:</strong> {sankalp_purpose}<br>
                        <strong>पुरोहित संदेश:</strong> {admin_notes}
                    </div>',
                    'भक्त डैशबोर्ड में देखें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'पूजा अनुष्ठान संपन्न व प्रसाद आशीष पत्र (Pooja Completed Blessings)',
                'slug' => 'pooja_booking_completed',
                'category' => 'services',
                'description' => 'Sent when ritual is successfully solemnized and prasad is consecrated or dispatched.',
                'subject' => 'अनुष्ठान संपन्न: कामतानाथ महाराज की कृपा व प्रसाद — #{booking_number}',
                'placeholders_hint' => '{name}, {email}, {service_name}, {booking_number}, {booking_date}, {admin_notes}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'अनुष्ठान संपन्न पत्र · SANKALP COMPLETED BLESSINGS',
                    'आपका वैदिक अनुष्ठान विधिपूर्वक संपन्न हुआ',
                    'जय कामतानाथ, {name} जी!',
                    'भगवान कामतानाथ जी एवं माता मंदाकिनी के पावन चरणों में आपका <strong>{service_name}</strong> का संकल्प (संदर्भ #{booking_number}) वैदिक आचार्यों द्वारा विधिपूर्वक मंत्रोच्चार सहित संपन्न करा दिया गया है।',
                    '<div style="background-color: #f7f3ea; border: 1px solid #d4ad7d; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>अनुष्ठान:</strong> {service_name}<br>
                        <strong>संपन्न तिथि:</strong> {booking_date}<br>
                        <strong>प्रसाद व्यवस्था:</strong> {admin_notes}<br>
                        भगवान श्री कामतानाथ जी आपके एवं आपके परिवार के समस्त मनोरथ पूर्ण करें।
                    </div>',
                    'विवरण देखें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
                'is_system' => true,
            ],

            // ==========================================
            // 6. ATITHI VISHRAM (Guest House)
            // ==========================================
            [
                'name' => 'अतिथि विश्राम गृह पूछताछ पावती (Atithi Vishram Enquiry Received)',
                'slug' => 'atithi_vishram_received',
                'category' => 'services',
                'description' => 'Sent to devotee when they request guest house room accommodation.',
                'subject' => 'अतिथि विश्राम गृह पूछताछ प्राप्त हुई: #{enquiry_number} — '.$siteName,
                'placeholders_hint' => '{name}, {email}, {enquiry_number}, {check_in_date}, {check_out_date}, {devotees}, {rooms}, {room_preference}, {purpose}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'अतिथि विश्राम पावती · GUEST HOUSE ENQUIRY RECEIVED',
                    'मंदिर अतिथि विश्राम पूछताछ सफलतापूर्वक दर्ज हुई',
                    'जय सियाराम, {name} जी!',
                    'चित्रकूट धाम तीर्थ यात्रा के दौरान कामदगिरि पीठम् अतिथि विश्राम गृह में प्रवास हेतु आपकी पूछताछ संदर्भ संख्या <strong>#{enquiry_number}</strong> के साथ प्राप्त हो गई है।',
                    '<div style="background-color: #faf7f0; border: 1px solid #d4ad7d; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>पूछताछ संदर्भ:</strong> #{enquiry_number}<br>
                        <strong>आगमन तिथि (Check-in):</strong> {check_in_date}<br>
                        <strong>प्रस्थान तिथि (Check-out):</strong> {check_out_date}<br>
                        <strong>भक्त संख्या:</strong> {devotees} भक्त &bull; <strong>कक्ष:</strong> {rooms}<br>
                        <strong>कक्ष प्राथमिकता:</strong> {room_preference}<br>
                        <strong>यात्रा उद्देश्य:</strong> {purpose}<br>
                        <span style="display: block; margin-top: 8px; color: #b45309; font-size: 12px;">* ध्यान दें: यह विश्राम कक्ष की पूछताछ है। उपलब्धता एवं आश्रम नियमों के अनुसार कार्यालय द्वारा शीघ्र संपर्क कर व्यवस्था सुनिश्चित की जाएगी।</span>
                    </div>',
                    'विश्राम स्थिति देखें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'अतिथि विश्राम पूछताछ अद्यतन सूचना (Atithi Vishram Status Update)',
                'slug' => 'atithi_vishram_status_update',
                'category' => 'services',
                'description' => 'Sent to devotee when room is allotted, confirmed, or updated by trust administration.',
                'subject' => 'अतिथि विश्राम पूछताछ अद्यतन: #{enquiry_number} — '.$siteName,
                'placeholders_hint' => '{name}, {email}, {enquiry_number}, {check_in_date}, {check_out_date}, {status}, {admin_notes}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'विश्राम स्थिति अद्यतन · GUEST STAY STATUS UPDATE',
                    'अतिथि विश्राम पूछताछ की अद्यतन स्थिति',
                    'सादर प्रणाम, {name} जी!',
                    'कामदगिरि पीठम् सेवा ट्रस्ट द्वारा आपकी अतिथि विश्राम गृह पूछताछ संदर्भ <strong>#{enquiry_number}</strong> की समीक्षा कर ली गई है।',
                    '<div style="background-color: #edf7ee; border: 1px solid #a3d9a5; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong style="color: #2e7d32;">वर्तमान स्थिति: {status}</strong><br>
                        <strong>आगमन तिथि:</strong> {check_in_date} &mdash; <strong>प्रस्थान:</strong> {check_out_date}<br>
                        <strong>व्यवस्थापक निर्देश:</strong> {admin_notes}
                    </div>',
                    'डैशबोर्ड पर विवरण देखें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'प्रबंधन सूचना: नई अतिथि विश्राम पूछताछ (Admin Alert: Atithi Vishram)',
                'slug' => 'atithi_vishram_admin_alert',
                'category' => 'services',
                'description' => 'Sent to trust guest office when new accommodation request is lodged.',
                'subject' => '[ADMIN ALERT] नई अतिथि विश्राम पूछताछ: #{enquiry_number} — {name}',
                'placeholders_hint' => '{name}, {email}, {phone}, {enquiry_number}, {check_in_date}, {check_out_date}, {devotees}, {rooms}, {room_preference}, {special_requests}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'प्रबंधन सूचना · ADMIN ATITHI VISHRAM ALERT',
                    'नई अतिथि विश्राम पूछताछ प्राप्त हुई',
                    'आदरणीय व्यवस्थापक जी,',
                    'मंदिर पोर्टल पर भक्त द्वारा तीर्थ विश्राम गृह में ठहरने हेतु नई पूछताछ दर्ज की गई है:',
                    '<div style="background-color: #fff8e1; border: 1px solid #ffe082; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>संदर्भ संख्या:</strong> #{enquiry_number}<br>
                        <strong>भक्त नाम:</strong> {name}<br>
                        <strong>संपर्क:</strong> {phone} &bull; {email}<br>
                        <strong>आगमन / प्रस्थान:</strong> {check_in_date} से {check_out_date}<br>
                        <strong>यात्री संख्या:</strong> {devotees} &bull; <strong>कक्ष:</strong> {rooms}<br>
                        <strong>कक्ष प्रकार:</strong> {room_preference}<br>
                        <strong>विशेष अनुरोध:</strong> {special_requests}
                    </div>',
                    'एडमिन पैनल में समीक्षा करें',
                    '{site_url}/admin/atithi-vishram'
                ),
                'is_active' => true,
                'is_system' => true,
            ],

            // ==========================================
            // 7. MANDIR PARISAR & EVENT BOOKINGS
            // ==========================================
            [
                'name' => 'मंदिर परिसर व आयोजन आरक्षण पूछताछ पावती (Temple Booking Received)',
                'slug' => 'temple_booking_received',
                'category' => 'services',
                'description' => 'Sent to organizer upon submitting temple premises booking enquiry.',
                'subject' => 'मंदिर परिसर व आयोजन आरक्षण पूछताछ प्राप्त हुई: #{enquiry_number} — '.$siteName,
                'placeholders_hint' => '{name}, {email}, {enquiry_number}, {event_type}, {proposed_date}, {gathering}, {duration}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'परिसर आरक्षण पावती · TEMPLE PREMISES BOOKING ENQUIRY',
                    'मंदिर परिसर व धार्मिक आयोजन पूछताछ दर्ज हुई',
                    'जय सियाराम, {name} जी!',
                    'श्री कामदगिरि पीठम् सेवा ट्रस्ट के पावन परिसर में <strong>{event_type}</strong> के आयोजन हेतु आपकी पूछताछ संदर्भ संख्या <strong>#{enquiry_number}</strong> के साथ प्राप्त हो गई है।',
                    '<div style="background-color: #faf7f0; border: 1px solid #d4ad7d; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>पूछताछ संदर्भ:</strong> #{enquiry_number}<br>
                        <strong>आयोजन प्रकार:</strong> {event_type}<br>
                        <strong>प्रस्तावित तिथि:</strong> {proposed_date}<br>
                        <strong>अनुमानित उपस्थिति:</strong> लगभग {gathering} श्रद्धालु &bull; <strong>अवधि:</strong> {duration}<br>
                        <span style="display: block; margin-top: 8px; color: #b45309; font-size: 12px;">* कृपया ध्यान दें: यह आयोजन स्थल आरक्षण पूछताछ है, अंतिम बुकिंग नहीं। ट्रस्ट प्रबंधन शुभ मुहूर्त, मंदिर मर्यादा एवं उपलब्धता के आधार पर शीघ्र संपर्क कर परामर्श प्रदान करेगा।</span>
                    </div>',
                    'आयोजन पूछताछ विवरण देखें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'मंदिर परिसर आरक्षण पूछताछ अद्यतन (Temple Booking Status Update)',
                'slug' => 'temple_booking_status_update',
                'category' => 'services',
                'description' => 'Sent when temple premises booking is accepted, scheduled or updated by admin.',
                'subject' => 'मंदिर परिसर आरक्षण पूछताछ अद्यतन: #{enquiry_number} — '.$siteName,
                'placeholders_hint' => '{name}, {email}, {enquiry_number}, {event_type}, {proposed_date}, {status}, {admin_notes}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'आयोजन स्थिति अद्यतन · TEMPLE EVENT STATUS UPDATE',
                    'मंदिर परिसर आरक्षण प्रस्ताव की अद्यतन स्थिति',
                    'सादर प्रणाम, {name} जी!',
                    'कामदगिरि पीठम् सेवा ट्रस्ट द्वारा आपके <strong>{event_type}</strong> प्रस्ताव (संदर्भ <strong>#{enquiry_number}</strong>) की समीक्षा कर ली गई है।',
                    '<div style="background-color: #edf7ee; border: 1px solid #a3d9a5; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong style="color: #2e7d32;">वर्तमान स्थिति: {status}</strong><br>
                        <strong>प्रस्तावित तिथि:</strong> {proposed_date}<br>
                        <strong>समिति संदेश व दिशा-निर्देश:</strong> {admin_notes}
                    </div>',
                    'डैशबोर्ड पर विवरण देखें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'प्रबंधन सूचना: नया मंदिर परिसर आयोजन प्रस्ताव (Admin Alert: Temple Booking)',
                'slug' => 'temple_booking_admin_alert',
                'category' => 'services',
                'description' => 'Sent to administration when a devotee requests booking of temple premises, yajnashala, or hall.',
                'subject' => '[ADMIN ALERT] नया मंदिर परिसर आयोजन प्रस्ताव: #{enquiry_number} — {name}',
                'placeholders_hint' => '{name}, {email}, {phone}, {enquiry_number}, {event_type}, {proposed_date}, {gathering}, {duration}, {requirements}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'प्रबंधन सूचना · ADMIN TEMPLE BOOKING ALERT',
                    'मंदिर परिसर आयोजन आरक्षण पूछताछ प्राप्त हुई',
                    'आदरणीय व्यवस्थापक जी,',
                    'मंदिर परिसर / यज्ञशाला / कथा मंडप आरक्षण हेतु भक्त द्वारा नया आयोजन प्रस्ताव दर्ज किया गया है:',
                    '<div style="background-color: #fff8e1; border: 1px solid #ffe082; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>संदर्भ संख्या:</strong> #{enquiry_number}<br>
                        <strong>आयोजक नाम:</strong> {name}<br>
                        <strong>संपर्क:</strong> {phone} &bull; {email}<br>
                        <strong>आयोजन:</strong> {event_type} &bull; <strong>तिथि:</strong> {proposed_date}<br>
                        <strong>उपस्थिति:</strong> {gathering} श्रद्धालु &bull; <strong>अवधि:</strong> {duration}<br>
                        <strong>अतिरिक्त आवश्यकताएं:</strong> {requirements}
                    </div>',
                    'एडमिन पैनल में समीक्षा करें',
                    '{site_url}/admin/temple-bookings'
                ),
                'is_active' => true,
                'is_system' => true,
            ],

            // ==========================================
            // 8. NEWSLETTER & COMMUNITY
            // ==========================================
            [
                'name' => 'न्यूज़लेटर सदस्यता पुष्टि व स्वागत (Newsletter Welcome)',
                'slug' => 'newsletter_welcome',
                'category' => 'newsletter',
                'description' => 'Sent automatically to new newsletter subscribers when they subscribe on website.',
                'subject' => 'The Chitrakoot Journal — पावन संवाद में आपका स्वागत है',
                'placeholders_hint' => '{email}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'पावन संवाद स्वागत · THE CHITRAKOOT JOURNAL',
                    'चित्रकूट के पावन संदेशों से जुड़े रहने हेतु धन्यवाद',
                    'जय कामतानाथ!',
                    'श्री कामदगिरि पीठम् के पावन ई-संवाद में जुड़ने हेतु आपका हार्दिक अभिनंदन। अब आपको कामदगिरि धाम के आध्यात्मिक आलेख, उत्सवों एवं पर्वों की तिथियां, आरती समय-सारिणी तथा ट्रस्ट के गौ सेवा व समाज सेवा कार्यों की प्रामाणिक सूचना सीधे आपके ईमेल पर प्राप्त होगी।',
                    '<div style="background-color: #faf7f0; border: 1px solid #e8dfd3; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13px; line-height: 1.8; color: #554;">
                        आप किसी भी समय अपने ईमेल के माध्यम से अपनी सदस्यता प्राथमिकताएं प्रबंधित कर सकते हैं।
                    </div>',
                    'नवीनतम लेख व समाचार पढ़ें',
                    '{site_url}/blog'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'नवीन आलेख व शोध-पत्र प्रकाशन सूचना (New Post Newsletter Broadcast)',
                'slug' => 'new_post_newsletter',
                'category' => 'newsletter',
                'description' => 'Broadcasted to active newsletter subscribers when a new article or sacred story is published.',
                'subject' => 'New Story: {post_title} — The Chitrakoot Journal',
                'placeholders_hint' => '{name}, {post_title}, {post_excerpt}, {article_url}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'नवीन आलेख प्रकाशन · THE CHITRAKOOT JOURNAL',
                    '{post_title}',
                    'जय कामतानाथ!',
                    '{post_excerpt}',
                    '<div style="text-align: center; margin: 24px 0 10px;">
                        <a href="{article_url}" style="display: inline-block; background-color: #8f1719; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 9999px; font-weight: 700; font-size: 13px;">पूरा आलेख पढ़ें (Read Full Story) &rarr;</a>
                    </div>',
                    'सभी आलेख देखें',
                    '{site_url}/blog'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
            [
                'name' => 'टिप्पणी स्वीकृति सूचना (Blog Comment Approved Notification)',
                'slug' => 'comment_approved',
                'category' => 'newsletter',
                'description' => 'Sent to devotee when their comment on a blog article is moderated and published by admin.',
                'subject' => 'आपकी टिप्पणी प्रकाशित हुई — The Chitrakoot Journal',
                'placeholders_hint' => '{name}, {post_title}, {article_url}, {site_name}, {site_url}',
                'content_html' => self::wrapTempleLayout(
                    'टिप्पणी प्रकाशित · COMMENT PUBLISHED',
                    'आपकी विचारपूर्ण टिप्पणी लेख पर प्रकाशित कर दी गई है',
                    'सादर प्रणाम, {name} जी!',
                    'The Chitrakoot Journal के आलेख <strong>"{post_title}"</strong> पर आपके द्वारा प्रेषित टिप्पणी की ट्रस्ट संपादकीय मंडल द्वारा समीक्षा कर उसे आधिकारिक रूप से प्रकाशित कर दिया गया है।',
                    '<div style="background-color: #edf7ee; border: 1px solid #a3d9a5; border-radius: 8px; padding: 16px; margin: 20px 0; font-size: 13.5px; color: #1e4620;">
                        सनातन विचारों एवं चित्रकूट महात्म्य पर आपकी सहभागिता हमारे पाठकों हेतु अत्यंत प्रेरणादायक है।
                    </div>',
                    'प्रकाशित टिप्पणी देखें',
                    '{article_url}'
                ),
                'is_active' => true,
                'is_system' => true,
            ],
        ];

        foreach ($templates as $tmpl) {
            EmailTemplate::updateOrCreate(
                ['slug' => $tmpl['slug']],
                $tmpl
            );
        }
    }

    /**
     * Wrap content in the breathtaking, bulletproof, luxury HTML email master layout.
     */
    public static function wrapTempleLayout(
        string $badgeTitle,
        string $headline,
        string $greeting,
        string $leadParagraph,
        string $highlightHtml = '',
        string $ctaText = '',
        string $ctaUrl = ''
    ): string {
        $ctaHtml = '';
        if (! empty($ctaText) && ! empty($ctaUrl)) {
            $ctaHtml = '
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin: 26px 0 10px;">
              <tr>
                <td align="center">
                  <a href="'.$ctaUrl.'" target="_blank" style="display: inline-block; background-color: #8f1719; color: #ffffff; text-decoration: none; padding: 13px 32px; border-radius: 6px; font-weight: 700; font-size: 13.5px; letter-spacing: 0.5px; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Arial, sans-serif;">'.$ctaText.' &rarr;</a>
                </td>
              </tr>
            </table>';
        }

        return '<!DOCTYPE html>
<html lang="hi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>'.$headline.'</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f7f4ed; font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased;">
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color: #f7f4ed; padding: 32px 12px;">
  <tr>
    <td align="center">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="max-width: 620px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #e5ded5;">
        <!-- Top Maroon & Gold Accent Bar -->
        <tr>
          <td style="height: 5px; background-color: #8f1719;"></td>
        </tr>
        <!-- Masthead -->
        <tr>
          <td style="background-color: #16100d; padding: 28px 24px; text-align: center;">
            <div style="font-size: 11px; letter-spacing: 2.5px; color: #d4a365; text-transform: uppercase; font-weight: 800; margin-bottom: 5px;">श्री कामदगिरि पीठम् सेवा ट्रस्ट</div>
            <div style="font-size: 20px; font-weight: 700; color: #ffffff; font-family: Georgia, \'Times New Roman\', serif; letter-spacing: 0.5px;">चित्रकूट धाम &bull; SEWA & SANATAN HERITAGE</div>
            <div style="width: 44px; height: 1.5px; background-color: #b98550; margin: 10px auto 0;"></div>
          </td>
        </tr>
        <!-- Content Body -->
        <tr>
          <td style="padding: 32px 28px 24px;">
            <div style="font-size: 11px; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; color: #b98550; margin-bottom: 8px;">'.$badgeTitle.'</div>
            <h1 style="font-size: 21px; font-weight: 700; color: #16100d; margin: 0 0 16px; font-family: Georgia, \'Times New Roman\', serif; line-height: 1.35;">'.$headline.'</h1>
            <div style="font-size: 14.5px; color: #593a28; margin-bottom: 12px; font-weight: 600;">'.$greeting.'</div>
            <div style="font-size: 14px; line-height: 1.7; color: #3d332a; margin-bottom: 18px;">'.$leadParagraph.'</div>
            '.$highlightHtml.'
            '.$ctaHtml.'
            <div style="font-size: 12px; color: #8c7e73; line-height: 1.5; margin-top: 24px; border-top: 1px dashed #e8e2d8; padding-top: 16px;">
              यदि आपको इस संबंध में कोई सहायता अथवा जानकारी चाहिए, तो कृपया हमारे सेवा केंद्र (info@kamadgiripeetham.org) पर संपर्क करें।
            </div>
          </td>
        </tr>
        <!-- Official Legal Footer -->
        <tr>
          <td style="background-color: #faf7f2; border-top: 1px solid #ede7dc; padding: 22px 28px; text-align: center;">
            <div style="font-size: 12px; color: #6a5d52; line-height: 1.6;">
              <strong style="color: #16100d;">श्री कामदगिरि पीठम् सेवा ट्रस्ट (रजि.)</strong><br>
              कामदगिरि परिक्रमा मार्ग, चित्रकूट धाम, उत्तर प्रदेश / मध्य प्रदेश<br>
            </div>
            <div style="font-size: 11px; color: #9c8e82; margin-top: 10px; border-top: 1px dashed #e2dbce; padding-top: 10px;">
              सभी दान आयकर अधिनियम 1961 की धारा 80G के अंतर्गत 50% कर-मुक्त हैं। यह डिजिटल रूप से प्रमाणित आधिकारिक सूचना है।
            </div>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>';
    }
}
