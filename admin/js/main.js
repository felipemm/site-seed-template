
function initializePage(){
	switch (page_action){
		case 'USER':
			getWorkspace().add(createUserGrid());
			break;
		case 'STATUS':
			getWorkspace().add(createStatusGrid());
			break;
		case 'STATUSTYPE':
			getWorkspace().add(createStatusTipoGrid());
			break;
		case 'BANK':
			getWorkspace().add(createBankGrid());
			break;
		case 'BANKAGENCY':
			getWorkspace().add(createBankAgencyGrid());
			break;
		case 'SYMBOL':
			getWorkspace().add(createSymbolGrid());
			break;
		case 'SECTOR':
			getWorkspace().add(createSectorGrid());
			break
		case 'SUBSECTOR':
			getWorkspace().add(createSubSectorGrid());
			break;
		case 'BROKERAGE':
			getWorkspace().add(createBrokerageGrid());
			break;
		default:
			//alert('LIST not implemented yet!');
            getWorkspace().add(createUserGrid());
			break;
	}
}

function createBrokerageGrid(){
	
	var store = Ext.create('Ext.data.Store', {
		model: 'CORRETORA',
		proxy: {
			type: 'ajax',
			api: {
				read : 'ajax/maint_subsector.php?action=SEL',
				create : 'ajax/maint_subsector.php?action=ADD',
				update: 'ajax/maint_subsector.php?action=UPD',
				destroy: 'ajax/maint_subsector.php?action=DEL',
			},
			reader: {
				type: 'json',
				root: 'result.data'
			},
		},
		autoLoad: true,
		autoSync: true
	});

	var editor = new Ext.grid.plugin.CellEditing({
		pluginId: 'cellplugin',
		clicksToEdit: 2
	});	 
	
	//create grid
	var grid = new Ext.grid.GridPanel({
		store: store,
		columns: [{
			header: "ID",
			width: 50,
			sortable: true,
			dataIndex: 'corretora_id',
		},{
			//flex:1,
			width: 100,
			header: "Nome",
			sortable: true,
			dataIndex: 'corretora_nome',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:2,
			header: "Código BM&F",
			sortable: true,
			dataIndex: 'corretora_codigo_bmf',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:2,
			header: "Descrição",
			width: 150,
			//height: 50,
			sortable: true,
			dataIndex: 'subsetor_descricao',
			editor: {
				xtype: 'textareafield',
				allowBlank: false
			}
		}],
		plugins: [editor],
		title: 'Ativos',
		frame:true,
		tbar: [{
			iconCls: Ext.ux.Icon('add',16,16),
			text: 'Incluir',
			handler: function(){
				var e = Ext.create('SUBSETOR',{
					subsetor_id:0,
					setor_id:0,
					subsetor_codigo:'',
					subsetor_nome:'',
					subsetor_descricao:'',
				});
				store.insert(0, e);
			}
		},{
			iconCls: Ext.ux.Icon('remove',16,16),
			text: 'Excluir',
			handler: function(){
				var s = grid.getSelectionModel().getSelection();
				for(var i = 0, r; r = s[i]; i++){
					store.remove(r);
				}
			}
		},{
			iconCls: Ext.ux.Icon('save',16,16),
			text: 'Salvar Tudo',
			handler: function(){
				store.save();
			}
		},{
			iconCls: Ext.ux.Icon('rollback',16,16),
			text: 'Rejeitar Alterações',
			handler: function(){
				store.rejectChanges();
			}
		}],
	});
	return grid;
}

