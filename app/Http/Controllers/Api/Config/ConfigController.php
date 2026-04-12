<?php

namespace App\Http\Controllers\Api\Config;

use App\Api\Request as ApiRequest;
use App\Http\Controllers\Api\Controller;
use App\Http\Controllers\Web\traits\UserFormFieldsTrait;
use App\Models\PaymentChannel;
use App\Support\ApiPayloadCache;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    use UserFormFieldsTrait;


    /**
     * Get public app config (register method, payment channels, currency, features, etc.).
     *
     * @OA\Get(
     *     path="/v1/config",
     *     summary="Get app config",
     *     tags={"Config"},
     *     @OA\Response(response=200, description="Config", @OA\JsonContent(
     *         @OA\Property(property="success", type="boolean", example=true),
     *         @OA\Property(property="status", type="string", example="retrieved"),
     *         @OA\Property(property="data", type="object",
     *             @OA\Property(property="register_method", type="string"),
     *             @OA\Property(property="user_language", type="array", @OA\Items(type="object", @OA\Property(property="id", type="string"), @OA\Property(property="title", type="string"))),
     *             @OA\Property(property="payment_channels", type="object"),
     *             @OA\Property(property="currency", type="object"),
     *             @OA\Property(property="show_google_login_button", type="boolean"),
     *             @OA\Property(property="show_facebook_login_button", type="boolean")
     *         )
     *     ))
     * )
     */
    public function list(Request $request)
    {
        $cacheKey = 'api:v1:config:list:' . ApiPayloadCache::localeTag();
        $data = ApiPayloadCache::rememberShared($cacheKey, 'config_list', function () {
            $generalSettings = getGeneralSettings();
            $generalOptionsSettings = getGeneralOptionsSettings();
            $featuresSettings = getFeaturesSettings();
            $financialSettings = getFinancialSettings();
            $financialCurrencySettings = getFinancialCurrencySettings();
            $referralSettings = getReferralSettings();

            $registerMethod = $generalSettings['register_method'] ?? 'mobile';
            $userLanguages = $generalSettings['user_languages'] ?? [];

            if (!empty($userLanguages) and is_array($userLanguages)) {
                $userLanguages = getLanguages($userLanguages);
            } else {
                $userLanguages = [];
            }

            $paymentChannels = PaymentChannel::get()->groupBy('status');

            $currency = [
                'sign' => currencySign(),
                'name' => currency()
            ];
            $showOtherRegisterMethod = (!empty($featuresSettings) and !empty($featuresSettings['show_other_register_method']));

            $selectRolesDuringRegistration = !empty($featuresSettings['select_the_role_during_registration']) ? $featuresSettings['select_the_role_during_registration'] : null;

            $allowInstructorDeleteContent = !!(!empty($generalOptionsSettings['allow_instructor_delete_content']));
            $contentDeleteMethod = !empty($generalOptionsSettings['content_delete_method']) ? $generalOptionsSettings['content_delete_method'] : 'delete_directly';

            return [
                'register_method' => $registerMethod,
                'selectRolesDuringRegistration' => $selectRolesDuringRegistration,
                'offline_bank_account' => getOfflineBanksTitle() ?? null,
                'user_language' => $userLanguages,
                'payment_channels' => $paymentChannels,
                'minimum_payout_amount' => !empty($financialSettings['minimum_payout']) ? $financialSettings['minimum_payout'] : null,
                'currency' => $currency,
                'price_display' => !empty($financialSettings['price_display']) ? $financialSettings['price_display'] : 'only_price',
                'multi_currency' => !empty($financialCurrencySettings['multi_currency']),
                'currency_position' => !empty($financialCurrencySettings['currency_position']) ? $financialCurrencySettings['currency_position'] : 'left',
                'currency_decimal' => $financialCurrencySettings['currency_decimal'] ?? null,
                'forum_settings' => getForumsHomepageSettings(),
                'course_forum_status' => !empty($featuresSettings['course_forum_status']) ? $featuresSettings['course_forum_status'] : null,
                'show_google_login_button' => !empty($featuresSettings['show_google_login_button']),
                'show_facebook_login_button' => !empty($featuresSettings['show_facebook_login_button']),
                'showOtherRegisterMethod' => $showOtherRegisterMethod,
                'webinar_private_content_status' => !empty($featuresSettings['webinar_private_content_status']) ? $featuresSettings['webinar_private_content_status'] : null,
                'sequence_content_status' => !empty($featuresSettings['sequence_content_status']) ? $featuresSettings['sequence_content_status'] : null,
                'course_notes_status' => !empty($featuresSettings['course_notes_status']) ? $featuresSettings['course_notes_status'] : null,
                'course_notes_attachment' => !empty($featuresSettings['course_notes_attachment']) ? $featuresSettings['course_notes_attachment'] : null,
                'allow_instructor_delete_content' => $allowInstructorDeleteContent,
                'content_delete_method' => $contentDeleteMethod,
                'referralSettings' => $referralSettings,
            ];
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'),
            $data
        );
    }

    /**
     * Get registration form config by type (e.g. mobile, email).
     *
     * @OA\Get(
     *     path="/v1/config/register/{type}",
     *     summary="Get register config",
     *     tags={"Config"},
     *     @OA\Parameter(name="type", in="path", required=true, @OA\Schema(type="string", example="mobile")),
     *     @OA\Response(response=200, description="Register config (form fields, options)")
     * )
     */
    public function getRegisterConfig(Request $request, $type)
    {
        $cacheKey = 'api:v1:config:register:' . $type . ':' . ApiPayloadCache::localeTag();
        $config = ApiPayloadCache::rememberShared($cacheKey, 'config_register', function () use ($type) {
            $generalSettings = getGeneralSettings();
            $featuresSettings = getFeaturesSettings();
            $referralSettings = getReferralSettings();
            $generalOptionsSettings = getGeneralOptionsSettings();

            $registerMethod = $generalSettings['register_method'] ?? 'mobile';
            $userLanguages = $generalSettings['user_languages'] ?? [];

            if (!empty($userLanguages) and is_array($userLanguages)) {
                $userLanguages = getLanguages($userLanguages);
            } else {
                $userLanguages = [];
            }

            $showOtherRegisterMethod = !empty($featuresSettings['show_other_register_method']);

            $formFields = $this->getFormFieldsByType($type);
            $showCertificateAdditionalInRegister = !empty($featuresSettings['show_certificate_additional_in_register']);
            $selectRolesDuringRegistration = !empty($featuresSettings['select_the_role_during_registration']) ? $featuresSettings['select_the_role_during_registration'] : null;
            $selectedTimezone = $generalSettings['default_time_zone'] ?? null;

            return [
                'selectedTimezone' => $selectedTimezone,
                'selectRolesDuringRegistration' => $selectRolesDuringRegistration,
                'showCertificateAdditionalInRegister' => $showCertificateAdditionalInRegister,
                'showOtherRegisterMethod' => $showOtherRegisterMethod,
                'referralSettings' => $referralSettings,
                'formFields' => $formFields,
                'register_method' => $registerMethod,
                'user_language' => $userLanguages,
                'show_google_login_button' => !empty($featuresSettings['show_google_login_button']),
                'show_facebook_login_button' => !empty($featuresSettings['show_facebook_login_button']),
                'disable_registration_verification' => !empty($generalOptionsSettings['disable_registration_verification_process']),
            ];
        });

        return apiResponse2(1, 'retrieved', trans('api.public.retrieved'),
            $config
        );
    }


}
