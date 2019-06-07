;(function () {

	BX.namespace("Fusion.Sheet");

	BX.Fusion.Sheet.Row =
	{
		savePopups: {},

		cellNames: ['A','B','C','D','E','F'],

		getPopUp( params )
		{
			var option = {};
			option.code       = params.code || 'default';
			option.width      = params.width || 450;
			option.zIndex     = params.zIndex || 1000;
			option.titleBar   = params.titleBar || '';
			option.closeByEsc = params.closeByEsc || true;

			if ( this.savePopups[ option.code ] )
			{
				return this.savePopups[ option.code ];
			}

			let buttons = this.getButtons( option.code );

			this.savePopups[option.code] = BX.PopupWindowManager.create(
				"sheet-"+ option.code +"-popup", 
				null,
				{
					width: option.width,
					zIndex: option.zIndex,
					closeIcon: {
						opacity: 1
					},
					titleBar: option.titleBar,
					closeByEsc: option.closeByEsc,
					buttons: buttons
				}
			);

			return this.savePopups[option.code];
		},

		getButtons( code )
		{
			let buttons = [];

			if ( code == 'save' )
			{
				buttons.push(new BX.PopupWindowButton({
					text: 'Сохранить',
					className: 'ui-btn ui-btn-success',
					events: {
						click: BX.delegate(this.onSaveForm,this)
					}
				}));
			}

			return buttons;
		},

		showAddRow()
		{
			let popupCode = 'save';

			let content = [];

			for ( var cellNameId in this.cellNames )
			{
				var cellName = this.cellNames[ cellNameId ];

				content.push(BX.create('div',{
					attrs: {
						class: 'sheet-popup-content-block-row'
					},
					children: [
						BX.create('div',{
							attrs: {
								class: 'sheet-row-title-block'
							},
							children: [
								BX.create('span',{
									text: 'Ячейка '+cellName
								})
							]
						}),
						BX.create('div',{
							attrs:{
								class: 'sheet-row-data-block'
							},
							children: [
								BX.create('div',{
									attrs: {
										class: 'sheet-row-data-block-innet'
									},
									children: [
										BX.create('input',{
											attrs: {
												id: 'CELL_'+cellName,
												type: 'text',
												class: 'ui-ctl-element ui-ctl-md crm-entity-widget-content-input',
												value: ''
											}
										})
									]
								})
							]
						})
					]
				}));
			}

			popup = this.getPopUp({code: popupCode});
			popup.setContent(BX.create('div',{
				attrs: {
					class: 'sheet-popup-content-block'
				},
				children: content
			}));
			popup.show();
		},

		onSaveForm()
		{
			let cellNames = ['A','B','C','D','E','F'];

			let fields = {};

			for( var cellNameId in this.cellNames )
			{
				let cellName = this.cellNames[ cellNameId ];

				fields[ 'CELL_'+cellName ] = BX('CELL_'+cellName).value;
			}

			BX.ajax.runComponentAction(
				'fusion:sheet',
				'saveRow',
				{
					mode: 'class',
					data: {
						params: fields
					}
				}
			).then(
				BX.delegate(function(response){
					BX.onCustomEvent('BX.Fusion.Sheet.Row::saveRow', [ fields ]);
					popup = this.getPopUp({code: 'save'});
					popup.close();
				}, this),
				BX.Fusion.Core.Utils.showError
			);
		}
	};

})();