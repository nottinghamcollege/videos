var Dukt = {};

var Dukt = {
    ajaxEndpoint:false,

    getUrl: function (action)
    {
        return Craft.getUrl(action);
    },

    getActionUrl: function (action)
    {
        return Craft.getActionUrl(action);
    },

    postActionRequest: function (action, data, callback, options)
    {
        return Craft.postActionRequest(action, data, callback, options);
    }
};


// Base

Dukt.Base = Garnish.Base;

