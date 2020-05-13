/*
 * $.D3PDFObject
 */
var D3PDFObject = function(options){

    /*
     * Variables accessible
     * in the class
     */
    this.pdfOptions = {};
    this.domElements = {
            wrapper: $('.pdfobject-wrapper'),
            embed: $('.pdfobject-embed'),
            closeButton: $('.pdfobject-close-button')
        };
    this.contentTarget = null;
    this.loadingSpinnerClass = null;

    /*
     * Can access this.method
     * inside other methods using
     * root.method()
     */
    var self = this;

    /*
     * Constructor
     */
    this.construct = function(options){
        var self = this;
        $.each(options, function (o, v) {
            self[o] = v;
        })
    };

    /*
     * Pass options when class instantiated
     */
    this.construct(options);

    if (self.loadingSpinnerClass) {
        self.domElements.spinner = $(self.loadingSpinnerClass);
    }
};

//assigning an object literal to the prototype is a shorter syntax
//than assigning one property at a time
D3PDFObject.prototype = {
    embed: function(url) {
        return PDFObject.embed(url, this.contentTarget, this.pdfOptions);
    },
    setContentTarget: function(e) {
        this.contentTarget = e;
    },
    setPdfOptions: function(o) {
        this.pdfOptions = o;
    },
    setDOMElements: function(e) {
        this.domElements = e;
    },
    trigger: function(url) {
        var targetEl = $(this.contentTarget),
            err = false;

        if ("undefined" === typeof PDFObject) {
            console.log('Missing PDFObject! Asset pdfobject.min.js not loaded?');
            return false;
        }

        if("undefined" == typeof url) {
            console.log("Missing attribute for PDF source URL");
            err = true;
        }

        
        if(targetEl.length === 0) {
            console.log("Target element not found: " + this.contentTarget);
            err = true;
        }

        if(err) {
            return false;
        }

        if (this.domElements.spinner) {
            this.domElements.spinner.show();
        }
        this.domElements.wrapper.show();

        if (this.embed(url) && this.domElements.spinner) {
            this.domElements.spinner.hide();
        }

        if("undefined" !== typeof callback) {
            // @TODO - callback action
        }
    },
    clear: function(el) {
        $(el).html('');
    },
    listenOnChange: function(el, targetElSel) {
        var self = this;
        $(el).on('change', function (e) {
            self.clear(targetElSel);
            var url = $(self).find(':selected').data('src');
            self.trigger(url);
        });
    },
    listenOnClick: function(el, targetElSel) {
        // Make class accesible into event
        var self = this;
        $(el).on('click', function (e) {
            e.preventDefault();
            self.clear(targetElSel);
            var url = $(this).data('src');
            self.trigger(url);
        });
    },
    initHandlers: function() {
        var self = this;
        this.domElements.closeButton.on('click', function (e) {
            e.preventDefault();
            self.domElements.wrapper.hide();
        });
    }
};
/*
(function ($) {
    "use strict";

}(jQuery));*/