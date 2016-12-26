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

class ChangeDateFormate extends Component {

      public function DateFormat($model) {
            if (!empty($model)) {
                  $a = ['id', 'appointment_id', 'additional_info', 'comments', 'status', 'type', 'data_id', 'label', 'CB', 'UB', 'DOC', 'fwd_arrival_unit', 'fwd_arrival_quantity', 'aft_arrival_unit',
                      'aft_arrival_quantity', 'mean_arrival_unit', 'mean_arrival_quantity', 'fwd_sailing_unit', 'fwd_sailing_quantity', 'aft_sailing_unit', 'aft_sailing_quantity',
                      'mean_sailing_unit', 'mean_sailing_quantity',];
                  foreach ($model->attributes as $key => $dta) {
                        if (!in_array($key, $a)) {
                              $model->$key = $this->SingleDateFormat($dta);
                        }
                  }
                  return $model;
            }
      }

      public function ChangeFormat($data) {

            $day = substr($data, 0, 2);
            $month = substr($data, 2, 2);
            $year = substr($data, 4, 4);
            $hour = substr($data, 9, 2) == '' ? '00' : substr($data, 9, 2);
            $min = substr($data, 11, 2) == '' ? '00' : substr($data, 11, 2);
            $sec = substr($data, 13, 2) == '' ? '00' : substr($data, 13, 2);
            if ($hour != '00' && $min != '00' && $sec != '00') {
                  //echo '1';exit;
                  return $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min . ':' . $sec;
            } elseif ($hour == '00' && $min != '00') {
                  //echo '2';exit;
                  return $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min;
            } elseif ($hour != '00' && $min != '00') {
                  //echo '2';exit;
                  return $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min;
            } elseif ($hour != '00') {
                  //echo '3';exit;
                  return $year . '-' . $month . '-' . $day . ' ' . $hour . ':00';
            } else {

                  return $year . '-' . $month . '-' . $day;
            }
      }

      public function SingleDateFormat($dta) {
            if (strpos($dta, '-') == false) {

                  if (strlen($dta) < 16 && strlen($dta) >= 8 && $dta != NULL)
                        return $this->ChangeFormat($dta);
                  //echo $model->$key;exit;
            }else {
                  $year = substr($dta, 0, 4);
                  $month = substr($dta, 5, 2);
                  $day = substr($dta, 8, 2);
                  $hour = substr($dta, 11, 2) == '' ? '00' : substr($dta, 11, 2);
                  $min = substr($dta, 14, 2) == '' ? '00' : substr($dta, 14, 2);
                  $sec = substr($dta, 17, 2) == '' ? '00' : substr($dta, 17, 2);

                  if ($hour != '00' && $min != '00' && $sec != '00') {
                        return $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min . ':' . $sec;
                  } elseif ($hour == '00' && $min != '00') {
                        //echo '2';exit;
                        return $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min;
                  } elseif ($hour != '00' && $min != '00') {
                        return $year . '-' . $month . '-' . $day . ' ' . $hour . ':' . $min;
                  } elseif ($hour != '00') {
                        return $year . '-' . $month . '-' . $day . ' ' . $hour . ':00';
                  } else {
                        return $year . '-' . $month . '-' . $day;
                  }
            }
      }

}
