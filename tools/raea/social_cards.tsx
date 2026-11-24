import { AbsoluteFill, Composition, registerRoot } from 'raea';

interface ModernCardProps {
  eyebrowCategory: string;
  titleHi: string;
  titleEn: string;
  description: string;
  tags: string[];
  metaLeftLabel?: string;
  metaLeftValue?: string;
  metaRightLabel?: string;
  metaRightValue?: string;
  accentColor: string;
  secondaryAccent?: string;
  bgGradient: string;
  sealSymbol?: string;
  sealTopText?: string;
  sealBottomText?: string;
}

function ModernEditorialCard({
  eyebrowCategory,
  titleHi,
  titleEn,
  description,
  tags,
  metaLeftLabel = 'LOCATION',
  metaLeftValue = 'Kamadgiri Parikrama, Chitrakoot',
  metaRightLabel = 'INSTITUTION',
  metaRightValue = 'Shri Kamadgiri Peetham Trust',
  accentColor,
  secondaryAccent = '#fbbf24',
  bgGradient,
  sealSymbol = 'श्री',
  sealTopText = 'SANATAN DHARMA',
  sealBottomText = 'KAMADGIRI PEETHAM',
}: ModernCardProps) {
  return (
    <AbsoluteFill
      style={{
        background: '#0a0a0c',
        color: '#f5f5f4',
        fontFamily: 'system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif',
        display: 'flex',
        flexDirection: 'row',
        overflow: 'hidden',
        border: `2px solid ${accentColor}44`,
        boxSizing: 'border-box',
      }}
    >
      {/* Background Radial Glow */}
      <div
        style={{
          position: 'absolute',
          inset: 0,
          background: bgGradient,
          zIndex: 1,
        }}
      />

      {/* Subtle Geometric Grid Lines */}
      <div
        style={{
          position: 'absolute',
          inset: 0,
          backgroundImage: `linear-gradient(${accentColor}08 1px, transparent 1px), linear-gradient(90deg, ${accentColor}08 1px, transparent 1px)`,
          backgroundSize: '40px 40px',
          opacity: 0.6,
          zIndex: 2,
        }}
      />

      {/* Outer Fine Framing Border with Classical Corner Notches */}
      <div
        style={{
          position: 'absolute',
          inset: '16px',
          border: `1px solid ${accentColor}33`,
          pointerEvents: 'none',
          zIndex: 5,
        }}
      >
        <div style={{ position: 'absolute', top: -1, left: -1, width: '14px', height: '14px', borderTop: `2px solid ${secondaryAccent}`, borderLeft: `2px solid ${secondaryAccent}` }} />
        <div style={{ position: 'absolute', top: -1, right: -1, width: '14px', height: '14px', borderTop: `2px solid ${secondaryAccent}`, borderRight: `2px solid ${secondaryAccent}` }} />
        <div style={{ position: 'absolute', bottom: -1, left: -1, width: '14px', height: '14px', borderBottom: `2px solid ${secondaryAccent}`, borderLeft: `2px solid ${secondaryAccent}` }} />
        <div style={{ position: 'absolute', bottom: -1, right: -1, width: '14px', height: '14px', borderBottom: `2px solid ${secondaryAccent}`, borderRight: `2px solid ${secondaryAccent}` }} />
      </div>

      {/* Left Column: Typographic & Informational Content (66%) */}
      <div
        style={{
          flex: '1 1 66%',
          padding: '52px 50px',
          display: 'flex',
          flexDirection: 'column',
          justifyContent: 'space-between',
          position: 'relative',
          zIndex: 10,
        }}
      >
        {/* Top Eyebrow Section */}
        <div>
          <div
            style={{
              display: 'inline-flex',
              alignItems: 'center',
              gap: '10px',
              marginBottom: '20px',
            }}
          >
            <span
              style={{
                display: 'inline-block',
                width: '7px',
                height: '7px',
                borderRadius: '50%',
                background: accentColor,
                boxShadow: `0 0 8px ${accentColor}`,
              }}
            />
            <span
              style={{
                fontSize: '11px',
                fontWeight: 800,
                letterSpacing: '0.14em',
                textTransform: 'uppercase',
                color: secondaryAccent,
              }}
            >
              SHRI KAMADGIRI PEETHAM
            </span>
            <span style={{ color: 'rgba(255, 255, 255, 0.25)', fontSize: '11px' }}>/</span>
            <span
              style={{
                fontSize: '11px',
                fontWeight: 700,
                letterSpacing: '0.1em',
                textTransform: 'uppercase',
                color: '#a8a29e',
              }}
            >
              {eyebrowCategory}
            </span>
          </div>

          {/* Primary Hindi Title */}
          <h1
            style={{
              fontSize: '50px',
              fontWeight: 800,
              lineHeight: 1.15,
              color: '#fafaf9',
              margin: '0 0 12px 0',
              letterSpacing: '-0.02em',
              textShadow: '0 2px 10px rgba(0,0,0,0.6)',
            }}
          >
            {titleHi}
          </h1>

          {/* Secondary English Subtitle */}
          <div
            style={{
              fontSize: '20px',
              color: secondaryAccent,
              fontWeight: 600,
              marginBottom: '14px',
              letterSpacing: '0.01em',
            }}
          >
            {titleEn}
          </div>

          {/* Description */}
          <p
            style={{
              fontSize: '16px',
              color: '#a8a29e',
              lineHeight: 1.55,
              margin: '0 0 22px 0',
              maxWidth: '640px',
            }}
          >
            {description}
          </p>

          {/* Modern Pill Tags (NO EMOJIS) */}
          <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap' }}>
            {tags.map((tag, idx) => (
              <span
                key={idx}
                style={{
                  fontSize: '11px',
                  fontWeight: 700,
                  letterSpacing: '0.07em',
                  textTransform: 'uppercase',
                  color: '#e7e5e4',
                  background: `${accentColor}18`,
                  border: `1px solid ${accentColor}44`,
                  padding: '5px 12px',
                  borderRadius: '4px',
                }}
              >
                {tag}
              </span>
            ))}
          </div>
        </div>

        {/* Footer Meta Row */}
        <div
          style={{
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
            borderTop: '1px solid rgba(255, 255, 255, 0.1)',
            paddingTop: '18px',
          }}
        >
          <div style={{ display: 'flex', gap: '28px' }}>
            <div>
              <div style={{ fontSize: '10px', color: '#78716c', fontWeight: 800, letterSpacing: '0.1em', textTransform: 'uppercase' }}>
                {metaLeftLabel}
              </div>
              <div style={{ fontSize: '13px', color: '#e7e5e4', fontWeight: 600, marginTop: '2px' }}>
                {metaLeftValue}
              </div>
            </div>
            <div>
              <div style={{ fontSize: '10px', color: '#78716c', fontWeight: 800, letterSpacing: '0.1em', textTransform: 'uppercase' }}>
                {metaRightLabel}
              </div>
              <div style={{ fontSize: '13px', color: '#e7e5e4', fontWeight: 600, marginTop: '2px' }}>
                {metaRightValue}
              </div>
            </div>
          </div>

          <div
            style={{
              fontSize: '15px',
              color: secondaryAccent,
              fontWeight: 700,
              letterSpacing: '0.06em',
              fontFamily: 'monospace',
            }}
          >
            kamadgiripeetham.org
          </div>
        </div>
      </div>

      {/* Right Column: Architectural Seal & Geometric Motif (34%) */}
      <div
        style={{
          flex: '1 1 34%',
          position: 'relative',
          display: 'flex',
          alignItems: 'center',
          justifyContent: 'center',
          zIndex: 10,
          borderLeft: `1px solid ${accentColor}25`,
          background: `linear-gradient(180deg, rgba(18,18,22,0.4) 0%, rgba(10,10,14,0.85) 100%)`,
        }}
      >
        {/* Outer Fine Dashed Ring */}
        <div
          style={{
            position: 'absolute',
            width: '330px',
            height: '330px',
            borderRadius: '50%',
            border: `1px dashed ${accentColor}33`,
          }}
        />

        {/* Rotated Square Accent */}
        <div
          style={{
            position: 'absolute',
            width: '240px',
            height: '240px',
            transform: 'rotate(45deg)',
            border: `1px solid ${secondaryAccent}22`,
          }}
        />

        {/* Inner Solid Ring */}
        <div
          style={{
            position: 'absolute',
            width: '260px',
            height: '260px',
            borderRadius: '50%',
            border: `1px solid ${accentColor}35`,
          }}
        />

        {/* Central Traditional Emblem Seal */}
        <div
          style={{
            position: 'relative',
            width: '168px',
            height: '168px',
            borderRadius: '50%',
            background: 'linear-gradient(135deg, #1c1917 0%, #0c0a09 100%)',
            border: `2px solid ${accentColor}`,
            boxShadow: `0 24px 48px rgba(0,0,0,0.85), inset 0 0 20px ${accentColor}22`,
            display: 'flex',
            flexDirection: 'column',
            alignItems: 'center',
            justifyContent: 'center',
            textAlign: 'center',
            padding: '16px',
            boxSizing: 'border-box',
          }}
        >
          <div
            style={{
              fontSize: '10px',
              fontWeight: 800,
              letterSpacing: '0.14em',
              textTransform: 'uppercase',
              color: secondaryAccent,
              marginBottom: '4px',
            }}
          >
            {sealTopText}
          </div>

          <div
            style={{
              fontSize: '38px',
              fontWeight: 800,
              color: '#fafaf9',
              lineHeight: 1,
              margin: '4px 0',
              textShadow: '0 2px 12px rgba(0,0,0,0.7)',
            }}
          >
            {sealSymbol}
          </div>

          <div
            style={{
              fontSize: '11px',
              fontWeight: 800,
              letterSpacing: '0.14em',
              textTransform: 'uppercase',
              color: '#e7e5e4',
              marginTop: '4px',
            }}
          >
            {sealBottomText}
          </div>

          <div
            style={{
              fontSize: '8px',
              fontWeight: 600,
              letterSpacing: '0.12em',
              textTransform: 'uppercase',
              color: '#78716c',
              marginTop: '3px',
            }}
          >
            CHITRAKOOT DHAM
          </div>
        </div>
      </div>
    </AbsoluteFill>
  );
}

