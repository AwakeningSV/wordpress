import countdown from "./libs/countdown.min";

document.documentElement.addEventListener("jenner:load", () => {
    const $ = jQuery;
    var streams = $(".announce-upcoming");
    if (!streams.length) return;

    $.each(streams, function (index, stream) {
        stream = $(stream);
        var start = parseInt(stream.attr("data-livestart"), 10);

        if (start) {
            var interval;

            start = start * 1000;

            function updater(timer) {
                if (timer.value > 0) {
                    stream
                        .find(".announce-when:first")
                        .html("in " + timer.toString());
                } else {
                    clearInterval(interval);
                    stream
                        .removeClass("announce-upcoming")
                        .addClass("announce-sunday")
                        .addClass("announce-sunday-live");
                    stream.find("b:first").html("Live");
                    stream
                        .find(".announce-when:first")
                        .html("Live stream starting now");
                }
            }

            interval = countdown(
                updater,
                start,
                countdown.DAYS | countdown.MINUTES | countdown.HOURS
            );
        }
    });
});
