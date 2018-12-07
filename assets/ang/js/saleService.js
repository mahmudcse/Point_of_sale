
'use strict';

define(['myApp'], function (app) {

	var saleService = function ($resource, $q, configurationService) {
		
		var saleResource, delay ;
        
		saleResource = $resource(configurationService.menuRestUrl, {}, {
			postObject: { method: 'POST'}
           
		});
				
		this.postObject = function (obj) {
            delay = $q.defer();
            saleResource.postObject(obj, function (data) {
                delay.resolve(data);
            }, function () {
            	//TODO: need to localize the this message
                delay.reject('Unable to fetch..');
            });
            return delay.promise;
        };
        
        this.isEmpty = function(){
        	var b = new Boolean(true);
        	var uniqueCode = $('#uniqueCode').val();
        	if (uniqueCode === ''){
        		$("#validaTionUniqueCode").show();
           		b = false;
        	}else{
        		$("#validaTionUniqueCode").hide();
        	}        	
        };
    };
    
    app.service('saleService', ['$resource', '$q', 'configurationService', saleService]);

});

