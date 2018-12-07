
'use strict';

define(['myApp'], function (app) {

    var configurationService = function ($rootScope) {
    	
    	this.server = 'localhost';
        this.port = ':8080';
        
        // Local Host
        this.app = '/openschool/rest/';
        
        // Cloud Foundry
    	//this.app = '/rest/';
        this.loginCookieStoreKey = 'user.info.cookie.store.key';
        this.baseUrl = 'http://' + this.server + this.port + this.app;
    	this.wsBaseUrl = 'ws://' + this.server + this.port +'/openschool/';
    	this.loginMetaData = 'loginMetaData';
		this.dashboard = this.app + 'dashboard';
		this.login = this.app + 'security/useraccess';
		this.userRestUrl = this.app + 'user/post';
		this.roleRestUrl = this.app + 'role/post';
		this.featureRestUrl = this.app + 'feature/post';
		
		this.saleRestUrl = this.app + 'sale/post';
    };
    
    app.service('configurationService', ['$rootScope', configurationService]);

});


