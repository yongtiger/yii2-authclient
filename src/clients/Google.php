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
 * Google OAuth2
 *
 * In order to use Google OAuth2 you must create a project at <https://console.developers.google.com/project>
 * and setup its credentials at <https://console.developers.google.com/project/[yourProjectId]/apiui/credential>.
 * In order to enable using scopes for retrieving user attributes, you should also enable Google+ API at
 * <https://console.developers.google.com/project/[yourProjectId]/apiui/api/plus>.
 *
 * Note: You need to enable Google+!
 *
 * Note: Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing.
 *
 * Sample `Callback URL`: 
 *
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth&authclient=google` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site%2Fauth&authclient=google` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site%2Fauth%26authclient=google` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/?r=site/auth` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/?r=site%2Fauth&authclient=google` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php?r=site/auth` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php?r=site/auth&authclient=google` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php?r=site%2Fauth&authclient=google` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php?r=site%2Fauth%26authclient=google` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/?r=site/auth` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/?r=site%2Fauth&authclient=google` (OK)
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'google' => [
 *                 'class' => 'yii\authclient\clients\Google',
 *                 'clientId' => 'google_client_id',
 *                 'clientSecret' => 'google_client_secret',
 *                 ///'scope' => 'profile email',
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
 *     echo "<pre>";print_r($client->provider);echo "</pre>";
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
 * https://accounts.google.com/o/oauth2/auth?client_id=409149769406-aigbmsp0doiqtgj167c2oqtl294pdbsp.apps.googleusercontent.com&response_type=code&redirect_uri=http%3A%2F%2Flocalhost%2F1_oauth%2Ffrontend%2Fweb%2Findex.php%3Fr%3Dsite%252Fauth%26authclient%3Dgoogle&xoauth_displayname=My%20Application&scope=profile%20email&state=1dafec3e5851b9f49ea58394738f42161f8dd20229aa6a76312d72cca17f75e9
 * ```
 *
 * AccessToken Request:
 *
 * ```
 * https://accounts.google.com/o/oauth2/token
 * ```
 *
 * AccessToken Response:
 *
 * ```
 * { "access_token" : "ya29.GlvYA1AC1yCtiIKH_F9O938NehCu-UHZYjir_jyqZToLevrWJIutV0XNC6M71WZRHPUFB5vlCawo9b5L52PcaBYvgZEk_RDBIAK2eFnqvqeVWNvo_9ywlaPX11dd", "expires_in" : 3592, "id_token" : "eyJhbGciOiJSUzI1NiIsImtpZCI6ImFjYTJhZTQwZTA2NDY5YmQ0YjQ2NmI1MDI1MGVmNWE2MGM5OGU0ZTAifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiaWF0IjoxNDg0ODY2NzQyLCJleHAiOjE0ODQ4NzAzNDIsImF0X2hhc2giOiJwMHlvR3FtRF9oa2pEem8zcjl0TXZRIiwiYXVkIjoiNDA5MTQ5NzY5NDA2LWFpZ2Jtc3AwZG9pcXRnajE2N2Myb3F0bDI5NHBkYnNwLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwic3ViIjoiMTEzNTQ0NzI0NDc0NTczMjMxMzA2IiwiZW1haWxfdmVyaWZpZWQiOnRydWUsImF6cCI6IjQwOTE0OTc2OTQwNi1haWdibXNwMGRvaXF0Z2oxNjdjMm9xdGwyOTRwZGJzcC5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsImVtYWlsIjoic2VydmljZS5icmFpbmJvb2suY2NAZ21haWwuY29tIn0.m_GTkWKHHJALJjOtrY4-ssiJiwmd9SYpbn_LPjI6_SvdvsQe-StIvoVzk6koVsLIvRxW7miI70jCRLAVLdLpi-unKwcp0C6xVh5p9VuLMJe7orYNNTY4Yet4hQb2Tgr8GMDmFgaiIW1LfgngxiP46q7C3AQHP6Ay3jEcWt47n4r9oLJiwHo-_Lqebc2y7hflC4pom6rfPD4VBjjmIX7FaH18D5ib8mUOhc8x7W4eBYrikagzbBmrbh9QHP_m3dvzRAnJ01e4KXTYBJtvmg6yLNdvKi2JVijZwBqoamiTZZ-xg_5sPxRP2RdBhbJZ_OvVlXCfRO0iQYjBIzM-lXqKvw", "token_type" : "Bearer" }
 * ```
 *
 * Request of `initUserAttributes()`:
 *
 * ```
 * https://www.googleapis.com/plus/v1/people/me?access_token=ya29.GlvYA1AC1yCtiIKH_F9O938NehCu-UHZYjir_jyqZToLevrWJIutV0XNC6M71WZRHPUFB5vlCawo9b5L52PcaBYvgZEk_RDBIAK2eFnqvqeVWNvo_9ywlaPX11dd
 * ```
 *
 * Response of `initUserAttributes()`:
 *
 * ```
 * { "kind": "plus#person", "etag": "\"FT7X6cYw9BSnPtIywEFNNGVVdio/m83Ca7xVMoDVrGZ6EOj8pdlzKbM\"", "emails": [ { "value": "service.brainbook.cc@gmail.com", "type": "account" } ], "objectType": "person", "id": "113544724474573231306", "displayName": "service brainbook", "name": { "familyName": "brainbook", "givenName": "service" }, "image": { "url": "https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg?sz=50", "isDefault": true }, "isPlusUser": false, "language": "zh_CN", "verified": false }
 * ```
 *
 * ```php
 * Array
 * (
 *     [kind] => plus#person
 *     [etag] => "FT7X6cYw9BSnPtIywEFNNGVVdio/m83Ca7xVMoDVrGZ6EOj8pdlzKbM"
 *     [emails] => Array
 *         (
 *             [0] => Array
 *                 (
 *                     [value] => service.brainbook.cc@gmail.com
 *                     [type] => account
 *                 )
 * 
 *         )
 * 
 *     [objectType] => person
 *     [id] => 113544724474573231306
 *     [displayName] => service brainbook
 *     [name] => Array
 *         (
 *             [familyName] => brainbook
 *             [givenName] => service
 *         )
 * 
 *     [image] => Array
 *         (
 *             [url] => https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg?sz=50
 *             [isDefault] => 1
 *         )
 * 
 *     [isPlusUser] => 
 *     [language] => zh_CN
 *     [verified] => 
 *     [provider] => google
 *     [openid] => 113544724474573231306
 *     [email] => service.brainbook.cc@gmail.com
 *     [fullname] => service brainbook
 *     [firstname] => service
 *     [lastname] => brainbook
 *     [gender] => 
 *     [avatarUrl] => https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg?sz=50
 * )
 * ```
 *
 * [REFERENCES]
 *
 * @see https://console.developers.google.com/project
 * @see https://support.google.com/googleapi/answer/6158857
 * @see http://www.yiiframework.com/doc-2.0/yii-authclient-clients-google.html
 */
