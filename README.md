# Validator v1.3

Validador de datos en PHP utilizando expresiones regulares, orientado a validar entradas de formularios, parámetros y otros datos, de forma sencilla y flexible. Permite validar cadenas, números, correos, fechas y más, con funciones encadenables y reportes de errores detallados.

## Descripción

Este validador facilita la comprobación de datos usando patrones regex predefinidos y personalizados. Es útil para asegurar que los datos recibidos cumplen los requisitos de formato y contenido en aplicaciones PHP. Ofrece métodos para validar presencia, longitud, rangos, pertenencia a listas, patrones complejos y validaciones especiales (como AJAX o referers).

## Funciones principales

- **name(string $name):** Establece el nombre del campo a validar.
- **val($value, $opcional = false):** Establece el valor a validar y si es opcional.
- **required():** Marca el campo como obligatorio.
- **max($max):** Valor máximo permitido (numérico).
- **min($min):** Valor mínimo permitido (numérico).
- **maxlen($max):** Longitud máxima de cadena.
- **minlen($min):** Longitud mínima de cadena.
- **pattern($p, $opcional = false):** Valida contra un patrón regex predefinido.
- **pattern_and(array $patrones):** Debe cumplir todos los patrones indicados.
- **pattern_or(array $patrones):** Debe cumplir al menos uno de los patrones.
- **array():** El valor debe ser un arreglo.
- **arrayempty():** El arreglo no debe estar vacío.
- **arraypattern($p):** Todos los elementos del arreglo deben cumplir el patrón.
- **belongs(array $lista):** El valor debe pertenecer al arreglo dado.
- **referer($whitelist):** Valida el referer HTTP.
- **ajax_req():** Valida si la petición es AJAX.
- **validate($option = null):** Ejecuta la validación. Devuelve `1` si no hay errores, `0` si hay errores.
- **display_errors():** Muestra los mensajes de error generados.

## Patrones soportados

Algunos patrones integrados incluyen:
- `id`, `int`, `float`, `money`
- `sql_string`, `basic_string`, `alnum_string`, `chara`
- `mail`, `fullmail`, `url`
- `fullname`, `fullname2`, `fullname2_html`, `fulltext`
- `date(dd/mm/aaaa)`, `date(dd/mm/aa)`

## Ejemplo de uso

```php
$val = new validator();

// Requerido
$val->val($data["id"])->required();

// Validación de patrón y longitud máxima
$val->val($data["texto"])->pattern("basic_string")->maxlen(2000);

// Validación de email
$val->val($data["Mail"])->pattern("mail");

// Validar número de teléfono
$val->val($data["Tel"])->pattern("int");

// Validar nombre
$val->val($data["nombre"])->pattern("basic_string");

// Ejecuta la validación
if(!$val->validate()) {
    $val->display_errors();
    return;
}
```

## Changelog
1.3
Agregado:
- "fullname", "fullname2", "fulltext", "date(dd/mm/aa)", "fullname2_html"
- Funciones pattern_and, pattern_or

1.2
Cambios:
- Agregado array
- Agregado arrayempty
- Error si en arraypattern detecta un no arreglo
- Parámetro name($name = null) ahora es name(string $name = "")
- Regex "chara"
- No se pueden ingresar nuevos patrones

1.1
Bugfix:
- Referer no actúa correctamente

## Instalación

Copia el archivo `validator.php` a tu proyecto e inclúyelo en tu código PHP.
