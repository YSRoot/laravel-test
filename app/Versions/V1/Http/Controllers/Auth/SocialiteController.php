<?php

namespace App\Versions\V1\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Versions\V1\Bridge\Socialite;
use App\Versions\V1\Http\Requests\Auth\SocialiteRedirectRequest;
use App\Versions\V1\Http\Resources\Auth\OAuthTokenResource;
use Illuminate\Http\Request;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Throwable;

class SocialiteController extends Controller
{
    public function redirect(SocialiteRedirectRequest $request, string $driver, Socialite $socialite): RedirectResponse
    {
        return $socialite->redirect($request, $driver);
    }

    /**
     * @throws UnknownProperties|Throwable
     */
    public function callback(Request $request, string $driver, Socialite $socialite): OAuthTokenResource
    {
        $passwordTokenDTO = $socialite->callback($request, $driver);

        return new OAuthTokenResource($passwordTokenDTO);
    }
}