/* =========================================================================
   All 17 Major Pages (Curated, Modern, No 3D, No Emojis)
   ========================================================================= */

function HomeCard() {
  return (
    <ModernEditorialCard
      eyebrowCategory="OFFICIAL SPIRITUAL PORTAL"
      titleHi="श्री कामदगिरि पीठम् सेवा ट्रस्ट"
      titleEn="Official Spiritual Portal & Sacred Heritage Foundation"
      description="चित्रकूट धाम — सनातन संस्कृति, वैदिक गुरुकुल, गौसेवा, रामघाट आरती एवं तीर्थ कल्याण के लिए समर्पित पावन पीठ।"
      tags={['CHITRAKOOT DHAM', 'KAMADGIRI PARIKRAMA', 'VEDIC EDUCATION', '80G CERTIFIED']}
      metaLeftLabel="ESTABLISHED"
      metaLeftValue="Chitrakoot Dham, UP"
      metaRightLabel="ACCREDITATION"
      metaRightValue="Section 80G Registered"
      accentColor="#d97706"
      secondaryAccent="#f59e0b"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(180, 83, 9, 0.3) 0%, rgba(12, 10, 9, 0.95) 65%, #0c0a09 100%)"
      sealSymbol="श्री"
      sealTopText="SANATAN DHARMA"
      sealBottomText="KAMADGIRI PEETHAM"
    />
  );
}

