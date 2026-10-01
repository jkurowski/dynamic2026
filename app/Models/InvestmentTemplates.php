<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestmentTemplates extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_send_thanks',
        'template_offer',
        'template_offer_mail',
        'template_offer_reminder',
        'template_open_day',
        'template_preliminary_agreement',
        'template_local_review',
        'template_local_pickup',
        'template_transfer_of_ownership',
        'template_tenant_changes',
        'template_documents_for_credit',
        'template_invoices',
        'template_documents',
        'template_special_offers',
        'template_meeting_invitation',
        'template_meeting_reminder',
        'template_offer_expiration'
    ];

    public function investment():BelongsTo
    {
        return $this->belongsTo(Investment::class, 'investment_id');
    }
}
