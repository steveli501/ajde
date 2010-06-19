/**
 * User interaction
 */
var user = {
	
	uid : null,
	username : null,
	realname : null,
	
	init : function(uid, username, realname) {
		this.uid = uid;
		this.username = username;
		this.realname = realname;
	},
	
	getUid : function() {
		return this.uid;
	},
	
	getUsername : function() {
		return this.username;
	},
	
	getRealname : function() {
		return this.realname;
	},
	
	isLoggedIn : function() {
		return this.uid > 0;
	}
	
}