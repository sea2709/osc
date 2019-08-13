/**
mje-request.js
*/

(function($, Models, Collections, Views) {
	 Models.MRequest = Backbone.Model.extend({
            action: 'ae_mje_offer_sync',
            initialize: function() {}
     });

     Views.archiveRequest =     Backbone.View.extend({
        el: 'body',
        model: [],
        events: {
            //'click .request-paginations a.page-numbers': 'selectPage',
            'click .btn-archive-request': 'actArchivedARequest',

        },
        initialize: function (options) {
            console.log('init single request');

            this.blockUi    = new Views.BlockUi();

        },
        actArchivedARequest: function(event){

            var c = confirm(mje_request.archive_confirm);
            var _this = $(event.currentTarget);
            var re_id = _this.attr('request-id');
            if(c){
                $.ajax({
                    url: ae_globals.ajaxURL,
                    type: 'post',
                    data: {
                        action: 'archive-request',
                        ID: re_id,
                    },
                    beforeSend: function () {
                    },
                    success: function (res) {
                        $('.tags').html(res);
                    }
                });
            }
            return false;
        },

        selectPage: function(event) {
            event.preventDefault();
            var $target = $(event.currentTarget),
                page = parseInt($target.text().replace(/,/g, '')),
                view = this;
            if ($target.hasClass('current') || $target.hasClass('next') || $target.hasClass('prev')) return;
            view.page = page;
            // fetch posts
            view.fetch($target);
            //scroll to block control id
            $('html, body').animate({
                scrollTop: view.$el.offset().top - 180
            }, 800);

        },
        fetch: function($target) {
            var view = this,
                page = view.page;
            view.collection.fetch({
                wait: true,
                remove: true,
                reset: true,
                data: {
                    query: view.query,
                    page: view.page,
                    paged: view.page,
                    paginate: view.query.paginate,
                    thumbnail: view.thumbnail,
                },
                beforeSend: function() {
                    var blockedTarget = $target;

                    if(view.blockedEl != '') {
                      blockedTarget = view.blockedEl;
                    } else if($target.hasClass('multi-tax-item') || $target.hasClass('is-chosen')) {
                        if ($target.next('.chosen-container').length > 0) {
                          blockedTarget = $target.next('.chosen-container');
                        }
                    }

                    view.blockUi.block(blockedTarget);

                    view.triggerMethod("before:fetch");
                },
                success: function(result, res, xhr) {
                    view.blockUi.unblock();
                    // view.collection.reset();
                    if (res && !res.success) {
                        //view.$('.paginations').remove();
                        view.$('.paginations-wrapper').hide();
                        view.$('.paginations').remove();
                        view.$('.found_post').html(0);
                        view.$('.plural').addClass('hide');
                        view.$('.singular').removeClass('hide');
                    } else {
                        view.$('.paginations-wrapper').show();
                        view.$('.paginations-wrapper').html(res.paginate);
                        $('#place-status').html(res.status);
                        view.$('.found_post').html(res.total);
                        if (res.total > 1) {
                            view.$('.plural').removeClass('hide');
                            view.$('.singular').addClass('hide');
                        } else {
                            view.$('.plural').addClass('hide');
                            view.$('.singular').removeClass('hide');
                        }
                        view.switchTo();
                    }

                    // Focus out
                    if($target.hasClass('multi-tax-item') || $target.hasClass('is-chosen')) {
                        var chosenContainer = $target.next('.chosen-container');
                        chosenContainer.find('.search-field input').blur();
                    }

                    AE.pubsub.trigger('ae:on:after:fetch', result, res, $target);
                    view.triggerMethod("after:fetch", result, res, $target);

                }
            });
        },

    });
	Views.singleRequest = Backbone.View.extend({
        el: 'body',
        model: [],
        events: {
            'click .full-text .show-less': 'showLessDescription',
            'submit .js-submit-offer': 'submitOffer',

        },
        initialize: function (options) {

            this.blockUi    = new Views.BlockUi();
        	this.mjeRequest = new Models.MRequest({post_status:'publish'});
        },
        submitOffer: function(event){
        	event.preventDefault();
            var view 		= this,
                $target 		= $(event.currentTarget),
                temp 		= new Array();

            $target.find('input,textarea,select').each(function() {
                view.mjeRequest.set($(this).attr('name'), $(this).val());
            });
            $target.find('select option:selected').each(function() {
                view.mjeRequest.set('mjob_title', $(this).text() );
            });


            if( this.$("form.js-submit-offer").validate() && view.customValidate() )  {
                view.mjeRequest.save('', '', {
                    beforeSend: function() {
                        view.blockUi.block($target.find('.btn-save'));
                        //view.loading();
                    },
                    success: function(result, res, jqXHR) {
                        view.blockUi.unblock();
                        if (res.success) {
                            //view.updateOpeningMessage(res.msg, 10);

                            AE.pubsub.trigger('ae:after:edit:mjob', result, res, jqXHR);
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: 'success'
                            });
                            window.location.reload(true);
                        } else {
                            AE.pubsub.trigger('ae:notification', {
                                msg: res.msg,
                                notice_type: 'error'
                            });
                        }

                    }
                });
            }
        	return false;
        },
         customValidate: function(){
                var view = this;

                return true;
            },
    });
	$(document).ready(function(){
		new Views.singleRequest();
        new Views.archiveRequest();
	})
})(jQuery, window.AE.Models, window.AE.Collections, window.AE.Views);