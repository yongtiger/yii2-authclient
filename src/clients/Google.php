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
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth&authclient=google` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site%2Fauth&authclient=google` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site%2Fauth%26authclient=google` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/?r=site/auth` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/?r=site%2Fauth&authclient=google` (OK)
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
 *     ...
 * ]
 * ```
 *
 * [EXAMPLE JSON RESPONSE BODY FOR GET]
 * 
 * `$responseContent` at `/vendor/yiisoft/yii2-httpclient/StreamTransport.php`:
 *
 * ```
 *  { "kind": "plus#person", "etag": "\"FT7X6cYw9BSnPtIywEFNNGVVdio/
 m83Ca7xVMoDVrGZ6EOj8pdlzKbM\"", "emails": [ { "value": "service.brainbook.cc@gmail.com", "type": 
 "account" } ], "objectType": "person", "id": "113544724474573231306", "displayName": "service 
 brainbook", "name": { "familyName": "brainbook", "givenName": "service" }, "image": { "url": "https://
 lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg?
 sz=50", "isDefault": true }, "isPlusUser": false, "language": "zh_CN", "verified": false }
 * ```
 *
 * `getUserAttributes()`:
 *
    Array
    (
        [kind] => plus#person
        [etag] => "FT7X6cYw9BSnPtIywEFNNGVVdio/m83Ca7xVMoDVrGZ6EOj8pdlzKbM"
        [emails] => Array
            (
                [0] => Array
                    (
                        [value] => service.brainbook.cc@gmail.com
                        [type] => account
                    )

            )

        [objectType] => person
        [id] => 113544724474573231306
        [displayName] => service brainbook
        [name] => Array
            (
                [familyName] => brainbook
                [givenName] => service
            )

        [image] => Array
            (
                [url] => https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg?sz=50
                [isDefault] => 1
            )

        [isPlusUser] => 
        [language] => zh_CN
        [verified] => 
        [openid] => 113544724474573231306
        [email] => service.brainbook.cc@gmail.com
        [fullname] => service brainbook
        [firstname] => service
        [lastname] => brainbook
        [gender] => 
        [avatarUrl] => https://lh3.googleusercontent.com/-XdUIqdMkCWA/AAAAAAAAAAI/AAAAAAAAAAA/4252rscbv5M/photo.jpg?sz=50
    )
 * ```
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
