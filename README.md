# VALIDATOR v1.3
---------------------------------------------
1.3
agregado:
- "fullname","fullname2","fulltext","date(dd/mm/aa)","fullname2_html"
- funciones pattern_and , pattern_or
- 
1.2
cambios:
- agregado array
- agregado arrayempty
- error si en arraypattern detecta un no arreglo
- parametro name($name = null) ====> name(string $name="")
- regex "chara"
- no se pueden ingresar nuevos patrones
- 
1.1
bugfix:
- referer no actua correctamente

---------------------------------------------
usabilidad:
    
		$val = new validator();

		//required: da error si el valor es null o ""
		$val->val($data["id"])->required();

		//maxlen: da error si el valor contiene mas caracteres que el valor indicado
		$val->val($data["texto"])->pattern("basic_string")->maxlen(2000);

		//pattern: da error si el valor no cumple con el patron indicado
		$val->val($data["Mail"])->pattern("mail");
		$val->val($data["Tel"])->pattern("int");
		$val->val($data["nombre"])->pattern("basic_string");

		//validate: devuelve 1 si no hay errores y 0 si hay errores
		if(!$val->validate())
			return;
