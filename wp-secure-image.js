<!-- hide JavaScript from non-JavaScript browsers

	//  WP Secure Image - Version 0.6
	//  Copyright (c) ArtistScope 1998-2013. All Rights Reserved.
	//  www.artistscope.com
	//
	//  Supported platforms: Windows, Mac, Linux

	//
	// Special JS version for Wordpress

// Debugging outputs the generated html into a textbox instead of rendering
//	option has been moved to wp-secure-image.php

// REDIRECTS

var m_szLocation = document.location.href.replace(/&/g,'%26');	
var m_szDownloadNo = wpsiw_plugin_url + "download_no.html";
var m_szDownloadJava = wpsiw_plugin_url + "download_java.html?ref=" + m_szLocation;
var m_szDownloadJavaScript = wpsiw_plugin_url + "download_javascript.html?ref=" + m_szLocation;

//====================================================
//   Current version == 4.6.0.6
//====================================================

var m_nV1=4;
var m_nV2=6;
var m_nV3=0;
var m_nV4=6;

//===========================
//   DO NOT EDIT BELOW 
//===========================

var m_szAgent = navigator.userAgent.toLowerCase();
var m_szBrowserName = navigator.appName.toLowerCase();
var m_szPlatform = navigator.platform.toLowerCase();
var m_bNetscape = false;
var m_bMicrosoft = false;
var m_szPlugin = "";

var m_bWin64 = ((m_szPlatform == "win64") || (m_szPlatform.indexOf("win64")!=-1) || (m_szAgent.indexOf("win64")!=-1));
var m_bWin32 = ((m_szPlatform == "win32") || (m_szPlatform.indexOf("win32")!=-1));
var m_bWin2k = ((m_szAgent.indexOf("windows nt 5.0")!=-1) || (m_szAgent.indexOf("windows 2000")!=-1));
var m_bWinxp = ((m_szAgent.indexOf("windows nt 5.1")!=-1) || (m_szAgent.indexOf("windows xp")!=-1));
var m_bWin2k3 = (m_szAgent.indexOf("windows nt 5.2")!=-1);	
var m_bVista = (m_szAgent.indexOf("windows nt 6.0")!=-1);
var m_bWindows7 = (m_szAgent.indexOf("windows nt 6.1")!=-1);
var m_bWindows8 = ((m_szAgent.indexOf("windows nt 6.2")!=-1) || (m_szAgent.indexOf("windows nt 6.3")!=-1));
var m_bWindows = (((m_bWin2k) || (m_bWinxp) || (m_bWin2k3) || (m_bVista) || (m_bWindows7) || (m_bWindows8)) && ((m_bWin32) || (m_bWin64)));
var m_bMacintosh = ((m_szPlatform.indexOf("mac")!=-1) || (m_szAgent.indexOf("mac")!=-1));
var m_sbLinux = ((m_szPlatform.indexOf("x11")!=-1) || (m_szPlatform.indexOf("linux i686")!=-1));


var m_bOpera = ((m_szAgent.indexOf("opera")!=-1) && !!(window.opera && window.opera.version) && (m_bpOpera));
var m_bFirefox = ((m_szAgent.indexOf("firefox")!=-1) && testCSS("MozBoxSizing") && (m_bpFx));
var m_bSafari = ((m_szAgent.indexOf("safari")!=-1) && Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0 && (m_bpSafari));
var m_bChrome = ((m_szAgent.indexOf("chrome")!=-1) && !!(window.chrome && chrome.webstore && chrome.webstore.install) && (m_bpChrome));
var m_bNav = ((m_szAgent.indexOf("navigator")!=-1) && (m_bpNav));
var m_Konq = (m_szAgent.indexOf("konqueror")!=-1);

var m_bNetscape = ((m_bChrome) || (m_bFirefox) || (m_bNav) || (m_bOpera) || (m_bSafari) || (m_Konq));
var m_bMicrosoft = ((m_szAgent.indexOf("msie")!=-1) && (/*@cc_on!@*/false || testCSS("msTransform")) && (m_bpMSIE)); 

function testCSS(prop) {
    return prop in document.documentElement.style;
}