function AboutCard() {
  return (
    <ModernEditorialCard
      eyebrowCategory="SACRED HERITAGE & VISION"
      titleHi="ट्रस्ट परिचय एवं पावन इतिहास"
      titleEn="Institutional Heritage, Lineage & Mission"
      description="सनातन धर्म, वैदिक गुरुकुल, गौ संवर्धन, सांस्कृतिक संरक्षण और असहाय सेवा की सुदृढ़ परंपरा का विस्तृत परिचय।"
      tags={['LINEAGE & HISTORY', 'ORGANIZATIONAL VISION', 'VEDIC PRESERVATION', 'COMMUNITY SERVICE']}
      metaLeftLabel="FOUNDATION"
      metaLeftValue="Chitrakoot Heritage"
      metaRightLabel="MISSION"
      metaRightValue="Preservation & Dharma"
      accentColor="#b45309"
      secondaryAccent="#fbbf24"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(146, 64, 14, 0.3) 0%, rgba(15, 10, 7, 0.95) 65%, #0f0a07 100%)"
      sealSymbol="धर्म"
      sealTopText="ANCIENT HERITAGE"
      sealBottomText="TRUST HISTORY"
    />
  );
}

function LeadershipCard() {
  return (
    <ModernEditorialCard
      eyebrowCategory="GOVERNANCE & TRUSTEES"
      titleHi="मार्गदर्शक मंडल एवं पूज्य पीठाधीश्वर"
      titleEn="Guiding Visionaries, Spiritual Leadership & Trustees"
      description="परम पूज्य संतों का पावन सानिध्य एवं न्यासी परिषद के कुशल नेतृत्व में संचालित सेवा प्रकल्प एवं वैधानिक मान्यताएं।"
      tags={['PEETHADHISHWAR', 'BOARD OF TRUSTEES', 'STATUTORY GOVERNANCE', 'ETHICAL LEADERSHIP']}
      metaLeftLabel="PATRONAGE"
      metaLeftValue="Peethadhishwar Council"
      metaRightLabel="GOVERNANCE"
      metaRightValue="Board of Trustees"
      accentColor="#991b1b"
      secondaryAccent="#f59e0b"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(127, 29, 29, 0.35) 0%, rgba(15, 6, 8, 0.95) 65%, #0f0608 100%)"
      sealSymbol="पीठ"
      sealTopText="SACRED THRONE"
      sealBottomText="BOARD OF TRUSTEES"
    />
  );
}

