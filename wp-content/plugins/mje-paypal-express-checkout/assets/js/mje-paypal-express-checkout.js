(function($, Views, Models) {
  Views.PayPalExpressCheckout = Backbone.View.extend({
    el: '.list-payment-gateway',
    initialize: function() {
      this.checkoutData = '';
      this.setupCheckoutButton();

      AE.pubsub.on('mje:after:setup:checkout', this.afterSetupCheckout, this);
    },
    afterSetupCheckout: function(data) {
      this.checkoutData = data;
      this.productData = this.checkoutData.get('p_data');
      this.productData.payment_type = 'paypal_express_checkout';
      this.checkoutData.set('p_payment', 'paypal_express_checkout');
    },
    setupCheckoutButton: function() {
      var _this = this;
      paypal.Button.render({
        env: mje_ppec.env, // Specify 'sandbox' for the test environment

        style: {
          size: mje_ppec.button.size,
          color: mje_ppec.button.color,
          shape: mje_ppec.button.shape,
          label: mje_ppec.button.label
        },

        payment: function(resolve, reject) {
          _this.checkoutData.save('', '', {
            beforeSend: function() {},
            success: function(status, response, xhr) {
              if( response.data.paymentID === null ) {
                // Reject payment flow
                AE.pubsub.trigger('ae:notification', {
                  msg: response.data.msg,
                  notice_type: 'error'
                });
                reject();
              } else {
                // Request to checkout
                resolve(response.data.paymentID)
              }
            }
          });
        },

        onAuthorize: function(data, actions) {
          // Redirect to execute payment page
          return actions.redirect();
        },
        onCancel: function(data, actions) {
          return actions.redirect();
        },
        onError: function(err) {
          console.log(err);
        }
      }, '#paypal-button');
    }
  });

  $(document).ready(function() {
    new Views.PayPalExpressCheckout();
  });
})(jQuery, window.AE.Views, window.AE.Models)