-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema trabajophp
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema trabajophp
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `trabajophp` DEFAULT CHARACTER SET utf8 ;
USE `trabajophp` ;

-- -----------------------------------------------------
-- Table `trabajophp`.`Proveedores`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `trabajophp`.`Proveedores` (
  `idProveedor` INT NOT NULL,
  `NombreProv` VARCHAR(45) NOT NULL,
  `Direccion` VARCHAR(200) NOT NULL,
  `Telefono` INT NOT NULL,
  `PersonaContacto` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idProveedor`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trabajophp`.`Empleados`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `trabajophp`.`Empleados` (
  `idEmpleado` INT NOT NULL,
  `NombreEmp` VARCHAR(45) NOT NULL,
  `Edad` VARCHAR(45) NOT NULL,
  `FechaContratacion` DATE NOT NULL,
  `NumeroCuenta` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idEmpleado`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trabajophp`.`Clientes`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `trabajophp`.`Clientes` (
  `idCliente` INT NOT NULL,
  `NombreCli` VARCHAR(45) NOT NULL,
  `Direccion` VARCHAR(200) NOT NULL,
  `Telefono` INT NOT NULL,
  PRIMARY KEY (`idCliente`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trabajophp`.`Usuarios`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `trabajophp`.`Usuarios` (
  `idUsuario` INT NOT NULL,
  `Tipo` VARCHAR(45) NOT NULL,
  `idEmpleado` INT NOT NULL,
  PRIMARY KEY (`idUsuario`),
  INDEX `fk_Usuarios_Empleados1_idx` (`idEmpleado` ASC) VISIBLE,
  CONSTRAINT `fk_Usuarios_Empleados1`
    FOREIGN KEY (`idEmpleado`)
    REFERENCES `trabajophp`.`Empleados` (`idEmpleado`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trabajophp`.`Categoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `trabajophp`.`Categoria` (
  `idCategoria` INT NOT NULL,
  `NombreCat` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idCategoria`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trabajophp`.`Productos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `trabajophp`.`Productos` (
  `idProducto` INT NOT NULL,
  `NombreProd` VARCHAR(45) NOT NULL,
  `Precio` DECIMAL NOT NULL,
  `Stock` INT NULL,
  `IdCategoria` INT NOT NULL,
  `idProveedor` INT NOT NULL,
  PRIMARY KEY (`idProducto`),
  INDEX `fk_Productos_Categoria_idx` (`IdCategoria` ASC) VISIBLE,
  INDEX `fk_Productos_Proveedores1_idx` (`idProveedor` ASC) VISIBLE,
  CONSTRAINT `fk_Productos_Categoria`
    FOREIGN KEY (`IdCategoria`)
    REFERENCES `trabajophp`.`Categoria` (`idCategoria`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Productos_Proveedores1`
    FOREIGN KEY (`idProveedor`)
    REFERENCES `trabajophp`.`Proveedores` (`idProveedor`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `trabajophp`.`Pedidos`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `trabajophp`.`Pedidos` (
  `idPedido` INT NOT NULL,
  `idProducto` INT NOT NULL,
  `Cantidad` INT NOT NULL,
  `Total` VARCHAR(45) NOT NULL,
  `idCliente` INT NOT NULL,
  `idEmpleado` INT NOT NULL,
  PRIMARY KEY (`idPedido`),
  INDEX `fk_Pedidos_Clientes1_idx` (`idCliente` ASC) VISIBLE,
  INDEX `fk_Pedidos_Productos1_idx` (`idProducto` ASC) VISIBLE,
  INDEX `fk_Pedidos_Empleados1_idx` (`idEmpleado` ASC) VISIBLE,
  CONSTRAINT `fk_Pedidos_Clientes1`
    FOREIGN KEY (`idCliente`)
    REFERENCES `trabajophp`.`Clientes` (`idCliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Pedidos_Productos1`
    FOREIGN KEY (`idProducto`)
    REFERENCES `trabajophp`.`Productos` (`idProducto`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Pedidos_Empleados1`
    FOREIGN KEY (`idEmpleado`)
    REFERENCES `trabajophp`.`Empleados` (`idEmpleado`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

/*Categoria*/

INSERT INTO categoria (idCategoria, NombreCat) value ("1", "Café Americano"),("2", "Café Colombiano"),("3", "Café Italiano"),("4", "Café Puertorriqueño"),("5", "Café Arabica");

/*Clientes*/

INSERT INTO clientes (idCliente, NombreCli, Direccion, Telefono) value ("1", "Teresa Roman García", "C/ Guardia Civil, nº 3, 2º B", "665445588"),("2", "Diego Velez Muñoz", "C/ Golfo de Salónica, nº 40, 5º A", "983251421"), ("3", "Guatavo Garcia Pimienta", "C/ Oviedo, nº 3, 1º A", "983256655"), ("4", "Marta Fernandez Asís", "C/ Santa María de la Cabeza, nº 20, 3º F", "983114477"), ("5", "María de la Enseñanza Rodriguez", "C/ Laurel, nº 4, Bº B", "654587799");

/*Empleados*/

INSERT INTO empleados (idEmpleado, NombreEmp, Edad, FechaContratacion, NumeroCuenta) value ("1", "Lucia García García", "40", "2018-05-02", "ES12 1548 1254 8547 7788"), ("2", "Mario Campiñez Fernandez", "20", "2021-01-22", "ES25 3658 2145 1452 2288"), ("3", "Fermín Lopez Reverte", "32", "2008-11-14", "ES52 4896 2314 2365 1452"), ("4", "Juana María Martón Gómez", "50", "2001-12-07", "ES78 8521 4563 2145 4488"), ("5", "Fernando Gobernado Fuentes", "33", "2022-07-09", "ES14 2369 8547 4521 4521");

/*Proveedores*/

INSERT INTO proveedores (idProveedor, NombreProv, Direccion, Telefono, PersonaContacto) value ("1", "Incapto", "Puerto de Barcelona, 27", "911231467", "Elena Rodriguez"), ("2", "CafePlatino", "Avenida Sistema Solar 18, 28830, San Fernando de Henares", "916556979", "Rodolfo Jimenez"), ("3", "Kaffekapslen", "C/ Fernino Guarda, 34, Madrid", "910780586", "Juan Golondo"), ("4", "Cafe Saula", "C/ Laureà Miró, 422-424, 08980, Sant Feliu De Llobregat", "936662698", "Xavier Vidaller"), ("5", "Cafe Kimbo", "Enric Prat de la Riba, 2 , Sant Boi de Llobregat - Barcelona", "933037615", "Arnau Terra");

/*Usuarios*/

INSERT INTO usuarios (idUsuario, Tipo, idEmpleado) value ("1", "Estandar", (Select idEmpleado from empleados where empleados.idEmpleado = "1")),("2", "Estandar", (Select idEmpleado from empleados where empleados.idEmpleado = "2")),("3", "ADMIN", (Select idEmpleado from empleados where empleados.idEmpleado = "3")),("4", "ADMIN", (Select idEmpleado from empleados where empleados.idEmpleado = "4")),("5", "Estandar", (Select idEmpleado from empleados where empleados.idEmpleado = "5"));

/*Productos*/

INSERT INTO productos (idProducto, NombreProd, Precio, Stock, IdCategoria, idProveedor) values ("1", "Café Puertorriqueño Suave", 4.99, 100, (select idCategoria from categoria where categoria.NombreCat = "Café Puertorriqueño"),(select idProveedor from proveedores where proveedores.NombreProv = "Incapto")),('2', 'Café Arabica Premium', 7.49, 50, (select idCategoria from categoria where categoria.NombreCat = "Café Arabica"), (select idProveedor from proveedores where proveedores.NombreProv = "CafePlatino")),
('3', 'Café Espresso Doble', 3.99, 75, (select idCategoria from categoria where categoria.NombreCat = "Café Italiano"), (select idProveedor from proveedores where proveedores.NombreProv = "Kaffekapslen")),
('4', 'Café Molido Fino', 5.99, 90, (select idCategoria from categoria where categoria.NombreCat = "Café Americano"), (select idProveedor from proveedores where proveedores.NombreProv = "Cafe Saula")),
('5', 'Café Tueste Medio', 6.49, 80, (select idCategoria from categoria where categoria.NombreCat = "Café Colombiano"), (select idProveedor from proveedores where proveedores.NombreProv = "Cafe Kimbo")),
('6', 'Café Descafeinado', 4.99, 120, (select idCategoria from categoria where categoria.NombreCat = "Café Puertorriqueño"), (select idProveedor from proveedores where proveedores.NombreProv = "Incapto")),
('7', 'Café Latte Caramel', 4.29, 60, (select idCategoria from categoria where categoria.NombreCat = "Café Italiano"), (select idProveedor from proveedores where proveedores.NombreProv = "CafePlatino")),
('8', 'Café Mocha', 4.79, 70, (select idCategoria from categoria where categoria.NombreCat = "Café Colombiano"), (select idProveedor from proveedores where proveedores.NombreProv = "Kaffekapslen")),
('9', 'Café Cold Brew', 3.99, 110, (select idCategoria from categoria where categoria.NombreCat = "Café Americano"), (select idProveedor from proveedores where proveedores.NombreProv = "Cafe Saula")),
('10', 'Café Irlandés', 5.99, 55, (select idCategoria from categoria where categoria.NombreCat = "Café Arabica"), (select idProveedor from proveedores where proveedores.NombreProv = "Cafe Kimbo"));

/*Pedido*/

INSERT INTO pedidos (idPedido, idProducto, Cantidad, Total, idCliente, idEmpleado) values ('1', (select productos.idProducto from productos where productos.NombreProd = 'Café Puertorriqueño Suave'), 10, 10*(select productos.precio from productos where productos.idProducto = '2'),(select clientes.idCliente from clientes where clientes.NombreCli = 'Teresa Roman García'),(select empleados.idEmpleado from empleados where empleados.NombreEmp = 'Lucia García García'));