function SevaCard() {
  return (
    <ModernEditorialCard
      eyebrowCategory="HUMANITARIAN & VEDIC SEVA"
      titleHi="समर्पित सेवा प्रकल्प"
      titleEn="Ongoing Philanthropic & Spiritual Initiatives"
      description="कामधेनु गौशाला सेवा, निःशुल्क अन्नक्षेत्र, संस्कृत गुरुकुल एवं चिकित्सा सेवा के माध्यम से अनवरत जन-कल्याण।"
      tags={['KAMDHENU GAUSHALA', 'FREE ANNA KSHETRA', 'SANSKRIT GURUKUL', 'HEALTHCARE SEVA']}
      metaLeftLabel="DAILY IMPACT"
      metaLeftValue="Thousands Nourished"
      metaRightLabel="TAX STATUS"
      metaRightValue="80G Tax Exemption"
      accentColor="#059669"
      secondaryAccent="#34d399"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(6, 95, 70, 0.35) 0%, rgba(4, 20, 14, 0.95) 65%, #04140e 100%)"
      sealSymbol="सेवा"
      sealTopText="SELFLESS SERVICE"
      sealBottomText="SEVA PRAKALP"
    />
  );
}

function ChitrakootCard() {
  return (
    <ModernEditorialCard
      eyebrowCategory="PILGRIMAGE & SACRED SITES"
      titleHi="चित्रकूट धाम तीर्थ दर्शन एवं पावन स्थल"
      titleEn="Sacred Sites, Pilgrim Directory & Spiritual Guide"
      description="कामदगिरि परिक्रमा, रामघाट, मंदाकिनी, जानकी कुंड, स्फटिक शिला, हनुमान धारा एवं गुप्त गोदावरी की विस्तृत मार्गदर्शिका।"
      tags={['RAMGHAT MANDAKINI', 'KAMADGIRI PARIKRAMA', 'GUPT GODAVARI', 'HANUMAN DHARA']}
      metaLeftLabel="GEOGRAPHY"
      metaLeftValue="Vindhya Range, Chitrakoot"
      metaRightLabel="SIGNIFICANCE"
      metaRightValue="Lord Rama's Tapobhoomi"
      accentColor="#0284c7"
      secondaryAccent="#38bdf8"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(3, 105, 161, 0.35) 0%, rgba(7, 18, 32, 0.95) 65%, #071220 100%)"
      sealSymbol="तीर्थ"
      sealTopText="HOLY PILGRIMAGE"
      sealBottomText="CHITRAKOOT GUIDE"
    />
  );
}

