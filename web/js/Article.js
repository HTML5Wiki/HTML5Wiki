Article = {
	
	create: function() {
		var form	= $('#create-article');
		if( form ) {
			var name = $('#txtAuthor').val();
			var email = $('#txtAuthorEmail').val();

			$.ajax({
				type: 'POST',
				url: form.action, 
				complete: this.onSaved.bind(this),
				data: 'ajax=true&txtAuthor='+name+'&txtAuthorEmail='+email
			});
		}
	},

	onSaved: function(response) {
		$('#content').replaceWith(response.responseText);
	}
};