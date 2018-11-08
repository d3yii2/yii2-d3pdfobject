PDFObject
=========
Wrapper for the  PDFObject -  A lightweight JavaScript utility for dynamically embedding PDFs in HTML documents. http://pdfobject.com/

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist laurism/pdfobject "*"
```

or add

```
"laurism/pdfobject": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \laurism\pdfobject\PDFObject::widget(); ?>
```