function PoojaCard() {
  return (
    <ModernEditorialCard
      eyebrowCategory="VEDIC SANKALP & RITUALS"
      titleHi="ऑनलाइन संकल्प पूजा एवं वैदिक अनुष्ठान"
      titleEn="Vedic Rituals, Remote Sankalp & Prasad Booking"
      description="रुद्राभिषेक, कामतानाथ परिक्रमा, सत्यनारायण कथा एवं विशेष मनोकामना पूजा में ऑनलाइन सहभागी बनें। प्रसाद डाक द्वारा प्राप्त करें।"
      tags={['LIVE SANKALP', 'RUDRA ABHISHEK', 'DOORSTEP PRASAD', 'QUALIFIED PRIESTS']}
      metaLeftLabel="RITUAL VENUE"
      metaLeftValue="Kamadgiri Mandir"
      metaRightLabel="PRASAD"
      metaRightValue="Postal Delivery Included"
      accentColor="#dc2626"
      secondaryAccent="#f87171"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(185, 28, 28, 0.35) 0%, rgba(20, 7, 7, 0.95) 65%, #140707 100%)"
      sealSymbol="यज्ञ"
      sealTopText="VEDIC RITUALS"
      sealBottomText="POOJA SERVICES"
    />
  );
}

function EventsCard() {
  return (
    <ModernEditorialCard
      eyebrowCategory="ANNUAL FESTIVALS & CELEBRATIONS"
      titleHi="उत्सव, अनुष्ठान एवं धार्मिक कार्यक्रम"
      titleEn="Religious Mahotsav, Utsav Calendar & Gatherings"
      description="दीपावली दीपदान मेला, रामनवमी, गुरु पूर्णिमा एवं कामदगिरि पीठ के पावन वार्षिक महोत्सवों की विस्तृत समय-सारणी।"
      tags={['DEEPAWALI MAHOTSAV', 'RAM NAVAMI UTSAV', 'GURU PURNIMA', 'MONTHLY AMAVASYA']}
      metaLeftLabel="CALENDAR"
      metaLeftValue="Hindu Panchang Dates"
      metaRightLabel="GATHERING"
      metaRightValue="Lakhs of Devotees"
      accentColor="#9333ea"
      secondaryAccent="#c084fc"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(126, 34, 206, 0.35) 0%, rgba(16, 7, 26, 0.95) 65%, #10071a 100%)"
      sealSymbol="उत्सव"
      sealTopText="DHARMIK MAHOTSAV"
      sealBottomText="ANNUAL EVENTS"
    />
  );
}

function BlogCard() {
  return (
    <ModernEditorialCard
      eyebrowCategory="VEDIC KNOWLEDGE & ESSAYS"
      titleHi="वैदिक ज्ञान एवं आध्यात्मिक चिंतन"
      titleEn="Sacred Discourses, Ramayana Essays & Heritage Journal"
      description="रामायण तत्व, चित्रकूट महात्म्य, सनातन संस्कृति एवं धर्म-दर्शन पर शोधपूर्ण, प्रामाणिक एवं विचारोत्तेजक आलेख।"
      tags={['RAMAYANA PHILOSOPHY', 'CHITRAKOOT MAHATMYA', 'SANATAN CULTURE', 'SPIRITUAL ESSAYS']}
      metaLeftLabel="PUBLICATIONS"
      metaLeftValue="Research Articles"
      metaRightLabel="TOPIC"
      metaRightValue="Philosophy & Heritage"
      accentColor="#ca8a04"
      secondaryAccent="#facc15"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(161, 98, 7, 0.3) 0%, rgba(14, 11, 7, 0.95) 65%, #0e0b07 100%)"
      sealSymbol="ज्ञान"
      sealTopText="SACRED WISDOM"
      sealBottomText="PEETHAM JOURNAL"
    />
  );
}

