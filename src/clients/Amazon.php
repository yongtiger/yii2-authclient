<?php ///[yongtiger/yii2-authclient]

/**
 * Yii2 user
 *
 * @link        http://www.brainbook.cc
 * @see         https://github.com/yongtiger/yii2-user
 * @author      Tiger Yong <tigeryang.brainbook@outlook.com>
 * @copyright   Copyright (c) 2016 BrainBook.CC
 * @license     http://opensource.org/licenses/MIT
 */

namespace yongtiger\authclient\clients;

use yii\authclient\OAuth2;

/**
 * Amazon OAuth2
 *
 * In order to use Amazon OAuth2 you must register your application at <http://login.amazon.com/manageApps>.
 *
 * Note: The redirect URI for web apps and services must begin with the scheme `https`. That means you cannot use `localhost` or `127.0.0.1` for testing!
 *
 * Sample `Callback URL`: 
 *
 * `https://yourdomain.com/1_oauth/frontend/web/index.php/site/auth?authclient=amazon` (OK)
 * `https://yourdomain.com/1_oauth/frontend/web/index.php?r=site/auth` (WRONG!)
 * `https://yourdomain.com/1_oauth/frontend/web/index.php?r=site/auth&authclient=amazon` (WRONG!)
 * 
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'amazon' => [
 *                 'class' => 'yii\authclient\clients\Amazon',
 *                 'clientId' => 'amazon_client_id',
 *                 'clientSecret' => 'amazon_client_secret',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * [Usage]
 * 
 * public function connectCallback(\yongtiger\authclient\clients\IAuth $client)
 * {
 *     ///Uncomment below to see which attributes you get back.
 *     ///First time to call `getUserAttributes()`, only return the basic attrabutes info for login, such as openid.
 *     echo "<pre>";print_r($client->getUserAttributes());echo "</pre>";
 *     echo "<pre>";print_r($client->openid);echo "</pre>";
 *     ///If `$attribute` is not exist in the basic user attrabutes, call `initUserInfoAttributes()` and merge the results into the basic user attrabutes.
 *     echo "<pre>";print_r($client->email);echo "</pre>";
 *     ///After calling `initUserInfoAttributes()`, will return all user attrabutes.
 *     echo "<pre>";print_r($client->getUserAttributes());echo "</pre>";
 *     echo "<pre>";print_r($client->fullName);echo "</pre>";
 *     echo "<pre>";print_r($client->firstName);echo "</pre>";
 *     echo "<pre>";print_r($client->lastName);echo "</pre>";
 *     echo "<pre>";print_r($client->language);echo "</pre>";
 *     echo "<pre>";print_r($client->gender);echo "</pre>";
 *     echo "<pre>";print_r($client->avatarUrl);echo "</pre>";
 *     echo "<pre>";print_r($client->linkUrl);echo "</pre>";
 *     exit;
 *     // ...
 * }
 *
 * [EXAMPLE RESPONSE]
 *
 * Authorization URL:
 *
 * ```
 * https://www.amazon.com/ap/signin?_encoding=UTF8&openid.mode=checkid_setup&openid.ns=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0&openid.claimed_id=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0%2Fidentifier_select&openid.pape.max_auth_age=0&ie=UTF8&openid.ns.pape=http%3A%2F%2Fspecs.openid.net%2Fextensions%2Fpape%2F1.0&openid.identity=http%3A%2F%2Fspecs.openid.net%2Fauth%2F2.0%2Fidentifier_select&openid.assoc_handle=amzn_lwa_na&marketPlaceId=ATVPDKIKX0DER&arb=2ecc0dd7-bb09-4dac-baa8-53ff19603d8e&language=zh_CN&openid.return_to=https%3A%2F%2Fna.account.amazon.com%2Fap%2Foa%3FmarketPlaceId%3DATVPDKIKX0DER%26arb%3D2ecc0dd7-bb09-4dac-baa8-53ff19603d8e%26language%3Dzh_CN&enableGlobalAccountCreation=1&metricIdentifier=amzn1.application.d65c23fdd3c64214bafc7a604820ffe9&signedMetricIdentifier=1OSQ4f5W%2FJkz4a3EihAaiJGcjJUYQPY%2Bg4dwp8c5Gus%3D
 * ```
 *
 * AccessToken Request:
 *
 * ```
 * https://api.amazon.com/auth/o2/token
 * ```
 *
 * AccessToken Response:
 *
 * ```
 * {"access_token":"Atza|IwEBIBwZ5x52Bkt2hMraELedoaLP6mtmuW_Y1rMJVuSUTRxJcXRRtZEdsQs9qYgSfnyr6xHxcxjxJ9u1e2NZmC0FU4m3eWB6JoptMLQH3StqfOyYJK8e1L2lz149DczqLo5G9wAVFPI3rvkwbEy75EHN71AHrXNk7XmXqiwxEtkHUN8L2xclRGupA-WclwQCcsNEev7G-f8x4OsUjEzOTRiewyzYGvVUI2Nx6PP5PSbkXKU4WUvUtb8zj6wOHh1WzS5Ubyz2mXOP7rQAsxdXsIFgxOm1kLSrsuIwocDh7Tim7WXxGj50WXpG7NB57SV4nHf3i5yj_eLtYGW3us_cZXHdOCgqQ2Ky-IjXcDSqNIF7KWuqrn5dXg95mMsC9z4SVoCi1aOzg_o7t1V_twuB97sUIfBMpYMVzGvDx5afn3N5eBJ8H95eDK6s4Y2UKgclTlMT1vTUnGaphxAf9kVacv0bkpgB7a9B8ALcfnYtg7HKn2u05x0OOUHSktmS6Tk6W55sK1GON60vqRwYUydvt4lquqhYLzPdMqVWt9LGUWVlAHOCQg","refresh_token":"Atzr|IwEBICEi_pAezBPPDro-sDLyjwiNZ773sZ5Ley77oVWQ0R_icstPyez-YgDrj0SLg2SfAPKZUV4fgox8DlvvRuf3uAXcZmQyFV9RHs3nNvqMCEiEmtHe0_hFtvxSSSwjs57tBaKoVQW_H7y19O4bnH4HmEV1nt6atRElBIYnIwkDvbfXRqx2van0a-g2UcNlcEL4dipkrFPPjj1WWhruzaIkal8bPlC_LaR0gP2RBwiJBp4wh8gt-h7cK1WiaV6OHJ75lMv45lHxc9Fg7KhvN9U9aV_zkeNKoHl8a7KgD_i-muC_eILjp5psJtSiE8QfW91Yeq-ZNXk1u-De4fQV-Nzt-GKmwhDjc3k9pQ_bQ0qUObf4Moa88c_707SNr-fW-qY7xB5OCW_rvdKDhhZr6Sk6e0R5tFoJ0QTjTWQ8ounhqjQkwZX5uX8Irlc8gX9JKKZU1XB-MSN5GNgwKTzEYEUpldhdG-e6yN5fAj4f1uDRrQlTgByhZcMeRwH7UUyka5UT7us","token_type":"bearer","expires_in":3600}
 * ```
 *
 * Request of `initUserAttributes()`:
 *
 * ```
 * https://api.amazon.com/auth/O2/tokeninfo?access_token=Atza%7CIwEBIJosLKZKHE5BFowqIt_dABUG37ZVlwD5UxrYvaXynLLVApiRS-8mibC-S6FgNwot2FunjIQmFky3C-AsG3G0n2TfIH_9MOjWTagbUuiZGc-NPc_AzAXCbL6N_RU582_UIM3kQadC8g36nlFscQDeE3hHmr63Stzsan9lpvgpNBCLBNKAZw0-P4nxJgOWGyFBme0O8nf_6xRfbR1C0lQyr1RMmAvJWHBJEC03T3NVATxa8trONBmEQyJIh8Br0SbpwbeR37QUD113uSh65vxLPt036oIraYJSvBXsL73so3JN_OKxmJISJzYEmSl1Ie_iDAepmkNLSc1vSXWo5yW6yiboTyg8paeLrX6cIZQ-jXR7mRTQ99vbt9NWDL52_sc2MzTemZR_ut3xlX986wXGkP0bS6hgfV5wFFFxT6yTdFhphZ0LfP2rj7bokly2C7XK_KEMwLLBVz3ohdt92PZNaGwOz6Aqm-DZrJyU5PCR-o3EWvyLWPeMXfMH8vphh59VpkvDl7YPEZF1duHk8or4fTnTYScnkrvijxpnMrDHGE73peVFNFoKa7SCMUAo6OW0k0k
 * ```
 *
 * Response of `initUserAttributes()`:
 *
 * ```
 * {"aud":"amzn1.application-oa2-client.c3b583b90a274981a36d53f47234f111","user_id":"amzn1.account.AHQG2JPGF47GOIO4WYGDWAHKTIVA","iss":"https://www.amazon.com","exp":3598,"app_id":"amzn1.application.d65c23fdd3c64214bafc7a604820ffe9","iat":1484875823}
 * ```
 *
 * ```php
 * Array
 * (
 *     [aud] => amzn1.application-oa2-client.c3b583b90a274981a36d53f47234f111
 *     [user_id] => amzn1.account.AHQG2JPGF47GOIO4WYGDWAHKTIVA
 *     [iss] => https://www.amazon.com
 *     [exp] => 3598
 *     [app_id] => amzn1.application.d65c23fdd3c64214bafc7a604820ffe9
 *     [iat] => 1484875823
 *     [openid] => amzn1.account.AHQG2JPGF47GOIO4WYGDWAHKTIVA
 * )
 * ```
 *
 * Request of `initUserInfoAttributes()`:
 *
 * ```
 * https://api.amazon.com//user/profile?access_token=Atza%7CIwEBIJosLKZKHE5BFowqIt_dABUG37ZVlwD5UxrYvaXynLLVApiRS-8mibC-S6FgNwot2FunjIQmFky3C-AsG3G0n2TfIH_9MOjWTagbUuiZGc-NPc_AzAXCbL6N_RU582_UIM3kQadC8g36nlFscQDeE3hHmr63Stzsan9lpvgpNBCLBNKAZw0-P4nxJgOWGyFBme0O8nf_6xRfbR1C0lQyr1RMmAvJWHBJEC03T3NVATxa8trONBmEQyJIh8Br0SbpwbeR37QUD113uSh65vxLPt036oIraYJSvBXsL73so3JN_OKxmJISJzYEmSl1Ie_iDAepmkNLSc1vSXWo5yW6yiboTyg8paeLrX6cIZQ-jXR7mRTQ99vbt9NWDL52_sc2MzTemZR_ut3xlX986wXGkP0bS6hgfV5wFFFxT6yTdFhphZ0LfP2rj7bokly2C7XK_KEMwLLBVz3ohdt92PZNaGwOz6Aqm-DZrJyU5PCR-o3EWvyLWPeMXfMH8vphh59VpkvDl7YPEZF1duHk8or4fTnTYScnkrvijxpnMrDHGE73peVFNFoKa7SCMUAo6OW0k0k
 * ```
 *
 * Response of `initUserInfoAttributes()`:
 *
 * ```
 * {"user_id":"amzn1.account.AHQG2JPGF47GOIO4WYGDWAHKTIVA","name":"yongtiger","email":"tigeryang.brainbook@outlook.com"}"
 * ```
 *
 * ```php
 * Array
 * (
 *     [aud] => amzn1.application-oa2-client.c3b583b90a274981a36d53f47234f111
 *     [user_id] => amzn1.account.AHQG2JPGF47GOIO4WYGDWAHKTIVA
 *     [iss] => https://www.amazon.com
 *     [exp] => 3598
 *     [app_id] => amzn1.application.d65c23fdd3c64214bafc7a604820ffe9
 *     [iat] => 1484875823
 *     [openid] => amzn1.account.AHQG2JPGF47GOIO4WYGDWAHKTIVA
 *     [name] => yongtiger
 *     [email] => tigeryang.brainbook@outlook.com
 *     [fullname] => yongtiger
 * )
 * ```
 *
 * [REFERENCES]
 *
 * @see http://login.amazon.com/website
 * @see https://images-na.ssl-images-amazon.com/images/G/01/lwa/dev/docs/website-developer-guide._TTH_.pdf
 */
class Amazon extends OAuth2 implements IAuth
{
    use ClientTrait;

    /**
     * @inheritdoc
     */
    public $authUrl = 'https://www.amazon.com/ap/oa';

    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://api.amazon.com/auth/o2/token';

    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://api.amazon.com';

    /**
     * @inheritdoc
     */
    public $scope = 'profile';

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'amazon';
    }
    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Amazon';
    }

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions() {
        return [
            'popupWidth' => 800,
            'popupHeight' => 600,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap() {
        return [
            'openid' => 'user_id',
        ];
    }

    /**
     * UserInfo Normalize User Attribute Map
     *
     * @return array
     */
    protected function userInfoNormalizeUserAttributeMap() {
        return [
            'fullname' => 'name',
        ];
    }

    /**
     * Get user openid and other basic information.
     *
     * @return array
     */
    protected function initUserAttributes()
    {
        return $this->api('/auth/O2/tokeninfo');
    }

    /**
     * Get extra user info.
     *
     * @return array
     */
    protected function initUserInfoAttributes()
    {
        return $this->api('/user/profile');
    }
}