function createSubSectorGrid(){
	var sectorStore = Ext.create('Ext.data.Store', {
		model: 'SETOR',
		proxy: {
			type: 'ajax',
			url: 'ajax/maint_sector.php?action=sel',
			reader: {
				idProperty: 'setor_id',
				type: 'json',
				root: 'result.data'
			}
		},
		autoLoad: true
	});
	
	var store = Ext.create('Ext.data.Store', {
		model: 'SUBSETOR',
		proxy: {
			type: 'ajax',
			api: {
				read : 'ajax/maint_subsector.php?action=SEL',
				create : 'ajax/maint_subsector.php?action=ADD',
				update: 'ajax/maint_subsector.php?action=UPD',
				destroy: 'ajax/maint_subsector.php?action=DEL',
			},
			reader: {
				type: 'json',
				root: 'result.data'
			},
		},
		autoLoad: true,
		autoSync: true
	});

	var editor = new Ext.grid.plugin.CellEditing({
		pluginId: 'cellplugin',
		clicksToEdit: 2
	});	 
	
	//create grid
	var grid = new Ext.grid.GridPanel({
		store: store,
		columns: [{
			header: "ID",
			width: 50,
			sortable: true,
			dataIndex: 'subsetor_id',
		},{
			//flex:1,
			header: "Setor",
			width: 300,
			sortable: true,
			dataIndex: 'setor_id',
			editor: {
				xtype: 'combo',
				allowBlank: false,
				valueField: 'setor_id',
				displayField: 'setor_nome',
				store: sectorStore,
			},
			renderer: function(value){
				if(value != 0 && value != ""){
					if(sectorStore.findRecord("setor_id", value) != null)
						return sectorStore.findRecord("setor_id", value).get('setor_nome');
					else 
						return value;
				}
				else
					return "";  // display nothing if value is empty
			}
		},{
			//flex:1,
			width: 100,
			header: "Código",
			sortable: true,
			dataIndex: 'subsetor_codigo',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:2,
			header: "Nome",
			sortable: true,
			dataIndex: 'subsetor_nome',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:2,
			header: "Descrição",
			width: 150,
			//height: 50,
			sortable: true,
			dataIndex: 'subsetor_descricao',
			editor: {
				xtype: 'textareafield',
				allowBlank: false
			}
		}],
		plugins: [editor],
		title: 'Ativos',
		frame:true,
		tbar: [{
			iconCls: Ext.ux.Icon('add',16,16),
			text: 'Incluir',
			handler: function(){
				var e = Ext.create('SUBSETOR',{
					subsetor_id:0,
					setor_id:0,
					subsetor_codigo:'',
					subsetor_nome:'',
					subsetor_descricao:'',
				});
				store.insert(0, e);
			}
		},{
			iconCls: Ext.ux.Icon('remove',16,16),
			text: 'Excluir',
			handler: function(){
				var s = grid.getSelectionModel().getSelection();
				for(var i = 0, r; r = s[i]; i++){
					store.remove(r);
				}
			}
		},{
			iconCls: Ext.ux.Icon('save',16,16),
			text: 'Salvar Tudo',
			handler: function(){
				store.save();
			}
		},{
			iconCls: Ext.ux.Icon('rollback',16,16),
			text: 'Rejeitar Alterações',
			handler: function(){
				store.rejectChanges();
			}
		}],
	});
	return grid;
}