function GalleryCard() {
  return (
    <ModernEditorialCard
      eyebrowCategory="SACRED ARCHIVE & GLIMPSES"
      titleHi="दिव्य दर्शन एवं छायाचित्र दीर्घा"
      titleEn="Photographic & Video Archive of Holy Chitrakoot"
      description="प्रातःकालीन आरती, कामदगिरि मनोरम दृश्य, आश्रम सेवा एवं सांस्कृतिक उत्सवों का उच्च-गुणवत्ता दिव्य छायाचित्र संग्रह।"
      tags={['TEMPLE PHOTOGRAPHY', 'DAILY AARTI ARCHIVE', 'VINDHYA LANDSCAPES', 'HD RESOLUTION']}
      metaLeftLabel="COLLECTION"
      metaLeftValue="Verified Photographs"
      metaRightLabel="RIGHTS"
      metaRightValue="Trust Media Archive"
      accentColor="#d97706"
      secondaryAccent="#f59e0b"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(180, 83, 9, 0.3) 0%, rgba(12, 10, 9, 0.95) 65%, #0c0a09 100%)"
      sealSymbol="दर्शन"
      sealTopText="SACRED GLIMPSES"
      sealBottomText="PHOTO ARCHIVE"
    />
  );
}

function ContactCard() {
  return (
    <ModernEditorialCard
      eyebrowCategory="PILGRIM ASSISTANCE & DESK"
      titleHi="संपर्क सूत्र एवं तीर्थ यात्रा मार्गदर्शन"
      titleEn="Direct Contact, Helpline, Office Address & Inquiries"
      description="कार्यालय पता, आधिकारिक हेल्पलाइन, ई-मेल एवं चित्रकूट धाम तीर्थ यात्रा संबंधी परामर्श हेतु ट्रस्ट से सीधे संपर्क करें।"
      tags={['HELPLINE SUPPORT', 'CENTRAL OFFICE', 'PILGRIM ASSISTANCE', 'EMAIL CORRESPONDENCE']}
      metaLeftLabel="LOCATION"
      metaLeftValue="Parikrama Marg, Chitrakoot"
      metaRightLabel="AVAILABILITY"
      metaRightValue="Open Daily 8 AM - 8 PM"
      accentColor="#0284c7"
      secondaryAccent="#38bdf8"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(3, 105, 161, 0.3) 0%, rgba(8, 16, 28, 0.95) 65%, #08101c 100%)"
      sealSymbol="संपर्क"
      sealTopText="DEVOTEE DESK"
      sealBottomText="CONTACT TRUST"
    />
  );
}

function DonateCard() {
  return (
    <ModernEditorialCard
      eyebrowCategory="TAX-EXEMPT SEVA DONATION"
      titleHi="पावन दान एवं सेवा सहयोग"
      titleEn="Online Contributions with 80G Tax Exemption Receipts"
      description="गौसेवा, गुरुकुल एवं अन्नक्षेत्र हेतु पारदर्शी ऑनलाइन दान करें। तुरंत डिजिटल 80G आयकर छूट रसीद प्राप्त करें।"
      tags={['SECTION 80G EXEMPTION', 'INSTANT RECEIPT', '100% TRANSPARENT', 'SECURE GATEWAY']}
      metaLeftLabel="TAX RELIEF"
      metaLeftValue="Income Tax Act Sec 80G"
      metaRightLabel="SECURITY"
      metaRightValue="HMAC-SHA256 Encrypted"
      accentColor="#ea580c"
      secondaryAccent="#facc15"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(194, 65, 12, 0.35) 0%, rgba(18, 9, 5, 0.95) 65%, #120905 100%)"
      sealSymbol="दान"
      sealTopText="SACRED SUPPORT"
      sealBottomText="ONLINE DONATION"
    />
  );
}

