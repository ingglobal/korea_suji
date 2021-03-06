/*
IML v1.0.0 | 2015-09-06
Сustom tooltip/popover/modal jQuery plugin
Developed under the MIT license http://opensource.org/licenses/MIT
*/
+function($){

    function Modal(target){  
        this.element;
        this.target = target;
        this.fOut_bg = parseInt(target.attr('data-fadeOut-bg')) || 300;
        this.fIn_bg = parseInt(target.attr('data-fadeIn-bg')) || 300;  
        this.fOut_modal = parseInt(target.attr('data-fadeOut-modal')) || 100;
        this.fIn_modal = parseInt(target.attr('data-fadeIn-modal')) || 100; 
        this.background = target.attr('data-background') || '#fff';
        this.width = parseFloat(target.attr('data-width')) || '550';
        this.height = parseFloat(target.attr('data-height')) || '300';
        this.trigger_on = target.attr('data-trigger-on')|| "click";
        this.winWidth = $(window).width();
        this.topMargin = $(window).scrollTop();
        this.bb = $('<div id="background"></div>');
    }

    Modal.prototype.constructor = Modal;

    Modal.prototype.scrollStop = function(){
        if (document.body.addEventListener) document.body.addEventListener('DOMMouseScroll', this.blockWheel, false);
        document.body.onmousewheel = this.blockWheel;
    }
    Modal.prototype.blockWheel = function(event){
        if (!event) event = window.event;
        if (event.stopPropagation) event.stopPropagation();
        else event.cancelBubble = true;
        if(event.preventDefault) event.preventDefault();
        else event.returnValue = false;
    }
    Modal.prototype.scrollStart = function(){
        if (document.body.addEventListener) document.body.addEventListener('DOMMouseScroll', this.enabledWheel, true);
        document.body.onmousewheel = this.enabledWheel;
    }
    Modal.prototype.enabledWheel = function(event){
        event.returnValue = true;
    }

    Modal.prototype.showModal = function(){
        $(this.element).css({'background-color':this.background});
        $(this.element).fadeIn(this.fIn_modal);
        this.bb.fadeIn(this.fIn_bg);
        this.scrollStop();  
    }
    

    Modal.prototype.hideModal = function(){
        $(this.element).fadeOut(this.fOut_modal); 
        this.bb.fadeOut(this.fOut_bg,function(){$(this).remove();});
        this.scrollStart();
    }
    Modal.prototype.stayPosition = function(){
       
        var stayTop,stayLeft;
        stayTop = (this.topMargin + 90);
        stayLeft = (parseFloat($(window).width()) - this.width)/2;  
        $(this.element).css({
            'top':stayTop+'px',
            'left':stayLeft+'px',
            'width':this.width,
            'height':this.height
        });

        $('#background').css({
            'width':'100%',
            'height': '100%'
        });
        
    }

    Modal.prototype.start = function(target,obj){
        this.bb.appendTo('body');
        this.stayPosition();     
        this.showModal();
        $('.close,#background').on('click', function(){
            obj.hideModal();
        }); 
    }

    $.fn.modal = function(el){
        var target = this, md = new Modal(target);
        md.element = el;
        target.on(md.trigger_on,function(){ 
            md.start(target,md);
        }); 
        $(window).resize(function(){
            target.modalresize(el);      
        });
    };

    $.fn.modalresize = function(el){
        var md = new Modal(this);
        md.element = el;
        md.stayPosition();
    };
}(jQuery);