function createSectorGrid(){
	
	var store = Ext.create('Ext.data.Store', {
		model: 'SETOR',
		proxy: {
			type: 'ajax',
			api: {
				read : 'ajax/maint_sector.php?action=SEL',
				create : 'ajax/maint_sector.php?action=ADD',
				update: 'ajax/maint_sector.php?action=UPD',
				destroy: 'ajax/maint_sector.php?action=DEL',
			},
			reader: {
				type: 'json',
				root: 'result.data'
			},
		},
		autoLoad: true,
		autoSync: true
	});

	var editor = new Ext.grid.plugin.CellEditing({
		pluginId: 'cellplugin',
		clicksToEdit: 2
	});	 
	
	//create grid
	var grid = new Ext.grid.GridPanel({
		store: store,
		columns: [{
			header: "ID",
			width: 50,
			sortable: true,
			dataIndex: 'setor_id',
		},{
			flex:1,
			header: "Código",
			sortable: true,
			dataIndex: 'setor_codigo',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:2,
			header: "Nome",
			sortable: true,
			dataIndex: 'setor_nome',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:2,
			header: "Descrição",
			width: 150,
			//height: 50,
			sortable: true,
			dataIndex: 'setor_descricao',
			editor: {
				xtype: 'textareafield',
				allowBlank: false
			}
		}],
		plugins: [editor],
		title: 'Ativos',
		frame:true,
		tbar: [{
			iconCls: Ext.ux.Icon('add',16,16),
			text: 'Incluir',
			handler: function(){
				var e = Ext.create('SETOR',{
					setor_id:0,
					setor_codigo:'',
					setor_nome:'',
					setor_descricao:'',
				});
				store.insert(0, e);
			}
		},{
			iconCls: Ext.ux.Icon('remove',16,16),
			text: 'Excluir',
			handler: function(){
				var s = grid.getSelectionModel().getSelection();
				for(var i = 0, r; r = s[i]; i++){
					store.remove(r);
				}
			}
		},{
			iconCls: Ext.ux.Icon('save',16,16),
			text: 'Salvar Tudo',
			handler: function(){
				store.save();
			}
		},{
			iconCls: Ext.ux.Icon('rollback',16,16),
			text: 'Rejeitar Alterações',
			handler: function(){
				store.rejectChanges();
			}
		}],
	});
	return grid;
}


function createSymbolGrid(){

	var subSectorStore = Ext.create('Ext.data.Store', {
		model: 'SUBSETORCOMBO',
		proxy: {
			type: 'ajax',
			url: 'ajax/maint_subsector.php?action=selcombo',
			reader: {
				idProperty: 'subsetor_id',
				type: 'json',
				root: 'result.data'
			}
		},
		autoLoad: true
	});
	
	var store = Ext.create('Ext.data.Store', {
		model: 'ATIVO',
		proxy: {
			type: 'ajax',
			api: {
				read : 'ajax/maint_symbol.php?action=SEL',
				create : 'ajax/maint_symbol.php?action=ADD',
				update: 'ajax/maint_symbol.php?action=UPD',
				destroy: 'ajax/maint_symbol.php?action=DEL',
			},
			reader: {
				type: 'json',
				root: 'result.data'
			},
		},
		autoLoad: true,
		autoSync: true
	});

	var editor = new Ext.grid.plugin.CellEditing({
		pluginId: 'cellplugin',
		clicksToEdit: 2
	});	 
	
	//create grid
	var grid = new Ext.grid.GridPanel({
		store: store,
		columns: [{
			header: "ID",
			width: 40,
			sortable: true,
			dataIndex: 'ativo_id',
		},{
			width: 80,
			header: "Símbolo",
			sortable: true,
			dataIndex: 'ativo_simbolo',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:6,
			header: "Nome Empresa",
			sortable: true,
			dataIndex: 'ativo_empresa',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			width: 90,
			header: "Data IPO",
			sortable: true,
			dataIndex: 'ativo_ipo_data',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			width: 110,
			header: "Lote Padrão",
			sortable: true,
			dataIndex: 'ativo_lote_padrao',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:4,
			id: 'subsector',
			header: "Sub-Setor",
			width: 300,
			sortable: true,
			dataIndex: 'subsetor_id',
			editor: {
				xtype: 'combo',
				allowBlank: false,
				valueField: 'subsetor_id',
				displayField: 'subsetor_nome',
				store: subSectorStore,
				tpl: Ext.create('Ext.XTemplate',
					'<tpl for=".">',
						'<div class="x-boundlist-item">{setor_codigo} -> {subsetor_nome}</div>',
					'</tpl>'
				),
			},
			renderer: function(value){
				if(value != 0 && value != ""){
					if(subSectorStore.findRecord("subsetor_id", value) != null)
						return subSectorStore.findRecord("subsetor_id", value).get('subsetor_nome');
					else 
						return value;
				}
				else
					return "";  // display nothing if value is empty
			}
		},{
			width: 90,
			id: 'symbol_logo',
			dataIndex: 'ativo_imagem',
			header: "Logo",
			sortable: false,
			editor: {
				xtype: 'textfield',
				allowBlank: true
			},
			renderer: function(value, meta) { 
				if(value == '') value = 'no-symbol';
				meta.tdCls = Ext.ux.Icon(value,36,36,'symbols'); 
				return ''; 
            } 
		},{
			width: 70,
			header: "",
			xtype:'actioncolumn', 
			items:[{
				iconCls: Ext.ux.Icon('image-search',24,24),
				tooltip: 'Alterar Imagem',
				handler:function(grid2, rowIndex, colIndex){
					grid.getPlugin('cellplugin').startEdit(rowIndex, colIndex-1);
				}
			},{
				iconCls: Ext.ux.Icon('remove',24,24),
				tooltip: 'Resetar',
				handler:function(grid, rowIndex, colIndex){
					var rec = grid.getStore().getAt(rowIndex);
					rec.set('ativo_imagem','no-symbol');
				}
			}],
		}],
		plugins: [editor],
		title: 'Ativos',
		frame:true,
		tbar: [{
			iconCls: Ext.ux.Icon('add',16,16),
			text: 'Incluir',
			handler: function(){
				var e = Ext.create('ATIVO',{
					ativo_id:0,
					ativo_simbolo:'',
					ativo_empresa:'',
					ativo_ipo_data:'0000-00-00',
					ativo_lote_padrao:0,
					ativo_imagem:'',
				});
				store.insert(0, e);
			}
		},{
			iconCls: Ext.ux.Icon('remove',16,16),
			text: 'Excluir',
			handler: function(){
				var s = grid.getSelectionModel().getSelection();
				for(var i = 0, r; r = s[i]; i++){
					store.remove(r);
				}
			}
		},{
			iconCls: Ext.ux.Icon('save',16,16),
			text: 'Salvar Tudo',
			handler: function(){
				store.save();
			}
		},{
			iconCls: Ext.ux.Icon('rollback',16,16),
			text: 'Rejeitar Alterações',
			handler: function(){
				store.rejectChanges();
			}
		}],
	});
	return grid;
}


