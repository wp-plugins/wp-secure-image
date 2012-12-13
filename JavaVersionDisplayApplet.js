<!--
// edit to suit version and Java download

	var minversion = "1.4";
	var m_szReferrer = document.location.href.replace(/&/g,'%26');	
	var javadownload = "/wp-content/plugins/wp-secure-image/download_java.html?ref=" + m_szReferrer;
	
function showerror()
	{
	window.alert("Java version " + minversion + " or later is required to view this page!\n\nIf java is installed it needs to be enabled.\n\nOr you can get the latest version from www.java.com");
	document.location.href = javadownload;
	}
	
function showJVMDetails()
{
	var undefined;
	var app = document.applets[0];
	var version3 = app.getVersion();
	var version = (version3.substr(0,3));
		
if ((version*1) < (minversion*1))
		{
		window.alert("Java version " + minversion + " or later is required to view this page!\n\nYou can get the latest version from www.java.com");
		document.location.href = javadownload;
		}
}

window.onerror = showerror;
// -->