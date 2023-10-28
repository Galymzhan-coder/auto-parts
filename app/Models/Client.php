<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Filters\Filterable;
use Orchid\Metrics\Chartable;
use Orchid\Screen\AsSource;

class Client extends Model
{
    use Chartable;
    use HasFactory;
    use AsSource;
    use Filterable;
    use Attachable;

    protected $fillable = ['phone', 'name', 'last_name', 'status', 'email', 'birthday', 'service_id', 'assessment', 'invoice_id'];

   
    public function make_phone_normalized(string $rawPhone): string
    {
            //return str_replace('+', '', PhoneNumber::make($rawPhone, 'RU')->formatE164());
            return str_replace('+', '', (new  PhoneNumber($rawPhone, 'RU'))->isOfCountry());
    }
    

    protected $allowedSorts = [
        'status'
    ];
    protected $allowedFilters = [
        'phone'
    ];

    public const STATUS = [
        'interviewed' => 'Опрошен',
        'not_interviewed' => 'Не опрошен'
    ];

    public function setPhoneAttribute($phoneCandidate)
    {
        $phone_normalized = str_replace('+', '', $phoneCandidate);
        //$this->attributes['phone'] =  make_phone_normalized($phoneCandidate);
        $this->attributes['phone'] =   $phone_normalized;
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function invoice()
    {
        return $this->hasOne(Attachment::class, 'id', 'invoice_id');
    }
}
