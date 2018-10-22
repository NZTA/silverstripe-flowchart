window.addEventListener('DOMContentLoaded', function() {
    jQuery(function () {
        var scrollTo = function (element) {
            jQuery('html, body').animate({
                scrollTop: element.offset().top
            }, 500);
        };

        var hideQuestion = function (questionElement) {
            questionElement.addClass('flowchart__question--hidden');

            var selected = questionElement.find('.flowchart__question-responses button.selected').first();

            if (selected.length) {
                hideQuestion(
                    jQuery('.flowchart__question[data-flowchart-question-id="' + selected.attr('data-next-question-id') + '"]')
                );

                selected.removeClass('selected');
            }
        };

        jQuery('.flowchart__question-responses button').on('click', function (ev) {
            var next = jQuery(this).attr('data-next-question-id');
            var nextQuestion = null;
            var selected = jQuery(this).parent().find('.selected').first();

            if (selected.length) {
                hideQuestion(
                    jQuery('.flowchart__question[data-flowchart-question-id="' + selected.attr('data-next-question-id') + '"]')
                );

                selected.removeClass('selected');
            }

            if (next) {
                nextQuestion = jQuery('.flowchart__question[data-flowchart-question-id="' + next + '"]');
            }

            if (nextQuestion) {
                nextQuestion.removeClass('flowchart__question--hidden');
                jQuery(this).addClass('selected');

                if (nextQuestion.attr('data-flowchart-end')) {
                    jQuery('.flowchart__form').show();
                }

                scrollTo(nextQuestion);
            }
        });

        jQuery('.flowchart__question-content-more').on('click', function (ev) {
            ev.preventDefault();
            var info = jQuery(this).parent().parent().find('.flowchart__question-info');

            if (info) {
                if (info.is(':visible')) {
                    info.slideUp();
                } else {
                    info.slideDown();
                }
            }
        });

        jQuery('.flowchart__form').on('submit', function (ev) {
            ev.preventDefault();

            var url = document.location.protocol + '//' + document.location.hostname + '/_flowchart/form';
            var vote = null;
            var feedback = null;
            var token = null;
            var id = jQuery(this).attr('data-flowchart-id');

            if (jQuery('input:radio[name="vote"]')) {
                vote = jQuery('input:radio[name="vote"]:checked').val();
            }

            if (jQuery('textarea[name="feedback"]')) {
                feedback = jQuery('textarea[name="feedback"]').val();
            }

            if (feedback || vote) {
                token = jQuery('input[name="securityToken"]').val();

                var postData = {
                    vote: vote,
                    feedback: feedback,
                    token: token,
                    id: id,
                };

                jQuery.post(url, postData, function (data) {
                    if (data.error) {
                        jQuery('.flowchart__form-message').html(data.error);
                    } else {
                        jQuery('.flowchart__form-message').html(data.message);
                    }
                });
            }
        });
    });
}, true);
