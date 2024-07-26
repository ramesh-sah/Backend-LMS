<?php
namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait UpdateCreator
{
    protected function getCreator($value){
        if (is_null($value) || $value == "") {
            $user = Auth::user();
            if ($user->username != "") {
                return $user->username;
            } else {
                return 'Unknown User';
            }
        } else {
            return $value;
        }
    }

    protected function createdBy(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->getCreator($value),
            set: fn($value) => $this->getCreator($value)
        );
    }

    protected function updatedBy(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->getCreator($value),
            set: fn($value) => $this->getCreator($value)
        );
    }

}
