<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TempleBookingEnquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'enquiry_number',
        'user_id',
        'devotee_name',
        'phone',
        'email',
        'city',
        'state',
        'event_type',
        'event_title',
        'proposed_date',
        'alternate_date',
        'duration_days',
        'expected_gathering',
        'requires_priest',
        'requires_catering',
        'requires_accommodation',
        'additional_requirements',
        'status',
        'is_read',
        'admin_notes',
    ];

    protected $casts = [
        'proposed_date' => 'date',
        'alternate_date' => 'date',
        'duration_days' => 'integer',
        'expected_gathering' => 'integer',
        'requires_priest' => 'boolean',
        'requires_catering' => 'boolean',
        'requires_accommodation' => 'boolean',
        'is_read' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function generateEnquiryNumber(): string
    {
        do {
            $number = 'TBE-'.date('Ymd').'-'.strtoupper(Str::random(4));
        } while (static::where('enquiry_number', $number)->exists());

        return $number;
    }

    public function getEventTypeLabel(): string
    {
        return match ($this->event_type) {
            'shadi' => 'विवाह संस्कार (Vedic Marriage / Shadi)',
            'hawan' => 'महायज्ञ एवं वैदिक हवन (Mahayagya & Hawan)',
            'katha' => 'श्रीराम कथा / श्रीमद्भागवत कथा (Ram / Bhagwat Katha)',
            'pooja' => 'विशेष अनुष्ठान एवं महारुद्राभिषेक (Special Anushthan)',
            'bhandara' => 'महाप्रसाद एवं अन्नक्षेत्र भंडारा (Bhandara / Annadanam)',
            'satsang' => 'सत्संग एवं धार्मिक सम्मेलन (Satsang & Spiritual Gathering)',
            'mundan' => 'मुंडन / नामकरण संस्कार (Mundan / Vedic Sanskar)',
            default => ucfirst(str_replace('_', ' ', $this->event_type ?? 'अन्य धार्मिक आयोजन')),
        };
    }

    public function getStatusBadge(): array
    {
        return match ($this->status) {
            'new' => ['label' => 'New / नया', 'class' => 'bg-amber-100 text-amber-800 border-amber-300'],
            'contacted' => ['label' => 'Contacted / संपर्कित', 'class' => 'bg-sky-100 text-sky-800 border-sky-300'],
            'approved' => ['label' => 'Date Approved / तिथि स्वीकृत', 'class' => 'bg-emerald-100 text-emerald-800 border-emerald-300'],
            'rejected' => ['label' => 'Unavailable / अनुपलब्ध', 'class' => 'bg-rose-100 text-rose-800 border-rose-300'],
            'completed' => ['label' => 'Concluded / संपन्न', 'class' => 'bg-slate-100 text-slate-800 border-slate-300'],
            default => ['label' => ucfirst($this->status), 'class' => 'bg-gray-100 text-gray-800 border-gray-300'],
        };
    }
}