function createBankAgencyGrid(){

	var bankStore = Ext.create('Ext.data.Store', {
		model: 'BANCO',
		proxy: {
			type: 'ajax',
			url: 'ajax/maint_bank.php?action=sel',
			reader: {
				idProperty: 'banco_id',
				type: 'json',
				root: 'result.data'
			}
		},
		autoLoad: true
	});
	
	
	var store = Ext.create('Ext.data.Store', {
		model: 'AGENCIA',
		proxy: {
			type: 'ajax',
			api: {
				read : 'ajax/maint_bank_agency.php?action=SEL',
				create : 'ajax/maint_bank_agency.php?action=ADD',
				update: 'ajax/maint_bank_agency.php?action=UPD',
				destroy: 'ajax/maint_bank_agency.php?action=DEL',
			},
			reader: {
				type: 'json',
				root: 'result.data'
			},
		},
		autoLoad: true,
		autoSync: true
	});

	var editor = new Ext.grid.plugin.CellEditing({
		pluginId: 'cellplugin',
		clicksToEdit: 2
	});	 
	
	//create grid
	var grid = new Ext.grid.GridPanel({
		store: store,
		columns: [{
			header: "ID",
			width: 50,
			sortable: true,
			dataIndex: 'agencia_id',
		},{
			flex:1,
			header: "Número",
			sortable: true,
			dataIndex: 'agencia_numero',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:2,
			header: "Nome",
			sortable: true,
			dataIndex: 'agencia_nome',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:3	,
			header: "Endereço",
			width: 150,
			sortable: true,
			dataIndex: 'agencia_endereco',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:1,
			header: "Telefone",
			sortable: true,
			dataIndex: 'agencia_telefone',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			header: "Banco",
			width: 250,
			sortable: true,
			dataIndex: 'banco_id',
			editor: {
				xtype: 'combo',
				allowBlank: false,
				valueField: 'banco_id',
				displayField: 'banco_nome',
				store: bankStore,
			},
			renderer: function(value){
				if(value != 0 && value != ""){
					if(bankStore.findRecord("banco_id", value) != null)
						return bankStore.findRecord("banco_id", value).get('banco_nome');
					else 
						return value;
				}
				else
					return "";  // display nothing if value is empty
			}
		}],
		plugins: [editor],
		title: 'Bancos',
		frame:true,
		tbar: [{
			iconCls: Ext.ux.Icon('add',16,16),
			text: 'Incluir',
			handler: function(){
				var e = Ext.create('AGENCIA',{
					agencia_id:0,
					agencia_numero:'',
					agencia_nome:'',
					agencia_endereco:'',
					agencia_telefone:'',
					banco_id:0,
				});
				store.insert(0, e);
			}
		},{
			iconCls: Ext.ux.Icon('remove',16,16),
			text: 'Excluir',
			handler: function(){
				var s = grid.getSelectionModel().getSelection();
				for(var i = 0, r; r = s[i]; i++){
					store.remove(r);
				}
			}
		},{
			iconCls: Ext.ux.Icon('save',16,16),
			text: 'Salvar Tudo',
			handler: function(){
				store.save();
			}
		},{
			iconCls: Ext.ux.Icon('rollback',16,16),
			text: 'Rejeitar Alterações',
			handler: function(){
				store.rejectChanges();
			}
		}],
	});
	return grid;
}


