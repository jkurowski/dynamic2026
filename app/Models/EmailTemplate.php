<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmailTemplate extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'content',
        'meta',
        'status',
    ];

    protected $casts = [
        'meta' => 'array',
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sections()
    {
        return $this->hasMany(EmailTemplateSection::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($emailTemplate) {
            $columns = [
                'template_send_thanks',
                'template_offer',
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

            foreach ($columns as $column) {
                DB::table('investment_templates')
                    ->where($column, $emailTemplate->id)
                    ->update([$column => null]);
            }
        });
    }
}
