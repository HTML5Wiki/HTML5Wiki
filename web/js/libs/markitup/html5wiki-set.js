html5WikiMarkItUpSettings = {
	previewParserPath:	'../../wiki/preview',
	previewInWindow: 'width=1000, height=600, resizable=yes, scrollbars=yes',
	previewAutoRefresh: true,
	onShiftEnter:		{keepDefault:false, openWith:'\n\n'},
	markupSet: [
		{name:'First Level Heading', key:'1', placeHolder:'Your title here...', closeWith:function(markItUp) { return miu.markdownTitle(markItUp, '=') }, className:'heading1' },
		{name:'Second Level Heading', key:'2', placeHolder:'Your title here...', closeWith:function(markItUp) { return miu.markdownTitle(markItUp, '-') }, className:'heading2' },
		{name:'Heading 3', key:'3', openWith:'### ', placeHolder:'Your title here...', className:'heading3' },
		{name:'Heading 4', key:'4', openWith:'#### ', placeHolder:'Your title here...', className:'heading4' },
		{name:'Heading 5', key:'5', openWith:'##### ', placeHolder:'Your title here...', className:'heading5' },
		{name:'Heading 6', key:'6', openWith:'###### ', placeHolder:'Your title here...', className:'heading6' },
		{separator:'---------------' },		
		{name:'Bold', key:'B', openWith:'**', closeWith:'**', className:'bold'},
		{name:'Italic', key:'I', openWith:'_', closeWith:'_', className:'italic'},
		{separator:'---------------' },
		{name:'Bulleted List', openWith:'- ', className:'bulletList' },
		{name:'Numeric List', openWith:function(markItUp) {
			return markItUp.line+'. ';
		}, className:'numberedList'},
		{separator:'---------------' },
		{name:'Picture', key:'P', replaceWith:'![[![Alternative text]!]]([![Url:!:http://]!] "[![Title]!]")', className:'addImage'},
		{name:'Link', key:'L', openWith:'[', closeWith:']([![Url:!:http://]!] "[![Title]!]")', placeHolder:'Your text to link here...', className:'addLink' },
		{separator:'---------------'},	
		{name:'Quotes', openWith:'> ', className:'quote'},
		{name:'Code Block / Code', openWith:'(!(\t|!|`)!)', closeWith:'(!(`)!)', className:'sourcecode'},
		{separator:'---------------'},
		{name:'Preview', call:'preview', className:"preview"}
	]
}

// mIu nameSpace to avoid conflict.
miu = {
	markdownTitle: function(markItUp, character) {
		var heading = '';
		var n = $.trim(markItUp.selection||markItUp.placeHolder).length;
		for(i = 0; i < n; i++) {
			heading += character;
		}
		return '\n'+heading;
	}
}