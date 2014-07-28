<!--
// edit to suit redirect link

	var JavaCheck = navigator.javaEnabled();
	var m_szJavaRedirect = "download_java.html";
	
//	document.writeln("Check = " + JavaCheck + "<br><br>");
	
	if (!(JavaCheck))
	{
	window.location=unescape(m_szJavaRedirect);
	document.MM_returnValue=false;
	}

// -->