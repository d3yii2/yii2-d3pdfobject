<?php
namespace laurism\pdfobject\widgets;

use laurism\pdfobject\PDFObjectAsset;
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
    public $listenElement;
    public $targetElement;
    public $listenEvent;
    public $closeButtonOptions;
    
    public function init() {
        parent::init();
        PDFObjectAsset::register( $this->getView() );

        // By default PDF will be loaded into container having id: embed-pdf
        $this->targetElement = '.embed-pdf-content';

        $this->listenEvent = 'onclick';
        
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
        ?>
        <div class="collapse" class="embed-pdf">
            <?= \yii\bootstrap\Button::widget($buttonOptions) ?>
            <div class="embed-pdf-content card card-body"></div>
        </div>
        <?php
    }
    
    public function registerScript($js)
    {
        $this->view->registerJs($js . ';', $this->view::POS_END);
    }
    
    public function getListenerScript()
    {
        $listenElement = addslashes($this->listenElement);
        $targetElement = addslashes($this->targetElement);

        switch ($this->listenEvent) {
            case 'onclick':
                $script  = 'listenOnClick("'
                    . $listenElement . '", "' . $targetElement . '", ' . $this->getOptions()
                    . ');';
        }
        
        return $script;
    }
    
    public function getOptions()
    {
        $defaultOptions = [
            'pdfOpenParams' => [
                'navpanes' => 0,
                'toolbar' => 0,
                'statusbar' => 0,
                'view' => 'FitH',
            ]
        ];

        $options = Json::encode(array_merge($defaultOptions, $this->pdfOptions));
        
        return $options;
    }
}
