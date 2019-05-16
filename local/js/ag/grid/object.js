'use strict';

BX.namespace('AgGrid');

BX.AgGrid = {

    container: '',

    nodeContainer: {},

    grid: {},

    options: {},

    init: function ( config )
    {
        this.container = config.container || 'agGridNodeContainer';

        this.nodeContainer = BX( this.container ) || BX.create('div', {attrs: {id:this.container}});

        this.options = config.options;

        this.options.localeTextFunc = BX.delegate(this.locateText, this); 

        this.grid = new agGrid.Grid(
            this.nodeContainer, 
            this.options
        );

        this.options.api.sizeColumnsToFit();

        this.options.api.setServerSideDatasource(this);

        this.options.columnApi.autoSizeColumns();
    },

    getRows: function( params )
    {
        BX.ajax.runComponentAction('fusion:sheet', 'getRows', {
            mode: 'class',
            data: {
                params: params.request
            }
        }).then(
            BX.delegate(function(response){
                if ( response.status == 'success' )
                {
                    params.successCallback(response.data.rows, response.data.lastRow);
                }
                else
                {
                    params.failCallback();
                }
            }, this),
            BX.delegate(function(response){
                console.log(params);
                params.failCallback();
            }, this)
        );
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