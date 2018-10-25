<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'css/site.css',
        
        'css/style.css',        
        'css/checkbox.css',
        'css/radio.css',
        'css/style.css',
        
        'plugins/material-form/css/jquery.material.form.css',
        'plugins/infinite-li-scroll/css/style.css',
        'plugins/frosted-glass-effect-master/css/style.css',
        'plugins/iziModal-master/css/iziModal.min.css',
        'plugins/slick/slick.css',
        'plugins/Smooth-Div-Scroll-master/css/smoothDivScroll.css',
        'plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css',
        'plugins/starrr-gh-pages/dist/starrr.css',
        
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
        'http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.2.0/css/font-awesome.min.css',
        
        
    ];
    public $js = [
        'js/common.js',
        
        'plugins/material-form/js/jquery.material.form.js',
        'plugins/infinite-li-scroll/js/index.js',
        'plugins/iziModal-master/js/iziModal.js',
        'plugins/Smooth-Div-Scroll-master/js/jquery-ui-1.10.3.custom.min.js',
        'plugins/Smooth-Div-Scroll-master/js/jquery.mousewheel.min.js',
        'plugins/Smooth-Div-Scroll-master/js/jquery.kinetic.min.js',
        'plugins/Smooth-Div-Scroll-master/js/jquery.smoothdivscroll-1.3-min.js',
//        'plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.js',
        'plugins/autosize-master/dist/autosize.js',
        'plugins/slick/slick.js',
        'plugins/starrr-gh-pages/dist/starrr.js',
        
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];
} 