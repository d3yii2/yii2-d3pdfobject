function embedPDF(url, target, options)
{
    var pdf = PDFObject.embed(url, target, options);
    return pdf;
}

function listenOnClick(listenElSel, targetElSel, pdfOptions, callback)
{
    $(document).ready(function () {
        var le = $(listenElSel),
            targetEl = $(targetElSel);

        $(le).on('click', function (e) {
            e.preventDefault();

            var url = $(this).data('src');

            if("undefined" == url) {
                console.log("Missing attribute for PDF source URL");
                return false;
            }
            
            if("undefined" == targetEl) {
                console.log("Target element nor found: " + targetElSel);
                return false;
            }

            embedPDF(url, targetElSel, pdfOptions);
            
            var parentCollapsible = targetEl.parent(".collapse");

            if("undefined" != parentCollapsible) {
                parentCollapsible.collapse('toggle');
            }
            
            if("undefined" !== callback) {
                // @TODO - callback action
            }
        });
    });
}

$(document).ready(function () {
    $(".embed-pdf-close-button ").on('click', function (e) {
        e.preventDefault();
        $(this).parent(".collapse").collapse('toggle');
    });
});