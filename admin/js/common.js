Ext.onReady(function(){

	//add icon manager extension
	Ext.ux.Icon = function(icon, width, height, subfolder){
		var path = IMAGE_LIBRARY_PATH+'/icons/' + (subfolder == undefined ? '' : subfolder + '/');
		if(!Ext.util.CSS.getRule('.icon-'+icon)){
			//Ext.util.CSS.createStyleSheet('.icon-'+icon+width+' { background-image: url('+path+icon+'.png); background-size: '+width+'px; background-position:center top; max-width: '+width+'px; max-height:'+height+'px; background-repeat:no-repeat;}');
			Ext.util.CSS.createStyleSheet('.icon-'+icon+width+' { background-image: url('+path+icon+'.png) !important; background-size:'+width+'px '+height+'px; vertical-align: middle; background-position:center; width: '+(width+6)+'px !important; height:'+(height+6)+'px !important; background-repeat:no-repeat; !important;}');
		}
		return 'icon-'+icon+width;
	}
	
	//initialize quick tips exhibition
	Ext.QuickTips.init();

	//create a loading mask while the viewport gets loaded
	var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Carregando a página, aguarde..."});
	myMask.show();

	//define all the table models to be used
	setModels();
	
	//Create the main view component, and the basic layout components within
	mainView = Ext.create('Ext.container.Viewport', {
		layout: 'border',
		items: [{
			//header
			region: 'north',
			id: 'header',
			border: false,
			height: 80,
			margins: '0 0 5 0',
			layout: 'border',
			items:[{
				//Logo image
				margins: '5 0 5 5',
				region: 'west',
				id: 'headerLogo',
				xtype:'image',
				src: IMAGE_LIBRARY_PATH+'/logo.png'
			},{
				//Page title
				region: 'center',
				id: 'headerTitle',
				xtype: 'component',
				cls:'location_header_title',
				margin: 15,
				html: 'This is the title, dude!'
			}]
		},
			getLeftMenu(),
			getBottom({
				items: [{
					height: 70,
					xtype: 'container',
					//style: {'background-color': 'red'}
				}]
			}),
			getWorkspace({style: {}}),//{'background-color': 'green'}}),
		],
		listeners:{
			afterrender: function(){
				//Call the page initialization function, based on the user request, to build the page
				//this function is called in another javascript file, specific for the page requested
				initializePage();

				//destroy the loading mask
				myMask.destroy();
			}
		}
	});
});


function setModels(){
	//Define the table model to be used
	Ext.define('CORRETORA', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'corretora_id', type: 'int'},
			{name: 'corretora_nome',type: 'string'},
			{name: 'corretora_codigo_bmf',type: 'string'},
			{name: 'corretora_url', type: 'string'},
			{name: 'corretora_cnpj',type: 'string'},
			{name: 'corretora_telefone',type: 'string'},
			{name: 'corretora_endereco',type: 'string'},
			{name: 'corretora_conta_deposito',type: 'string'},
		],
	});	
		
	//Define the table model to be used
	Ext.define('SUBSETORCOMBO', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'subsetor_id', type: 'int'},
			{name: 'subsetor_codigo',type: 'string'},
			{name: 'subsetor_nome',type: 'string'},
			{name: 'setor_id', type: 'int'},
			{name: 'setor_codigo',type: 'string'},
			{name: 'setor_nome',type: 'string'},
		],
	});	
		
	//Define the table model to be used
	Ext.define('SUBSETOR', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'subsetor_id', type: 'int'},
			{name: 'setor_id', type: 'int'},
			{name: 'subsetor_codigo',type: 'string'},
			{name: 'subsetor_nome',type: 'string'},
			{name: 'subsetor_descricao',type: 'string'},
		],
	});	
		
	//Define the table model to be used
	Ext.define('SETOR', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'setor_id', type: 'int'},
			{name: 'setor_codigo',type: 'string'},
			{name: 'setor_nome',type: 'string'},
			{name: 'setor_descricao',type: 'string'},
		],
	});	
		
	//Define the table model to be used
	Ext.define('ATIVO', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'ativo_id', type: 'int'},
			{name: 'ativo_simbolo',type: 'string'},
			{name: 'ativo_empresa',type: 'string'},
			{name: 'ativo_ipo_data',type: 'string'},
			{name: 'ativo_lote_padrao',type: 'int'},
			{name: 'ativo_imagem',type: 'string'},
			{name: 'subsetor_id',type: 'int'},
		],
	});	
		
	//Define the table model to be used
	Ext.define('AGENCIA', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'agencia_id', type: 'int'},
			{name: 'agencia_numero',type: 'string'},
			{name: 'agencia_nome',type: 'string'},
			{name: 'agencia_endereco',type: 'string'},
			{name: 'agencia_telefone',type: 'string'},
			{name: 'banco_id',type: 'int'},
		],
	});	
	
	//Define the table model to be used
	Ext.define('BANCO', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'banco_id', type: 'int'},
			{name: 'banco_numero',type: 'string'},
			{name: 'banco_nome',type: 'string'},
			{name: 'banco_url',type: 'string'},
			{name: 'banco_imagem',type: 'string'},
		],
	});	
	
	//Define the table model to be used
	Ext.define('STATUS_TIPO', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'status_tipo_id'},
			{name: 'status_tipo_nome',type: 'string'},
		],
	});

	//Define the table model to be used
	Ext.define('STATUS', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'status_id',type: 'int'},
			{name: 'status_codigo',type: 'string'},
			{name: 'status_nome',type: 'string'},
			{name: 'status_descricao',type: 'string'},
			{name: 'status_visivel',type: 'string'},
			{name: 'status_tipo_id',type: 'int'},
		],
	});

	//Define the table model to be used
	Ext.define('USUARIO', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'usuario_id',type: 'int'},
			{name: 'usuario_nick',type: 'string'},
			{name: 'usuario_nome',type: 'string'},
			{name: 'usuario_email',type: 'string'},
			{name: 'usuario_telefone',type: 'string'},
			{name: 'usuario_facebook',type: 'string'},
			{name: 'usuario_twitter',type: 'string'},
			{name: 'usuario_senha',type: 'string'},
			{name: 'usuario_foto',type: 'string'},
			{name: 'usuario_admin',type: 'string'},
			{name: 'status_id',type: 'int'},
		],
	});
}


