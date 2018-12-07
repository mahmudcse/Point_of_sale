/**
	* Optionally used to deploy multiple versions of the applet for mixed
	* environments.  Oracle uses document.write(), which puts the applet at the
	* top of the page, bumping all HTML content down.
	*/
	
	
	/**
	* Deploys different versions of the applet depending on Java version.
	* Useful for removing warning dialogs for Java 6.  This function is optional
	* however, if used, should replace the <applet> method.  Needed to address 
	* MANIFEST.MF TrustedLibrary=true discrepency between JRE6 and JRE7.
	*/
	function deployQZ() {
		var attributes = {id: "qz", code:'qz.PrintApplet.class', 
			archive:'./qz-print.jar', width:1, height:1};
		var parameters = {jnlp_href: '../../assets/qz/qz-print_jnlp.jnlp', 
			cache_option:'plugin', disable_logging:'false', 
			initial_focus:'false'};
		if (deployJava.versionCheck("1.7+") == true) {}
		else if (deployJava.versionCheck("1.6+") == true) {
			attributes['archive'] = 're6/qz-print.jar';
			parameters['jnlp_href'] = 'jre6/qz-print_jnlp.jnlp';
		}
		deployJava.runApplet(attributes, parameters, '1.5');
	}
	
	/**
	* Automatically gets called when applet has loaded.
	*/
	function qzReady() {
		// Setup our global qz object
		window["qz"] = document.getElementById('qz');
		var title = document.getElementById("title");
		if (qz) {
			try {
				title.innerHTML = title.innerHTML + " " + qz.getVersion();
				document.getElementById("content").style.background = "#F0F0F0";
				useDefaultPrinter();
			} catch(err) { // LiveConnect error, display a detailed meesage
				document.getElementById("content").style.background = "#F5A9A9";
				alert("ERROR:  \nThe applet did not load correctly.  Communication to the " + 
					"applet has failed, likely caused by Java Security Settings.  \n\n" + 
					"CAUSE:  \nJava 7 update 25 and higher block LiveConnect calls " + 
					"once Oracle has marked that version as outdated, which " + 
					"is likely the cause.  \n\nSOLUTION:  \n  1. Update Java to the latest " + 
					"Java version \n          (or)\n  2. Lower the security " + 
					"settings from the Java Control Panel.");
		  }
	  }
	}
	
	/**
	* Returns whether or not the applet is not ready to print.
	* Displays an alert if not ready.
	*/
	function notReady() {
		// If applet is not loaded, display an error
		if (!isLoaded()) {
			return true;
		}
		// If a printer hasn't been selected, display a message.
		else if (!qz.getPrinter()) {
		
			alert('Please select a printer first by using the "Detect Printer" button.');
			return true;
		}
		return false;
	}
	
	/**
	* Returns is the applet is not loaded properly
	*/
	function isLoaded() {
		if (!qz) {
			alert('Error:\n\n\tPrint plugin is NOT loaded!');
			return false;
		} else {
			try {
				if (!qz.isActive()) {
					alert('Error:\n\n\tPrint plugin is loaded but NOT active!');
					return false;
				}
			} catch (err) {
				alert('Error:\n\n\tPrint plugin is NOT loaded properly!');
				return false;
			}
		}
		return true;
	}
	
	/**
	* Automatically gets called when "qz.print()" is finished.
	*/
	function qzDonePrinting() {
		// Alert error, if any
		if (qz.getException()) {
			alert('Error printing:\n\n\t' + qz.getException().getLocalizedMessage());
			qz.clearException();
			return; 
		}
		
		// Alert success message
		alert('Successfully sent print data to "' + qz.getPrinter() + '" queue.');
	}
	
	/***************************************************************************
	* Prototype function for finding the "default printer" on the system
	* Usage:
	*    qz.findPrinter();
	*    window['qzDoneFinding'] = function() { alert(qz.getPrinter()); };
	***************************************************************************/
	function useDefaultPrinter() {
		if (isLoaded()) {
			// Searches for default printer
			qz.findPrinter();
			
			// Automatically gets called when "qz.findPrinter()" is finished.
			window['qzDoneFinding'] = function() {
				// Alert the printer name to user
				var printer = qz.getPrinter();
				alert(printer !== null ? 'Default printer found: "' + printer + '"':
					'Default printer ' + 'not found');
				
				// Remove reference to this function
				window['qzDoneFinding'] = null;
			};
		}
	}
	

	
	/***************************************************************************
	* Prototype function for printing raw commands directly to a hostname or IP
	* Usage:
	*    qz.append("\n\nHello world!\n\n");
	*    qz.printToHost("192.168.1.254", 9100);
	***************************************************************************/
	function printToHost() {
		if (isLoaded()) {
			// Any printer is ok since we are writing to a host address instead
			qz.findPrinter();
			
			// Automatically gets called when "qz.findPrinter()" is finished.
			window['qzDoneFinding'] = function() {
				// Send characters/raw commands to qz using "append"
				// Hint:  Carriage Return = \r, New Line = \n, Escape Double Quotes= \"
				qz.append("A590,1600,2,3,1,1,N,\"QZ Print Plugin " + qz.getVersion() + " sample.html\"\n");
				qz.append("A590,1570,2,3,1,1,N,\"Testing qz.printToHost() function\"\n");
				qz.append("P1\n");
				
				// qz.printToHost(String hostName, int portNumber);
				// qz.printToHost("192.168.254.254");   // Defaults to 9100
				qz.printToHost("192.168.1.254", 9100);
				
				// Remove reference to this function
				window['qzDoneFinding'] = null;
			};
		}
	}
	
	
	/***************************************************************************
	* Prototype function for finding the closest match to a printer name.
	* Usage:
	*    qz.findPrinter('zebra');
	*    window['qzDoneFinding'] = function() { alert(qz.getPrinter()); };
	***************************************************************************/
	function findPrinter(name) {
		// Get printer name from input box
		var p = document.getElementById('printer');
		if (name) {
			p.value = name;
		}
		
		if (isLoaded()) {
			// Searches for locally installed printer with specified name
			qz.findPrinter(p.value);
			
			// Automatically gets called when "qz.findPrinter()" is finished.
			window['qzDoneFinding'] = function() {
				var p = document.getElementById('printer');
				var printer = qz.getPrinter();
				
				// Alert the printer name to user
				if(printer == null){
					alert(printer !== null ? 'Printer found: "' + printer + 
					'" after searching for "' + p.value + '"' : 'Printer "' + 
					p.value + '" not found.');
				}
				// Remove reference to this function
				window['qzDoneFinding'] = null;
			};
		}
	}
	
	/***************************************************************************
	* Prototype function for listing all printers attached to the system
	* Usage:
	*    qz.findPrinter('\\{dummy_text\\}');
	*    window['qzDoneFinding'] = function() { alert(qz.getPrinters()); };
	***************************************************************************/
	function findPrinters() {
		if (isLoaded()) {
			// Searches for a locally installed printer with a bogus name
			qz.findPrinter('\\{bogus_printer\\}');
			
			// Automatically gets called when "qz.findPrinter()" is finished.
			window['qzDoneFinding'] = function() {
				// Get the CSV listing of attached printers
				var printers = qz.getPrinters().split(',');
				for (i in printers) {
					alert(printers[i] ? printers[i] : 'Unknown');      
				}
				
				// Remove reference to this function
				window['qzDoneFinding'] = null;
			};
		}
	}
	
	/***************************************************************************
	* Prototype function for printing plain HTML 1.0 to a PostScript capable 
	* printer.  Not to be used in combination with raw printers.
	* Usage:
	*    qz.appendHTML('<h1>Hello world!</h1>');
	*    qz.printPS();
	***************************************************************************/ 
	function printHTML() {
		
		if (notReady()) { return; }
		
		// Preserve formatting for white spaces, etc.
		var colA = fixHTML('<h2>*  QZ Print Plugin HTML Printing  *</h2>');
		colA = colA + '<color=red>Version:</color> ' + qz.getVersion() + '<br />';
		colA = colA + '<color=red>Visit:</color> http://code.google.com/p/jzebra';
		
		// HTML image
		var colB = '<img src="' + getPath() + 'img/image_sample.png">';
		
		// Append our image (only one image can be appended per print)
		qz.appendHTML('<html><table face="monospace" border="1px"><tr height="6cm">' + 
		'<td valign="top">' + colA + '</td>' + 
		'<td valign="top">' + colB + '</td>' + 
		'</tr></table></html>');
		
		qz.printHTML();
	}
	
	/***************************************************************************
	* Prototype function for printing plain HTML 1.0 to a PostScript capable 
	* printer.  Not to be used in combination with raw printers.
	* Usage:
	*    qz.appendHTML('<h1>Hello world!</h1>');
	*    qz.printPS();
	***************************************************************************/ 
	function printDiv(div_id) {
		
		if (notReady()) { return; }
		
		
		// Append our image (only one image can be appended per print)
		qz.appendHTML('<html>'+document.getElementById(div_id).innerHTML+'</html>');
		
		qz.printHTML();
	}
        
 
	
	/***************************************************************************
	****************************************************************************
	* *                          HELPER FUNCTIONS                             **
	****************************************************************************
	***************************************************************************/
	
	
	/***************************************************************************
	* Gets the current url's path, such as http://site.com/example/dist/
	***************************************************************************/
	function getPath() {
		var path = window.location.href;
		return path.substring(0, path.lastIndexOf("/")) + "/";
	}
	
	/**
	* Fixes some html formatting for printing. Only use on text, not on tags!
	* Very important!
	*   1.  HTML ignores white spaces, this fixes that
	*   2.  The right quotation mark breaks PostScript print formatting
	*   3.  The hyphen/dash autoflows and breaks formatting  
	*/
	function fixHTML(html) {
		return html.replace(/ /g, "&nbsp;").replace(/’/g, "'").replace(/-/g,"&#8209;"); 
	}
	
	/**
	* Equivelant of VisualBasic CHR() function
	*/
	function chr(i) {
		return String.fromCharCode(i);
	}
	
	/***************************************************************************
	* Prototype function for allowing the applet to run multiple instances.
	* IE and Firefox may benefit from this setting if using heavy AJAX to
	* rewrite the page.  Use with care;
	* Usage:
	*    qz.allowMultipleInstances(true);
	***************************************************************************/ 
	function allowMultiple() {
	  if (isLoaded()) {
		var multiple = qz.getAllowMultipleInstances();
		qz.allowMultipleInstances(!multiple);
		alert('Allowing of multiple applet instances set to "' + !multiple + '"');
	  }
	}