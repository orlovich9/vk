<html>
    <head>
        <title>Hello Ext</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('public/css/ext-all.css') }}">
        <script type="text/javascript" src="{{ asset('public/js/extjs/adapter/ext/ext-base.js') }}"></script>
        <script type="text/javascript" src="{{ asset('public/js/extjs/ext-all-debug.js') }}"></script>
    </head>
    <body>
        <p id="my_id"></p>
        <div id="my-div">
            <div id="toolbar"></div>
            <div id="form" style="width: 550px;"></div>
            <div id="download" style="visibility: hidden;"></div>
        </div>
        <script>
            Ext.onReady(function() {

                Ext.getBody().highlight("ffff9c", {
                    attr: "background-color", //can be any valid CSS property (attribute) that supports a color value
                    endColor: "ffffff",
                    easing: 'easeIn',
                    duration: 1
                });

                //Work with DOM
                Ext.DomHelper.useDom = true;
                var dh = Ext.DomHelper; // create shorthand alias
// specification object
                var spec = {
                    id: 'my-ul',
                    tag: 'ul',
                    cls: 'my-list',
                    // append children after creating
                    children: [     // may also specify 'cn' instead of 'children'
                        {tag: 'li', id: 'item0', html: 'List Item 0'},
                        {tag: 'li', id: 'item1', html: 'List Item 1'},
                        {tag: 'li', id: 'item2', html: 'List Item 2'}
                    ]
                };
                var list = dh.append(
                    'my_id', // the context element 'my-div' can either be the id or the actual node
                    spec      // the specification object
                );
                // Ext.BLANK_IMAGE_URL = 'images/s.gif';
               /* Ext.Msg.show({
                   title: 'Test',
                   msg: 'You twentyseventheen years old?',
                   buttons: {
                       yes: true,
                       no: true
                   },
                    fn: function(btn) {
                       switch(btn) {
                           case 'yes':
                               Ext.Ajax.request({
                                   url: '/vk/update',
                                   method: 'POST',
                                   success: function(response) {
                                       Ext.get('my_id').update('Hi');
                                   },
                                   failure: function(response) {

                                   },
                                   params: { foo: 'bar' }
                               });
                           break;
                           case 'no':
                               Ext.Msg.wait('Данные сохраняются на диск...', 'Подождите');
                           break;
                       }
                    }
                });*/

               //Class
                Ext.define('My.Test',{
                   name: 'test',
                   age: '27',
                   constructor: function (name, age) {
                       if (name) {
                            this.name = name;
                       }
                       if (age){
                           this.age = age;
                       }
                   },
                   getUser: function() {
                       return this.name + ' ' + this.age;
                   }
                });
                var test = new My.Test('15', 'test2');

                //Toolbar
                new Ext.Toolbar({
                    renderTo: 'toolbar',
                    width: 550,
                    items: [
                        {
                            xtype: 'tbbutton',
                            text: 'Скрыть/Показать',
                            handler: function() {
                                Ext.get('form').toggle();
                            }
                        },
                        {
                            xtype: 'tbfill'
                        },
                        {
                            xtype: 'tbbutton',
                            text: 'Работа с лотом',
                            menu: [
                                {
                                    text: 'Создать новый лот',
                                    handler: function() {
                                        Ext.get('form').update();
                                        formInitialize();
                                    }
                                },
                                {
                                    text: 'Загрузить лот',
                                    handler: function() {
                                        Ext.get('form').update();
                                        loadInitialize('form');
                                    }
                                },
                                {
                                    text: 'Обновить лот'
                                }
                            ]
                        },
                        {
                            xtype: 'tbseparator'
                        },
                        {
                            xtype: 'tbsplit',
                            text: 'Menu Button',
                            menu: [
                                {
                                    text: 'Button'
                                },
                                {
                                    text: 'Button'
                                },
                                {
                                    text: 'Button'
                                }
                            ]
                        }
                    ]
                });

                //Form
                var getTest = new Ext.data.Store({
                    reader: new Ext.data.JsonReader({
                       fields: ['id', 'type_lot'],
                       root: 'rows'
                    }),
                    proxy: new Ext.data.HttpProxy({
                        url: '/vk/test'
                    }),
                    autoLoad: true
                });

                Ext.QuickTips.init();

                Ext.apply(Ext.form.VTypes, {
                    number:  function(v) {
                        return /^[0-9]+\.?[0-9]+$/.test(v);
                    },
                    numberText: 'Неккоректная цена',
                    numberMask: /[\d\.]/i
                },
                    {
                        text: function(v) {
                            return /^[А-Яа-я]+$/;
                        },
                        textText: 'Неккоректное название',
                        textMask: /[А-Яа-я]/i
                    });

                function loadInitialize(id)
                {
                    var download = new Ext.FormPanel({
                        url: '/vk/create',
                        method: 'post',
                        id: 'downloads',
                        renderTo: id,
                        frame: true,
                        title: 'Загрузить лот',
                        width: 550,
                        buttons: [
                            {
                                text: 'Загрузить',
                                style: 'position: relative; right: 325%;',
                                handler: function() {
                                    download.getForm().submit({
                                        success: function(form, response) {
                                            if (response.result.success) {
                                                Ext.Msg.alert('Результат', 'Лот сохранён');
                                            }
                                        },
                                        failure: function(form, response) {
                                        }
                                    });
                                }
                            }
                        ],
                        items: [
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Загрузите лот',
                                name: 'lot',
                                inputType:'file'
                            }
                        ]
                    });

                }

                loadInitialize('download');

                function formInitialize()
                {
                    var form = new Ext.FormPanel({
                        url: '/vk/create',
                        method: 'post',
                        renderTo: 'form',
                        id: 'forms',
                        frame: true,
                        title: 'Создать лот',
                        width: 550,
                        buttons: [
                            {
                                text: 'Создать',
                                style: 'position: relative; right: 240%;',
                                handler: function() {
                                    form.getForm().submit({
                                        success: function(form, response) {
                                            if (response.result.success) {
                                                Ext.Msg.alert('Результат', 'Лот сохранён');
                                            }
                                        },
                                        failure: function(form, response) {
                                        }
                                    });
                                }
                            },
                            {
                                text: 'Сбросить',
                                style: 'position: relative; right: 240%;',
                                handler: function() {
                                    form.getForm().reset();
                                }
                            }
                        ],
                        items: [{
                            xtype: 'textfield',
                            fieldLabel: 'Название',
                            name: 'name',
                            allowBlank: false,
                            vtype: 'text'
                        },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Цена в рублях',
                                name: 'price',
                                vtype: 'number',
                                allowBlank: false
                            },
                            {
                                xtype: 'datefield',
                                fieldLabel: 'Дата начала действия лота',
                                name: 'date-from',
                                disabledDays:  [0, 6]
                            },
                            {
                                xtype: 'datefield',
                                fieldLabel: 'Дата окончания действия лота',
                                name: 'date-to',
                                disabledDays:  [0, 6]
                            },
                            {
                                xtype: 'combo',
                                fieldLabel: 'Тип лота',
                                mode: 'local',
                                store: getTest,
                                displayField: 'type_lot',
                                name: 'type',
                                width:  120
                            },
                            {
                                xtype: 'htmleditor',
                                fieldLabel: 'Описание лота',
                                name: 'description',
                                height: 100,
                            },
                            {
                                xtype: 'checkbox',
                                fieldLabel: 'Я принимаю согласие на обработку моих персональных данных',
                                inputValue: true,
                                name: 'aligment',
                            }
                        ]
                    });

                    return form;
                }

                var form = formInitialize();

                //Ajax download data with server on load form
                form.getForm().load({
                   url: '/vk/lot',
                   params: {
                       id: 1
                   }
                });

                //Tables
                var getTable = new Ext.data.Store({
                    reader: new Ext.data.JsonReader({
                        fields: [
                            {
                                name: 'id', type: 'int'
                            },
                            {
                                name: 'type_lot', type: 'string'
                            }
                        ],
                        root: 'rows',
                        totalProperty: 'result'
                    }),
                    proxy: new Ext.data.HttpProxy({
                        url: '/vk/test'
                    }),
                    autoLoad: true
                });

                //Tables - Group Table
                var groupTable = new Ext.data.GroupingStore({
                    reader: new Ext.data.JsonReader({
                        fields: [
                            {
                                name: 'id', type: 'int'
                            },
                            {
                                name: 'type_lot', type: 'string'
                            }
                        ],
                        root: 'rows',
                        totalProperty: 'result'
                    }),
                    proxy: new Ext.data.HttpProxy({
                        url: '/vk/test'
                    }),
                    groupField: 'type_lot',
                    sortInfo: {
                        field: 'type_lot',
                        direction: 'ASC'
                    },
                    autoLoad: true
                });

                var type_lot = new Ext.form.TextField({vtype: 'text'});
                /*var type_lot = new Ext.form.ComboBox({
                    mode: 'remote',
                    store: getTest,
                    valueField: 'id',
                    displayField: 'type_lot',
                    triggerAction: 'all'
                });*/

                var grid = new Ext.grid.EditorGridPanel({
                    renderTo: document.body,
                    frame: true,
                    title: 'Разделы лотов',
                    clickstoEdit: 1,
                    height: 400,
                    width: 500,
                    store: getTable,
                    // view: new Ext.grid.GroupingView(),
                    //Pagination
                    bbar: new Ext.PagingToolbar({
                        pageSize: 1,
                        store: getTable
                    }),
                    tbar: [{
                                text: 'Изменить название раздела',
                                handler: function() {
                                    var sm = grid.getSelectionModel();
                                    if (sm.hasSelection()) {
                                        var sel = sm.getSelected();
                                        Ext.Msg.show({
                                            title:'Название раздела',
                                            prompt: true,
                                            buttons: Ext.Msg.OKCANCEL,
                                            value: sel.data.type_lot,
                                            fn: function(btn, text){
                                                if (text.length != '' && btn == 'ok') {
                                                    sel.set('type_lot', text);
                                                    Ext.Ajax.request({
                                                        url: 'update',
                                                        success: function(response) {
                                                            console.log(response);
                                                        },
                                                        failure: function(response){
                                                            console.log(response);
                                                        },
                                                        params: sel.data
                                                    });
                                                }
                                            },
                                        });
                                    }
                                }
                            },
                            {
                                text: 'Скрыть колонку',
                                handler: function(btn) {
                                    var cm = grid.getColumnModel(),
                                        col = cm.getIndexById('1');
                                    if (cm.isHidden(col)) {
                                        cm.setHidden(col, false);
                                        btn.setText('Скрыть колонку');
                                    } else {
                                        cm.setHidden(col, true);
                                        btn.setText('Показать колонку');
                                    }
                                }
                            },
                            {
                                text: 'Удалить строку',
                                cls: 'x-btn-text-icon',
                                icon: 'images/table_delete.png',
                                handler: function() {
                                    var sm = grid.getSelectionModel(),
                                        sel = sm.getSelected();
                                    if (sm.hasSelection()) {
                                        Ext.Msg.show({
                                           title: 'Удаление записи',
                                           buttons: Ext.MessageBox.YESNO,
                                           msg: 'Удалить раздел ' +  sel.data.type_lot,
                                           fn: function(btn) {
                                               if (btn == 'ok') {
                                                   grid.getStore().remove(sel);
                                               }
                                           }
                                        });
                                    }
                                }
                            }
                    ],
                    sm: new Ext.grid.RowSelectionModel({
                       singleSelect: true,
                       /*listeners: {
                           rowselect: {
                               fn: function(sm, index, rec) {
                                   Ext.Msg.alert('Вы выбрали лот', rec.data.type_lot)
                               }
                           }
                       }*/
                    }),
                    columns: [
                        {
                            header: 'Изображение', renderer: getImageLot
                        },
                        {
                            header: 'Id', dataIndex: 'id', sortable: true
                        },
                        {
                            header: 'Раздел лотов', dataIndex: 'type_lot', editor: type_lot
                        }
                    ],
                    listeners: {
                        afteredit: function(e) {
                            if (e.field == 'type_lot' && e.value != '') {
                                e.record.commit();
                            } else {
                                Ext.Msg.alert('Ошибка', 'Запись не сохранена');
                            }
                        }
                    }
                });

                function getImageLot(val, x, store)
                {
                    return '<img src="public/images/' + store.data.id + '.jpg" width="50" height="50">';
                }

                function showLot(val, x, store)
                {
                    return '<b>' + val + '</b></br>' + store.data.type_lot;
                }

                //Loader on Ajax Request
                showLoadingMask();

                function showLoadingMask(loadingMessage)
                {
                    if (Ext.isEmpty(loadingMessage)) {
                        loadText = 'Loading... Please wait';
                    }

                    Ext.Ajax.on('beforerequest',function(){Ext.getBody().mask(loadText, 'loading') }, Ext.getBody());
                    Ext.Ajax.on('requestcomplete',Ext.getBody().unmask ,Ext.getBody());
                    Ext.Ajax.on('requestexception', Ext.getBody().unmask , Ext.getBody());
                }
            });
        </script>
    </body>
</html>