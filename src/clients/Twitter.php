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
 * Twitter OAuth1
 *
 * In order to use Twitter OAuth1 you must register your application at <https://dev.twitter.com/apps>.
 *
 * Note: Be sure to select `Request email addresses from users` in your `Twitter Additional Permissions`!
 *
 * Note: Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing, but CANNOT contain `&`.
 *
 * Sample `Callback URL`:
 *
 * `http://localhost/1_oauth/frontend/web/index.php/site/auth` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php/site/auth?authclient=twitter` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth` (OK)
 * `http://localhost/1_oauth/frontend/web/index.php?r=site/auth&authclient=twitter` (WRONG!)
 * `http://localhost/1_oauth/frontend/web/index.php` (WRONG!)
 * `http://localhost/1_oauth/frontend/web` (WRONG!)
 * `http://localhost` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php/site/auth` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php/site/auth?authclient=twitter` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php?site=auth` (OK)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php?r=site/auth&authclient=twitter` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web/index.php` (WRONG!)
 * `http://127.0.0.1/1_oauth/frontend/web` (WRONG!)
 * `http://127.0.0.1` (WRONG!)
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'twitter' => [
 *                 'class' => 'yii\authclient\clients\Twitter',
 *                 'attributeParams' => [
 *                     'include_email' => 'true'
 *                 ],
 *                 'consumerKey' => 'twitter_consumer_key',
 *                 'consumerSecret' => 'twitter_consumer_secret',
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
 * https://api.twitter.com/oauth/authenticate?oauth_token=mjSD9gAAAAAAyyhNAAABWbkNdUM
 * ```
 *
 * AccessToken Request:
 *
 * ```
 * https://api.twitter.com/oauth/access_token
 * ```
 *
 * AccessToken Response:
 *
 * ```
 * oauth_token=821256943540940801-GjY4KCrG5ENZLvnMJO6OUEZECwYx4Gl&oauth_token_secret=dOfpCdgbDP4icildmTrnUY7zEwbh8yTkd1p3oW75w8iWV&user_id=821256943540940801&screen_name=yongtiger2544&x_auth_expires=0
 * ```
 *
 * Request of `initUserAttributes()`:
 *
 * ```
 * https://api.twitter.com/1.1/account/verify_credentials.json?oauth_version=1.0&oauth_nonce=e577bec937312f6b77cac8fe0ad07322&oauth_timestamp=1484868466&include_email=true&oauth_consumer_key=8qsSIMpcPm0UWdBpHWl8bGNKY&oauth_token=821256943540940801-GjY4KCrG5ENZLvnMJO6OUEZECwYx4Gl&oauth_signature_method=HMAC-SHA1&oauth_signature=%2FF8vwhejWyN%2Bk%2FjM28e6CtANukU%3D
 * ```
 *
 * Response of `initUserAttributes()`:
 *
 * ```
 * {"id":821256943540940801,"id_str":"821256943540940801","name":"yongtiger","screen_name":"yongtiger2544","location":"","description":"","url":null,"entities":{"description":{"urls":[]}},"protected":false,"followers_count":4,"friends_count":40,"listed_count":0,"created_at":"Tue Jan 17 07:24:48 +0000 2017","favourites_count":0,"utc_offset":null,"time_zone":null,"geo_enabled":false,"verified":false,"statuses_count":0,"lang":"zh-cn","contributors_enabled":false,"is_translator":false,"is_translation_enabled":false,"profile_background_color":"F5F8FA","profile_background_image_url":null,"profile_background_image_url_https":null,"profile_background_tile":false,"profile_image_url":"http:\/\/abs.twimg.com\/sticky\/default_profile_images\/default_profile_6_normal.png","profile_image_url_https":"https:\/\/abs.twimg.com\/sticky\/default_profile_images\/default_profile_6_normal.png","profile_link_color":"1DA1F2","profile_sidebar_border_color":"C0DEED","profile_sidebar_fill_color":"DDEEF6","profile_text_color":"333333","profile_use_background_image":true,"has_extended_profile":false,"default_profile":true,"default_profile_image":true,"following":false,"follow_request_sent":false,"notifications":false,"translator_type":"none","email":"tigeryang.brainbook@outlook.com"}
 * ```
 *
 * ```php
 * Array
 * (
 *     [id] => 8.2125694354094E+17
 *     [id_str] => 821256943540940801
 *     [name] => yongtiger
 *     [screen_name] => yongtiger2544
 *     [location] => 
 *     [description] => 
 *     [url] => 
 *     [protected] => 
 *     [followers_count] => 4
 *     [friends_count] => 40
 *     [listed_count] => 0
 *     [created_at] => Tue Jan 17 07:24:48 +0000 2017
 *     [favourites_count] => 0
 *     [utc_offset] => 
 *     [time_zone] => 
 *     [geo_enabled] => 
 *     [verified] => 
 *     [statuses_count] => 0
 *     [lang] => zh-cn
 *     [contributors_enabled] => 
 *     [is_translator] => 
 *     [is_translation_enabled] => 
 *     [profile_background_color] => F5F8FA
 *     [profile_background_image_url] => 
 *     [profile_background_image_url_https] => 
 *     [profile_background_tile] => 
 *     [profile_image_url] => http://abs.twimg.com/sticky/default_profile_images/default_profile_6_normal.png
 *     [profile_image_url_https] => https://abs.twimg.com/sticky/default_profile_images/default_profile_6_normal.png
 *     [profile_link_color] => 1DA1F2
 *     [profile_sidebar_border_color] => C0DEED
 *     [profile_sidebar_fill_color] => DDEEF6
 *     [profile_text_color] => 333333
 *     [profile_use_background_image] => 1
 *     [has_extended_profile] => 
 *     [default_profile] => 1
 *     [default_profile_image] => 1
 *     [following] => 
 *     [follow_request_sent] => 
 *     [notifications] => 
 *     [translator_type] => none
 *     [email] => tigeryang.brainbook@outlook.com
 *     [provider] => twitter
 *     [openid] => 821256943540940801
 *     [fullname] => yongtiger
 *     [language] => zh-cn
 *     [avatarUrl] => http://abs.twimg.com/sticky/default_profile_images/default_profile_6_normal.png
 *     [linkUrl] => 
 * )
 * ```
 *
 * [REFERENCES]
 *
 * @see https://dev.twitter.com/oauth
 * @see https://dev.twitter.com/docs/api
 */
class Twitter extends \yii\authclient\clients\Twitter implements IAuth
{
    use ClientTrait;

    /**
     * @inheritdoc
     */
    protected function defaultViewOptions() {
        return [
            'popupWidth' => 720,
            'popupHeight' => 720,
        ];
    }

    /**
     * @inheritdoc
     */
    protected function defaultNormalizeUserAttributeMap() {
        return [
            'provider' => $this->defaultName,
            'openid' => 'id_str',
            'fullname' => 'name',
            'language' => 'lang',
            'avatarUrl' => 'profile_image_url',
            'linkUrl' => 'url',
        ];
    }
}
