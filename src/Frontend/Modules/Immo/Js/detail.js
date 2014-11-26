$(document).ready(function() {
	$(".colorbox").colorbox({
        maxWidth: '95%',
        maxHeight: '95%',
        current: jsData.immo.current,
        previous: jsData.immo.previous,
        next: jsData.immo.next,
        close: jsData.immo.close,
        xhrError: jsData.immo.xhrError,
        imgError: jsData.immo.imgError
    });
});