+function($){
	
    function Interface(){}
    Interface.factory = function(type,arg1,arg2){
        var copy,constr = type;
            if(typeof Interface[constr] !== 'function'){
                throw {
                name : "Error",
                message : constr + " doesn't exist" 
                };
            }
            if (Interface[constr].prototype.start === undefined) {
                Interface[constr].prototype = new Interface();
            }
        copy = new Interface[constr](arg1,arg2);
        return copy;
    }
    Interface.prototype.scrollStop = function(){
        if (document.body.addEventListener) document.body.addEventListener('DOMMouseScroll', this.blockWheel, false);
        document.body.onmousewheel = this.blockWheel;
    }
        
    Interface.prototype.blockWheel = function(event){
        if (!event) event = window.event;
        if (event.stopPropagation) event.stopPropagation();
        else event.cancelBubble = true;
        if(event.preventDefault) event.preventDefault();
        else event.returnValue = false;
    }
    
    Interface.prototype.scrollStart = function(){
        if (document.body.addEventListener) document.body.addEventListener('DOMMouseScroll', this.enabledWheel, true);
        document.body.onmousewheel = this.enabledWheel;
    }
    
    Interface.prototype.enabledWheel = function(event){
        event.returnValue = true;
    }
    
    Interface.prototype.loading = function(){
        var pos_left,pos_top;
            switch(this.position){
                case "top" :       
                    pos_left = this.target.offset().left + ( this.target.outerWidth() / 2 ) - ( this.interface.outerWidth() / 2 );
                    pos_top  = this.target.offset().top - this.interface.outerHeight() - this.shift;
                    break;  
                case "top-right":
					
                    pos_left = this.target.offset().left + this.target.outerWidth() - this.interface.outerWidth();
                    pos_top  = this.target.offset().top - this.interface.outerHeight() - this.shift;
                    break;
                case "top-left":
                    pos_left = this.target.offset().left;
                    pos_top  = this.target.offset().top - this.interface.outerHeight() - this.shift;
                    break;
                    
                case "bottom" : 
                    pos_left = this.target.offset().left + ( this.target.outerWidth() / 2 ) - (this.interface.outerWidth() / 2 );
                    pos_top  = this.target.offset().top + this.target.outerHeight() + this.shift;
                    break;  
                case "bottom-right" : 
                    pos_left = this.target.offset().left + this.target.outerWidth() - this.interface.outerWidth();
                    pos_top  = this.target.offset().top + this.target.outerHeight() + this.shift;
                    break;
                case "bottom-left" : 
                    pos_left = this.target.offset().left;
                    pos_top  = this.target.offset().top + this.target.outerHeight() + this.shift;
                    break;
                    
                case "right" : 
                    pos_left = this.target.offset().left + this.target.outerWidth() + this.shift;
                    pos_top  = this.target.offset().top + (this.target.outerHeight()/2) - (this.interface.outerHeight()/2);
                    break;  
                case "right-top" : 
                    pos_left = this.target.offset().left + this.target.outerWidth() + this.shift;
                    pos_top  = this.target.offset().top;
                    break; 
                case "right-bottom" : 
                    pos_left = this.target.offset().left + this.target.outerWidth() + this.shift;
                    pos_top  = this.target.offset().top + this.target.outerHeight() - this.interface.outerHeight();
                    break; 
                    
                case "left" : 
                    pos_left = this.target.offset().left - this.interface.outerWidth() - this.shift;
                    pos_top  = this.target.offset().top + (this.target.outerHeight()/2) - (this.interface.outerHeight()/2);
                    break;  
                case "left-top" : 
                    pos_left = this.target.offset().left - this.interface.outerWidth() - this.shift;
                    pos_top  = this.target.offset().top;
                    break; 
                case "left-bottom" : 
                    pos_left = this.target.offset().left - this.interface.outerWidth() - this.shift;
                    pos_top  = this.target.offset().top + this.target.outerHeight() - this.interface.outerHeight();
                    break; 
            };  
			
        this.interface.css( { left: pos_left, top:pos_top } );
    }

    Interface.prototype.idRandom = function(){
        var rand = Math.floor(Math.random() * (9999999999999 - 10 + 1)) + 10,
        id = (this.toggle+'_'+rand);
        this.interface.attr({
            id:id,
            class: this.toggle+' ' + this.position
        });
        if(this.setClass !== undefined){
            this.target.addClass(this.setClass);
        }
        return id;
    }
    Interface.prototype.show = function(){
        switch(this.toggle){
            case 'tooltip' : 
                this.interface.html('<div>'+this.content+'</div>')
                                .appendTo('body');
                this.interface.children().css({borderRadius:this.radius});
                break;
            case 'popover' :     
                if(this.title === 'none'){
                    this.within = '<div class="popover_content_s">'+this.content+'</div>';
                    this.interface.children().css({borderRadius:this.radius}); 
                }else{
                    this.interface.children('.popover_content').css({borderRadius:'0 0 '+this.radius+' '+this.radius});
					this.interface.children('.popover_title').css({borderRadius:this.radius+' '+this.radius+' 0 0'});
                }
                this.interface.html(this.within);
                this.interface.appendTo('body');
                break;
        }
        this.interface.addClass(this.theme)
                        .stop(true, true)
                        .fadeIn(this.fIn);
        this.scrollStop(); 
    }
    Interface.prototype.hide = function(){
        if(this.target.hasClass(this.setClass)){
            this.target.removeClass(this.setClass); 
        }
        this.scrollStart();
        switch(this.codeRemove){  
            case 'on': 
                $('#'+this.id).delay(this.delay).fadeOut(this.fOut,function(){$(this).remove();});
                break;
            case 'off': 
                $('#'+this.id).delay(this.delay).fadeOut(this.fOut);
                break;
        }
    }
    
    Interface.prototype.start = function(obj){
        this.id = this.idRandom();
        if(this.trigger_on === this.trigger_off){
            this.setInterface();
        }else{
            this.show();
        }
        this.loading();
        if(this.trigger_on === this.trigger_off){
            this.fadeOver();
        }else{
            obj.target.on(obj.trigger_off, function(){
                obj.hide();
            });  
        }
    }
    Interface.prototype.displayState = function(el) {
        if($(el).css('display') === 'none'){
            return true;
        }else{
            return false;
        }
    };
    Interface.prototype.fadeOver = function(){
        if (this.displayState('#'+this.id)) {
            $('#'+this.id).fadeIn(this.fIn);
        } else {
            this.hide();
        }
    }
    Interface.prototype.setInterface = function(){
        if(this.title === 'none' && this.toggle === 'popover'){
            this.within = '<div class="popover_content_s">'+this.content+'</div>';
			this.interface.addClass(this.theme)
							.html(this.within);
			this.interface.children().css({borderRadius:this.radius});
        }else{
            this.interface.addClass(this.theme)
                            .html(this.within);
            if(this.toggle === 'popover'){
                this.interface.children('.popover_content').css({borderRadius:'0 0 '+this.radius+' '+this.radius});
                this.interface.children('.popover_title').css({borderRadius:this.radius+' '+this.radius+' 0 0'});
            }else{
                this.interface.children().css({borderRadius:this.radius});
            }	
		}
        this.interface.appendTo('body');
        this.scrollStop();
    }
    Interface.tooltipes = function (target,settings){  
        this.id;
        this.toggle = 'tooltip';
        this.target = target;
        this.interface = $('<div class="tooltip"></div>');   
        this.trigger_on =  target.attr('data-trigger-on') || settings.trigger_on || 'mouseover';
        this.trigger_off = target.attr('data-trigger-off') || settings.trigger_off || 'mouseleave';
        this.shift = parseInt(target.attr('data-shift')) || parseInt(settings.shift) || 10;
        this.content = target.attr('data-content') || settings.content || 'Enter the text';
        this.position = target.attr('data-position') || settings.position || 'top';
        this.theme = target.attr('data-theme') || settings.theme || 'black';
        this.delay = parseInt(target.attr('data-delay')) || parseInt(settings.delay) || 100;
        this.fOut = parseInt(target.attr('data-fadeOut')) || parseInt(settings.fOut) || 150;
        this.fIn = parseInt(target.attr('data-fadeIn')) || parseInt(settings.fIn) || 150;    
        this.codeRemove = target.attr('data-codeRemove') || settings.codeRemove || 'on';
        this.setClass = target.attr('data-setClass') || settings.defClass || undefined;
        this.radius =  target.attr('data-radius') || settings.radius || '3px';
        this.within = '<div>'+this.content+'</div>';
    }
    
    Interface.popovers = function (target,settings){   
        this.id;
        this.toggle = 'popover';
        this.target = target;
        this.interface = $('<div class="popover"></div>');  
        this.trigger_on =  target.attr('data-trigger-on') || settings.trigger_on || 'click';
        this.trigger_off = target.attr('data-trigger-off') || settings.trigger_off || 'click';
        this.shift = parseInt(target.attr('data-shift')) || parseInt(settings.shift) || 10;
        this.content = target.attr('data-content') || settings.content || 'Enter the text';
        this.title = target.attr('data-title') || settings.title || 'none';
        this.position = target.attr('data-position') || settings.position || 'top';
        this.theme = target.attr('data-theme') || settings.theme || 'black';
        this.delay = parseInt(target.attr('data-delay')) || parseInt(settings.delay) || 100;
        this.fOut = parseInt(target.attr('data-fadeOut')) || parseInt(settings.fOut) || 150;
        this.fIn = parseInt(target.attr('data-fadeIn')) || parseInt(settings.fIn) || 150;   
        this.codeRemove = target.attr('data-codeRemove') || settings.codeRemove || 'on';
        this.setClass = target.attr('data-setClass') || settings.defClass || undefined;
        this.radius =  target.attr('data-radius') || settings.radius || '3px';
        this.within = '<div class="popover_title">'+this.title+'</div><div class="popover_content">' + this.content+'</div>';
    }

    $.fn.tooltip = function(settings){     
        var target = this,tltp;   
        tltp =  settings ? Interface.factory('tooltipes',target,settings) : Interface.factory('tooltipes',target,{}); 
        tltp.target.on(tltp.trigger_on,function(){
            tltp.start(tltp);
        });   
    };

    $.fn.popover = function(settings){
        var target = this,ppvr;
        ppvr = settings ? Interface.factory('popovers',target,settings) : Interface.factory('popovers',target,{});
        ppvr.target.on(ppvr.trigger_on,function(){
            ppvr.start(ppvr);
        });  
    };

    $(document).ready(function(){
        $('[data-interface="tooltip"]').each(function(){
            $(this).tooltip();
        }); 
        $('[data-interface="popover"]').each(function(){
            $(this).popover();
        });		
    });
}(jQuery);




