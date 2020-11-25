<?php

namespace d3yii2\pdfobject\widgets;

use d3yii2\pdfobject\PDFObjectAsset;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * PDFObject Widget
 * PDFObject is a lightweight JavaScript utility for dynamically embedding PDFs in HTML documents.
 * Homepage: http://pdfobject.com/
 * Github: https://github.com/pipwerks/PDFObject/
 * @var array $pdfOptions
 * @var string $targetElementClass
 * @var array $listenEvents
 * @var boolean $renderLayout
 * @var array $closeButtonOptions
 * @var array $wrapperHtmlOptions
 * @var array $headerHtmlOptions
 * @var bool $showCloseButton
 * @var string LAYOUT_PREFIX
 * @var string LOAD_BUTTON_CLASS
 * @var string CONTENT_CLASS
 * @var string LISTEN_ON_CLICK
 * @var string LISTEN_ON_CHANGE
 */
class PDFObject extends \yii\base\Widget
{
    public $pdfOptions = [];
    public $targetElementClass = self::CONTENT_CLASS;
    public $listenEvents = [];
    public $renderLayout = true;
    public $closeButtonOptions = [];
    public $wrapperHtmlOptions = [];
    public $headerHtmlOptions = [];
    public $showCloseButton = true;
    public $loadingSpinnerClass = 'spinner';

    const LAYOUT_PREFIX = 'pdfobject';
    const LOAD_BUTTON_CLASS = self::LAYOUT_PREFIX . '-load-button';
    const CONTENT_CLASS = self::LAYOUT_PREFIX . '-embed';
    const LISTEN_ON_CLICK = 'click';
    const LISTEN_ON_CHANGE = 'change';

    /**
     * Set the default options and register the scripts
     */
    public function init(): void
    {
        parent::init();

        $this->listenEvents = array_merge(
            ['.' . self::LOAD_BUTTON_CLASS => self::LISTEN_ON_CLICK],
            $this->listenEvents
        );

        $this->wrapperHtmlOptions = array_merge(['style' => 'height: 100%'], $this->wrapperHtmlOptions);

        $this->closeButtonOptions = array_merge(
            [
                'label' => 'Close',
                'options' => ['class' => '' . self::LAYOUT_PREFIX . '-close-button btn-md btn-primary pull-right'],
            ],
            $this->closeButtonOptions
        );

        $this->pdfOptions = array_merge_recursive(
            [
                'pdfOpenParams' => [
                        'navpanes' => 0,
                        'toolbar' => 0,
                        'statusbar' => 0,
                        'view' => 'FitV',
                ]
            ],
            $this->pdfOptions
        );

        PDFObjectAsset::register($this->getView());

        $script = $this->getInlineScripts();

        $this->registerScript($script);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function run(): string
    {
        $content = '';

        if ($this->renderLayout) {
            $content = $this->getLayout();
        }

        return $content;
    }

    public function getLayout()
    {
        $content = '
            <div class="' . self::LAYOUT_PREFIX . '-wrapper" ' . Html::renderTagAttributes($this->wrapperHtmlOptions) . '>
                <div class="' . self::LAYOUT_PREFIX . '-header" ' . Html::renderTagAttributes($this->headerHtmlOptions) . '>';
            if ($this->showCloseButton) {
                $content .= \yii\bootstrap\Button::widget($this->closeButtonOptions);
            }
            $content .= '
                   </div>
                <div class="' . $this->targetElementClass . '"></div>
            </div>';

         return $content;
    }

    /**
     * Register inline Javascripts
     * @param string $js
     */
    public function registerScript(string $js): void
    {
        $this->view->registerJs($js . ';', $this->view::POS_READY);
    }

    /**
     * Get the content of inline scripts
     * @return string
     */
    public function getInlineScripts(): string
    {
        $options = Json::encode([
            'contentTarget' => '.' . $this->targetElementClass,
            'pdfOptions' => Json::encode($this->pdfOptions),
            'loadingSpinnerClass' => '.' . $this->loadingSpinnerClass,
        ]);

        $script = 'D3PDF = new D3PDFObject(' . $options . ');D3PDF.initHandlers();';

        foreach ($this->listenEvents as $selector => $event) {
            $listen = 'D3PDF.listenOn' . ucfirst($event) . '("'
                . addslashes($selector) . '");';

            // Reinitialize events after pajax call
            $script .= $listen . " 
                jQuery(document).on('pjax:success', function() {
                   $listen
                });
            ";
        }

        return $script;
    }
}
