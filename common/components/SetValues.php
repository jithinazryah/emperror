<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SetValues
 *
 * @author user
 */

namespace common\components;

use Yii;
use yii\base\Component;

class SetValues extends Component {

        public function Attributes($model) {
                if (isset($model) && !Yii::$app->user->isGuest) {

                        if ($model->isNewRecord) {
                                $model->CB = Yii::$app->user->identity->id;
                                $model->DOC = date('Y-m-d');
                        }
                        $model->UB = Yii::$app->user->identity->id;


                        return TRUE;
                } else {
                        return FALSE;
                }
        }

        public function Selected($value) {
                $options = array();
                if (is_array($value)) {
                        $array = $value;
                } else {
                        $array = explode(',', $value);
                }

                foreach ($array as $valuee):
                        $options[$valuee] = ['selected' => true];
                endforeach;
                return $options;
        }

        public function ChangeFormate($date) {
                if ($date == Null || $date == '0000-00-00 00:00:00') {
                        return '(Not Set)';
                } else {
                        return date("d-M-Y h:i:s", strtotime($date));
                }
        }

        public function DateFormate($date) {
                 $old = strtotime('1999-01-01 00:00:00');
                if ($date == Null || $date == '0000-00-00 00:00:00') {
                        return ;
                } else {
                         $f = 'd-M-Y' . (date('H:i:s', strtotime($date)) != '00:00:00' ? ' H:i' : '');
                        return str_replace(' 00:00:00', '', date($f, strtotime($date)));
                }
        }

}
