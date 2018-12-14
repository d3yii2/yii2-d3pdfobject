PDFObject
=========
Wrapper for the  PDFObject -  A lightweight JavaScript utility for dynamically embedding PDFs in HTML documents. http://pdfobject.com/

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist d3yii2/pdfobject "*"
```

or add

```
"d3yii2/pdfobject": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \d3yii2\pdfobject\PDFObject::widget(); ?>
```