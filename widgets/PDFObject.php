<?php
namespace d3yii2\pdfobject\widgets;

use d3yii2\pdfobject\PDFObjectAsset;
use yii\helpers\Json;

/**
 * PDFObject Widget
 * PDFObject is a lightweight JavaScript utility for dynamically embedding PDFs in HTML documents.
 * Homepage: http://pdfobject.com/
 * Github: https://github.com/pipwerks/PDFObject/
 */
class PDFObject extends \yii\base\Widget
{
    public $pdfOptions = [];
    public $targetElementClass = self::TARGET_ELEMENT_DEFAULT;
    public $listenEvents = [];
    public $renderCollapsible = true;
    public $closeButtonOptions = [];

    const LISTEN_ELEMENT_DEFAULT = '[data-embed-pdf]';
    const TARGET_ELEMENT_DEFAULT = 'embed-pdf-content';
    const LISTEN_EVENT_CLICK = 'click';
    const LISTEN_EVENT_CHANGE = 'change';

    public function init() {
        parent::init();

        $defaultListenEvents = [self::LISTEN_ELEMENT_DEFAULT => self::LISTEN_EVENT_CLICK];

        $this->listenEvents = array_merge($defaultListenEvents, $this->listenEvents);

        PDFObjectAsset::register( $this->getView() );

        $script = $this->getListenerScript();

        $this->registerScript($script);
    }

    public function run()
    {
        $defaultButtonOptions = [
            'label' => 'Close',
            'options' => ['class' => 'embed-pdf-close-button btn-md btn-primary pull-right'],
        ];

        $buttonOptions = array_merge($defaultButtonOptions, $this->closeButtonOptions);

        if ($this->renderCollapsible): ?>
            <div class="collapse" class="embed-pdf">
                <?= \yii\bootstrap\Button::widget($buttonOptions) ?>
                <div class="<?= self::TARGET_ELEMENT_DEFAULT ?> card card-body"></div>
            </div>
        <?php
        endif;
    }
    
    public function registerScript($js)
    {
        $this->view->registerJs($js . ';', $this->view::POS_END);
    }
    
    public function getListenerScript()
    {
        $script = '';

        foreach($this->listenEvents as $selector => $event) {
            $listen  = 'listenOn' . ucfirst($event) . '("'
                    . addslashes($selector) . '", ".' . addslashes($this->targetElementClass) . '", ' . $this->getOptions()
                    . ');';

            // Reinitialize events after pajax call
            $script .= $listen . " 
                $(document).on('pjax:success', function() {
                   $listen
                });
            ";
        }
        
        return $script;
    }
    
    public function getOptions(): string
    {
        $defaultOptions = [
            'pdfOpenParams' => [
                'navpanes' => 0,
                'toolbar' => 0,
                'statusbar' => 0,
                'view' => 'FitH',
            ]
        ];

        return Json::encode(array_merge($defaultOptions, $this->pdfOptions));


    }
}
