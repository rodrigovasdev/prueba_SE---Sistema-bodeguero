# Módulo de Gestión de Bodegas

El siguiente módulo permite la creación de bodegas a través de un formulario,
seguido por un listado de las bodegas ingresadas. El usuario puede modificar las bodegas o eliminarlas.
Los encargados ya se encuentran cargados en la base de datos.

---

## Versiones utilizadas

| Tecnología | Versión |
|------------|---------|
| PostgreSQL | 18.3.3 |
| PHP        | 8.5.5  |

> **Nota:** No fue posible encontrar el historial de versiones para descargar PHP 7.x,
> por lo que se decidió continuar el desarrollo con una versión actual.

---

## Instrucciones de instalación

1. Crear una nueva base de datos en PostgreSQL.
2. Restaurar con el archivo ubicado en `bd/Backup`.
3. En el archivo `php/conexion.php`, configurar las credenciales de la base de datos según corresponda.
