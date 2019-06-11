function embedPDF(url, target, options)
{
    var pdf = PDFObject.embed(url, target, options);
    return pdf;
}

function loadContent(url, targetElSel, pdfOptions, callback) {
    var targetEl = $(targetElSel),
        err = false;

    if("undefined" == typeof url) {
        console.log("Missing attribute for PDF source URL");
        err = true;
    }

    if(targetEl.length === 0) {
        console.log("Target element not found: " + targetElSel);
        err = true;
    }

    if(err) {
        return false;
    }

    targetEl.html('<div class=\'spinner\'><div class=\'mask\'></div><div id=\'loader\'></div></div>');

    embedPDF(url, targetElSel, pdfOptions);

    var parentCollapsible = targetEl.parent(".collapse");

    if("undefined" != typeof parentCollapsible) {
        parentCollapsible.collapse('toggle');
    }

    if("undefined" !== typeof callback) {
        // @TODO - callback action
    }
}

function clearTarget(el) {
    $(el).html('');
}

function listenOnChange(el, targetElSel, pdfOptions, callback)
{
    $(el).on('change', function (e) {
        clearTarget(targetElSel);
        var url = $(this).find(':selected').data('src');
        loadContent(url, targetElSel, pdfOptions, callback);
    });
}

function listenOnClick(el, targetElSel, pdfOptions, callback)
{
    $(el).on('click', function (e) {
        e.preventDefault();
        clearTarget(targetElSel);
        var url = $(this).data('src');
        loadContent(url, targetElSel, pdfOptions, callback);
    });
}

$(document).ready(function () {
    $(".embed-pdf-close-button ").on('click', function (e) {
        e.preventDefault();
        $(this).parent(".collapse").collapse('toggle');
    });
});