/**
 * 自定义jquery扩展插件
 */

/**
 * 	限制文本输入字符长度
 * @param num max	 	//最大字符
 * 用法：$('#mytextarea').maxLength(100);
 */
(function ($) {
	$.fn.maxLength = function(max){ 
	    return this.each(function(){
	        var type = this.tagName.toLowerCase(); 
	        var inputType = this.type? this.type.toLowerCase() : null; 
	        if(type == "input" && inputType == "text" || inputType == "password"){ 
	            //Apply the standard maxLength 
	            this.maxLength = max; 
	        } else if(type == "textarea"){
	            this.onkeypress = function(e){ 
	                var ob = e || event; 
	                var keyCode = ob.keyCode; 
	                var hasSelection = document.selection? document.selection.createRange().text.length > 0 : this.selectionStart != this.selectionEnd; 
	                return !(this.value.length >= max && (keyCode > 50 || keyCode == 32 || keyCode == 0 || keyCode == 13) && !ob.ctrlKey && !ob.altKey && !hasSelection); 
	            }; 
	            this.onkeyup = function(){ 
	                if(this.value.length > max){ 
	                    this.value = this.value.substring(0,max); 
	                } 
	            };
	        }
	    });
	};
})(jQuery);



/**
 * 让任意元素在屏幕中间显示
 * 用法：$('#mytextarea').center();
 */
(function ($) {
	$.fn.center = function () { 
		return this.each(function () {
			$(this).css({
				position : 'absolute',
				top : ($(window).height() - $(this).height()) / 2 + $(window).scrollTop() + 'px', 
				left:($(window).width() - $(this).width()) / 2 + $(window).scrollLeft() + 'px'
			});	
		});
	}
})(jQuery);


/**
 * 去除元素中的所有html
 * 用法：$('p').stripHtml();
 */
(function($) { 
	$.fn.stripHtml = function() { 
	　 var regexp = /<("[^"]*"|'[^']*'|[^'">])*>/gi; 
	　 this.each(function()  { 
	　　　 $(this).html( $(this).html().replace(regexp,'') ); 
	　 });
	　 return $(this); 
	} 
})(jQuery); 
