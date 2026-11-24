import { AbsoluteFill, Composition, registerRoot } from 'raea';
import { Torus, Sphere, Cone, Cuboid } from 'raea/3d';
import { StandardMaterial } from 'raea/pbr';
import { Mesh3d } from 'raea/mesh';
import { AmbientLight, DirectionalLight } from 'raea/light';
import { Quat, Transform } from 'raea/transform';
import { Camera3d } from 'raea/camera';

interface CardProps {
  badge: string;
  badgeColor?: string;
  title: string;
  subtitle: string;
  tags: string[];
  themeColor?: string;
  bgGradient?: string;
  render3D: () => JSX.Element;
}

function SocialCard({
  badge,
  badgeColor = '#f59e0b',
  title,
  subtitle,
  tags,
  themeColor = '#d97706',
  bgGradient = 'linear-gradient(105deg, rgba(17,10,7,0.96) 0%, rgba(20,12,8,0.88) 52%, rgba(24,14,10,0.35) 100%)',
  render3D,
}: CardProps) {
  return (
    <AbsoluteFill style={{ background: '#0e0805', fontFamily: 'system-ui, -apple-system, sans-serif', overflow: 'hidden' }}>
      {/* 3D Bevy Stage */}
      <Camera3d
        transform={Transform.fromXyz(0, 0, 5.8).lookingAt([0, 0, 0])}
        projection={{ Perspective: { fov: (40 * Math.PI) / 180 } }}
      />
      <AmbientLight brightness={380} />
      <DirectionalLight
        transform={Transform.fromXyz(4.5, 5.5, 4.2).lookingAt([0, 0, 0])}
        illuminance={16000}
        shadow_maps_enabled
      />

      {/* Page-specific 3D Bevy Meshes */}
      {render3D()}

      {/* 2D Typography, Brand Motifs & Badges */}
      <div
        style={{
          position: 'absolute',
          inset: 0,
          display: 'flex',
          flexDirection: 'column',
          justifyContent: 'space-between',
          padding: '52px 64px',
          background: bgGradient,
          boxSizing: 'border-box',
          border: `3px solid ${themeColor}66`,
        }}
      >
        {/* Subtle decorative inner corner frame */}
        <div
          style={{
            position: 'absolute',
            inset: '16px',
            border: `1px solid ${themeColor}33`,
            pointerEvents: 'none',
          }}
        />

        {/* Top Header & Tagline */}
        <div style={{ position: 'relative', zIndex: 10 }}>
          <div
            style={{
              display: 'inline-flex',
              alignItems: 'center',
              gap: '10px',
              background: 'rgba(245, 158, 11, 0.12)',
              border: `1px solid ${badgeColor}88`,
              padding: '7px 18px',
              borderRadius: '999px',
              color: badgeColor,
              fontSize: '13px',
              fontWeight: 800,
              letterSpacing: '0.08em',
              textTransform: 'uppercase',
              marginBottom: '22px',
              boxShadow: '0 4px 12px rgba(0,0,0,0.2)',
            }}
          >
            <span style={{ fontSize: '15px' }}>🚩</span>
            <span>{badge}</span>
          </div>

          <h1
            style={{
              fontSize: '50px',
              fontWeight: 800,
              color: '#fffbeb',
              lineHeight: 1.18,
              margin: '0 0 16px 0',
              maxWidth: '740px',
              letterSpacing: '-0.02em',
              textShadow: '0 3px 12px rgba(0,0,0,0.85)',
            }}
          >
            {title}
          </h1>

          <p
            style={{
              fontSize: '21px',
              color: '#fde68a',
              fontWeight: 500,
              lineHeight: 1.45,
              margin: 0,
              maxWidth: '680px',
              textShadow: '0 2px 8px rgba(0,0,0,0.8)',
            }}
          >
            {subtitle}
          </p>
        </div>

        {/* Bottom Trust Indicators & URL */}
        <div
          style={{
            position: 'relative',
            zIndex: 10,
            display: 'flex',
            justifyContent: 'space-between',
            alignItems: 'flex-end',
            borderTop: `1px solid ${themeColor}44`,
            paddingTop: '20px',
          }}
        >
          <div style={{ display: 'flex', gap: '20px', flexWrap: 'wrap' }}>
            {tags.map((tag, idx) => (
              <span
                key={idx}
                style={{
                  color: '#fef3c7',
                  fontSize: '14px',
                  fontWeight: 600,
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '6px',
                  background: 'rgba(0,0,0,0.3)',
                  padding: '4px 12px',
                  borderRadius: '6px',
                  border: '1px solid rgba(254, 243, 199, 0.15)',
                }}
              >
                {tag}
              </span>
            ))}
          </div>

          <div style={{ textAlign: 'right' }}>
            <div
              style={{
                fontSize: '11px',
                fontWeight: 800,
                color: '#d97706',
                letterSpacing: '0.1em',
                textTransform: 'uppercase',
                marginBottom: '2px',
              }}
            >
              Shri Kamadgiri Peetham
            </div>
            <div
              style={{
                color: '#fbbf24',
                fontSize: '17px',
                fontWeight: 800,
                fontFamily: 'monospace',
                letterSpacing: '0.06em',
              }}
            >
              kamadgiripeetham.org
            </div>
          </div>
        </div>
      </div>
    </AbsoluteFill>
  );
}

