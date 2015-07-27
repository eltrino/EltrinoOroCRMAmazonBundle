/* global:define */
define([
    'jquery',
    'underscore',
    'routing',
    'backbone',
    'orotranslation/js/translator',
    'oroui/js/mediator',
    'oroui/js/messenger'
], function($, _, routing, Backbone, __, mediator, messenger) {
    'use strict';

    return Backbone.View.extend({
        events: {
            'click': 'processClick'
        },

        /**
         * Check url
         * @property string
         */
        route: 'orocrm_amazon_check_status',
        url: null,
        id: null,
        requiredOptions: [],

        resultTemplate: _.template(
            '<div class="alert alert-<%= type %> connection-status"><%= message %></div>'
        ),

        connectorTemplate: _.template(
            '<div class="oro-clearfix">' +
            '<input type="checkbox" id="oro_integration_channel_form_connectors_<%= i %>" ' +
            'name="oro_integration_channel_form[connectors][]" value="<%= name %>">' +
            '<label for="oro_integration_channel_form_connectors_<%= i %>"><%= label %></label>' +
            '</div>'
        ),

        initialize: function(options) {
            this.options = _.defaults(options || {}, this.options);
            this.id = options.transportEntityId || null;
            this.url = this.getUrl();

            var requiredMissed = this.requiredOptions.filter(function(option) {
                return _.isUndefined(options[option]);
            });
            if (requiredMissed.length) {
                throw new TypeError('Missing required option(s): ' + requiredMissed.join(','));
            }
        },

        getUrl: function(type) {
            var params = {id: this.id};
            if (type !== undefined) {
                params.type = type;
            }

            return routing.generate(this.route, params);
        },

        /**
         * Click handler
         */
        processClick: function() {
            var data = this.$el.parents('form').serializeArray();
            var typeData = _.filter(data, function(field) {
                return field.name.indexOf('[type]') !== -1;
            });
            if (typeData.length) {
                typeData = typeData[0].value;
            }

            data = _.filter(data, function(field) {
                return field.name.indexOf('[transport]') !== -1;
            });
            data = _.map(data, function(field) {
                field.name = field.name.replace(/.+\[(.+)\]$/, 'check-status[$1]');
                return field;
            });
            mediator.execute('showLoading');
            $.post(this.getUrl(typeData), data, _.bind(this.responseHandler, this), 'json')
                .always(_.bind(function(response, status) {
                    mediator.execute('hideLoading');
                    if(status !== 'success') {
                        this.renderResult('error', __('orocrm.amazon.transport_check_message.error'));
                    }
                }, this));
        },

        /**
         * Handler ajax response
         *
         * @param res {}
         */
        responseHandler: function(res) {
            var success = res.success || false;
            var messagePrefix = 'orocrm.amazon.transport_check_message.';
            var message = success ? messagePrefix + 'success' : messagePrefix + 'invalid';

            if (success) {
                var form = this.$el.parents('form');
            }

            this.renderResult(success ? 'success' : 'error', __(message));
        },

        /**
         * Render check result message
         *
         * @param type string
         * @param message string
         */
        renderResult: function(type, message) {
            messenger.notificationFlashMessage(type, message, {
                container: this.$el.parent(),
                template: this.resultTemplate
            });
        }
    });
});