function getHeader(config){
	//Create the header component or return the component if exists
	header = Ext.getCmp('header');

	if(header == undefined){
		header = Ext.create('Ext.container.Container',{
			//header
			region: 'north',
			id: 'header',
			border: false,
			height: config.height,
			margins: '0 0 5 0',
			layout: 'border',
			items:[{
				//Logo image
				margins: '5 0 5 5',
				region: 'west',
				id: 'headerLogo',
				xtype:'image',
				src: config.logo
			},{
				//Page title
				region: 'center',
				id: 'headerTitle',
				xtype: 'component',
				cls:'location_header_title',
				margin: 15,
				html: config.pageTitle
			}]
		});
	}
	return header;
}

function getLeftMenu(){
	//Create the left menu component or return the component if exists
	leftMenu = Ext.getCmp('leftMenu');

	if(leftMenu == undefined){
		leftMenu = Ext.create('Ext.container.Container',{
			region: 'west',
			id: 'leftMenu',
			width:'15%',
			title: 'Menu',
			xtype: 'panel',
			layout: {
				type: 'accordionx',
				align: 'stretch',
				hideCollapseTool: true,
				multi:true,
				fill:false
			},
			items: []
		});
		
		var store = Ext.create('Ext.data.JsonStore', {
			autoLoad: true,
			proxy: {
				type: 'ajax',
				noCache: false,
				url: AJAX_LIBRARY_PATH+'/menu.php',
				actionMethods:'POST'
			},
			root: 'items',
			fields: ['items'],
			listeners: {
				load: function(st) {
					var menuConfig;

					Ext.each(this.data.items[0].data.items, function(index){
						if(index.items != undefined){
							menuConfig = {
								title: index['title'],
								iconCls: index['iconCls'],
								items: [
									{
										root: {
											children: []
										},
										xtype:'treepanel',
										loadMask: true,
										border: false,
										autoScroll: true,
										rootVisible: false,
										listeners:{
											itemclick: function(view, record, item, i){
												window.location = index['items'][i]['menuURL'];
											}
										}
									}
								]
							};
							Ext.each(index.items, function (kids, i) {
								menuConfig.items[0].root.children.push({
									text: kids['text'], 
									leaf: true, 
									iconCls: kids['iconCls'],
									id:kids['id']
								})
							});
						} else {
							menuConfig =  {
								//xtype: 'button',
								title: index['title'],
								iconCls: index['iconCls']
							}
						}
						leftMenu.add(menuConfig);					
					});
					leftMenu.doLayout();
				}
			}		
		});
	}
	return leftMenu;
}



function getWorkspace(config){
	//Create the workspace area or return the component if exists
	workspace = Ext.getCmp('workspace');

	if(workspace == undefined){
		workspace = Ext.create('Ext.container.Container',{
			region: 'center',
			id: 'workspace',
			margins: '0 0 5 0',
			autoScroll: true,
			layout: 'anchor',
			defaults:{
				anchor: '100%'
			},
			items: config.items,
			style: config.style
		});
	}
	return workspace;
}

function getBottom(config){
	//Create the bottom area or return the component if exists
	bottom = Ext.getCmp('bottom');

	if(bottom == undefined){
		bottom = Ext.create('Ext.container.Container',{
			region: 'south',
			id: 'bottom',
			height: config.height,
			autoScroll: true,
			items: config.items
		});
	}
	return bottom;
}