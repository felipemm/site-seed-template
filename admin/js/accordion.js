Ext.define('Ext.ux.layout.Accordion', {
    extend: 'Ext.layout.container.Accordion',
    alias: ['layout.accordionx'],

    beforeRenderItems: function (items) {
        var me = this,
            ln = items.length,
            i = 0,
            comp;

        for (; i < ln; i++) {
            comp = items[i];
            if (!comp.rendered) {
                // Set up initial properties for Panels in an accordion.
                if (me.collapseFirst) {
                    comp.collapseFirst = me.collapseFirst;
                }
                if (me.hideCollapseTool) {
                    comp.hideCollapseTool = me.hideCollapseTool;
                    comp.titleCollapse = true;
                }
                else if (me.titleCollapse) {
                    comp.titleCollapse = me.titleCollapse;
                }

                delete comp.hideHeader;
                delete comp.width;
                comp.collapsible = true;
                comp.title = comp.title || '&#160;';
                comp.addBodyCls(Ext.baseCSSPrefix + 'accordion-body');

                // If only one child Panel is allowed to be expanded
                // then collapse all except the first one found with collapsed:false
                if (!me.multi) {
                    // If there is an expanded item, all others must be rendered collapsed.
                    if (me.expandedItem !== undefined) {
                        comp.collapsed = true;
                    }
                    // Otherwise expand the first item with collapsed explicitly configured as false
                    else if (comp.hasOwnProperty('collapsed') && comp.collapsed === false) {
                        me.expandedItem = i;
                    } else {
                        comp.collapsed = true;
                    }

                    // If only one child Panel may be expanded, then intercept expand/show requests.
                    me.owner.mon(comp, {
                        show: me.onComponentShow,
                        beforeexpand: me.onComponentExpand,
                        scope: me
                    });
                }

                // If we must fill available space, a collapse must be listened for and a sibling must
                // be expanded.
                if (me.fill) {
                    me.owner.mon(comp, {
                        beforecollapse: me.onComponentCollapse,
                        scope: me
                    });
                }
            }
        }
        // If no collapsed:false Panels found, make the first one expanded.
        if (ln && me.expandedItem === undefined) {
           // me.expandedItem = 0;
            //items[0].collapsed = false;
        }
    },

    onComponentCollapse: function(comp) {
        var me = this,
            owner = me.owner,
            toExpand,
            expanded,
            previousValue;

        if (me.owner.items.getCount() === 1) {
            // do not allow collapse if there is only one item
            return false;
        }

        if (!me.processing) {
            me.processing = true;
            previousValue = owner.deferLayouts;
            owner.deferLayouts = true;
            toExpand = comp.next() || comp.prev();

            // If we are allowing multi, and the "toCollapse" component is NOT the only expanded Component,
            // then ask the box layout to collapse it to its header.
            if (me.multi) {
                expanded = me.owner.query('>panel:not([collapsed])');

                // If the collapsing Panel is the only expanded one, expand the following Component.
                // All this is handling fill: true, so there must be at least one expanded,
                if (expanded.length === 1) {
                    toExpand.expand();
                }

            } else if (toExpand) { //*Fabyo deixado por padrao nao abrir proximo painel quando fechado algum
                //toExpand.expand();
            }
            owner.deferLayouts = previousValue;
            me.processing = false;
        }
    }  
});