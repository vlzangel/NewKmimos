<?php

namespace Sample;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

// ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
// ini_set('display_errors', '1');
// ini_set('display_startup_errors', '1');

class PayPalClient
{
    /**
     * Returns PayPal HTTP client instance with environment which has access
     * credentials context. This can be used invoke PayPal API's provided the
     * credentials have the access to do so.
     */
    public static function client()
    {
        return new PayPalHttpClient(self::environment());
    }
    
    /**
     * Setting up and Returns PayPal SDK environment with PayPal Access credentials.
     * For demo purpose, we are using SandboxEnvironment. In production this will be
     * LiveEnvironment.
     */
    public static function environment()
    {
        $clientId = getenv("CLIENT_ID") ?: "AVUOYKnU8VsRyGCr1i_CL2vJRG09GdmkCXy8IWqETtAX1ZpW9VUf-V8GIpo1e5-KsGvL8N23E_apik0e";
        $clientSecret = getenv("CLIENT_SECRET") ?: "EPpBKngn_8y99xrS3SdBTTaVg5wsA4c4IrtsMClM563Rtj_rA6dAz8sGNGIMTM_3vgLG_dusJ9ynHUr-";
        return new SandboxEnvironment($clientId, $clientSecret);
    }
}