function createBankGrid(){

	var store = Ext.create('Ext.data.Store', {
		model: 'BANCO',
		proxy: {
			type: 'ajax',
			api: {
				read : 'ajax/maint_bank.php?action=SEL',
				create : 'ajax/maint_bank.php?action=ADD',
				update: 'ajax/maint_bank.php?action=UPD',
				destroy: 'ajax/maint_bank.php?action=DEL',
			},
			reader: {
				type: 'json',
				root: 'result.data'
			},
		},
		autoLoad: true,
		autoSync: true
	});

	var editor = new Ext.grid.plugin.CellEditing({
		pluginId: 'cellplugin',
		clicksToEdit: 2
	});	 
	
	//create grid
	var grid = new Ext.grid.GridPanel({
		store: store,
		columns: [{
			header: "ID",
			width: 50,
			sortable: true,
			dataIndex: 'banco_id',
		},{
			flex:1,
			header: "Número",
			sortable: true,
			dataIndex: 'banco_numero',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:2,
			header: "Nome",
			sortable: true,
			dataIndex: 'banco_nome',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:2,
			header: "URL",
			width: 150,
			sortable: true,
			dataIndex: 'banco_url',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			width: 150,
			id: 'bank_logo',
			dataIndex: 'banco_imagem',
			header: "Logo",
			//tdCls: Ext.ux.Icon(grid.getSelectionModel().get('banco_imagem'),36,36),
			sortable: false,
			editor: {
				xtype: 'textfield',
				allowBlank: true
			},
			renderer: function(value, meta) { 
				if(value == '') value = 'no-bank';
				meta.tdCls = Ext.ux.Icon(value,36,36); 
				return ''; 
            } 
		},{
			width: 70,
			header: "",
			xtype:'actioncolumn', 
			items:[{
				iconCls: Ext.ux.Icon('image-search',24,24),
				tooltip: 'Alterar Imagem',
				handler:function(grid2, rowIndex, colIndex){
					grid.getPlugin('cellplugin').startEdit(rowIndex, colIndex-1);
				}
			},{
				iconCls: Ext.ux.Icon('remove',24,24),
				tooltip: 'Resetar',
				handler:function(grid, rowIndex, colIndex){
					var rec = grid.getStore().getAt(rowIndex);
					rec.set('banco_imagem','no-bank');
				}
			}],
		}],
		plugins: [editor],
		title: 'Bancos',
		frame:true,
		tbar: [{
			iconCls: Ext.ux.Icon('add',16,16),
			text: 'Incluir',
			handler: function(){
				var e = Ext.create('BANCO',{
					banco_id:0,
					banco_numero:'',
					banco_nome:'',
					banco_url:'',
					banco_imagem:'',
				});
				store.insert(0, e);
			}
		},{
			iconCls: Ext.ux.Icon('remove',16,16),
			text: 'Excluir',
			handler: function(){
				var s = grid.getSelectionModel().getSelection();
				for(var i = 0, r; r = s[i]; i++){
					store.remove(r);
				}
			}
		},{
			iconCls: Ext.ux.Icon('save',16,16),
			text: 'Salvar Tudo',
			handler: function(){
				store.save();
			}
		},{
			iconCls: Ext.ux.Icon('rollback',16,16),
			text: 'Rejeitar Alterações',
			handler: function(){
				store.rejectChanges();
			}
		}],
	});
	return grid;
}


