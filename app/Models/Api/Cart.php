<?php

namespace App\Models\Api;

use App\Models\Cart as Model;

class Cart extends Model
{

    public function getDetailsAttribute(){
       // dd($this->webinar->brief ) ;
        return [
            'id'=>$this->id  ,
            'user'=>$this->user->brief ,
            'webinar'=>($this->webinar_id ? $this->webinar->brief : ($this->chapter_id && $this->chapter && $this->chapter->webinar ? $this->chapter->webinar->brief : null)) ?? null ,
            'price'=>$this->price ,
            'discount'=>$this->discount ,
            'meeting'=>$this->reserveMeeting->details??null
        ] ;
    }

    public function getDiscountAttribute(){
        if($this->webinar_id){
            return $this->webinar->price - $this->webinar->getDiscount($this->ticket) ;
        }
        if ($this->chapter_id && $this->chapter) {
            return null;
        }
        return null ;
    }

    public function getPriceAttribute(){
        if($this->webinar_id){
            return $this->webinar->price  ;
        }
        if ($this->chapter_id && $this->chapter) {
            return (float) $this->chapter->price;
        }
        if ($this->reserve_meeting_id) {
            return $this->reserveMeeting->paid_amount ;
        }
        $info = $this->getItemInfo();
        return $info['price'] ?? 0;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Api\User', 'creator_id', 'id');
    }

    public function webinar()
    {
        return $this->belongsTo('App\Models\Api\Webinar', 'webinar_id', 'id');
    }

    public function reserveMeeting()
    {
        return $this->belongsTo('App\Models\Api\ReserveMeeting', 'reserve_meeting_id', 'id');
    }

    public function ticket()
    {
        return $this->belongsTo('App\Models\Ticket', 'ticket_id', 'id');
    }

}
