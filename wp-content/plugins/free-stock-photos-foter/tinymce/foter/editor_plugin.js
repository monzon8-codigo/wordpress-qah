if (tinymce.dom.Event.add == undefined) {
    tinymce.dom.Event.add = tinymce.dom.Event.bind;
}
(function() {
	tinymce.create('tinymce.plugins.foter', {

		init : function(ed, url) {
			var t = this;

			t.url = url;
			t._createButtons();

			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('...');
			ed.addCommand('foter_EditImage', function() {
				var el = ed.selection.getNode(), vp = tinymce.DOM.getViewPort(), H = vp.h, W = ( 720 < vp.w ) ? 720 : vp.w, cls = ed.dom.getAttrib(el, 'class');

                                if ((cls.indexOf('foter-photo') == -1)|| (el.nodeName != 'IMG')) return;

				tb_show('', url + '/editimage.html?ver=321&TB_iframe=true');
				tinymce.DOM.setStyles('TB_window', {
					'width':( W - 50 )+'px',
					'height':( H - 45 )+'px',
					'margin-left':'-'+parseInt((( W - 50 ) / 2),10) + 'px'
				});

				if ( ! tinymce.isIE6 ) {
					tinymce.DOM.setStyles('TB_window', {
						'top':'20px',
						'marginTop':'0'
					});
				}

				tinymce.DOM.setStyles('TB_iframeContent', {
					'width':( W - 50 )+'px',
					'height':( H - 75 )+'px'
				});
				tinymce.DOM.setStyle( ['TB_overlay','TB_window','TB_load'], 'z-index', '999999' );
			});

			ed.onInit.add(function(ed) {
				tinymce.dom.Event.add(ed.getBody(), 'dragstart', function(e) {
					if ( !tinymce.isGecko && e.target.nodeName == 'IMG' && ed.dom.getParent(e.target, 'dl.foter-photo') )
						return tinymce.dom.Event.cancel(e);
				});
			});

			// show editimage buttons
			ed.onMouseDown.add(function(ed, e) {
				var p, cls = ed.dom.getAttrib(e.target, 'class');
                                if ((e.target.nodeName == 'IMG') && (cls.indexOf('foter-photo') != -1)) {
                                	ed.plugins.foter._showButtons(e.target, 'foter_editbtns');
                                	if ( tinymce.isGecko && (p = ed.dom.getParent(e.target, 'dl.foter-photo')) ) {
                                            ed.selection.select(p);
                                        }
				}
			});

			// when pressing Return inside a caption move the cursor to a new parapraph under it
			ed.onKeyPress.add(function(ed, e) {
				var n, DL, P;

				if ( e.keyCode == 13 ) {
					n = ed.selection.getNode();
					DL = ed.dom.getParent(n, 'dl.foter-photo');

					if ( DL ) {
						P = ed.dom.create('p', {}, '&nbsp;');
						ed.dom.insertAfter( P, DL );

						if ( P.firstChild )
							ed.selection.select(P.firstChild);
						else
							ed.selection.select(P);

						tinymce.dom.Event.cancel(e);
						return false;
					}
				}
			});

			ed.onBeforeSetContent.add(function(ed, o) {
				//o.content = t._do_shcode(o.content);
			});

			ed.onPostProcess.add(function(ed, o) {
				//if (o.get)
					//o.content = t._get_shcode(o.content);
			});

                        ed.onInit.add(function(ed) {
				tinymce.dom.Event.add(ed.getWin(), 'scroll', function(e) {
					ed.plugins.foter._hideButtons();
				});
				tinymce.dom.Event.add(ed.getBody(), 'dragstart', function(e) {
					ed.plugins.foter._hideButtons();
				});
			});

			ed.onBeforeExecCommand.add(function(ed, cmd, ui, val) {
				ed.plugins.foter._hideButtons();
			});

			ed.onSaveContent.add(function(ed, o) {
				ed.plugins.foter._hideButtons();
			});

			ed.onMouseDown.add(function(ed, e) {
				if ( e.target.nodeName != 'IMG' )
					ed.plugins.foter._hideButtons();
			});
		},

		_do_shcode : function(co) {
			return co.replace(/(?:<p>)?\[(?:wp_)?caption([^\]]+)\]([\s\S]+?)\[\/(?:wp_)?caption\](?:<\/p>)?[\s\u00a0]*/g, function(a,b,c){
				var id, cls, w, cap, div_cls;

				b = b.replace(/\\'|\\&#39;|\\&#039;/g, '&#39;').replace(/\\"|\\&quot;/g, '&quot;');
				c = c.replace(/\\&#39;|\\&#039;/g, '&#39;').replace(/\\&quot;/g, '&quot;');
				id = b.match(/id=['"]([^'"]+)/i);
				cls = b.match(/align=['"]([^'"]+)/i);
				w = b.match(/width=['"]([0-9]+)/);
				cap = b.match(/caption=['"]([^'"]+)/i);

				id = ( id && id[1] ) ? id[1] : '';
				cls = ( cls && cls[1] ) ? cls[1] : 'alignnone';
				w = ( w && w[1] ) ? w[1] : '';
				cap = ( cap && cap[1] ) ? cap[1] : '';
				if ( ! w || ! cap ) return c;

				div_cls = (cls == 'aligncenter') ? 'mceTemp mceIEcenter' : 'mceTemp';

				return '<div class="'+div_cls+'" draggable><dl id="'+id+'" class="wp-caption '+cls+'" style="width: '+(10+parseInt(w))+
				'px"><dt class="wp-caption-dt">'+c+'</dt><dd class="wp-caption-dd">'+cap+'</dd></dl></div>';
			});
		},

		_get_shcode : function(co) {
			return co.replace(/<div class="mceTemp[^"]*">\s*<dl([^>]+)>\s*<dt[^>]+>([\s\S]+?)<\/dt>\s*<dd[^>]+>(.+?)<\/dd>\s*<\/dl>\s*<\/div>\s*/gi, function(a,b,c,cap){
				var id, cls, w;

				id = b.match(/id=['"]([^'"]+)/i);
				cls = b.match(/class=['"]([^'"]+)/i);
				w = c.match(/width=['"]([0-9]+)/);

				id = ( id && id[1] ) ? id[1] : '';
				cls = ( cls && cls[1] ) ? cls[1] : 'alignnone';
				w = ( w && w[1] ) ? w[1] : '';

				if ( ! w || ! cap ) return c;
				cls = cls.match(/align[^ '"]+/) || 'alignnone';
				cap = cap.replace(/<\S[^<>]*>/gi, '').replace(/'/g, '&#39;').replace(/"/g, '&quot;');

				return '[caption id="'+id+'" align="'+cls+'" width="'+w+'" caption="'+cap+'"]'+c+'[/caption]';
			});
		},

		_createButtons : function() {
			var t = this, ed = tinyMCE.activeEditor, DOM = tinymce.DOM, editButton, dellButton;

			DOM.remove('foter_editbtns');

			DOM.add(document.body, 'div', {
				id : 'foter_editbtns',
				style : 'display:none;'
			});

			editButton = DOM.add('foter_editbtns', 'img', {
				src : t.url+'/img/image.png',
				id : 'foter_editimgbtn',
				width : '24',
				height : '24',
				title : ed.getLang('wpeditimage.edit_rimg')
			});

			tinymce.dom.Event.add(editButton, 'mousedown', function(e) {
				var ed = tinyMCE.activeEditor;
				ed.windowManager.bookmark = ed.selection.getBookmark('simple');
				ed.execCommand("foter_EditImage");
			});

			dellButton = DOM.add('foter_editbtns', 'img', {
				src : t.url+'/img/delete.png',
				id : 'foter_delimgbtn',
				width : '24',
				height : '24',
				title : ed.getLang('wpeditimage.del_rimg')
			});

			tinymce.dom.Event.add(dellButton, 'mousedown', function(e) {
				var ed = tinyMCE.activeEditor, el = ed.selection.getNode(), p;

                                if ((e.target.nodeName == 'IMG') && (ed.dom.getAttrib(el, 'class').indexOf('foter-photo') != -1)) {

					if ( p = ed.dom.getParent(el, 'dl') )
						ed.dom.remove(p);
					else if ( (p = ed.dom.getParent(el, 'A')) && p.childNodes.length == 1 )
						ed.dom.remove(p);
					else
						ed.dom.remove(el);

					ed.execCommand('mceRepaint');
					return false;
				}
			});

		},

		getInfo : function() {
			return {
				longname : 'Foter Edit Image',
				author : 'foter',
				authorurl : 'http://foter.com',
				infourl : '',
				version : "1.0"
			};
		},

                _showButtons : function(n, id) {
			var ed = tinyMCE.activeEditor, p1, p2, vp, DOM = tinymce.DOM, X, Y;

			vp = ed.dom.getViewPort(ed.getWin());
			p1 = DOM.getPos(ed.getContentAreaContainer());
			p2 = ed.dom.getPos(n);

			X = Math.max(p2.x - vp.x, 0) + p1.x;
			Y = Math.max(p2.y - vp.y, 0) + p1.y;

			DOM.setStyles(id, {
				'top' : Y+5+'px',
				'left' : X+5+'px',
				'display' : 'block'
			});

			if ( this.mceTout )
				clearTimeout(this.mceTout);

			this.mceTout = setTimeout( function(){ed.plugins.foter._hideButtons();}, 5000 );
		},

		_hideButtons : function() {
			if ( !this.mceTout )
				return;

			if ( document.getElementById('foter_editbtns') )
				tinymce.DOM.hide('foter_editbtns');

			clearTimeout(this.mceTout);
			this.mceTout = 0;
		}

	});

	tinymce.PluginManager.add('foter', tinymce.plugins.foter);
})();