class Google extends \yii\authclient\clients\Google implements IAuth
{
    use ClientTrait;

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions() {
        return [
            'popupWidth' => 480,
            'popupHeight' => 640,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap() {
        return [
            'provider' => $this->defaultName(),
            'openid' => 'id',
            'email' => ['emails', 0, 'value'],      ///`[emails][0][value] => yongtiger@yahoo.com`
            ///Google register a new account with Email instead of username, also needed first name and last name.
            ///So we generate the fullname with givenName and familyName, 
            ///according to the above `EXAMPLE JSON RESPONSE BODY FOR GET`: `[givenName] => Tiger` and `[familyName] => Yong`.
            'fullname' => function ($attributes) {
                if (!isset($attributes['name']) || empty($attributes['name']['givenName']) || empty($attributes['name']['familyName'])) return null;
                return $attributes['name']['givenName'] . ' ' . $attributes['name']['familyName'];
            },
            'firstname' => ['name', 'givenName'],
            'lastname' => ['name', 'familyName'],
            'gender' => function ($attributes) {
                if (!isset($attributes['gender'])) return null;
                return $attributes['gender'] == 'male' ? static::GENDER_MALE : ($attributes['gender'] == 'female' ? static::GENDER_FEMALE : null);
            },
            'avatarUrl' => ['image', 'url'],    ///`[image][url] => https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg?sz=50`

            'linkUrl' => 'url',
        ];
    }
}