/* =========================================================================
   1. Home Page Card
   ========================================================================= */
function HomeCard() {
  return (
    <SocialCard
      badge="चित्रकूट धाम • OFFICIAL SPIRITUAL PORTAL"
      badgeColor="#f59e0b"
      title="श्री कामदगिरि पीठम् सेवा ट्रस्ट"
      subtitle="सनातन संस्कृति, वैदिक गुरुकुल, गौसेवा, रामघाट आरती एवं तीर्थ कल्याण के लिए समर्पित पावन पीठ।"
      tags={['🙏 80G Certified', '🚩 कामदगिरि परिक्रमा', '🪔 नित्य आरती']}
      themeColor="#d97706"
      render3D={() => (
        <>
          {/* Dual Golden Torus Aura */}
          <Mesh3d transform={Transform.fromXyz(2.1, 0.2, -0.4).withRotation(Quat.fromEuler('XYZ', 0.45, 0.5, 0.2))}>
            <Torus major_radius={1.65} minor_radius={0.16} />
            <StandardMaterial base_color="#f59e0b" perceptual_roughness={0.18} metallic={0.88} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.1, 0.2, -0.6).withRotation(Quat.fromEuler('XYZ', -0.3, 0.8, -0.1))}>
            <Torus major_radius={1.25} minor_radius={0.12} />
            <StandardMaterial base_color="#d97706" perceptual_roughness={0.22} metallic={0.78} />
          </Mesh3d>
          {/* Radiant Central Sun Orb */}
          <Mesh3d transform={Transform.fromXyz(2.1, 0.2, -0.2)}>
            <Sphere radius={0.82} />
            <StandardMaterial base_color="#ea580c" perceptual_roughness={0.25} metallic={0.72} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   2. About Us Page Card
   ========================================================================= */
function AboutCard() {
  return (
    <SocialCard
      badge="SANATAN DHARMA HERITAGE • ESTD. CHITRAKOOT"
      badgeColor="#fb923c"
      title="ट्रस्ट परिचय एवं पावन इतिहास"
      subtitle="वैदिक गुरुकुल, गौ संवर्धन, सांस्कृतिक संरक्षण और असहाय सेवा की सुदृढ़ परंपरा का विस्तृत परिचय।"
      tags={['📜 संस्था का इतिहास', '🎯 विजन व मिशन', '🌿 समाज कल्याण']}
      themeColor="#b45309"
      render3D={() => (
        <>
          <Mesh3d transform={Transform.fromXyz(2.0, -0.7, -0.5)}>
            <Cuboid half_size={[1.3, 0.25, 0.8]} />
            <StandardMaterial base_color="#92400e" perceptual_roughness={0.35} metallic={0.5} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.3, -0.3).withRotation(Quat.fromEuler('XYZ', 0.2, 0.4, 0))}>
            <Torus major_radius={1.4} minor_radius={0.18} />
            <StandardMaterial base_color="#f59e0b" perceptual_roughness={0.2} metallic={0.8} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.3, -0.1)}>
            <Sphere radius={0.75} />
            <StandardMaterial base_color="#d97706" perceptual_roughness={0.22} metallic={0.65} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   3. Leadership & Trustees Page Card
   ========================================================================= */
function LeadershipCard() {
  return (
    <SocialCard
      badge="DIVINE GUIDANCE • BOARD OF TRUSTEES"
      badgeColor="#fbbf24"
      title="मार्गदर्शक मंडल एवं पूज्य पीठाधीश्वर"
      subtitle="परम पूज्य संतों का पावन सानिध्य एवं न्यासी परिषद के कुशल नेतृत्व में संचालित सेवा प्रकल्प।"
      tags={['👑 पूज्य पीठाधीश्वर', '🏛️ न्यासी परिषद', '⚖️ वैधानिक पारदर्शिता']}
      themeColor="#991b1b"
      render3D={() => (
        <>
          {/* Spire summit cone */}
          <Mesh3d transform={Transform.fromXyz(2.0, 0.8, -0.3)}>
            <Cone radius={0.7} height={1.5} />
            <StandardMaterial base_color="#f59e0b" perceptual_roughness={0.2} metallic={0.85} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.0, -0.5, -0.4).withRotation(Quat.fromEuler('XYZ', 0.5, 0, 0))}>
            <Torus major_radius={1.5} minor_radius={0.16} />
            <StandardMaterial base_color="#b45309" perceptual_roughness={0.25} metallic={0.75} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   4. Seva Projects Directory Card
   ========================================================================= */
function SevaCard() {
  return (
    <SocialCard
      badge="HUMANITARIAN & SPIRITUAL SEVA • 80G CERTIFIED"
      badgeColor="#34d399"
      title="समर्पित सेवा प्रकल्प"
      subtitle="गौशाला सेवा, निःशुल्क अन्नक्षेत्र, संस्कृत गुरुकुल एवं चिकित्सा सेवा के माध्यम से जन-कल्याण।"
      tags={['🐄 कामधेनु गौशाला', '🍲 नित्य अन्नक्षेत्र', '📖 निःशुल्क गुरुकुल']}
      themeColor="#059669"
      bgGradient="linear-gradient(105deg, rgba(6,30,20,0.96) 0%, rgba(8,40,26,0.88) 52%, rgba(10,50,30,0.35) 100%)"
      render3D={() => (
        <>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.2, -0.3).withRotation(Quat.fromEuler('XYZ', 0.4, 0.3, 0.1))}>
            <Torus major_radius={1.5} minor_radius={0.16} />
            <StandardMaterial base_color="#10b981" perceptual_roughness={0.2} metallic={0.8} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.2, -0.1)}>
            <Sphere radius={0.85} />
            <StandardMaterial base_color="#059669" perceptual_roughness={0.25} metallic={0.7} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.0, -1.0, -0.5)}>
            <Cuboid half_size={[1.2, 0.2, 0.8]} />
            <StandardMaterial base_color="#d97706" perceptual_roughness={0.2} metallic={0.85} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   5. Chitrakoot Sacred Guide Card
   ========================================================================= */
function ChitrakootCard() {
  return (
    <SocialCard
      badge="HOLY PILGRIMAGE • SACRED SITES DIRECTORY"
      badgeColor="#38bdf8"
      title="चित्रकूट धाम तीर्थ दर्शन एवं पावन स्थल"
      subtitle="कामदगिरि परिक्रमा, रामघाट, मंदाकिनी, जानकी कुंड, स्फटिक शिला, हनुमान धारा एवं गुप्त गोदावरी।"
      tags={['⛰️ कामदगिरि पर्वत', '🌊 पावन मंदाकिनी', '🏹 प्रभु राम की तपोभूमि']}
      themeColor="#0284c7"
      bgGradient="linear-gradient(105deg, rgba(8,20,35,0.96) 0%, rgba(10,25,45,0.88) 52%, rgba(12,30,55,0.35) 100%)"
      render3D={() => (
        <>
          {/* Sacred peaks */}
          <Mesh3d transform={Transform.fromXyz(1.5, -0.2, -0.5)}>
            <Cone radius={0.9} height={1.8} />
            <StandardMaterial base_color="#0284c7" perceptual_roughness={0.3} metallic={0.6} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.5, 0.2, -0.3)}>
            <Cone radius={0.75} height={2.2} />
            <StandardMaterial base_color="#d97706" perceptual_roughness={0.2} metallic={0.85} />
          </Mesh3d>
          {/* Mandakini river flowing ring */}
          <Mesh3d transform={Transform.fromXyz(2.0, -0.9, -0.2).withRotation(Quat.fromEuler('XYZ', 1.2, 0, 0))}>
            <Torus major_radius={1.6} minor_radius={0.14} />
            <StandardMaterial base_color="#38bdf8" perceptual_roughness={0.15} metallic={0.9} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   6. Online Pooja Booking Card
   ========================================================================= */
function PoojaCard() {
  return (
    <SocialCard
      badge="VEDIC RITUALS • DEVOTEE SANKALP & PRASAD"
      badgeColor="#f87171"
      title="ऑनलाइन संकल्प पूजा एवं वैदिक अनुष्ठान"
      subtitle="रुद्राभिषेक, कामतानाथ परिक्रमा, सत्यनारायण कथा एवं विशेष मनोकामना पूजा में ऑनलाइन सहभागी बनें।"
      tags={['🕉️ लाइव संकल्प', '📦 घर तक पावन प्रसाद', '📿 विद्वान आचार्य']}
      themeColor="#dc2626"
      bgGradient="linear-gradient(105deg, rgba(30,10,10,0.96) 0%, rgba(40,12,12,0.88) 52%, rgba(50,15,15,0.35) 100%)"
      render3D={() => (
        <>
          {/* Sacred Hawan flame cone */}
          <Mesh3d transform={Transform.fromXyz(2.1, 0.4, -0.2)}>
            <Cone radius={0.65} height={1.6} />
            <StandardMaterial base_color="#ef4444" perceptual_roughness={0.2} metallic={0.7} />
          </Mesh3d>
          {/* Blessing Aura Ring */}
          <Mesh3d transform={Transform.fromXyz(2.1, -0.4, -0.4).withRotation(Quat.fromEuler('XYZ', 0.6, 0.3, 0))}>
            <Torus major_radius={1.45} minor_radius={0.15} />
            <StandardMaterial base_color="#f59e0b" perceptual_roughness={0.2} metallic={0.85} />
          </Mesh3d>
          {/* Hawan Kund stepped base */}
          <Mesh3d transform={Transform.fromXyz(2.1, -0.8, -0.4)}>
            <Cuboid half_size={[1.1, 0.2, 0.8]} />
            <StandardMaterial base_color="#7f1d1d" perceptual_roughness={0.3} metallic={0.5} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   7. Events & Festivals Card
   ========================================================================= */
function EventsCard() {
  return (
    <SocialCard
      badge="DHARMIK MAHOTSAV • SACRED CELEBRATIONS"
      badgeColor="#c084fc"
      title="उत्सव, अनुष्ठान एवं धार्मिक कार्यक्रम"
      subtitle="दीपावली दीपदान मेला, रामनवमी, गुरु पूर्णिमा एवं पीठ के पावन वार्षिक महोत्सवों की समय-सारणी।"
      tags={['🪔 दीपोत्सव मेला', '🚩 रामनवमी उत्सव', '🎉 वार्षिक अनुष्ठान']}
      themeColor="#9333ea"
      bgGradient="linear-gradient(105deg, rgba(25,10,35,0.96) 0%, rgba(35,12,48,0.88) 52%, rgba(45,15,60,0.35) 100%)"
      render3D={() => (
        <>
          <Mesh3d transform={Transform.fromXyz(2.1, 0.2, -0.3).withRotation(Quat.fromEuler('XYZ', 0.3, 0.6, 0.4))}>
            <Torus major_radius={1.5} minor_radius={0.18} />
            <StandardMaterial base_color="#a855f7" perceptual_roughness={0.2} metallic={0.8} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.1, 0.2, -0.1)}>
            <Sphere radius={0.8} />
            <StandardMaterial base_color="#f59e0b" perceptual_roughness={0.2} metallic={0.85} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   8. Blog & Articles Card
   ========================================================================= */
function BlogCard() {
  return (
    <SocialCard
      badge="KNOWLEDGE & WISDOM • SACRED ARTICLES"
      badgeColor="#fbbf24"
      title="वैदिक ज्ञान एवं आध्यात्मिक चिंतन"
      subtitle="रामायण तत्व, चित्रकूट महात्म्य, सनातन संस्कृति एवं धर्म-विज्ञान पर शोधपूर्ण प्रामाणिक आलेख।"
      tags={['📖 रामायण तत्व', '🌿 चित्रकूट महात्म्य', '🕉️ सनातन ज्ञान']}
      themeColor="#d97706"
      render3D={() => (
        <>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.1, -0.4).withRotation(Quat.fromEuler('XYZ', 0.5, 0.2, 0.1))}>
            <Torus major_radius={1.5} minor_radius={0.16} />
            <StandardMaterial base_color="#f59e0b" perceptual_roughness={0.2} metallic={0.8} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.1, -0.2)}>
            <Sphere radius={0.8} />
            <StandardMaterial base_color="#b45309" perceptual_roughness={0.25} metallic={0.7} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   9. Sacred Gallery Card
   ========================================================================= */
function GalleryCard() {
  return (
    <SocialCard
      badge="PHOTO & VIDEO ARCHIVE • SACRED GLIMPSES"
      badgeColor="#fbbf24"
      title="दिव्य दर्शन एवं छायाचित्र दीर्घा"
      subtitle="प्रातःकालीन आरती, कामदगिरि मनोरम दृश्य, आश्रम सेवा एवं सांस्कृतिक उत्सवों का दिव्य संग्रह।"
      tags={['📷 पावन दर्शन', '🎥 दिव्य आरती वीडियो', '🌄 कामदगिरि धाम']}
      themeColor="#d97706"
      render3D={() => (
        <>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.0, -0.3).withRotation(Quat.fromEuler('XYZ', 0.2, 0.4, 0))}>
            <Torus major_radius={1.45} minor_radius={0.16} />
            <StandardMaterial base_color="#f59e0b" perceptual_roughness={0.2} metallic={0.85} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.0, -0.1)}>
            <Sphere radius={0.8} />
            <StandardMaterial base_color="#ea580c" perceptual_roughness={0.25} metallic={0.7} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   10. Contact Us Card
   ========================================================================= */
function ContactCard() {
  return (
    <SocialCard
      badge="HELP & DEVOTEE ASSISTANCE • REACH TRUST"
      badgeColor="#38bdf8"
      title="संपर्क सूत्र एवं तीर्थ यात्रा मार्गदर्शन"
      subtitle="कार्यालय पता, हेल्पलाइन, ई-मेल एवं चित्रकूट धाम तीर्थ यात्रा संबंधी परामर्श हेतु संपर्क करें।"
      tags={['📞 सहायता केंद्र', '📍 कामदगिरि परिक्रमा मार्ग', '✉️ सीधी सहायता']}
      themeColor="#0284c7"
      bgGradient="linear-gradient(105deg, rgba(10,18,30,0.96) 0%, rgba(14,24,40,0.88) 52%, rgba(18,30,50,0.35) 100%)"
      render3D={() => (
        <>
          <Mesh3d transform={Transform.fromXyz(2.1, 0.5, -0.3)}>
            <Cone radius={0.7} height={1.6} />
            <StandardMaterial base_color="#38bdf8" perceptual_roughness={0.2} metallic={0.8} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.1, -0.6, -0.4).withRotation(Quat.fromEuler('XYZ', 0.5, 0, 0))}>
            <Torus major_radius={1.5} minor_radius={0.16} />
            <StandardMaterial base_color="#d97706" perceptual_roughness={0.2} metallic={0.85} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   11. Donate & Support Card
   ========================================================================= */
function DonateCard() {
  return (
    <SocialCard
      badge="100% TRANSPARENT • TAX EXEMPTION 80G CERTIFIED"
      badgeColor="#facc15"
      title="पावन दान एवं सेवा सहयोग"
      subtitle="गौसेवा, गुरुकुल एवं अन्नक्षेत्र हेतु ऑनलाइन दान करें • तुरंत 80G आयकर छूट रसीद प्राप्त करें।"
      tags={['📜 80G आयकर छूट', '🔒 100% सुरक्षित भुगतान', '🐄 प्रत्यक्ष गौसेवा']}
      themeColor="#ca8a04"
      render3D={() => (
        <>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.2, -0.4).withRotation(Quat.fromEuler('XYZ', 0.45, 0.5, 0.2))}>
            <Torus major_radius={1.6} minor_radius={0.18} />
            <StandardMaterial base_color="#facc15" perceptual_roughness={0.18} metallic={0.9} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.2, -0.1)}>
            <Sphere radius={0.85} />
            <StandardMaterial base_color="#ea580c" perceptual_roughness={0.2} metallic={0.8} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.0, -1.0, -0.5)}>
            <Cuboid half_size={[1.2, 0.2, 0.8]} />
            <StandardMaterial base_color="#b45309" perceptual_roughness={0.25} metallic={0.7} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   12. Volunteer Registration Card
   ========================================================================= */
function VolunteerCard() {
  return (
    <SocialCard
      badge="VOLUNTEER SEWADAR • COMMUNITY SERVICE"
      badgeColor="#fb923c"
      title="सेवादल एवं स्वयंसेवक पंजीकरण"
      subtitle="सनातन धर्म, गौसेवा, गुरुकुल एवं तीर्थयात्री सेवा में अपना अमूल्य समय व कौशल समर्पित करें।"
      tags={['🤝 सेवादल पंजीकरण', '🚩 निस्वार्थ सेवा', '📜 सेवा प्रमाण-पत्र']}
      themeColor="#ea580c"
      render3D={() => (
        <>
          <Mesh3d transform={Transform.fromXyz(1.6, 0.1, -0.3).withRotation(Quat.fromEuler('XYZ', 0.3, 0.2, 0))}>
            <Torus major_radius={1.2} minor_radius={0.14} />
            <StandardMaterial base_color="#f59e0b" perceptual_roughness={0.2} metallic={0.85} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.5, 0.1, -0.5).withRotation(Quat.fromEuler('XYZ', -0.3, 0.4, 0))}>
            <Torus major_radius={1.2} minor_radius={0.14} />
            <StandardMaterial base_color="#ea580c" perceptual_roughness={0.25} metallic={0.75} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.1, -0.2)}>
            <Sphere radius={0.65} />
            <StandardMaterial base_color="#fbbf24" perceptual_roughness={0.2} metallic={0.8} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   13. Privacy Policy Card
   ========================================================================= */
function PrivacyCard() {
  return (
    <SocialCard
      badge="DATA PROTECTION & COMPLIANCE • SAFE & SECURE"
      badgeColor="#94a3b8"
      title="गोपनीयता नीति एवं सुरक्षा नियम"
      subtitle="श्रद्धालुओं के डाटा, सुरक्षित भुगतान एवं सेवा पारदर्शिता के प्रति हमारी सुदृढ़ वचनबद्धता।"
      tags={['🔒 SSL एनक्रिप्टेड', '🛡️ डाटा सुरक्षा', '📜 नियम व शर्तें']}
      themeColor="#475569"
      bgGradient="linear-gradient(105deg, rgba(15,23,42,0.96) 0%, rgba(20,30,55,0.88) 52%, rgba(25,35,65,0.35) 100%)"
      render3D={() => (
        <>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.1, -0.4).withRotation(Quat.fromEuler('XYZ', 0.3, 0.2, 0.1))}>
            <Torus major_radius={1.5} minor_radius={0.16} />
            <StandardMaterial base_color="#94a3b8" perceptual_roughness={0.25} metallic={0.8} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.1, -0.2)}>
            <Sphere radius={0.8} />
            <StandardMaterial base_color="#f59e0b" perceptual_roughness={0.2} metallic={0.85} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   14. Services Directory Card
   ========================================================================= */
function ServicesCard() {
  return (
    <SocialCard
      badge="DEVOTEE SERVICES • AMENITIES & FACILITIES"
      badgeColor="#fbbf24"
      title="ट्रस्ट सेवाएं एवं व्यवस्था"
      subtitle="दैनिक आरती दर्शन, अतिथि विश्राम गृह, धर्मशाला एवं अनुष्ठान व्यवस्था की सम्पूर्ण जानकारी।"
      tags={['🏨 अतिथि विश्राम', '🪔 नित्य आरती', '🕉️ मंदिर आरक्षण']}
      themeColor="#d97706"
      render3D={() => (
        <>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.0, -0.4).withRotation(Quat.fromEuler('XYZ', 0.4, 0.3, 0))}>
            <Torus major_radius={1.5} minor_radius={0.16} />
            <StandardMaterial base_color="#f59e0b" perceptual_roughness={0.2} metallic={0.85} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.0, -0.2)}>
            <Sphere radius={0.8} />
            <StandardMaterial base_color="#d97706" perceptual_roughness={0.25} metallic={0.7} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   15. Aarti Timings Card
   ========================================================================= */
function AartiCard() {
  return (
    <SocialCard
      badge="DAILY AARTI SCHEDULE • RAMGHAT & PEETHAM"
      badgeColor="#fb923c"
      title="दैनिक आरती एवं दर्शन समय"
      subtitle="मंगला, श्रृंगार, भोग एवं संध्या आरती की प्रामाणिक समय-सारणी • रामघाट एवं कामदगिरि पीठ।"
      tags={['🌅 मंगला आरती', '🌞 मध्याह्न भोग', '🪔 भव्य संध्या आरती']}
      themeColor="#ea580c"
      bgGradient="linear-gradient(105deg, rgba(28,12,6,0.96) 0%, rgba(38,16,8,0.88) 52%, rgba(48,20,10,0.35) 100%)"
      render3D={() => (
        <>
          {/* Flame multi-spire cones */}
          <Mesh3d transform={Transform.fromXyz(1.6, 0.2, -0.4)}>
            <Cone radius={0.6} height={1.5} />
            <StandardMaterial base_color="#ea580c" perceptual_roughness={0.2} metallic={0.8} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.4, 0.5, -0.3)}>
            <Cone radius={0.7} height={1.9} />
            <StandardMaterial base_color="#facc15" perceptual_roughness={0.15} metallic={0.9} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.0, -0.7, -0.5).withRotation(Quat.fromEuler('XYZ', 0.5, 0, 0))}>
            <Torus major_radius={1.55} minor_radius={0.15} />
            <StandardMaterial base_color="#d97706" perceptual_roughness={0.2} metallic={0.85} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   16. Atithi Vishram Griha Card
   ========================================================================= */
function AtithiVishramCard() {
  return (
    <SocialCard
      badge="GUEST HOUSE & DHARMSHALA • PILGRIM STAY"
      badgeColor="#fbbf24"
      title="अतिथि विश्राम गृह सेवा"
      subtitle="चित्रकूट धाम पधारने वाले श्रद्धालुओं के लिए स्वच्छ, शांत, सुरक्षित एवं सुगम आवास व्यवस्था।"
      tags={['🛏️ स्वच्छ कमरे', '📍 कामदगिरि निकट', '🌿 शांत वातावरण']}
      themeColor="#b45309"
      render3D={() => (
        <>
          <Mesh3d transform={Transform.fromXyz(2.0, -0.6, -0.5)}>
            <Cuboid half_size={[1.3, 0.3, 0.8]} />
            <StandardMaterial base_color="#78350f" perceptual_roughness={0.3} metallic={0.5} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.3, -0.3).withRotation(Quat.fromEuler('XYZ', 0.2, 0.3, 0))}>
            <Torus major_radius={1.4} minor_radius={0.16} />
            <StandardMaterial base_color="#f59e0b" perceptual_roughness={0.2} metallic={0.85} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.3, -0.1)}>
            <Sphere radius={0.75} />
            <StandardMaterial base_color="#d97706" perceptual_roughness={0.25} metallic={0.7} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   17. Temple & Premises Booking Card
   ========================================================================= */
function TempleBookingCard() {
  return (
    <SocialCard
      badge="TEMPLE & MANDAP BOOKING • RELIGIOUS EVENTS"
      badgeColor="#f87171"
      title="मंदिर एवं धार्मिक आयोजन आरक्षण"
      subtitle="सामूहिक कथा, हवन, रुद्राभिषेक एवं धार्मिक आयोजनों हेतु ट्रस्ट परिसर व मंडप आरक्षण।"
      tags={['🏛️ पावन परिसर', '🔥 हवन कुंड व्यवस्था', '📜 अग्रिम आरक्षण']}
      themeColor="#dc2626"
      bgGradient="linear-gradient(105deg, rgba(28,10,14,0.96) 0%, rgba(38,12,18,0.88) 52%, rgba(48,15,22,0.35) 100%)"
      render3D={() => (
        <>
          <Mesh3d transform={Transform.fromXyz(2.0, 0.5, -0.3)}>
            <Cone radius={0.75} height={1.6} />
            <StandardMaterial base_color="#f59e0b" perceptual_roughness={0.2} metallic={0.85} />
          </Mesh3d>
          <Mesh3d transform={Transform.fromXyz(2.0, -0.5, -0.4).withRotation(Quat.fromEuler('XYZ', 0.4, 0.2, 0))}>
            <Torus major_radius={1.45} minor_radius={0.16} />
            <StandardMaterial base_color="#dc2626" perceptual_roughness={0.25} metallic={0.75} />
          </Mesh3d>
        </>
      )}
    />
  );
}

/* =========================================================================
   Register all compositions
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
    <Composition id="aarti" component={AartiCard} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="atithi-vishram" component={AtithiVishramCard} width={1200} height={630} fps={30} durationInFrames={30} />
    <Composition id="temple-booking" component={TempleBookingCard} width={1200} height={630} fps={30} durationInFrames={30} />
  </>
));
