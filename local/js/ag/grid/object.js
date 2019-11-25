'use strict';

BX.namespace('AgGrid');

BX.AgGrid = {

    container: '',

    nodeContainer: {},

    grid: {},

    options: {},

    getRowsParams: {},

    init: function ( config )
    {
        this.container = config.container || 'agGridNodeContainer';

        this.nodeContainer = BX( this.container ) || BX.create('div', {attrs: {id:this.container}});

        this.options = config.options;

        this.options.getContextMenuItems = BX.delegate(this.getContextMenuItems,this);

        this.options.localeTextFunc = BX.delegate(this.locateText, this); 

        this.grid = new agGrid.Grid(
            this.nodeContainer, 
            this.options
        );

        this.options.api.sizeColumnsToFit();

        this.options.api.setServerSideDatasource(this);

        this.options.columnApi.autoSizeColumns();

        this.options.onCellValueChanged = BX.delegate(this.onCellValueChanged, this);

        BX.addCustomEvent('BX.Fusion.Sheet.Row::saveRow', BX.delegate(this.onSheetRowSave, this));
    },

    getContextMenuItems: function(params)
    {
        return [
            {
                name: 'Business process ',
                action: BX.AgGrid.showRowBizProc( params.node.data['ROW_NUMBER'] ),
            },
            'separator',
            'copy',
            'copyWithHeaders',
            'paste',
            'separator',
            'export'
        ];
    },

    showRowBizProc( rowNumber )
    {

    },

    onBusinessProcessRowClick: function (event)
    {
        console.log(event);
    },

    onCellValueChanged: function (params)
    {
        BX.ajax.runComponentAction('fusion:sheet', 'saveRow', {
            mode: 'class',
            data: {
                params: params.data
            }
        }).then(
            BX.delegate(this.onCellValueChangedSuccess, this),
            BX.delegate(BX.Fusion.Core.Utils.showError, this)
        );
    },

    onCellValueChangedSuccess: function(response)
    {

    },



    getRows: function( params )
    {
        this.getRowsParams = params;

        BX.ajax.runComponentAction('fusion:sheet', 'getRows', {
            mode: 'class',
            data: {
                params: this.getRowsParams.request
            }
        }).then(
            BX.delegate(this.onGetRowsSuccess, this),
            BX.delegate(this.onGetRowsFail, this)
        );
    },

    onGetRowsSuccess: function(response)
    {
        if ( response.status == 'success' )
        {
            this.getRowsParams.successCallback(response.data.rows, response.data.lastRow);
        }
        else
        {
            this.getRowsParams.failCallback();
        }
    },

    onGetRowsFail: function(response)
    {
        this.getRowsParams.failCallback();
    },

    onSheetRowSave: function( field )
    {
        this.options.api.purgeServerSideCache();
        this.options.api.redrawRows();
    },

    locateText: function(key, defaultValue)
    {
        var previousDebugStatus = BX.debugStatus();

        BX.debugEnable( false );

        var langExist = BX.message('ag-grid-lang-exist');

        var value = BX.message('ag-grid-' + key);

        if ( langExist!='Y' )
        {
            return defaultValue;
        }

        BX.debugEnable( previousDebugStatus );

        if ( value.length < 1 )
        {
            value = defaultValue;
            BX.debug('AgGrid: undefined lang phrase for key "'+key+'". Use default: '+defaultValue);
        }

        return value;
    },

    processErrorResponse: function(response)
    {
        var errorText = '';

        for( var errorKey in response.errors )
        {
            errorText += response.errors[errorKey].message + '\n';
        }

        alert(errorText);
    }
};