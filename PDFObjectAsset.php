<?php
namespace d3yii2\pdfobject;

use yii\web\AssetBundle;

class PDFObjectAsset extends AssetBundle
{
    public $sourcePath = '@vendor/d3yii2/pdfobject/assets';
    public $basePath = '@webroot';
    public $css = [
        'css/pdfobject-custom.css',
    ];
    public $js = [
        'js/pdfobject.min.js',
        'js/pdfobject-custom.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',        
    ];
}