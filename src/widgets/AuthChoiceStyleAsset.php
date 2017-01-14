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

namespace yongtiger\authclient\widgets;

use yii\web\AssetBundle;

/**
 * AuthChoiceAsset is an asset bundle for [[AuthChoice]] widget.
 */
class AuthChoiceStyleAsset extends AssetBundle
{
    public $sourcePath = '@yongtiger/authclient/assets';	///[yongtiger/yii2-authclient]
    public $css = [
        'authchoice.css',
    ];
}