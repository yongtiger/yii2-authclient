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

/**
 * Microsoft Live OAuth2
 *
 * In order to use Microsoft Live OAuth2 you must register your application at <https://account.live.com/developers/applications>
 *
 * Note: Be sure to add `User.Read` and `User.ReadAll.Basic` in `Microsoft Graph Permissions`.
 *
 * Note: The redirect URI for web apps and services must begin with the scheme `https`. That means you cannot use `localhost` or `127.0.0.1` for testing!
 *
 * @see https://docs.microsoft.com/en-us/azure/active-directory/active-directory-v2-limitations#restrictions-on-redirect-uris
 *
 * Sample `Redirect URIs`: 
 *
 * `https://yourdomain.com/1_oauth/1_oauth/frontend/web/index.php (OK)
 * `http://yourdomain.com/1_oauth/1_oauth/frontend/web/index.php (WRONG!)
 * `https://www.yourdomain.com/1_oauth/1_oauth/frontend/web/index.php (OK)
 * `https://yourdomain.com/1_oauth/1_oauth/frontend/web/index.php?r=site/auth (WRONG!)
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'live' => [
 *                 'class' => 'yii\authclient\clients\Live',
 *                 'clientId' => 'live_client_id',
 *                 'clientSecret' => 'live_client_secret',
 *                 ///'scope' => 'wl.basic wl.emails wl.contacts_emails wl.signin',
 *             ],
 *         ],
 *     ]
 *     // ...
 * ]
 * ```
 *
 * [EXAMPLE RESPONSE]
 *
 * Authorization URL:
 *
 * ```
 * https://login.live.com/oauth20_authorize.srf?client_id=11a7aa93-7369-42aa-8477-426aca7d1839&response_type=code&redirect_uri=https%3A%2F%2Fwww.ljmy.info%2F1_oauth%2Ffrontend%2Fweb%2Findex.php%3Fr%3Dsite%252Fauth%26authclient%3Dlive&xoauth_displayname=My%20Application&scope=wl.basic%2Cwl.emails&state=0c5867f366336056d50a842ccbd184f4a7e4f4332631eff959f37ce1f34052d2
 * ```
 *
 * AccessToken Request:
 *
 * ```
 * https://login.live.com/oauth20_token.srf
 * ```
 *
 * AccessToken Response:
 *
 * ```
 * {"token_type":"bearer","expires_in":3600,"scope":"wl.basic wl.emails wl.signin wl.contacts_emails wl.birthday wl.work_profile","access_token":"EwAgA61DBAAUGCCXc8wU/zFu9QnLdZXy+YnElFkAAQtHON7jgP1gDCI0PjsdKkCfCYApmmxiNxwZygLparb6UA+N2owORJWAf/mACPxTjaaqWCQDvBrA88BXQWkBY33wapqLDPakZKVJDsE56zXOpFvk/j/0Nv9xQKheAuMOhjkYJNsKRpjqBCA09K1/XohTrZn4t4iHNagsk+UWOJ6j5fO1gxW6LmV9ZCGTfwqiA3EUAq16zsVtY7+SRYharL2UZWr5ZwuscMo+R1dQ7jkBJh2hsxm8naUzP0o46p5QvxzlYGsEdPQ1qHQ5aY8t1Ija2iwMSX5tr2x4ITLRxwzeSoiYuLnUX7NqTsKMlsQ4iw6WE1gG0/WYx3RBKqiUyFUDZgAACM8eMfcdp27Q8AESQdmHzoK2tK3otg/Ai6vsNgzPbVYyqA+IABPXuZK4wZjPUyk6cDAP7gRS10heGyDPaZyFBGXfxhM7TsQOMjulRa07joPp8+3FAaq5lZNDoP1dxEwFSdSie3YZnhXXQdqlz4BU9P0JRt4q7c8ar79fRtR2/GcrW0d8KiplcZaptGIWZFFFjmtja+P+4ylN1oacbhurMCg3If0hAHbWtwO069oEGEFLy7r14tS8e1aHs55NP6EqjzxH63SMaq22nyfi66euz+xnROCqzwUhISn5ApjfExz0qCwGBMpTNP0llcbFjHbWMtEG6jgrWE8u0s4u2vPmDRe4IPV+E2rE7DIig2r2aMN09jOZoFdxLbyLzFRxpOd+iD1hhobG6mpu+UKzcqOUfOD07DVHoRSJiyrdmeTRRalPYcV8yz4Cvx4vEIDI7b8sbF0HhUty7mj1kMdBwC3JFqgguSXMZb3P6XgOtvjmgWCOlDVuCfd5DRM6gT7t56VYubr+GD4xRMyX3BKe7M49e7k5gyicrRpbytXWwTrAIdORwithprSjTgWQSr1vQCoaTzpPB10JlwxQRwk47qeeksXKZ36yrzLzvZ+sALplB/9eanPKxrf/JIXIgQi0a29HMwTB9JpmwOKNVHbkg22rGcZnwkmjt2iFvmZbLwI=","user_id":"AAAAAAAAAAAAAAAAAAAAAL1ctsG2ewCA9htslsZwD9c"}
 * ```
 *
 * Request of `initUserAttributes()`:
 *
 * ```
 * https://login.live.com/oauth20_token.srf/me?access_token=EwAoA61DBAAUGCCXc8wU/zFu9QnLdZXy+YnElFkAAdPzpgEghD0S6KfjETk6KrbE5oQrEWiXNFLn4DAtfeLNNcY/CFscF1C7RFclMpdv8WF/Zad/3LdiMl5pK15N+4AMf0E0dmO1Gwl3qvKZkhU6YCg5BADpMapeOHkCouUNrJJl3uLyKlDqN/fIslmplbR4d/JZyaRQ4GcN3aSAxNudUh9GeGTpzWNkyBA0I1rB3D2C4aJS7uBKu+JtvnbSIH+ifmdUmnDFo4GxxsbXrcvCI+vPD+uUl9ghPMN/fUfpgcVXPtPV7Q6ynE3du7vbD87nPJ/6S/8sjlinIGfOPfWluybJc8gY9MgcNWrYEe0PVPRmhvsKTYWZqYr+z228OogDZgAACHL9b4mcY4U3+AHaL58M/AAPpdaGk869ry/fTeDU/T4GFgoiOhmYFo78cP0Relah+VZge2Hui163megUGO+uvYN7UgHF3EnXRb9q8jv2q1P6ucZedsXFCKhShLp6Mn/jUpV3pdBdb/X5yaH52WqNPygXKR0IQWN1Q7v+IYw1o9pupqPpTMyw6MvcN5xu56bf7NzJuvjriL8VgrXRtQYa4LyQrS5m54uv7zxM8FPuNtwwjqh1kT0YKVn0YUenPV0BcBiojT6h36yJYo6H/CfEFagGgzXFoDXrDfr/t3Icha7tILSMlYObfJfDxnqHMZcm9hAmRcA8F9CqwgjagZ+ucylTAm7a31Y8nInHGrVBs/0S6WqNzRT5J8ujFmFA7cr012HQcciGA5TFD8hChW6ncXxWPwYPtfboHInlRZ0+D0lOxrR2bvFfF0TnTd/rMtgVZxcBUfX2LGhzJPo+JWakXpTyXjFvcZNgiwViZqopFPMoWum6m2R2nAqt6rQ/LDiN/vo1Nuow0KW96Ui1/7zXKEkRnuDgFZ2Mx4QFFeKK81l7wGZLsEA2zXRI3WhMnWfOEtSW1/MCAuj/PWhhd9z0Jx9SrjpC75egaspJCUssFe5pQ8c7WrI9uWZX+2RQ9L/xfyuv2keKy91KMepjRhh7LEZlVgecyLGcuQQYPtLJw9vc2mIvAg==
 * ```
 *
 * Response of `initUserAttributes()`:
 *
 * ```
 * {"id": "ab30d9e58b344caa", "name": "tiger yang", "first_name": "tiger", "last_name": "yang", "link": "https://profile.live.com/", "birth_day": 15, "birth_month": 8, "birth_year": 1970, "work": [], "gender": null, "emails": {"preferred": "tigeryang.brainbook@outlook.com", "account": "tigeryang.brainbook@outlook.com", "personal": null, "business": null}, "locale": "zh_CN", "updated_time": "2017-01-20T00:10:41+0000"}
 * ```
 *
 * ```php
 * Array
 * (
 *     [id] => ab30d9e58b344caa
 *     [name] => tiger yang
 *     [first_name] => tiger
 *     [last_name] => yang
 *     [link] => https://profile.live.com/
 *     [birth_day] => 15
 *     [birth_month] => 8
 *     [birth_year] => 1970
 *     [work] => Array
 *         (
 *         )
 * 
 *     [gender] => 
 *     [emails] => Array
 *         (
 *             [preferred] => tigeryang.brainbook@outlook.com
 *             [account] => tigeryang.brainbook@outlook.com
 *             [personal] => 
 *             [business] => 
 *         )
 * 
 *     [locale] => zh_CN
 *     [updated_time] => 2017-01-16T19:31:18+0000
 *     [openid] => ab30d9e58b344caa
 *     [email] => tigeryang.brainbook@outlook.com
 *     [fullname] => tiger yang
 *     [firstname] => tiger
 *     [lastname] => yang
 *     [language] => zh_CN
 *     [provider] => live
 *     [avatar] => https://apis.live.net/v5.0/ab30d9e58b344caa/picture?type=large
 *     [link] => https://profile.live.com/cid-ab30d9e58b344caa
 * )
 * ```
 *
 * [REFERENCES]
 *
 * @see https://account.live.com/developers/applications
 * @see http://msdn.microsoft.com/en-us/library/live/hh243647.aspx
 * @see https://msdn.microsoft.com/en-us/library/hh243648.aspx#user
 */
