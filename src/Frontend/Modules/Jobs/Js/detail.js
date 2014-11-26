$(document).ready(function() {
	$(".colorbox").colorbox({
        maxWidth: '95%',
        maxHeight: '95%',
        current: jsData.jobs.current,
        previous: jsData.jobs.previous,
        next: jsData.jobs.next,
        close: jsData.jobs.close,
        xhrError: jsData.jobs.xhrError,
        imgError: jsData.jobs.imgError
    });
});
