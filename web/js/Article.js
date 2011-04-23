Article = {
	
	create: function() {
		var form	= $('#create-article');
		if( form ) {
			var name    = $('#txtAuthor').val();
			var email   = $('#txtAuthorEmail').val();
            var id      = $('#hiddenAuthorId').val();

			$.ajax({
				type: 'POST',
				url: form.attr('action'), 
				complete: this.onSaved.bind(this),
				data: 'ajax=true&txtAuthor='+name+'&txtAuthorEmail='+email+'&hiddenAuthorId='+id
			});
		}
	},

	onSaved: function(response) {
		$('#content').replaceWith(response.responseText);
	}
};