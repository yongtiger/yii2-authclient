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
 * Note:  Authorization `Callback URL` can contain `localhost` or `127.0.0.1` for testing.
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
 *     ...
 * ]
 * ```
 *
 * [EXAMPLE JSON RESPONSE BODY FOR GET]
 * 
 * `$responseContent` at `/vendor/yiisoft/yii2-httpclient/StreamTransport.php`:
 *
 * ```
 * {"id":821256943540940801,"id_str":"821256943540940801","name":"yongtiger","screen_name":"yongtiger254 4","location":"","description":"","url":null,"entities":{"description":{"urls": []}},"protected":false,"followers_count":2,"friends_count":40,"listed_count":0,"created_at":"Tue Jan 17  07:24:48 +0000 2017","favourites_count": 0,"utc_offset":null,"time_zone":null,"geo_enabled":false,"verified":false,"statuses_count":0,"lang":"zh- cn","contributors_enabled":false,"is_translator":false,"is_translation_enabled":false,"profile_background_ color":"F5F8FA","profile_background_image_url":null,"profile_background_image_url_https":null,"profile_ background_tile":false,"profile_image_url":"http:\/\/abs.twimg.com\/sticky\/default_profile_images\/ default_profile_6_normal.png","profile_image_url_https":"https:\/\/abs.twimg.com\/sticky\/ default_profile_images\/ default_profile_6_normal.png","profile_link_color":"1DA1F2","profile_sidebar_border_color":"C0DEED","p rofile_sidebar_fill_color":"DDEEF6","profile_text_color":"333333","profile_use_background_image":true,"h as_extended_profile":false,"default_profile":true,"default_profile_image":true,"following":false,"follow_re quest_sent":false,"notifications":false,"translator_type":"none","email":"tigeryang.brainbook@outlook.co m"}
 * ```
 *
 * getUserAttributes():
 *
 * ```php
    Array
    (
        [id] => 8.2125694354094E+17
        [id_str] => 821256943540940801
        [name] => yongtiger
        [screen_name] => yongtiger2544
        [location] => 
        [description] => 
        [url] => 
        [entities] => Array
            (
                [description] => Array
                    (
                        [urls] => Array
                            (
                            )

                    )

            )

        [protected] => 
        [followers_count] => 0
        [friends_count] => 40
        [listed_count] => 0
        [created_at] => Tue Jan 17 07:24:48 +0000 2017
        [favourites_count] => 0
        [utc_offset] => 
        [time_zone] => 
        [geo_enabled] => 
        [verified] => 
        [statuses_count] => 0
        [lang] => zh-cn
        [contributors_enabled] => 
        [is_translator] => 
        [is_translation_enabled] => 
        [profile_background_color] => F5F8FA
        [profile_background_image_url] => 
        [profile_background_image_url_https] => 
        [profile_background_tile] => 
        [profile_image_url] => http://abs.twimg.com/sticky/default_profile_images/default_profile_6_normal.png
        [profile_image_url_https] => https://abs.twimg.com/sticky/default_profile_images/default_profile_6_normal.png
        [profile_link_color] => 1DA1F2
        [profile_sidebar_border_color] => C0DEED
        [profile_sidebar_fill_color] => DDEEF6
        [profile_text_color] => 333333
        [profile_use_background_image] => 1
        [has_extended_profile] => 
        [default_profile] => 1
        [default_profile_image] => 1
        [following] => 
        [follow_request_sent] => 
        [notifications] => 
        [translator_type] => none
        [email] => tigeryang.brainbook@outlook.com
        [openid] => 821256943540940801
        [fullname] => yongtiger
        [language] => zh-cn
        [avatarUrl] => http://abs.twimg.com/sticky/default_profile_images/default_profile_6_normal.png
        [linkUrl] => 
    )
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
            'openid' => 'id_str',

            'fullname' => 'name',

            'language' => 'lang',

            'avatarUrl' => 'profile_image_url',

            'linkUrl' => 'url',
        ];
    }
}