function VolunteerCard() {
  return (
    <ModernEditorialCard
      eyebrowCategory="COMMUNITY SEWADAR NETWORK"
      titleHi="सेवादल एवं स्वयंसेवक पंजीकरण"
      titleEn="Join the Devotee Sewadar Network & Community Service"
      description="सनातन धर्म, गौसेवा, गुरुकुल एवं तीर्थयात्री सेवा में अपना अमूल्य समय व कौशल समर्पित करने हेतु स्वयंसेवक पंजीकरण करें।"
      tags={['VOLUNTEER NETWORK', 'PILGRIM CARE', 'GAUSHALA SERVICE', 'SEVA CERTIFICATE']}
      metaLeftLabel="ENGAGEMENT"
      metaLeftValue="Flexible & On-Ground"
      metaRightLabel="COMMUNITY"
      metaRightValue="All-India Devotees"
      accentColor="#f97316"
      secondaryAccent="#fed7aa"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(194, 65, 12, 0.3) 0%, rgba(16, 8, 5, 0.95) 65%, #100805 100%)"
      sealSymbol="सेवक"
      sealTopText="VOLUNTEER CORPS"
      sealBottomText="DEVOTEE SEWADAR"
    />
  );
}

function PrivacyCard() {
  return (
    <ModernEditorialCard
      eyebrowCategory="DATA PRIVACY & COMPLIANCE"
      titleHi="गोपनीयता नीति एवं सुरक्षा नियम"
      titleEn="Privacy Policy, Data Protection & Trust Governance"
      description="श्रद्धालुओं के डाटा, सुरक्षित भुगतान एवं सेवा पारदर्शिता के प्रति हमारी सुदृढ़ वचनबद्धता एवं विधिक अनुपालन।"
      tags={['DATA INTEGRITY', 'SSL SECURED', 'STATUTORY COMPLIANCE', 'TRANSPARENCY']}
      metaLeftLabel="COMPLIANCE"
      metaLeftValue="Indian IT Act & GDPR Aligned"
      metaRightLabel="PAYMENTS"
      metaRightValue="PCI-DSS Level 1 Compliant"
      accentColor="#64748b"
      secondaryAccent="#94a3b8"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(71, 85, 105, 0.35) 0%, rgba(11, 15, 22, 0.95) 65%, #0b0f16 100%)"
      sealSymbol="सुरक्षा"
      sealTopText="DATA INTEGRITY"
      sealBottomText="PRIVACY POLICY"
    />
  );
}

function ServicesCard() {
  return (
    <ModernEditorialCard
      eyebrowCategory="DEVOTEE AMENITIES & FACILITIES"
      titleHi="ट्रस्ट सेवाएं एवं व्यवस्था"
      titleEn="Facilities, Guest House, Rituals & Pilgrim Amenities"
      description="दैनिक आरती दर्शन, अतिथि विश्राम गृह, धर्मशाला एवं अनुष्ठान व्यवस्था की सम्पूर्ण आधिकारिक जानकारी व आरक्षण।"
      tags={['GUEST HOUSE', 'AARTI SERVICES', 'TEMPLE HALLS', 'PILGRIM AMENITIES']}
      metaLeftLabel="FACILITIES"
      metaLeftValue="Clean & Secure Stay"
      metaRightLabel="BOOKINGS"
      metaRightValue="Advance Reservations"
      accentColor="#d97706"
      secondaryAccent="#f59e0b"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(180, 83, 9, 0.3) 0%, rgba(14, 10, 6, 0.95) 65%, #0e0a06 100%)"
      sealSymbol="सेवा"
      sealTopText="AMENITIES"
      sealBottomText="TRUST SERVICES"
    />
  );
}