function createUserGrid(){

	var statusStore = Ext.create('Ext.data.Store', {
		model: 'STATUS',
		proxy: {
			type: 'ajax',
			url: 'ajax/maint_status.php?action=sel',
			reader: {
				idProperty: 'status_id',
				type: 'json',
				root: 'result.data'
			}
		},
		autoLoad: true
	});
	
	var isAdminStore = Ext.create('Ext.data.Store', {
		fields: ['code', 'name'],
		data : [
			{"code":0, "name":"Não"},
			{"code":1, "name":"Sim"},
		],
	});


	var store = Ext.create('Ext.data.Store', {
		model: 'USUARIO',
		proxy: {
			type: 'ajax',
			api: {
				read : 'ajax/maint_user.php?action=SEL',
				create : 'ajax/maint_user.php?action=ADD',
				update: 'ajax/maint_user.php?action=UPD',
				destroy: 'ajax/maint_user.php?action=DEL',
			},
			reader: {
				type: 'json',
				root: 'result.data'
			},
		},
		autoLoad: true,
		autoSync: true
	});

	var editor = new Ext.grid.plugin.CellEditing({
		clicksToEdit: 2
	});	 
	
	//create grid
	var grid = new Ext.grid.GridPanel({
		store: store,
		columns: [{
			header: "ID",
			width: 50,
			sortable: true,
			dataIndex: 'usuario_id',
		},{
			flex:1,
			header: "Nickname",
			sortable: true,
			dataIndex: 'usuario_nick',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:2,
			header: "Nome",
			sortable: true,
			dataIndex: 'usuario_nome',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:2,
			header: "E-Mail",
			width: 150,
			sortable: true,
			dataIndex: 'usuario_email',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:1,
			header: "Telefone",
			width: 150,
			sortable: true,
			dataIndex: 'usuario_telefone',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:1,
			header: "Facebook",
			width: 150,
			sortable: true,
			dataIndex: 'usuario_facebook',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:1,
			header: "Twitter",
			width: 150,
			sortable: true,
			dataIndex: 'usuario_twitter',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:1,
			header: "Foto",
			width: 150,
			sortable: true,
			dataIndex: 'usuario_foto',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:1,
			header: "Admin",
			width: 150,
			sortable: true,
			dataIndex: 'usuario_admin',
			editor: {
				xtype: 'combo',
				allowBlank: false,
				valueField: 'code',
				displayField: 'name',
				store: isAdminStore,
			},
			renderer: function(value){
				if(value != ""){
					if(isAdminStore.findRecord("code", value) != null)
						return isAdminStore.findRecord("code", value).get('name');
					else 
						return value;
				}
				else
					return "";  // display nothing if value is empty
			}
		},{
			flex:1,
			header: "Status",
			width: 150,
			sortable: true,
			dataIndex: 'status_id',
			editor: {
				xtype: 'combo',
				allowBlank: false,
				valueField: 'status_id',
				displayField: 'status_nome',
				store: statusStore,
			},
			renderer: function(value){
				if(value != 0 && value != ""){
					if(statusStore.findRecord("status_id", value) != null)
						return statusStore.findRecord("status_id", value).get('status_nome');
					else 
						return value;
				}
				else
					return "";  // display nothing if value is empty
			}
		},{
			//flex:1,
			width: 100,
			header: "Senha",
			xtype:'actioncolumn', 
			items:[{
				iconCls: Ext.ux.Icon('key-add',24,24),
				tooltip: 'Alterar',
				handler:function(){
					alert(1);
				}
			},{
				iconCls: Ext.ux.Icon('key-delete',24,24),
				tooltip: 'Resetar',
				handler:function(){
					alert(1);
				}
			},{
				iconCls: Ext.ux.Icon('mail',24,24),
				tooltip: 'Enviar senha por e-mail',
				handler:function(){
					alert(1);
				},
			}],
		}],
		plugins: [editor],
		title: 'Usuários',
		frame:true,
		tbar: [{
			iconCls: Ext.ux.Icon('add',16,16),
			text: 'Incluir',
			handler: function(){
				var e = Ext.create('USUARIO',{
					usuario_id:0,
					usuario_nick:'',
					usuario_nome:'',
					usuario_email:'',
					usuario_telefone:'',
					usuario_facebook:'',
					usuario_twitter:'',
					usuario_senha:'',
					usuario_foto:'',
					usuario_admin:0,
					status_id:1,
				});
				store.insert(0, e);
			}
		},{
			iconCls: Ext.ux.Icon('remove',16,16),
			text: 'Excluir',
			handler: function(){
				var s = grid.getSelectionModel().getSelection();
				for(var i = 0, r; r = s[i]; i++){
					store.remove(r);
				}
			}
		},{
			iconCls: Ext.ux.Icon('save',16,16),
			text: 'Salvar Tudo',
			handler: function(){
				store.save();
			}
		},{
			iconCls: Ext.ux.Icon('rollback',16,16),
			text: 'Rejeitar Alterações',
			handler: function(){
				store.rejectChanges();
			}
		}]
	});
	return grid;
}



