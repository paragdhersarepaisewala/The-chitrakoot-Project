<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use App\Models\HomepageSetting;
use App\Models\MediaFile;
use App\Services\EmailNotificationService;
use Illuminate\Database\Seeder;

class CmsFeaturesSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed luxury email templates with spiritual temple aesthetic
        $templates = [
            [
                'slug' => 'login_otp',
                'name' => 'Login / Registration Email OTP',
                'subject' => 'Your Verification OTP Code: {otp} — Shri Kamadgiri Peetham Sewa Trust',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'सुरक्षा प्रमाणीकरण · ONE-TIME PASSWORD',
                    'आपका लॉगिन / पंजीकरण सुरक्षा कोड',
                    'सादर प्रणाम, {name} जी!',
                    'श्री कामदगिरि पीठम् सेवा ट्रस्ट के डिजिटल पोर्टल पर सुरक्षित प्रवेश अथवा पंजीकरण सत्यापन हेतु आपका एक बार का सुरक्षा कोड (OTP) जारी किया गया है।',
                    '<div style="background: #faf7f0; border: 2px dashed #b98550; border-radius: 10px; text-align: center; padding: 24px 16px; margin: 24px 0;">
                        <span style="font-size: 36px; font-weight: 800; letter-spacing: 10px; color: #8f1719; font-family: monospace;">{otp}</span>
                        <div style="color: #7a6e65; font-size: 12px; margin-top: 10px;">यह कोड अगले 10 मिनट के लिए मान्य है। कृपया इसे किसी के साथ साझा न करें।</div>
                    </div>'
                ),
                'is_active' => true,
            ],
            [
                'slug' => 'welcome_registration',
                'name' => 'Welcome on Successful Registration',
                'subject' => 'Welcome to Shri Kamadgiri Peetham Family, {name}!',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'परम स्वागतम् · WELCOME TO PEETHAM FAMILY',
                    'श्री कामदगिरि सेवा परिवार में आपका सहृदय स्वागत है',
                    'सादर प्रणाम, {name} जी!',
                    'चित्रकूट धाम की पावन धरा और श्री कामदगिरि पीठम् सेवा ट्रस्ट से जुड़ने पर आपका हार्दिक अभिनंदन। आपका भक्त खाता सफलतापूर्वक सक्रिय कर दिया गया है। अब आप ऑनलाइन पूजा संकल्प, गौसेवा योगदान, तीर्थ दर्शन गाइड एवं धार्मिक शोध-पत्रों से सीधे जुड़ सकते हैं।',
                    '<div style="background: #faf7f0; border: 1px solid #e8dfd3; border-radius: 8px; padding: 18px 20px; margin: 20px 0;">
                        <div style="font-size: 13px; color: #4a3f35; line-height: 1.8;">
                            <strong>पंजीकृत नाम:</strong> {name}<br>
                            <strong>ईमेल आईडी:</strong> {email}<br>
                            <strong>पंजीकरण दिनांक:</strong> {date}
                        </div>
                    </div>',
                    'भक्त डैशबोर्ड में प्रवेश करें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
            ],
            [
                'slug' => 'donation_receipt',
                'name' => 'Donation & Cause Contribution Receipt',
                'subject' => 'Official Contribution Receipt #{receipt_id} for {cause} — Shri Kamadgiri Peetham',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'दान पावती एवं आभार पत्र · OFFICIAL 80G RECEIPT',
                    'आपके पुनीत सहयोग हेतु हार्दिक आभार एवं मंगलकामनाएं',
                    'परम आदरणीय {name} जी!',
                    'श्री कामदगिरि पीठम् सेवा ट्रस्ट के अंतर्गत <strong>{cause}</strong> हेतु आपका श्रद्धापूर्ण दान <strong>₹{amount}</strong> सफलतापूर्वक प्राप्त हुआ है। आपकी यह सेवा गौ संरक्षण, वैदिक शिक्षा और अन्नक्षेत्र के संवर्धन में प्रत्यक्ष सहायक होगी।',
                    '<div style="background: #faf7f0; border: 1px solid #b98550; border-radius: 10px; padding: 22px; margin: 24px 0;">
                        <table width="100%" style="border-collapse: collapse; font-size: 13.5px; color: #333;">
                            <tr style="border-bottom: 1px solid #ebd9c4;"><td style="padding: 8px 0; color: #7a6e65;">रसीद संख्या (Receipt No):</td><td style="padding: 8px 0; font-weight: 700; text-align: right; color: #8f1719;">#{receipt_id}</td></tr>
                            <tr style="border-bottom: 1px solid #ebd9c4;"><td style="padding: 8px 0; color: #7a6e65;">दान राशि (Amount):</td><td style="padding: 8px 0; font-weight: 800; text-align: right; color: #16100d; font-size: 16px;">₹{amount}</td></tr>
                            <tr style="border-bottom: 1px solid #ebd9c4;"><td style="padding: 8px 0; color: #7a6e65;">प्रकल्प / उद्देश्य (Cause):</td><td style="padding: 8px 0; font-weight: 600; text-align: right;">{cause}</td></tr>
                            <tr><td style="padding: 8px 0; color: #7a6e65;">दिनांक (Date):</td><td style="padding: 8px 0; text-align: right;">{date}</td></tr>
                        </table>
                    </div>',
                    'आधिकारिक डिजिटल रसीद देखें / डाउनलोड करें',
                    '{site_url}/receipt/{receipt_id}'
                ),
                'is_active' => true,
            ],
            [
                'slug' => 'monthly_donation_active',
                'name' => 'Monthly Membership / Recurring Renewal Email',
                'subject' => 'Thank you for your Monthly Patron Support ₹{amount} — Shri Kamadgiri Peetham',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'मासिक सेवा सहयोगी सम्मान · MONTHLY PATRON RENEWAL',
                    'नित्य सेवा संकल्प का नवीनीकरण संपन्न',
                    'प्रिय {name} जी!',
                    'श्री कामदगिरि पीठम् के मासिक सेवा प्रकल्प में आपके सतत सहयोग <strong>₹{amount}</strong> के नवीनीकरण हेतु ट्रस्ट परिवार आपका कृतज्ञ है।',
                    '<div style="background: #faf7f0; border: 1px solid #e8dfd3; border-radius: 8px; padding: 18px 20px; margin: 20px 0; font-size: 13.5px;">
                        <strong>माह:</strong> {month} &bull; <strong>सहयोग राशि:</strong> ₹{amount} &bull; <strong>रसीद:</strong> #{receipt_id}
                    </div>',
                    'मासिक सेवा खाता प्रबंधित करें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
            ],
            [
                'slug' => 'volunteer_received',
                'name' => 'Volunteer Enrolment Received (Devotee Acknowledgment)',
                'subject' => 'सेवादार पंजीकरण आवेदन प्राप्त हुआ: संदर्भ #{applicant_id} — Shri Kamadgiri Peetham',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'सेवा संकल्प पावती · VOLUNTEER ENROLMENT RECEIVED',
                    'चित्रकूट धाम सेवादार पंजीकरण आवेदन प्राप्त हुआ',
                    'सादर प्रणाम, {name} जी!',
                    'श्री कामदगिरि पीठम् सेवा ट्रस्ट के सेवा प्रकल्पों में आपकी निःस्वार्थ सहभागिता हेतु आपका आवेदन सफलतापूर्वक दर्ज कर लिया गया है। सनातन धर्म, गौसेवा, पर्यावरण एवं मानव कल्याण की इस पावन यात्रा में आपका समर्पण अत्यंत वंदनीय है।',
                    '<div style="background: #faf7f0; border: 1px solid #b98550; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>संदर्भ संख्या:</strong> #{applicant_id}<br>
                        <strong>आवेदक नाम:</strong> {name}<br>
                        <strong>संपर्क सूत्र:</strong> {phone}<br>
                        <strong>चयनित सेवा क्षेत्र:</strong> {seva_areas}<br>
                        <strong>नगर / राज्य:</strong> {city}, {state}<br>
                        <strong>स्थिति:</strong> समीक्षाधीन (Under Review)
                    </div>',
                    'भक्त पोर्टल खोलें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
            ],
            [
                'slug' => 'volunteer_approved',
                'name' => 'Volunteer Application Approved (Welcome to Seva Dal)',
                'subject' => 'बधाई! आपका सेवादार आवेदन स्वीकृत हुआ — Shri Kamadgiri Peetham Sewa Dal',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'सेवादार अनुमोदन पत्र · SEVA DAL ENROLMENT APPROVED',
                    'श्री कामदगिरि सेवादार परिवार में आपका सहर्ष स्वागत है',
                    'जय सियाराम, {name} जी!',
                    'अत्यंत हर्ष के साथ सूचित किया जाता है कि श्री कामदगिरि पीठम् सेवा ट्रस्ट द्वारा आपका सेवादार पंजीकरण आवेदन आधिकारिक रूप से स्वीकृत (Approved) कर लिया गया है। आप अब हमारे सेवा दल के सम्मानित सदस्य हैं।',
                    '<div style="background: #edf7ee; border: 1px solid #a3d9a5; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong style="color: #2e7d32;">स्थिति: आधिकारिक रूप से स्वीकृत (OFFICIALLY APPROVED)</strong><br>
                        <strong>सेवादार नाम:</strong> {name}<br>
                        <strong>सेवा विभाग:</strong> {seva_areas}<br>
                        <strong>व्यवस्थापक संदेश:</strong> {admin_notes}<br>
                        <strong>स्वीकृति दिनांक:</strong> {date}
                    </div>',
                    'सेवादार डैशबोर्ड व परिचय पत्र देखें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
            ],
            [
                'slug' => 'volunteer_rejected',
                'name' => 'Volunteer Application Regret / Notice',
                'subject' => 'सेवादार आवेदन के संबंध में आवश्यक सूचना — Shri Kamadgiri Peetham',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'सेवादार आवेदन सूचना · VOLUNTEER APPLICATION UPDATE',
                    'आपके सेवादार आवेदन के संबंध में सूचना',
                    'सादर प्रणाम, {name} जी!',
                    'श्री कामदगिरि पीठम् सेवा ट्रस्ट के सेवा प्रकल्पों में आपकी रुचि हेतु हम आपके आभारी हैं। वर्तमान सत्र में सेवादार स्लॉट्स की सीमित उपलब्धता के कारण इस समय आपके आवेदन को आगे बढ़ाने में हम असमर्थ हैं।',
                    '<div style="background: #faf7f0; border: 1px solid #e8dfd3; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>आवेदक नाम:</strong> {name}<br>
                        <strong>सेवा क्षेत्र:</strong> {seva_areas}<br>
                        <strong>ट्रस्ट का संदेश:</strong> {admin_notes}<br>
                        <strong>आगामी अवसर:</strong> आगामी आयोजनों एवं उत्सवों में पुनः नए सेवादार नामांकन आमंत्रित किए जाएंगे।
                    </div>',
                    'ट्रस्ट के अन्य प्रकल्पों से जुड़ें',
                    '{site_url}/seva'
                ),
                'is_active' => true,
            ],
            [
                'slug' => 'pooja_booking_received',
                'name' => 'Pooja & Sankalp Booking Received (Devotee Notice)',
                'subject' => 'पूजा व अनुष्ठान संकल्प अनुरोध प्राप्त हुआ: #{booking_number} — Shri Kamadgiri Peetham',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'पूजा संकल्प पावती · POOJA BOOKING RECEIVED',
                    'आपका वैदिक पूजा व अनुष्ठान संकल्प प्राप्त हुआ',
                    'जय कामतानाथ, {name} जी!',
                    'चित्रकूट धाम के पावन सानिध्य में <strong>{service_name}</strong> हेतु आपका संकल्प अनुरोध संदर्भ संख्या <strong>#{booking_number}</strong> के साथ पंजीकृत हो चुका है। हमारे वैदिक पुरोहित मंडल द्वारा आपके संकल्प की व्यवस्था की जा रही है।',
                    '<div style="background: #faf7f0; border: 1px solid #b98550; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>बुकिंग संदर्भ:</strong> #{booking_number}<br>
                        <strong>अनुष्ठान सेवा:</strong> {service_name}<br>
                        <strong>संकल्प तिथि व समय:</strong> {booking_date} ({time_slot})<br>
                        <strong>यजमान / गोत्र:</strong> {name} (गोत्र: {gotra})<br>
                        <strong>सहयोग राशि:</strong> ₹{amount} ({payment_status})
                    </div>',
                    'संकल्प स्थिति देखें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
            ],
            [
                'slug' => 'pooja_booking_confirmed',
                'name' => 'Pooja & Sankalp Booking Confirmed (Priest Schedule)',
                'subject' => 'पूजा व अनुष्ठान संकल्प पुष्ट हुआ: #{booking_number} — Shri Kamadgiri Peetham',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'अनुष्ठान पुष्टि पत्र · POOJA SANKALP CONFIRMED',
                    'आपका वैदिक पूजा व अनुष्ठान संकल्प सुनिश्चित हुआ',
                    'परम आदरणीय {name} जी!',
                    'चित्रकूट धाम की पवित्र भूमि पर कामदगिरि महाराज के चरणों में आपके द्वारा समर्पित <strong>{service_name}</strong> का संकल्प पुष्ट (Confirmed) कर दिया गया है। निर्धारित तिथि पर वैदिक आचार्यों द्वारा विधि-विधान से आपका अनुष्ठान संपन्न कराया जाएगा।',
                    '<div style="background: #edf7ee; border: 1px solid #a3d9a5; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong style="color: #2e7d32;">स्थिति: अनुष्ठान पुष्ट (CONFIRMED)</strong><br>
                        <strong>बुकिंग संख्या:</strong> #{booking_number}<br>
                        <strong>अनुष्ठान:</strong> {service_name}<br>
                        <strong>तिथि व समय:</strong> {booking_date} ({time_slot})<br>
                        <strong>गोत्र व नक्षत्र:</strong> {gotra} / {nakshatra}<br>
                        <strong>संकल्प उद्देश्य:</strong> {sankalp_purpose}<br>
                        <strong>पुरोहित संदेश:</strong> {admin_notes}
                    </div>',
                    'डिजिटल संकल्प पत्र व रसीद देखें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
            ],
            [
                'slug' => 'new_post_newsletter',
                'name' => 'Newsletter Notification for New Article',
                'subject' => 'New Story: {post_title} — The Chitrakoot Journal',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'चित्रकूट धाम शोध एवं आध्यात्मिक विचार · THE CHITRAKOOT JOURNAL',
                    '{post_title}',
                    'सादर प्रणाम, {name} जी!',
                    '{post_excerpt}',
                    '<div style="background: #faf7f0; border-left: 4px solid #8f1719; padding: 14px 18px; margin: 20px 0; font-size: 13.5px; font-style: italic; color: #555;">
                        "मंदाकिनी की पावन धारा और कामदगिरि के अंचल में रचित यह विशेष आलेख आपके समक्ष प्रस्तुत है।"
                    </div>',
                    'संपूर्ण आलेख एवं विवरण पढ़ें →',
                    '{article_url}'
                ),
                'is_active' => true,
            ],
            [
                'slug' => 'admin_new_user',
                'name' => 'Admin Alert: New User Registration',
                'subject' => '[Admin Alert] New Member Registered: {name} ({email})',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'प्रशासक सतर्कता · ADMIN AUDIT ALERT',
                    'नया सदस्य पंजीकरण दर्ज हुआ',
                    'व्यवस्थापक महोदय,',
                    'वेबसाइट पर एक नया भक्त / सदस्य खाता पंजीकृत किया गया है:',
                    '<div style="background: #faf7f0; border: 1px solid #e8dfd3; border-radius: 8px; padding: 18px; margin: 18px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>नाम:</strong> {name}<br>
                        <strong>ईमेल:</strong> {email}<br>
                        <strong>दिनांक:</strong> {date}
                    </div>',
                    'उपयोगकर्ता प्रबंधन खोलें',
                    '{site_url}/admin/users'
                ),
                'is_active' => true,
            ],
            [
                'slug' => 'atithi_vishram_received',
                'name' => 'Atithi Vishram Enquiry Received (Devotee)',
                'subject' => 'अतिथि विश्राम गृह पूछताछ प्राप्त हुई: #{enquiry_number} — {site_name}',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'अतिथि विश्राम पावती · GUEST HOUSE ENQUIRY RECEIVED',
                    'मंदिर अतिथि विश्राम पूछताछ सफलतापूर्वक दर्ज हुई',
                    'जय सियाराम, {name} जी!',
                    'चित्रकूट धाम तीर्थ यात्रा के दौरान कामदगिरि पीठम् अतिथि विश्राम गृह में प्रवास हेतु आपकी पूछताछ संदर्भ संख्या <strong>#{enquiry_number}</strong> के साथ प्राप्त हो गई है।',
                    '<div style="background: #faf7f0; border: 1px solid #d4ad7d; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>पूछताछ संदर्भ:</strong> #{enquiry_number}<br>
                        <strong>आगमन तिथि (Check-in):</strong> {check_in_date}<br>
                        <strong>प्रस्थान तिथि (Check-out):</strong> {check_out_date}<br>
                        <strong>भक्त संख्या:</strong> {devotees} श्रद्धालु &middot; <strong>कक्ष:</strong> {rooms}<br>
                        <strong>कक्ष प्रकार:</strong> {room_preference}<br>
                        <strong>यात्रा उद्देश्य:</strong> {purpose}<br>
                        <span style="display: block; margin-top: 8px; color: #b45309; font-size: 12px;">* ध्यान दें: यह विश्राम कक्ष की पूछताछ है। उपलब्धता एवं आश्रम मर्यादा के अनुसार कार्यालय द्वारा शीघ्र संपर्क कर व्यवस्था सुनिश्चित की जाएगी।</span>
                    </div>',
                    'विश्राम स्थिति देखें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
            ],
            [
                'slug' => 'atithi_vishram_admin_alert',
                'name' => 'Atithi Vishram Enquiry Alert (Admin)',
                'subject' => '[ADMIN ALERT] नई अतिथि विश्राम पूछताछ: #{enquiry_number} — {name}',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'प्रबंधन सूचना · ADMIN ATITHI VISHRAM ALERT',
                    'नई अतिथि विश्राम पूछताछ प्राप्त हुई',
                    'आदरणीय व्यवस्थापक जी,',
                    'मंदिर पोर्टल पर भक्त द्वारा तीर्थ विश्राम गृह में ठहरने हेतु नई पूछताछ दर्ज की गई है। विवरण नीचे दिया गया है:',
                    '<div style="background: #fff8e1; border: 1px solid #ffe082; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
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
            ],
            [
                'slug' => 'atithi_vishram_status_update',
                'name' => 'Atithi Vishram Status Update (Devotee)',
                'subject' => 'अतिथि विश्राम पूछताछ अद्यतन: #{enquiry_number} — {site_name}',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'विश्राम स्थिति अद्यतन · GUEST STAY STATUS UPDATE',
                    'अतिथि विश्राम पूछताछ की अद्यतन स्थिति',
                    'सादर प्रणाम, {name} जी!',
                    'कामदगिरि पीठम् सेवा ट्रस्ट द्वारा आपकी अतिथि विश्राम गृह पूछताछ संदर्भ <strong>#{enquiry_number}</strong> की समीक्षा कर ली गई है।',
                    '<div style="background: #edf7ee; border: 1px solid #a3d9a5; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong style="color: #2e7d32;">वर्तमान स्थिति: {status}</strong><br>
                        <strong>आगमन तिथि:</strong> {check_in_date} &mdash; <strong>प्रस्थान:</strong> {check_out_date}<br>
                        <strong>व्यवस्थापक संदेश / निर्देश:</strong> {admin_notes}
                    </div>',
                    'डैशबोर्ड पर विवरण देखें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
            ],
            [
                'slug' => 'temple_booking_received',
                'name' => 'Temple Booking Enquiry Received (Devotee)',
                'subject' => 'मंदिर परिसर व आयोजन आरक्षण पूछताछ प्राप्त हुई: #{enquiry_number} — {site_name}',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'परिसर आरक्षण पावती · TEMPLE PREMISES BOOKING ENQUIRY',
                    'मंदिर परिसर व धार्मिक आयोजन पूछताछ दर्ज हुई',
                    'जय सियाराम, {name} जी!',
                    'श्री कामदगिरि पीठम् सेवा ट्रस्ट के पावन परिसर में <strong>{event_type}</strong> के आयोजन हेतु आपकी पूछताछ संदर्भ संख्या <strong>#{enquiry_number}</strong> के साथ प्राप्त हो गई है।',
                    '<div style="background: #faf7f0; border: 1px solid #d4ad7d; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong>पूछताछ संदर्भ:</strong> #{enquiry_number}<br>
                        <strong>आयोजन प्रकार:</strong> {event_type}<br>
                        <strong>प्रस्तावित तिथि:</strong> {proposed_date}<br>
                        <strong>वैकल्पिक तिथि:</strong> {alternate_date}<br>
                        <strong>अनुमानित उपस्थिति:</strong> लगभग {gathering} श्रद्धालु &middot; <strong>अवधि:</strong> {duration}<br>
                        <span style="display: block; margin-top: 8px; color: #b45309; font-size: 12px;">* कृपया ध्यान दें: यह आयोजन स्थल आरक्षण पूछताछ है, अंतिम बुकिंग नहीं। ट्रस्ट प्रबंधन शुभ मुहूर्त, मंदिर मर्यादा एवं उपलब्धता के आधार पर शीघ्र संपर्क कर परामर्श प्रदान करेगा।</span>
                    </div>',
                    'आयोजन पूछताछ विवरण देखें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
            ],
            [
                'slug' => 'temple_booking_admin_alert',
                'name' => 'Temple Booking Enquiry Alert (Admin)',
                'subject' => '[ADMIN ALERT] नया मंदिर परिसर आयोजन प्रस्ताव: #{enquiry_number} — {name}',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'प्रबंधन सूचना · ADMIN TEMPLE BOOKING ALERT',
                    'मंदिर परिसर आयोजन आरक्षण पूछताछ प्राप्त हुई',
                    'आदरणीय व्यवस्थापक जी,',
                    'मंदिर परिसर / यज्ञशाला / कथा मंडप आरक्षण हेतु भक्त द्वारा नया आयोजन प्रस्ताव दर्ज किया गया है:',
                    '<div style="background: #fff8e1; border: 1px solid #ffe082; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
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
            ],
            [
                'slug' => 'temple_booking_status_update',
                'name' => 'Temple Booking Status Update (Devotee)',
                'subject' => 'मंदिर परिसर आरक्षण पूछताछ अद्यतन: #{enquiry_number} — {site_name}',
                'content_html' => EmailNotificationService::renderTempleCard(
                    'आयोजन स्थिति अद्यतन · TEMPLE EVENT STATUS UPDATE',
                    'मंदिर परिसर आरक्षण प्रस्ताव की अद्यतन स्थिति',
                    'सादर प्रणाम, {name} जी!',
                    'कामदगिरि पीठम् सेवा ट्रस्ट द्वारा आपके <strong>{event_type}</strong> प्रस्ताव (संदर्भ <strong>#{enquiry_number}</strong>) की समीक्षा कर ली गई है।',
                    '<div style="background: #edf7ee; border: 1px solid #a3d9a5; border-radius: 8px; padding: 18px; margin: 20px 0; font-size: 13.5px; line-height: 1.8;">
                        <strong style="color: #2e7d32;">वर्तमान स्थिति: {status}</strong><br>
                        <strong>प्रस्तावित तिथि:</strong> {proposed_date}<br>
                        <strong>समिति संदेश व दिशा-निर्देश:</strong> {admin_notes}
                    </div>',
                    'डैशबोर्ड पर विवरण देखें',
                    '{site_url}/dashboard'
                ),
                'is_active' => true,
            ],
        ];

        foreach ($templates as $tmpl) {
            EmailTemplate::updateOrCreate(
                ['slug' => $tmpl['slug']],
                $tmpl
            );
        }

        // 2. Default Homepage Settings
        $defaultHomeSettings = [
            'hero_eyebrow' => 'A SACRED LAND, LIVING STORIES',
            'hero_headline' => 'आस्था, संस्कृति और चित्रकूट का गौरव',
            'hero_subtitle' => 'चित्रकूट से जुड़ी धर्म, संस्कृति, समाचार, यात्रा और प्रेरणादायक कथाओं का सशक्त मंच',
            'hero_story' => 'मंदाकिनी की शांत धार, कामदगिरि के पावन शिखर और युगों-युगों से चली आ रही तपोभूमि की दिव्यता — आइए चित्रकूट को देखें गहरी दृष्टि से।',
            'hero_bg_type' => 'video',
            'hero_overlay_dim' => '40',
            'hero_texture_style' => 'vignette_grain',
            'hero_video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-river-flowing-smoothly-in-a-forest-4286-large.mp4',
            'hero_photo_url' => '/assets/uploads/chitrakoot-hero.webp',
            'hero_primary_btn_text' => 'ताज़ा समाचार पढ़ें →',
            'hero_primary_btn_link' => '/blog',
            'hero_secondary_btn_text' => 'दान करें',
            'hero_secondary_btn_link' => '/donate',
            'trust_eyebrow' => 'About Our Trust',
            'trust_heading' => 'चित्रकूट की पवित्र भूमि पर सेवा का सतत संकल्प',
            'trust_desc' => 'श्री कामदगिरि पीठम् सेवा ट्रस्ट का उद्देश्य सनातन परंपराओं, वैदिक एवं संस्कृत शिक्षा, गौ संरक्षण, पर्यावरण और समाज सेवा के माध्यम से एक अधिक करुणामय और संस्कारित समाज की दिशा में कार्य करना है।',
            'stat_years' => '25+',
            'stat_students' => '500+',
            'stat_cows' => '250+',
            'stat_trees' => '10K+',
            'seva_section_title' => 'धर्म, सेवा और प्रकृति के लिए समर्पित',
            'seva_section_subtitle' => 'हमारे प्रमुख प्रकल्पों की एक झलक। प्रत्येक सेवा की विस्तृत जानकारी उसके समर्पित पृष्ठ पर उपलब्ध होगी।',
            'places_section_title' => 'जहाँ हर पग एक कथा है',
            'places_section_subtitle' => 'चित्रकूट भारत की उन पवित्र भूमियों में से है जहाँ प्रकृति, इतिहास और आध्यात्मिक परंपरा एक साथ अनुभव होती है।',
            'events_section_title' => 'आगामी कार्यक्रम एवं उत्सव',
            'events_section_subtitle' => 'धार्मिक, सांस्कृतिक और सामाजिक गतिविधियों से जुड़ें।',
            'donation_heading' => 'आपकी सहभागिता एक सेवा बन सकती है',
            'donation_desc' => 'गौसेवा, संस्कृत शिक्षा, धार्मिक आयोजनों और समाज कल्याण के हमारे प्रयासों में सहभागी बनें। अपनी इच्छित सेवा का चयन कर ऑनलाइन दान करें।',
            'donation_btn_text' => 'Donate Now',
            'donation_btn_link' => '/donate',
            'newsletter_title' => 'चित्रकूट के पावन संदेशों से जुड़े रहें',
            'newsletter_subtitle' => 'मासिक शोध-पत्र, आध्यात्मिक आलेख, उत्सवों की तिथियाँ और ट्रस्ट के सेवा कार्यों की सूचना सीधे अपने ईमेल पर प्राप्त करें।',
            'gallery_section_title' => 'तपोभूमि की दृश्य यात्रा',
            'gallery_section_subtitle' => 'चित्रकूट के पावन घाट, प्राकृतिक छटा और पीठम् के सेवा प्रकल्पों की एक झलक।',
            'aarti_section_title' => 'दैनिक आरती एवं दर्शन समय-सारिणी',
            'aarti_section_subtitle' => 'कामदगिरि प्रमुख मंदिर एवं मंदाकिनी रामघाट पर नित्य होने वाली पवित्र आरतियों की प्रामाणिक समय-सारिणी',
            'aarti_mangala_time' => '05:30 AM',
            'aarti_shringar_time' => '08:00 AM',
            'aarti_rajbhog_time' => '12:15 PM',
            'aarti_sandhya_time' => '07:00 PM',
            'aarti_shayan_time' => '09:00 PM',
            'parikrama_route' => 'कामदगिरि परिक्रमा मार्ग (लगभग 5.5 किमी)',
            'parikrama_rules' => 'यथासंभव नंगे पैर परिक्रमा करें, स्वच्छता बनाए रखें और बंदरों को अनुचित खाद्य सामग्री न दें।',
            'volunteer_registration_enabled' => '1',
        ];

        foreach ($defaultHomeSettings as $key => $value) {
            HomepageSetting::set($key, $value);
        }

        // 3. Seed default media library assets
        $defaultMedia = [
            ['filename' => 'temple-lotus-logo.png', 'original_name' => 'Temple Lotus Crest', 'file_path' => 'assets/uploads/temple-lotus-logo.png', 'mime_type' => 'image/png'],
            ['filename' => 'kamadgiri-mountain.webp', 'original_name' => 'Kamadgiri Mountain', 'file_path' => 'assets/uploads/kamadgiri-mountain.webp', 'mime_type' => 'image/webp'],
            ['filename' => 'ramghat-aarti.webp', 'original_name' => 'Mandakini Ramghat Aarti', 'file_path' => 'assets/uploads/ramghat-aarti.webp', 'mime_type' => 'image/webp'],
            ['filename' => 'gau-seva-trust.webp', 'original_name' => 'Gau Seva Ashram', 'file_path' => 'assets/uploads/gau-seva-trust.webp', 'mime_type' => 'image/webp'],
            ['filename' => 'gurukul-students.webp', 'original_name' => 'Vedic Gurukul Ashram', 'file_path' => 'assets/uploads/gurukul-students.webp', 'mime_type' => 'image/webp'],
        ];

        foreach ($defaultMedia as $med) {
            MediaFile::firstOrCreate(
                ['file_path' => $med['file_path']],
                [
                    'filename' => $med['filename'],
                    'original_name' => $med['original_name'],
                    'file_type' => 'image',
                    'mime_type' => $med['mime_type'],
                    'file_size' => 102400,
                ]
            );
        }
    }
}
