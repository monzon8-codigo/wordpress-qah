
var tinymce = null, tinyMCEPopup, tinyMCE, foterImage;

tinyMCEPopup = {
	init: function() {
		var t = this, w, li, q, i, it;

		li = ('' + document.location.search).replace(/^\?/, '').split('&');
		q = {};
		for ( i = 0; i < li.length; i++ ) {
			it = li[i].split('=');
			q[unescape(it[0])] = unescape(it[1]);
		}

		if (q.mce_rdomain)
			document.domain = q.mce_rdomain;

		// Find window & API
		w = t.getWin();
		tinymce = w.tinymce;
		tinyMCE = w.tinyMCE;
		t.editor = tinymce.EditorManager.activeEditor;
		t.params = t.editor.windowManager.params;

		// Setup local DOM
		t.dom = t.editor.windowManager.createInstance('tinymce.dom.DOMUtils', document);
		t.editor.windowManager.onOpen.dispatch(t.editor.windowManager, window);
	},

	getWin : function() {
		return window.dialogArguments || opener || parent || top;
	},

	getParam : function(n, dv) {
		return this.editor.getParam(n, dv);
	},

	close : function() {
		var t = this, win = t.getWin();

		// To avoid domain relaxing issue in Opera
		function close() {
			win.tb_remove();
			tinymce = tinyMCE = t.editor = t.dom = t.dom.doc = null; // Cleanup
		};

		if (tinymce.isOpera)
			win.setTimeout(close, 0);
		else
			close();
	},

	execCommand : function(cmd, ui, val, a) {
		a = a || {};
		a.skip_focus = 1;

		this.restoreSelection();
		return this.editor.execCommand(cmd, ui, val, a);
	},

	storeSelection : function() {
		this.editor.windowManager.bookmark = tinyMCEPopup.editor.selection.getBookmark('simple');
	},

	restoreSelection : function() {
		var t = tinyMCEPopup;

		if (tinymce.isIE)
			t.editor.selection.moveToBookmark(t.editor.windowManager.bookmark);
	}
}
tinyMCEPopup.init();

