;(function () {

	'use strict';

	BX.namespace('BX.Fusion.Core');

	BX.Fusion.Core.Utils =
	{
		showSuccess: function( response )
		{
			if ( !response.data )
			{
				BX.Fusion.Core.Utils.internalShowSuccess();
				return;
			}

			if ( typeof response.data['successText'] != 'undefined' )
			{
				alert(response.data['successText']);
				return;
			}

			BX.Fusion.Core.Utils.internalShowSuccess();
		},

		internalShowSuccess: function()
		{
			alert('Действие успешно выполнено');
		},

		showError: function( response )
		{
			var errorText = '';

			for( var errorKey in response.errors )
			{
				errorText += response.errors[errorKey].message;
			}

			alert(errorText);
		},
		
		reloadTab: function( tabid )
		{
			if(typeof(BX.Crm.EntityDetailTabManager.items) != "undefined")
			{
				var managerObject = BX.Crm.EntityDetailTabManager.items[Object.keys(BX.Crm.EntityDetailTabManager.items)[0]]
				
				managerObject.findItemById(tabid)._loader._isLoaded = false;
				managerObject.findItemById(tabid)._loader.load();
			}
		},
		reloadGrid: function( gridId )
		{
			if ( typeof BX.Main.gridManager == 'undefined' )
			{
				return false;
			}

			if ( typeof BX.Main.gridManager.getById(gridId) != 'object' )
			{
				return false;
			}

			BX.Main.gridManager.getById(gridId).instance.reload();
		}
	};

})();


//;(function () {
//
//	'use strict';
//
//	BX.namespace('BX.Fusion.Core');
//
//	BX.Fusion.Core.Utils =
//	{
//		showSuccess: function( response )
//		{
//			var title = '';
//			var content = '';
//			var popupID = '';
//			
//			popupID = 'SUCCESS_MODAL';
//			title = 'Успешно';
//			
//			if ( typeof response.data['successText'] != 'undefined' )
//			{
//				content = response.data['successText'];
//			}
//			else
//			{
//				content = 'Действие успешно выполнено';
//			}
//			
//			BX.Fusion.Core.Utils.showModal(popupID, title, content);
//		},
//
//		showError: function( response )
//		{
//			var title = '';
//			var content = '';
//			var popupID = '';
//			
//			popupID = 'ERROR_MODAL';
//			title = 'Ошибка';
//			
//			var errorText = '';
//
//			for( var errorKey in response.errors )
//			{
//				errorText += response.errors[errorKey].message;
//			}
//
//			content = errorText;
//
//			BX.Fusion.Core.Utils.showModal(popupID, title, content);
//		},
//	};
//	
//	BX.Fusion.Core.Utils.showModal = function(popupID, title, content)
//		{
//				var popupWindow = BX.PopupWindowManager.create(
//				popupID, null, {
//					width : 450,
//					zIndex : 1500,
//					closeIcon : {
//						opacity : 1
//					},
//					titleBar : title,
//					content : content,
//					closeByEsc : false,
//					autoHide : false,
//					overlay : {
//						backgroundColor : 'black',
//						opacity : 500
//					}
//				});
//				
//				popupWindow.show();
//		}
//
//})();