function createStatusGrid(){

	var statusTipoStore = Ext.create('Ext.data.Store', {
		model: 'STATUS_TIPO',
		proxy: {
			type: 'ajax',
			url: 'ajax/maint_status_tipo.php?action=sel',
			reader: {
				idProperty: 'status_tipo_id',
				type: 'json',
				root: 'result.data'
			}
		},
		autoLoad: true
	});


	var store = Ext.create('Ext.data.Store', {
		model: 'STATUS',
		proxy: {
			type: 'ajax',
			api: {
				read : 'ajax/maint_status.php?action=SEL',
				create : 'ajax/maint_status.php?action=ADD',
				update: 'ajax/maint_status.php?action=UPD',
				destroy: 'ajax/maint_status.php?action=DEL',
			},
			reader: {
				type: 'json',
				root: 'result.data'
			},
		},
		autoLoad: true,
		autoSync: true
	});

	var editor = new Ext.grid.plugin.CellEditing({
		clicksToEdit: 1
	});	 
	
	//create grid
	var grid = new Ext.grid.GridPanel({
		store: store,
		columns: [{
			header: "ID",
			width: 170,
			sortable: true,
			dataIndex: 'status_id',
		},{
			flex:1,
			header: "Código",
			width: 150,
			sortable: true,
			dataIndex: 'status_codigo',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:1,
			header: "Nome",
			width: 150,
			sortable: true,
			dataIndex: 'status_nome',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:1,
			header: "Descrição",
			width: 150,
			sortable: true,
			dataIndex: 'status_descricao',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:1,
			header: "Visível",
			width: 150,
			sortable: true,
			dataIndex: 'status_visivel',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		},{
			flex:1,
			header: "Tipo",
			width: 150,
			sortable: true,
			dataIndex: 'status_tipo_id',
			editor: {
				xtype: 'combo',
				allowBlank: false,
				valueField: 'status_tipo_id',
				displayField: 'status_tipo_nome',
				store: statusTipoStore,
			},
			renderer: function(value){
				if(value != 0 && value != ""){
					if(statusTipoStore.findRecord("status_tipo_id", value) != null)
						return statusTipoStore.findRecord("status_tipo_id", value).get('status_tipo_nome');
					else 
						return value;
				}
				else
					return "";  // display nothing if value is empty
			}
		}],
		plugins: [editor],
		title: 'Status',
		frame:true,
		tbar: [{
			iconCls: 'add',
			text: 'Incluir',
			handler: function(){
				var e = Ext.create('STATUS',{
					status_id:'',
					status_codigo:'',
					status_nome:'',
					status_descricao:'',
					status_visivel:'S',
					status_tipo_id:1,
				});
				store.insert(0, e);
			}
		},{
			iconCls: 'delete',
			text: 'Excluir',
			handler: function(){
				var s = grid.getSelectionModel().getSelection();
				for(var i = 0, r; r = s[i]; i++){
					store.remove(r);
				}
			}
		},{
			iconCls: 'icon-user-save',
			text: 'Salvar Tudo',
			handler: function(){
				store.save();
			}
		},{
			iconCls: 'trash',
			text: 'Rejeitar Alterações',
			handler: function(){
				store.rejectChanges();
			}
		}]
	});
	
	return grid;
}