if (m_bpDebugging == true)
	{
//	document.write("UserAgent= " + m_szAgent + "<br>");
//	document.write("Browser= " + m_szBrowserName + "<br>");
//	document.write("Platform= " + m_szPlatform + "<br>");
//	document.write("Referer= " + m_szLocation + "<br>");
    }

	
if ((m_bWindows) && (m_bMicrosoft))
	{
	m_szPlugin = "JAVA";
	}
else if ((m_bWindows) && (m_bNetscape))
	{
	m_szPlugin = "JAVA";
	}
else if ((m_bMacintosh) && (m_bNetscape))
	{
	m_szPlugin = "JAVA";
	}
else if ((m_Konq) && (m_bNetscape))
	{
	m_szPlugin = "JAVA";
	}
else
	{
	window.location=unescape(m_szDownloadNo);
	document.MM_returnValue=false;
	}

function bool2String(bValue)
{
    if (bValue == true) {return "1";}
    else {return "0";}
}

function paramValue(szValue, szDefault)
{
    if (szValue.toString().length > 0) {return szValue;}
    else {return szDefault;}
}

function expandNumber(nValue, nLength)
{
    var szValue = nValue.toString();
    while(szValue.length < nLength)
        szValue = "0" + szValue;
    return szValue;
}


// The secure-image-insert functions

function insertSecureImage(szImageName)
{
    // Extract the image width and height from the image name (example name: zulu580_0580_0386_S.class)

    var nIndex = szImageName.lastIndexOf('_S.');
    if (nIndex == -1)
    {
        // Strange filename that doesn't conform to the secure image standard. Can't render it.
        return;
    }

    var szWidth = szImageName.substring(nIndex - 9, nIndex - 5);
    var szHeight = szImageName.substring(nIndex - 4, nIndex);

    var nWidth = szWidth * 1;
    var nHeight = szHeight * 1;


    // Expand width and height to allow for border

    var nBorder = m_szDefaultBorder * 1;
    nWidth = nWidth + (nBorder * 2);
    nHeight = nHeight + (nBorder * 2);

    insertSecureImageClass(nWidth, nHeight, "", "", nBorder, "", "", "", [szImageName]);
}

function insertSecureImageClass(nWidth, nHeight,
    szTextColor,
    szBorderColor,
    nBorder,
    szLoading,
    szLink,
    szTargetFrame,
    arFrames)

{
    if (m_bpDebugging == true)
        { 
        document.writeln("<textarea rows='27' cols='80'>"); 
        }       
    if (m_szPlugin == "JAVA")
    {

	document.writeln("<app" + "let codebase='" + wpsiw_plugin_url + "' code='ArtistScopeViewer.class' archive='ArtistScopeViewer.jar' id='Artistscope' width='" + nWidth + "' height='" + nHeight + "'>");
 
    document.writeln("<param name='Style' value='ImageLink' />");
    document.writeln("<param name='TextColor' value='" + paramValue(szTextColor, m_szDefaultTextColor) + "' />");
    document.writeln("<param name='BorderColor' value='" + paramValue(szBorderColor, m_szDefaultBorderColor) + "' />");
    document.writeln("<param name='Border' value='" + paramValue(nBorder, m_szDefaultBorder) + "' />");
    document.writeln("<param name='Loading' value='" + paramValue(szLoading, m_szDefaultLoading) + "' />");
    document.writeln("<param name='Label' value='' />");
    document.writeln("<param name='Link' value='" + paramValue(szLink, m_szDefaultLink) + "' />");
    document.writeln("<param name='TargetFrame' value='" + paramValue(szTargetFrame, m_szDefaultTargetFrame) + "' />");
    document.writeln("<param name='Message' value='' />");   
    document.writeln("<param name='FrameDelay' value='2000' />");
    document.writeln("<param name='FrameCount' value='1' />");
    document.writeln("<param name='Frame000' value='" + wpsiw_upload_url + m_szClassName + "' />");

    document.writeln("</app" + "let />"); 

    if (m_bpDebugging == true)
        { document.writeln("</textarea />"); }
    }
}
// -->
