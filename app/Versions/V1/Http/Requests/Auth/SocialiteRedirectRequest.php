<?php

namespace App\Versions\V1\Http\Requests\Auth;

use App\Versions\V1\Http\Requests\Traits\Auth\OAuthRequestParams;
use Illuminate\Foundation\Http\FormRequest;

class SocialiteRedirectRequest extends FormRequest
{
    use OAuthRequestParams;

    public function rules(): array
    {
        return $this->oauthParams();
    }
}