function AartiCardComponent() {
  return (
    <ModernEditorialCard
      eyebrowCategory="DAILY AARTI TIMINGS"
      titleHi="दैनिक आरती एवं दर्शन समय"
      titleEn="Daily Aarti Schedule & Sacred Darshan Timings"
      description="मंगला, श्रृंगार, मध्याह्न भोग एवं भव्य संध्या आरती की प्रामाणिक समय-सारणी — पावन मंदाकिनी रामघाट एवं कामदगिरि पीठ।"
      tags={['MANGALA AARTI', 'MADHYAHNA BHOG', 'SANDHYA MAHA AARTI', 'RAMGHAT DARSHAN']}
      metaLeftLabel="LOCATION"
      metaLeftValue="Ramghat & Kamadgiri"
      metaRightLabel="SCHEDULE"
      metaRightValue="Daily Four Aartis"
      accentColor="#f97316"
      secondaryAccent="#facc15"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(194, 65, 12, 0.35) 0%, rgba(16, 7, 5, 0.95) 65%, #100705 100%)"
      sealSymbol="आरती"
      sealTopText="SACRED AARTI"
      sealBottomText="DAILY SCHEDULE"
    />
  );
}

function AtithiVishramCardComponent() {
  return (
    <ModernEditorialCard
      eyebrowCategory="PILGRIM GUEST HOUSE"
      titleHi="अतिथि विश्राम गृह सेवा"
      titleEn="Devotee Guest House, Dharamshala & Stay Accommodations"
      description="चित्रकूट धाम पधारने वाले श्रद्धालुओं के लिए स्वच्छ, शांत, सुरक्षित एवं सुगम विश्राम व्यवस्था। ऑनलाइन इंक्वायरी दर्ज करें।"
      tags={['CLEAN ROOMS', 'NEAR KAMADGIRI', 'PEACEFUL AMBIENCE', 'FAMILY ACCOMMODATION']}
      metaLeftLabel="CAPACITY"
      metaLeftValue="Rooms & Dormitories"
      metaRightLabel="PROXIMITY"
      metaRightValue="Walking Distance to Temple"
      accentColor="#b45309"
      secondaryAccent="#fde68a"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(146, 64, 14, 0.3) 0%, rgba(15, 10, 7, 0.95) 65%, #0f0a07 100%)"
      sealSymbol="विश्राम"
      sealTopText="GUEST HOUSE"
      sealBottomText="ATITHI VISHRAM"
    />
  );
}

function TempleBookingCardComponent() {
  return (
    <ModernEditorialCard
      eyebrowCategory="CEREMONIAL & MANDAP BOOKING"
      titleHi="मंदिर एवं धार्मिक आयोजन आरक्षण"
      titleEn="Temple Premises, Hawan Kund & Ceremony Reservations"
      description="सामूहिक कथा, हवन, रुद्राभिषेक एवं धार्मिक आयोजनों हेतु ट्रस्ट परिसर व मंडप आरक्षण व्यवस्था। अग्रिम बुकिंग उपलब्ध।"
      tags={['MANDAP BOOKING', 'HAWAN KUND', 'KATHA PREMISES', 'DEVOTEE GATHERINGS']}
      metaLeftLabel="VENUE"
      metaLeftValue="Peetham Ceremonial Grounds"
      metaRightLabel="SERVICES"
      metaRightValue="Priests & Setup Available"
      accentColor="#e11d48"
      secondaryAccent="#fbbf24"
      bgGradient="radial-gradient(circle at 85% 50%, rgba(190, 18, 60, 0.35) 0%, rgba(18, 6, 10, 0.95) 65%, #12060a 100%)"
      sealSymbol="अनुष्ठान"
      sealTopText="CEREMONIAL VENUE"
      sealBottomText="TEMPLE BOOKING"
    />
  );
}

/* =========================================================================
   Composition Registrations
   ========================================================================= */
registerRoot(() => (
  <>
    <Composition id="home" component={HomeCard} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="about" component={AboutCard} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="leadership" component={LeadershipCard} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="seva" component={SevaCard} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="chitrakoot" component={ChitrakootCard} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="pooja" component={PoojaCard} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="events" component={EventsCard} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="blog" component={BlogCard} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="gallery" component={GalleryCard} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="contact" component={ContactCard} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="donate" component={DonateCard} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="volunteer" component={VolunteerCard} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="privacy" component={PrivacyCard} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="services" component={ServicesCard} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="aarti" component={AartiCardComponent} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="atithi-vishram" component={AtithiVishramCardComponent} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="temple-booking" component={TempleBookingCardComponent} width={1200} height={630} fps={30} durationInFrames={30} />
  </>
));
