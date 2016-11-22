<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UploadFile
 *
 * @author user
 */

namespace common\components;

use Yii;
use yii\base\Component;

class UploadFile extends Component {

        public function Upload($files, $model) {
                if ($files != '' && $model != '') {
                        $folder = $this->folderName(0, 10000, $model->appointment_id);

                        $paths = [Yii::$app->params['mainPath'], Yii::$app->params['appointmentPath'], $folder, $model->appointment_id, $model->type];
                        $path = $this->CheckPath($paths);


                        foreach ($files as $file) {
                                $name = $this->fileExists($path, $file->baseName . '.' . $file->extension, $file, 1);
                                $file->saveAs($path . '/' . $name);
                        }
                        return true;
                } else {
                        return false;
                }
        }

        public function UploadSingle($files, $model, $paths) {
                if ($files != '' && $model != '') {
                        // $folder = $this->folderName(0, 10000, $model->appointment_id);
                        //$paths = [Yii::$app->params['mainPath'], Yii::$app->params['appointmentPath'], $folder, $model->appointment_id, $model->type];
                        $path = $this->CheckPath1($paths);
                        $name = $this->fileExists($path, $files->baseName . '.' . $files->extension, $files, 1);
                        $files->saveAs($path . '/' . $name);
                        return true;
                } else {
                        return false;
                }
        }

        public function ListFile($id, $type) {

                $attach = '';
                $folder = $this->folderName(0, 10000, $id);
                $path = Yii::getAlias(Yii::$app->params['uploadPath']) . '/' . Yii::$app->params['mainPath'] . '/' . Yii::$app->params['appointmentPath'] . '/' . $folder . '/' . $id . '/' . $type;

                foreach (glob("{$path}/*") as $file) {
                        if ($file != '') {
                                $arry = explode('/', $file);
                                $path = Yii::$app->params['mainPath'] . '/' . Yii::$app->params['appointmentPath'] . '/' . $folder . '/' . $id . '/' . $type . '/' . end($arry);
                                $attach .= '<a target="_blank" href="' . Yii::$app->homeUrl . '/' . Yii::$app->params['mainPath'] . '/' . Yii::$app->params['appointmentPath'] . '/' . $folder . '/' . $id . '/' . $type . '/' . end($arry) . '">' . end($arry) . '</a>&nbsp;&nbsp;<a href="' . Yii::$app->homeUrl . Yii::$app->params['appointmentPath'] . '/' . $type . '/' . 'remove?path=' . $path . '"><i class="fa fa-remove"></i></a>&nbsp;&nbsp;|&nbsp;&nbsp;';
                        }
                }
                // echo $attach;exit;
                return $attach;
        }

        public function CheckPath($paths) {
                $root = Yii::getAlias(Yii::$app->params['uploadPath']); /* Yii::$app->basePath; */
                foreach ($paths as $path) {

                        $root .= '/' . $path;
                        if (!is_dir($root))
                                mkdir($root);
                }
                return $root;
        }

        public function CheckPath1($paths) {
                $root = Yii::getAlias(Yii::$app->params['uploadPath']); /* Yii::$app->basePath; */

                $root .= '/' . $paths;
                if (!is_dir($root))
                        mkdir($root);
                return $root;
        }

        public function folderName($min, $max, $id) {
                if ($id > $min && $id < $max) {
                        return $max;
                } else {
                        $xy = $this->folderName($min + 10000, $max + 10000, $id);
                        return $xy;
                }
        }

        public function fileExists($path, $name, $file, $sufix) {
                if (file_exists($path . '/' . $name)) {

                        $name = basename($path . '/' . $file->baseName, '.' . $file->extension) . '_' . $sufix . '.' . $file->extension;
                        return $this->fileExists($path, $name, $file, $sufix + 1);
                } else {
                        return $name;
                }
        }

}
