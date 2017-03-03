var $PHP = function( phpClass ) { 

	this.server = '';
	this.class = phpClass;
	this.params = "";
	this.loaded = '';
	this.new = function( cls, callback ){

						var me = this;
						me.class = cls;

						$.ajax({
							url: this.server+"/"+cls,
							dataType : "json",
							success : function( ret ){
								if( ret.success ){		

									me.fillproperty( ret.data.attributes );

									/*
									for( var a in ret.data.methods ){
										me[ret.data.methods[a]] = me.callmethod;
									}
									*/

									if( me.loaded ) me.loaded();
									if(callback) callback();
								}
							}
						})

				};


	if( phpClass ){
		this.new( phpClass );
	}

	this.call = function( method, params, callback ){
		this.callmethod( method, params, callback )
	}

	this.callmethod = function( method, params, callback ){
		//var method = this.getMethod( arguments.callee.caller );
		var me = this;
		me.params = params;
		$.ajax({
			url: this.server+"/"+this.class+'/'+method,
			dataType : "json",
			data: JSON.stringify(me),
			type: 'POST',
			success : function( ret ){
				me.fillproperty( ret.data.attributes );
				if( ret.success ){
					if(callback) callback( ret.data );	
				}
				
			}
		})
	}

	this.fillproperty = function( attributes ){
		for( var a in attributes ){
			this[a] = attributes[a]
		}
	}
}