function createStatusTipoGrid(){
	var store = Ext.create('Ext.data.Store', {
		model: 'STATUS_TIPO',
		proxy: {
			type: 'ajax',
			api: {
				read : 'ajax/maint_status_tipo.php?action=SEL',
				create : 'ajax/maint_status_tipo.php?action=ADD',
				update: 'ajax/maint_status_tipo.php?action=UPD',
				destroy: 'ajax/maint_status_tipo.php?action=DEL',
			},
			reader: {
				type: 'json',
				root: 'result.data'
			},
		},
		autoLoad: true,
		autoSync: true
	});

	var editor = new Ext.grid.plugin.CellEditing({
		clicksToEdit: 1
	});	 
	
	//create grid
	var grid = new Ext.grid.GridPanel({
		store: store,
		columns: [{
			header: "ID",
			width: 170,
			sortable: true,
			dataIndex: 'status_tipo_id',
		},{
			flex:1,
			header: "Nome",
			width: 150,
			sortable: true,
			dataIndex: 'status_tipo_nome',
			editor: {
				xtype: 'textfield',
				allowBlank: false
			}
		}],
		plugins: [editor],
		title: 'Tipo de Status',
		frame:true,
		tbar: [{
			iconCls: 'add',
			text: 'Incluir',
			handler: function(){
				var e = Ext.create('STATUS_TIPO', {
					status_tipo_id: '',
					status_tipo_nome: '',
				});
				store.insert(0, e);
			}
		},{
			iconCls: 'delete',
			text: 'Excluir',
			handler: function(){
				var s = grid.getSelectionModel().getSelection();
				for(var i = 0, r; r = s[i]; i++){
					store.remove(r);
				}
			}
		},{
			iconCls: 'icon-user-save',
			text: 'Salvar Tudo',
			handler: function(){
				store.save();
			}
		},{
			iconCls: 'trash',
			text: 'Rejeitar Alterações',
			handler: function(){
				store.rejectChanges();
			}
		}]
	});
	return grid;
}