class Live extends \yii\authclient\clients\Live implements IAuth
{
    use ClientTrait;

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions() {
        return [
            'popupWidth' => 480,
            'popupHeight' => 480,
        ];
    }
    
    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap() {
        return [
            'provider' => function ($attributes) {
                return $this->defaultName();
            },
            'openid' => 'id',
            'email' => ['emails', 'account'],
            'fullname' => 'name',
            'firstname' => 'first_name',
            'lastname' => 'last_name',
            'gender' => function ($attributes) {
                if (!isset($attributes['gender'])) return null;   ///why always null???
                return $attributes['gender'] == 'M' ? static::GENDER_MALE : ($attributes['gender'] == 'F' ? static::GENDER_FEMALE : null);
            },
            'language' => 'locale',
            ///@see http://stackoverflow.com/questions/8305521/get-profile-picture-from-windows-live
            ///e.g. `https://apis.live.net/v5.0/ab30d9e58b344caa/picture?type=large`
            ///
            ///Note: To redirect a GET call to the URL of a user's picture, you can call /me/picture or /USER_ID/picture.
            ///@see http://stackoverflow.com/questions/11900012/how-to-get-profile-pictures-of-each-contact
            'avatar' => function ($attributes) {
                return $this->apiBaseUrl . '/' . $attributes['id'] .'/picture?type=large';
            },
            ///https://profile.live.com/cid-ab30d9e58b344caa/
            'link' => function ($attributes) {
                return 'https://profile.live.com/cid-' . $attributes['id'] ;
            },
        ];
    }
}
