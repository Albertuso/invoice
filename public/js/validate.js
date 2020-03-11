function validateDNI() {
	valor = document.querySelector("#client_nif").value;
	if (valor) {
		var letras = ['T', 'R', 'W', 'A', 'G', 'M', 'Y', 'F', 'P', 'D', 'X', 'B', 'N', 'J', 'Z', 'S', 'Q', 'V', 'H', 'L', 'C', 'K', 'E'];
		if (!(/^\d{8}-?([A-Z]|[a-z])/.test(valor))) {
			alert("DNI incorrecto");
			return false;
		}
		if (valor.charAt(8) == "-") {
			if (valor.charAt(9).toUpperCase() != letras[(valor.substring(0, 8)) % 23]) {
				alert("DNI incorrecto");
				return false;
			}
		} else if (valor.charAt(8).toUpperCase() != letras[(valor.substring(0, 8)) % 23]) {
			alert("DNI incorrecto");
			return false;
		}
		return true;
	}
}

function validateCIF(cif) {
	//Quitamos el primer caracter y el ultimo digito
	var valueCif = cif.substr(1, cif.length - 2);

	var suma = 0;

	//Sumamos las cifras pares de la cadena
	for (i = 1; i < valueCif.length; i = i + 2) {
		suma = suma + parseInt(valueCif.substr(i, 1));
	}

	var suma2 = 0;

	//Sumamos las cifras impares de la cadena
	for (i = 0; i < valueCif.length; i = i + 2) {
		result = parseInt(valueCif.substr(i, 1)) * 2;
		if (String(result).length == 1) {
			// Un solo caracter
			suma2 = suma2 + parseInt(result);
		} else {
			// Dos caracteres. Los sumamos...
			suma2 = suma2 + parseInt(String(result).substr(0, 1)) + parseInt(String(result).substr(1, 1));
		}
	}

	// Sumamos las dos sumas que hemos realizado
	suma = suma + suma2;

	var unidad = String(suma).substr(1, 1)
	unidad = 10 - parseInt(unidad);

	var lastchar = cif.substr(cif.length - 1, 1);
	var lastcharchar = lastchar;
	if (isInteger(lastchar)) {
		lastcharchar = String.fromCharCode(64 + parseInt(lastchar));
	}
	if (primerCaracter.match(/^[FJKNPQRSUVW]$/)) {
		//Empieza por .... Comparamos la ultima letra
		if (String.fromCharCode(64 + unidad).toUpperCase() == lastcharchar) {
			return true;
		}
	} else if (primerCaracter.match(/^[XYZ]$/)) {
		//Se valida como un dni
		var newcif;
		if (primerCaracter == "X")
			newcif = cif.substr(1);
		else if (primerCaracter == "Y")
			newcif = "1" + cif.substr(1);
		else if (primerCaracter == "Z")
			newcif = "2" + cif.substr(1);
		return validateDNI(newcif);
	} else if (primerCaracter.match(/^[ABCDEFGHLM]$/)) {
		//Se revisa que el ultimo valor coincida con el calculo
		if (unidad == 10)
			unidad = 0;
		if (cif.substr(cif.length - 1, 1) == String(unidad))
			return true;
	} else {
		//Se valida como un dni
		return validateDNI(cif);
	}
	return false;
}