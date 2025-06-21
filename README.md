# BalancePro - Sistema de Contabilidad

BalancePro es un sistema de contabilidad desarrollado en PHP que permite gestionar cuentas, pólizas y generar reportes contables. Este proyecto utiliza una arquitectura basada en controladores y vistas, con un enfoque en la manipulación de datos mediante consultas SQL.

## Tabla de Contenidos

- [BalancePro - Sistema de Contabilidad](#balancepro---sistema-de-contabilidad)
  - [Tabla de Contenidos](#tabla-de-contenidos)
  - [Requisitos](#requisitos)
  - [Estructura del Proyecto](#estructura-del-proyecto)
  - [Funcionalidades Principales](#funcionalidades-principales)
    - [Gestión de Cuentas](#gestión-de-cuentas)
    - [Gestión de Pólizas](#gestión-de-pólizas)
    - [Gestión de Detalles de Pólizas](#gestión-de-detalles-de-pólizas)
    - [Reportes Contables](#reportes-contables)
  - [Configuración de la Base de Datos](#configuración-de-la-base-de-datos)
  - [Rutas del Proyecto](#rutas-del-proyecto)
  - [Cómo Ejecutar el Proyecto](#cómo-ejecutar-el-proyecto)

---

## Requisitos

- **Servidor Web:** Apache (incluido en WampServer).
- **Base de Datos:** MySQL.
- **PHP:** Versión 7.4 o superior.
- **Entorno:** WampServer o similar.

---

## Estructura del Proyecto

El proyecto tiene la siguiente estructura de directorios:

```
BalancePro/
├── index.php
├── src/
│   ├── config/
│   │   └── database.php
│   ├── controllers/
│   │   ├── CuentasController.php
│   │   ├── PolizasController.php
│   │   └── DetallePolizaController.php
│   ├── views/
│       ├── cuentas/
│       ├── polizas/
│       ├── reportes/
│       └── layout.php
└── public/
    ├── css/
    └── js/
```

---

## Funcionalidades Principales

### Gestión de Cuentas

- **Descripción:** Permite agregar, editar, listar y eliminar cuentas contables.
- **Consultas SQL:**
  - **Agregar Cuenta:**
    ```sql
    INSERT INTO Cuentas (NumCuenta, NombreCuenta, Tipo) VALUES (:numCuenta, :nombreCuenta, :tipo);
    ```
  - **Editar Cuenta:**
    ```sql
    UPDATE Cuentas SET NombreCuenta = :nombreCuenta, Tipo = :tipo WHERE NumCuenta = :numCuenta;
    ```
  - **Listar Cuentas:**
    ```sql
    SELECT * FROM Cuentas;
    ```
  - **Eliminar Cuenta:**
    ```sql
    DELETE FROM Cuentas WHERE NumCuenta = :numCuenta;
    ```

### Gestión de Pólizas

- **Descripción:** Permite agregar, editar, listar, ver y eliminar pólizas contables.
- **Consultas SQL:**
  - **Agregar Póliza:**
    ```sql
    INSERT INTO Polizas (NumPoliza, Fecha, Descripcion) VALUES (:numPoliza, :fecha, :descripcion);
    ```
  - **Editar Póliza:**
    ```sql
    UPDATE Polizas SET Fecha = :fecha, Descripcion = :descripcion WHERE NumPoliza = :numPoliza;
    ```
  - **Listar Pólizas:**
    ```sql
    SELECT * FROM Polizas;
    ```
  - **Ver Póliza:**
    ```sql
    SELECT * FROM Polizas WHERE NumPoliza = ?;
    SELECT D.DebeHaber, D.Valor, C.NombreCuenta 
    FROM DetallePoliza D, Cuentas C 
    WHERE D.NumCuenta = C.NumCuenta AND D.NumPoliza = ?;
    ```
  - **Eliminar Póliza:**
    ```sql
    DELETE FROM Polizas WHERE NumPoliza = :numPoliza;
    ```

### Gestión de Detalles de Pólizas

- **Descripción:** Permite agregar, editar, listar y eliminar los detalles de las pólizas.
- **Consultas SQL:**
  - **Agregar Detalle:**
    ```sql
    INSERT INTO DetallePoliza (NumPoliza, NumCuenta, DebeHaber, Valor) VALUES (:numPoliza, :numCuenta, :debeHaber, :valor);
    ```
  - **Listar Detalles:**
    ```sql
    SELECT * FROM DetallePoliza WHERE NumPoliza = ?;
    ```

### Reportes Contables

- **Reporte Diario:**
  - **Descripción:** Lista las pólizas de una fecha específica.
  - **Consulta SQL:**
    ```sql
    SELECT * FROM Polizas WHERE Fecha = :date;
    SELECT D.*, C.NombreCuenta 
    FROM DetallePoliza D
    JOIN Cuentas C ON D.NumCuenta = C.NumCuenta
    WHERE D.NumPoliza IN (SELECT NumPoliza FROM Polizas WHERE Fecha = :date);
    ```

- **Reporte Mayor:**
  - **Descripción:** Muestra el mayor de una cuenta específica.
  - **Consulta SQL:**
    ```sql
    SELECT P.NumPoliza AS poliza, P.Fecha AS fecha, P.Descripcion AS descripcion,
           D.DebeHaber AS debeHaber, D.Valor AS valor
    FROM DetallePoliza D
    JOIN Polizas P ON D.NumPoliza = P.NumPoliza
    WHERE D.NumCuenta = :cuenta;
    ```

- **Reporte de Balance:**
  - **Descripción:** Genera un balance general agrupado por tipo de cuenta.
  - **Consulta SQL:**
    ```sql
    SELECT Cuentas.NombreCuenta, Cuentas.Tipo, DetallePoliza.NumCuenta, 
           SUM(IF(DebeHaber='D', Valor, 0)) AS Debe,
           SUM(IF(DebeHaber='H', Valor, 0)) AS Haber
    FROM DetallePoliza
    JOIN Cuentas ON DetallePoliza.NumCuenta = Cuentas.NumCuenta
    GROUP BY NumCuenta;
    ```

---

## Configuración de la Base de Datos

El archivo `src/config/database.php` establece la conexión con la base de datos:

```php
$host = 'localhost'; 
$dbname = 'CONTABILIDAD'; 
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
```

---

## Rutas del Proyecto

El archivo `index.php` define las rutas principales del sistema:

- `/cuentas`: Gestión de cuentas.
- `/polizas`: Gestión de pólizas.
- `/detallePoliza`: Gestión de detalles de pólizas.
- `/reportes`: Generación de reportes contables.

---

## Cómo Ejecutar el Proyecto

1. Clona este repositorio en tu servidor local.
2. Configura la base de datos ejecutando las consultas SQL necesarias.
3. Asegúrate de que el archivo `src/config/database.php` tenga las credenciales correctas.
4. Inicia el servidor Apache y accede al proyecto desde tu navegador.

---
