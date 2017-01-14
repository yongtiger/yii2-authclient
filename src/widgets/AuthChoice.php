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

use Yii;
use yii\helpers\Json;
use yii\helpers\Html;
use yongtiger\authclient\widgets\AuthChoiceAsset;
use yongtiger\authclient\widgets\AuthChoiceStyleAsset;

/**
 * @inheritdoc
 */
class AuthChoice extends \yii\authclient\widgets\AuthChoice
{
    /**
     * Initializes the widget.
     */
    public function init()
    {
        $view = Yii::$app->getView();
        if ($this->popupMode) {
            AuthChoiceAsset::register($view);   ///[yongtiger/yii2-authclient]overriding by yongtiger\authclient\widgets\AuthChoiceAsset
            if (empty($this->clientOptions)) {
                $options = '';
            } else {
                $options = Json::htmlEncode($this->clientOptions);
            }
            $view->registerJs("\$('#" . $this->getId() . "').authchoice({$options});");
        } else {
            AuthChoiceStyleAsset::register($view);  ///[yongtiger/yii2-authclient]overriding by yongtiger\authclient\widgets\AuthChoiceStyleAsset
        }
        $this->options['id'] = $this->getId();
        echo Html::beginTag('div', $this->options);
    }
}