foterImage = {
    preInit : function() {
        // import colors stylesheet from parent
        var win = tinyMCEPopup.getWin(), styles = win.document.styleSheets, url, i;

        for ( i = 0; i < styles.length; i++ ) {
            url = styles.item(i).href;
            if ( url && url.indexOf('colors') != -1 )
                document.write( '<link rel="stylesheet" href="'+url+'" type="text/css" media="all" />' );
        }
    },

    I : function(e) {
        return document.getElementById(e);
    },

    current : '',
    link : '',
    link_rel : '',
    target_value : '',
    align : '',
    img_alt : '',

    init : function() {
        var ed = tinyMCEPopup.editor, h;

        h = document.body.innerHTML;
        document.body.innerHTML = ed.translate(h);
        window.setTimeout( function(){foterImage.setup();}, 500 );
    },

    setup : function() {
        var t = this, c, el, link, fname, f = document.forms[0], ed = tinyMCEPopup.editor,
        dom = tinyMCEPopup.dom, DL, caption = '', dlc, pa;

        document.dir = tinyMCEPopup.editor.getParam('directionality','');

        if ( tinyMCEPopup.editor.getParam('wpeditimage_disable_captions', false) )
            t.I('cap_field').style.display = 'none';

        tinyMCEPopup.restoreSelection();
        el = ed.selection.getNode();
        if (el.nodeName != 'IMG')
            return;

        //f.img_src.value = d.src = link = ed.dom.getAttrib(el, 'src');
        ed.dom.setStyle(el, 'float', '');
        //t.getImageData();
        

        if ( DL = dom.getParent(el, 'dl') ) {
            c = ed.dom.getAttrib(DL, 'class');
            
            tinymce.each(DL.childNodes, function(e) {
                if ( e.nodeName == 'DD') {
                    tinymce.each(e.childNodes, function(e1) {
                        if ( dom.hasClass(e1, 'foter-caption') ) {
                            caption = e1.innerHTML;
                            return;
                        }
                    });
                    return;
                }
            });
        }

        f.img_cap.value = caption;
        f.img_title.value = ed.dom.getAttrib(el, 'title');
        f.img_alt.value = ed.dom.getAttrib(el, 'alt');
        //f.border.value = ed.dom.getAttrib(el, 'border');
        //f.vspace.value = ed.dom.getAttrib(el, 'vspace');
        //f.hspace.value = ed.dom.getAttrib(el, 'hspace');
        //f.align.value = ed.dom.getAttrib(el, 'align');
        //f.width.value = t.width = ed.dom.getAttrib(el, 'width');
        //f.height.value = t.height = ed.dom.getAttrib(el, 'height');
        //f.img_classes.value = c;
        //f.img_style.value = ed.dom.getAttrib(el, 'style');

        // Move attribs to styles
        //if ( dom.getAttrib(el, 'hspace') )
        //    t.updateStyle('hspace');

        //if ( dom.getAttrib(el, 'border') )
        //    t.updateStyle('border');

        //if ( dom.getAttrib(el, 'vspace') )
        //    t.updateStyle('vspace');

        if ( pa = ed.dom.getParent(el, 'A') ) {
            f.link_href.value = t.current = ed.dom.getAttrib(pa, 'href');
            //f.link_title.value = ed.dom.getAttrib(pa, 'title');
            //f.link_rel.value = t.link_rel = ed.dom.getAttrib(pa, 'rel');
            //f.link_style.value = ed.dom.getAttrib(pa, 'style');
            //t.target_value = ed.dom.getAttrib(pa, 'target');
            //f.link_classes.value = ed.dom.getAttrib(pa, 'class');
        }

        //f.link_target.checked = ( t.target_value && t.target_value == '_blank' ) ? 'checked' : '';

        //fname = link.substring( link.lastIndexOf('/') );
        //fname = fname.replace(/-[0-9]{2,4}x[0-9]{2,4}/, '' );
        //t.link = link.substring( 0, link.lastIndexOf('/') ) + fname;

        if ( c.indexOf('alignleft') != -1 ) {
            t.I('alignleft').checked = "checked";
            //d.className = t.align = "alignleft";
        } else if ( c.indexOf('aligncenter') != -1 ) {
            t.I('aligncenter').checked = "checked";
            //d.className = t.align = "aligncenter";
        } else if ( c.indexOf('alignright') != -1 ) {
            t.I('alignright').checked = "checked";
            //d.className = t.align = "alignright";
        } else if ( c.indexOf('alignnone') != -1 ) {
            t.I('alignnone').checked = "checked";
            //d.className = t.align = "alignnone";
        }

        //if ( t.width && t.preloadImg.width ) t.showSizeSet();
        document.body.style.display = '';
    },

    update : function() {
        var t = this, f = document.forms[0], ed = tinyMCEPopup.editor, el, b, //fixSafari = null,
            DL, A, do_caption = null,caption,
            //img_class = f.img_classes.value,
            html, id, cap_id = '', cap, DT, DD, cap_width, div_cls, lnk = '', pa, aa;

        tinyMCEPopup.restoreSelection();
        el = ed.selection.getNode();
        if (el.nodeName != 'IMG') return;

        if ( f.img_cap.value != '' ) {
            do_caption = 1;
            //img_class = img_class.replace( /align[^ "']+\s?/gi, '' );
        }

        A = ed.dom.getParent(el, 'a');
        DL = ed.dom.getParent(el, 'dl');

        tinyMCEPopup.execCommand("mceBeginUndoLevel");

        var align = 'alignnone';
        if ( t.I('alignleft').checked )  align = 'alignleft';
        else if ( t.I('aligncenter').checked ) align = 'aligncenter';
        else if ( t.I('alignright').checked ) align = 'alignright';

        ed.dom.setAttribs(DL, {
            'class' : 'foter-photo '+ align
        });

        ed.dom.setAttribs(el, {
                //src : f.img_src.value,
                title : f.img_title.value,
                alt : f.img_alt.value
                //width : f.width.value,
                //height : f.height.value,
                //style : f.img_style.value,
                //'class' : img_class
        });

        if ( f.link_href.value ) {
        // Create new anchor elements
            if ( A == null ) {
                if ( ! f.link_href.value.match(/https?:\/\//i) )
                    f.link_href.value = tinyMCEPopup.editor.documentBaseURI.toAbsolute(f.link_href.value);

                //if ( tinymce.isWebKit && ed.dom.hasClass(el, 'aligncenter') ) {
                //        ed.dom.removeClass(el, 'aligncenter');
                //        fixSafari = 1;
                //}

                tinyMCEPopup.execCommand("CreateLink", false, "#mce_temp_url#", {skip_undo : 1});
                //if ( fixSafari ) ed.dom.addClass(el, 'aligncenter');

                tinymce.each(ed.dom.select("a"), function(n) {
                    if (ed.dom.getAttrib(n, 'href') == '#mce_temp_url#') {

                        ed.dom.setAttribs(n, {
                                href : f.link_href.value,
                                title : f.img_title.value//f.link_title.value,
                                //rel : f.link_rel.value,
                                //target : (f.link_target.checked == true) ? '_blank' : '',
                                //'class' : f.link_classes.value,
                                //style : f.link_style.value
                        });
                    }
                });
            } else {
                ed.dom.setAttribs(A, {
                        href : f.link_href.value,
                        title :  f.img_title.value//f.link_title.value,
                        //rel : f.link_rel.value,
                        //target : (f.link_target.checked == true) ? '_blank' : '',
                        //'class' : f.link_classes.value,
                        //style : f.link_style.value
                });
            }
        }

        var captbefore;
        if ( DL = ed.dom.getParent(el, 'dl') ) {
            tinymce.each(DL.childNodes, function(e) {
                if ( e.nodeName == 'DD') {
                    DD = e;
                    tinymce.each(e.childNodes, function(e1) {
                        if (!captbefore && (e1.nodeName == 'SPAN')) {
                            captbefore = e1;
                        }
                        if ( ed.dom.hasClass(e1, 'foter-caption') ) {
                            caption = e1;
                            return;
                        }
                    });
                    return;
                }
            });
        }

        if ( do_caption ) {
            //cap_width = 10 + parseInt(f.width.value);
            //div_cls = (t.align == 'aligncenter') ? 'mceTemp mceIEcenter' : 'mceTemp';


            if (caption) {
                ed.dom.setHTML(caption, f.img_cap.value);
            } else {
                caption = ed.dom.create('span', {
                            'class':'foter-caption',
                            'style':'display:block;font-size: 11px;line-height: 17px;margin-bottom: 0;'
                            }, f.img_cap.value);
                DD.insertBefore(caption, captbefore);
            }
        } else {
            if ( caption ) {
                ed.dom.remove(caption);
            }
        }

            //if ( f.img_classes.value.indexOf('aligncenter') != -1 ) {
            //        if ( P && ( ! P.style || P.style.textAlign != 'center' ) )
            //                ed.dom.setStyle(P, 'textAlign', 'center');
            //} else {
            //        if ( P && P.style && P.style.textAlign == 'center' )
            //                ed.dom.setStyle(P, 'textAlign', '');
            //}

            if ( ! f.link_href.value && A ) {
                    b = ed.selection.getBookmark();
                    ed.dom.remove(A, 1);
                    ed.selection.moveToBookmark(b);
            }

            tinyMCEPopup.execCommand("mceEndUndoLevel");
            ed.execCommand('mceRepaint');
            tinyMCEPopup.close();
	}
    
};

window.onload = function(){foterImage.init();}
foterImage.preInit();

