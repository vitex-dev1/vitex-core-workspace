$(function() {
	if($('.treeview-lv3').length) {
		$('.treeview-lv3').map(function(){
			checkboxChanged.call(this);
		});
	}
	
	$('input[data-role="treeview-cb"]').change(checkboxChanged);

	function checkboxChanged() {
		var $this = $(this),
				checked = $this.prop("checked"),
				container = $this.parent(),
				siblings = container.siblings();

		container.find('input[data-role="treeview-cb"]')
				.prop({
					indeterminate: false,
					checked: checked
				})
				.siblings('label')
				.removeClass('custom-checked custom-unchecked custom-indeterminate')
				.addClass(checked ? 'custom-checked' : 'custom-unchecked');

		checkSiblings(container, checked);
	}

	function checkSiblings($el, checked) {
		var parent = $el.parent().parent(),
				all = true,
				indeterminate = false;

		$el.siblings().each(function() {
			return all = ($(this).children('input[data-role="treeview-cb"]').prop("checked") === checked);
		});

		if (all && checked) {
			parent.children('input[data-role="treeview-cb"]')
					.prop({
						indeterminate: false,
						checked: checked
					})
					.siblings('label')
					.removeClass('custom-checked custom-unchecked custom-indeterminate')
					.addClass(checked ? 'custom-checked' : 'custom-unchecked');

			checkSiblings(parent, checked);
		} else if (all && !checked) {
			indeterminate = parent.find('input[data-role="treeview-cb"]:checked').length > 0;

			parent.children('input[data-role="treeview-cb"]')
					.prop("checked", checked)
					.prop("indeterminate", indeterminate)
					.siblings('label')
					.removeClass('custom-checked custom-unchecked custom-indeterminate')
					.addClass(indeterminate ? 'custom-indeterminate' : (checked ? 'custom-checked' : 'custom-unchecked'));

			checkSiblings(parent, checked);
		} else {
			$el.parents("li").children('input[data-role="treeview-cb"]')
					.prop({
						indeterminate: true,
						checked: false
					})
					.siblings('label')
					.removeClass('custom-checked custom-unchecked custom-indeterminate')
					.addClass('custom-indeterminate');
		}
	}
});