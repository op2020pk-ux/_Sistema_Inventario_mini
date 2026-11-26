-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Base de datos:                db_inventory
-- Adaptado a Venezuela (Bodega / Abasto - Precios en USD)
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE DATABASE IF NOT EXISTS `db_inventory` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `db_inventory`;

-- --------------------------------------------------------
-- 1. TABLA: branches (4 Sucursales en Venezuela)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `branches` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `branch_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `branches`;
INSERT INTO `branches` (`id`, `branch_name`, `created_at`, `updated_at`) VALUES
	(1, 'Sede Principal - La Victoria (Centro)', NOW(), NOW()),
	(2, 'Sucursal El Consejo - Av. Bolívar', NOW(), NOW()),
	(3, 'Sucursal Zuata - Calle Principal', NOW(), NOW()),
	(4, 'Sucursal Maracay - Las Delicias', NOW(), NOW());

-- --------------------------------------------------------
-- 2. TABLA: categories (Categorías de Bodega / Abasto)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `categories`;
INSERT INTO `categories` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Harinas, Granos y Cereales', 1, NOW(), NOW()),
	(2, 'Aceites, Grasas y Salsas', 1, NOW(), NOW()),
	(3, 'Enlatados y Conservas', 1, NOW(), NOW()),
	(4, 'Lácteos, Huevos y Charcutería', 1, NOW(), NOW()),
	(5, 'Chucherías Saladas y Snacks', 1, NOW(), NOW()),
	(6, 'Chocolates, Galletas y Dulces', 1, NOW(), NOW()),
	(7, 'Refrescos, Jugos y Bebidas', 1, NOW(), NOW()),
	(8, 'Café, Azúcar y Panadería', 1, NOW(), NOW()),
	(9, 'Artículos de Limpieza del Hogar', 1, NOW(), NOW()),
	(10, 'Higiene y Cuidado Personal', 1, NOW(), NOW());

-- --------------------------------------------------------
-- 3. TABLA: companies (Datos de la Bodega en Venezuela)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `companies` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `companies`;
INSERT INTO `companies` (`id`, `name`, `phone`, `address`, `created_at`, `updated_at`) VALUES
	(1, 'Bodega y Abasto El Gran Ahorro C.A.', '0244-3224589 / 0412-4589632', 'Av. Francisco de Loreto, Local N° 14, La Victoria, Estado Aragua, Venezuela.', NOW(), NOW());

-- --------------------------------------------------------
-- 4. TABLA: customers (Clientes con datos de Venezuela)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `customers`;
INSERT INTO `customers` (`id`, `customer_name`, `email`, `phone`, `address`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Carlos Eduardo Mendoza', 'carlos.mendoza@gmail.com', '0412-4587912', 'Calle Rivas Dávila, Casa N° 24, Sector Centro, La Victoria, Edo. Aragua', 1, NOW(), NOW()),
	(2, 'María Gabriela Rodríguez', 'mariag.rodriguez@gmail.com', '0414-3458921', 'Urb. Las Mercedes, Calle 3, Casa N° 12, La Victoria, Edo. Aragua', 1, NOW(), NOW()),
	(3, 'José Gregorio Bolívar', 'jgbolivar@hotmail.com', '0424-3124578', 'Barrio El Cementerio, Calle Principal, Casa S/N, La Victoria, Edo. Aragua', 1, NOW(), NOW()),
	(4, 'Ana Karina Hernández', 'anakarina.hernandez@gmail.com', '0416-5421890', 'Av. Bolívar, Edif. Los Bucares, Piso 2, Apto 2-B, Maracay, Edo. Aragua', 1, NOW(), NOW()),
	(5, 'Luis Alberto Zambrano', 'luiszambrano88@gmail.com', '0426-8954123', 'Sector Zuata, Calle El Samán, Casa N° 8, Zuata, Edo. Aragua', 1, NOW(), NOW()),
	(6, 'Yolanda del Carmen Pérez', 'yolandaperez.ve@gmail.com', '0412-7845120', 'Urb. El Castaño, Av. Principal, Quinta Mi Refugio, Maracay, Edo. Aragua', 1, NOW(), NOW()),
	(7, 'Miguel Ángel Castellanos', 'miguel.castellanos@yahoo.com', '0414-4895623', 'Calle Páez, Cruce con Calle Miranda, Local 4, Cagua, Edo. Aragua', 1, NOW(), NOW()),
	(8, 'Carmen Elena Rondón', 'carmenrondon@gmail.com', '0424-1258963', 'Av. Urdaneta, Res. San José, Torre A, Apto 5-A, Caracas, Distrito Capital', 1, NOW(), NOW()),
	(9, 'Jesús Rafael Briceño', 'jesus.briceno@gmail.com', '0412-9632587', 'Urb. La Mora I, Calle 14, Casa N° 45, La Victoria, Edo. Aragua', 1, NOW(), NOW()),
	(10, 'Daniela Alejandra Rojas', 'daniela.rojas95@gmail.com', '0414-5874123', 'Sector El Consejo, Calle Real, Casa N° 19, Revenga, Edo. Aragua', 1, NOW(), NOW()),
	(11, 'Pedro Antonio Quintero', 'pedroquintero.ve@hotmail.com', '0424-3698521', 'Av. Bolívar Norte, Urb. El Viñedo, Calle 102, Valencia, Edo. Carabobo', 1, NOW(), NOW()),
	(12, 'Marisol Josefina Gil', 'marisol.gil@gmail.com', '0416-7412589', 'Sector La Quebrada, Calle El Sol, Casa N° 31, La Victoria, Edo. Aragua', 1, NOW(), NOW()),
	(13, 'Rafael Enrique Solórzano', 'rafael.solorzano@gmail.com', '0412-3216549', 'Urb. Parque Aragua, Bloque 12, Apto 03, Maracay, Edo. Aragua', 1, NOW(), NOW()),
	(14, 'Genesis Paola Colmenares', 'genesis.colmenares@gmail.com', '0424-7539514', 'Calle Comercio, Edif. Don Pedro, Piso 1, Turmero, Edo. Aragua', 1, NOW(), NOW()),
	(15, 'Héctor Manuel Tovar', 'hectortovar.aragua@gmail.com', '0414-9517532', 'Urb. La Mora II, Av. Principal, Casa N° 108, La Victoria, Edo. Aragua', 1, NOW(), NOW()),
	(16, 'Nelly Coromoto Salcedo', 'nellysalcedo@gmail.com', '0426-4567891', 'Sector San Mateo, Calle Bolívar, Casa N° 52, San Mateo, Edo. Aragua', 1, NOW(), NOW());

-- --------------------------------------------------------
-- 5. TABLA: vendors (Proveedores con datos de Venezuela)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `vendors` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `vendors`;
INSERT INTO `vendors` (`id`, `name`, `phone`, `email`, `address`, `created_at`, `updated_at`) VALUES
	(1, 'Distribuidora Alimentos Polar Aragua', '0243-2358940', 'ventas.aragua@empresaspolar.com', 'Zona Industrial San Vicente I, Av. Anton Phillips, Maracay, Edo. Aragua', NOW(), NOW()),
	(2, 'Inversiones y Confitería El Rey C.A.', '0412-4589630', 'pedidos@confiteriaelrey.com.ve', 'Av. Bolívar Oeste, Galpón N° 5, La Victoria, Edo. Aragua', NOW(), NOW()),
	(3, 'Comercializadora Savoy y Golosinas Vzla', '0212-2418596', 'distribucion@golosinasvzla.com.ve', 'Zona Industrial Boleíta Sur, Calle Vargas, Caracas, Distrito Capital', NOW(), NOW()),
	(4, 'Mayorista de Víveres Los Llanos C.A.', '0244-3214569', 'ventas@vivereslosllanos.com.ve', 'Zona Industrial Soco, Parcela 18, La Victoria, Edo. Aragua', NOW(), NOW()),
	(5, 'Distribuidora Pepsico Snacks Aragua', '0243-2694512', 'rutas.aragua@snacksvenezuela.com.ve', 'Carretera Nacional Cagua - La Villa, Galpón 3, Cagua, Edo. Aragua', NOW(), NOW()),
	(6, 'Lácteos y Charcutería Los Andes C.A.', '0414-4859621', 'pedidos@lacteoslosandes.com.ve', 'Av. Constitución, Mercado Mayorista, Local 22, Maracay, Edo. Aragua', NOW(), NOW()),
	(7, 'Embotelladora Coca-Cola FEMSA Aragua', '0243-2714580', 'atencioncliente@femsavenezuela.com.ve', 'Av. Intercomunal Turmero - Maracay, Sector La Providencia, Edo. Aragua', NOW(), NOW()),
	(8, 'Importadora y Distribuidora La Giralda', '0212-5412369', 'ventas@lagiralda.com.ve', 'Av. San Martín, Edif. Industrial Giralda, Caracas, Distrito Capital', NOW(), NOW()),
	(9, 'Distribuidora de Químicos y Limpieza CleanVe', '0424-3589641', 'contacto@cleanve.com.ve', 'Zona Industrial Las Vegas, Calle 2, Cagua, Edo. Aragua', NOW(), NOW()),
	(10, 'Panificadora y Galletera Puig Distribuciones', '0212-4725896', 'ventas@galleterapuig.com.ve', 'Av. Nueva Granada, Galpón Industrial Puig, Caracas, Distrito Capital', NOW(), NOW());

-- --------------------------------------------------------
-- 6. TABLA: products (Cesta Básica y Chucherías - 90 Productos)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `product_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `products`;
INSERT INTO `products` (`id`, `category_id`, `product_name`, `details`, `status`, `created_at`, `updated_at`) VALUES
	-- CATEGORÍA 1: Harinas, Granos y Cereales (Cesta Básica)
	(1, 1, 'Harina de Maíz Blanco P.A.N. 1 Kg', 'Harina de maíz precocida blanca enriquecida con vitaminas y hierro para arepas y empanadas.', 1, NOW(), NOW()),
	(2, 1, 'Harina de Maíz Amarillo P.A.N. 1 Kg', 'Harina de maíz precocida amarilla ideal para bollitos, hallacas y empanadas.', 1, NOW(), NOW()),
	(3, 1, 'Harina de Maíz Juana 1 Kg', 'Harina de maíz blanco precocida suave y rendidora.', 1, NOW(), NOW()),
	(4, 1, 'Arroz Blanco Primor Clásico 1 Kg', 'Arroz blanco de mesa tipo I, grano entero de excelente cocción.', 1, NOW(), NOW()),
	(5, 1, 'Arroz Blanco Mary Tradicional 1 Kg', 'Arroz blanco seleccionado de grano largo para el consumo diario.', 1, NOW(), NOW()),
	(6, 1, 'Pasta Larga Espagueti Primor 1 Kg', 'Pasta alimenticia elaborada con sémola de trigo durum.', 1, NOW(), NOW()),
	(7, 1, 'Pasta Corta Plumitas Mary 1 Kg', 'Pasta corta tipo plumitas ideal para pastichos, ensaladas y guisos.', 1, NOW(), NOW()),
	(8, 1, 'Pasta Corta Tornillos Sindoni 1 Kg', 'Pasta corta en forma de tornillo de cocción rápida y firme.', 1, NOW(), NOW()),
	(9, 1, 'Caraotas Negras Empacadas 1 Kg', 'Granos seleccionados de caraotas negras limpias y listas para cocinar.', 1, NOW(), NOW()),
	(10, 1, 'Lentejas Empacadas 500 g', 'Lentejas secas seleccionadas ricas en hierro y proteínas.', 1, NOW(), NOW()),
	(11, 1, 'Frijol Bayo / Chino Empacado 1 Kg', 'Frijoles blancos seleccionados de primera calidad.', 1, NOW(), NOW()),
	(12, 1, 'Harina de Trigo Todo Uso Robin Hood 1 Kg', 'Harina de trigo refinada ideal para panadería, repostería y cocina general.', 1, NOW(), NOW()),
	(13, 1, 'Avena en Hojuelas Quaker 400 g', 'Hojuelas de avena integral 100% natural para bebidas y desayunos.', 1, NOW(), NOW()),
	(14, 1, 'Fororo Tradicional 500 g', 'Harina de maíz tostado dulce para bebidas nutritivas.', 1, NOW(), NOW()),

	-- CATEGORÍA 2: Aceites, Grasas y Salsas (Cesta Básica)
	(15, 2, 'Aceite Vegetal Mazeite 1 Litro', 'Aceite comestible 100% puro de maíz libre de colesterol.', 1, NOW(), NOW()),
	(16, 2, 'Aceite Vegetal Vatel 1 Litro', 'Aceite vegetal de soya ideal para freír y aderezar.', 1, NOW(), NOW()),
	(17, 2, 'Margarina Mavesa 500 g', 'Margarina untable con sal enriquecida con vitaminas A y D.', 1, NOW(), NOW()),
	(18, 2, 'Margarina Mavesa 250 g', 'Presentación pequeña de margarina untable para arepas y pan.', 1, NOW(), NOW()),
	(19, 2, 'Mayonesa Mavesa 445 g', 'Mayonesa tradicional cremosa en frasco de vidrio o envase plástico.', 1, NOW(), NOW()),
	(20, 2, 'Mayonesa Kraft 445 g', 'Mayonesa clásica ideal para ensaladas, perros calientes y hamburguesas.', 1, NOW(), NOW()),
	(21, 2, 'Salsa de Tomate Ketchup Pampero 397 g', 'Salsa de tomate tipo ketchup elaborada con tomates maduros.', 1, NOW(), NOW()),
	(22, 2, 'Salsa de Tomate Heinz 397 g', 'Salsa ketchup espesa de sabor tradicional.', 1, NOW(), NOW()),
	(23, 2, 'Salsa Inglesa Iberia 150 ml', 'Condimento líquido para carnes, guisos y sopas.', 1, NOW(), NOW()),
	(24, 2, 'Vinagre Blanco Mavesa 500 ml', 'Vinagre blanco destilado para ensaladas y limpieza de alimentos.', 1, NOW(), NOW()),
	(25, 2, 'Sal Refinada de Mesa 1 Kg', 'Sal marina refinada y yodada para cocinar.', 1, NOW(), NOW()),

	-- CATEGORÍA 3: Enlatados y Conservas (Cesta Básica)
	(26, 3, 'Atún en Aceite Margarita 140 g', 'Lomitos de atún en aceite vegetal listos para consumir.', 1, NOW(), NOW()),
	(27, 3, 'Atún al Natural Eveba 140 g', 'Atún en agua bajo en calorías.', 1, NOW(), NOW()),
	(28, 3, 'Sardinas en Salsa de Tomate Margarita 170 g', 'Sardinas frescas en salsa de tomate condimentada.', 1, NOW(), NOW()),
	(29, 3, 'Sardinas en Aceite Vegetal 170 g', 'Sardinas enlatadas en aceite comestible.', 1, NOW(), NOW()),
	(30, 3, 'Diablitos Underwood 110 g', 'Jamón endiablado untable para arepas, sándwiches y empanadas.', 1, NOW(), NOW()),
	(31, 3, 'Rikesa Cheddar 190 g', 'Crema untable sabor a queso cheddar.', 1, NOW(), NOW()),
	(32, 3, 'Pasta de Tomate La Giralda 250 g', 'Concentrado de tomate para salsas de pasta y guisos.', 1, NOW(), NOW()),
	(33, 3, 'Maíz Dulce en Grano Del Monte 300 g', 'Granos de maíz tierno enlatados listos para ensaladas.', 1, NOW(), NOW()),

	-- CATEGORÍA 4: Lácteos, Huevos y Charcutería (Cesta Básica)
	(34, 4, 'Cartón de Huevos (30 Unidades)', 'Huevos frescos de gallina tamaño grande.', 1, NOW(), NOW()),
	(35, 4, 'Medio Cartón de Huevos (15 Unidades)', 'Bandeja de 15 huevos frescos de gallina.', 1, NOW(), NOW()),
	(36, 4, 'Leche en Polvo Completa La Campiña 900 g', 'Leche entera en polvo instantánea fortificada.', 1, NOW(), NOW()),
	(37, 4, 'Leche en Polvo Completa 400 g', 'Leche entera en polvo en empaque flexible.', 1, NOW(), NOW()),
	(38, 4, 'Leche Líquida UHT Completa 1 Litro', 'Leche entera larga duración en envase Tetra Pak.', 1, NOW(), NOW()),
	(39, 4, 'Queso Blanco Duro Llanero (Por Kg)', 'Queso blanco llanero firme ideal para rallar.', 1, NOW(), NOW()),
	(40, 4, 'Queso Blanco Semiduro Paisa (Por Kg)', 'Queso blanco suave para rebanar en arepas y sándwiches.', 1, NOW(), NOW()),
	(41, 4, 'Mortadela Especial Plumrose 1 Kg', 'Mortadela de cerdo y pollo rebanada o por pieza.', 1, NOW(), NOW()),
	(42, 4, 'Salchichas Tipo Wiener Plumrose (Paquete)', 'Paquete de salchichas listas para hervir o freír.', 1, NOW(), NOW()),

	-- CATEGORÍA 5: Chucherías Saladas y Snacks
	(43, 5, 'Pepito Clásico Queso 75 g', 'Pasapalo horneado de maíz con intenso sabor a queso.', 1, NOW(), NOW()),
	(44, 5, 'Doritos Mega Queso 145 g', 'Tortillas de maíz crujientes con sabor a queso.', 1, NOW(), NOW()),
	(45, 5, 'Doritos Mega Queso Individual 42 g', 'Presentación individual de tortillas de maíz sabor queso.', 1, NOW(), NOW()),
	(46, 5, 'Cheese Tris 42 g', 'Palitos de maíz crujientes sabor a queso cheddar.', 1, NOW(), NOW()),
	(47, 5, 'Cheese Tris Familiar 140 g', 'Empaque grande de palitos de maíz sabor queso.', 1, NOW(), NOW()),
	(48, 5, 'Ruffles Onduladas Clásicas 135 g', 'Papas fritas onduladas con un toque de sal.', 1, NOW(), NOW()),
	(49, 5, 'Papas Fritas Lays Clásicas 40 g', 'Hojuelas de papa frita crujientes y saladas.', 1, NOW(), NOW()),
	(50, 5, 'Cheez Whiz Snacks / Boliqueso 45 g', 'Bolitas de maíz inflado sabor a queso.', 1, NOW(), NOW()),
	(51, 5, 'Platanitos Fritos Salados Natuchips 150 g', 'Tajadas crujientes de plátano verde salado.', 1, NOW(), NOW()),
	(52, 5, 'Tostones Salados en Bolsa 100 g', 'Tostones crujientes artesanales listos para comer.', 1, NOW(), NOW()),
	(53, 5, 'Maní Salado Tostado Jack’s 50 g', 'Maní tostado con sal ideal para merendar.', 1, NOW(), NOW()),
	(54, 5, 'De Todito Mix Clásico 140 g', 'Mezcla de Doritos, Pepito, Cheese Tris y Chicharrón.', 1, NOW(), NOW()),

	-- CATEGORÍA 6: Chocolates, Galletas y Dulces (Chucherías)
	(55, 6, 'Chocolate Savoy Carré Avellanas 100 g', 'Tableta de chocolate con leche y trozos de avellanas.', 1, NOW(), NOW()),
	(56, 6, 'Chocolate Savoy Leche Clásico 130 g', 'Tableta tradicional de chocolate con leche venezolano.', 1, NOW(), NOW()),
	(57, 6, 'Chocolate Savoy Cri Cri 123 g', 'Chocolate con leche y arroz tostado crujiente.', 1, NOW(), NOW()),
	(58, 6, 'Chocolate Savoy Cri Cri Individual 27 g', 'Barra individual de chocolate con arroz inflado.', 1, NOW(), NOW()),
	(59, 6, 'Galak Chocolate Blanco 25 g', 'Barra de chocolate blanco cremoso.', 1, NOW(), NOW()),
	(60, 6, 'Samba Fresa Savoy 32 g', 'Galleta tipo wafer cubierta de chocolate y relleno sabor fresa.', 1, NOW(), NOW()),
	(61, 6, 'Samba Chocolate Savoy 32 g', 'Galleta tipo wafer rellena de crema de chocolate.', 1, NOW(), NOW()),
	(62, 6, 'Toronto Savoy (Unidad)', 'Bombón de avellana entera cubierta de chocolate con leche.', 1, NOW(), NOW()),
	(63, 6, 'Susy Galleta Rellena 50 g', 'Galleta wafer crujiente rellena de crema sabor a chocolate.', 1, NOW(), NOW()),
	(64, 6, 'Cocosette Galleta 50 g', 'Galleta wafer rellena de crema sabor a coco.', 1, NOW(), NOW()),
	(65, 6, 'Pirulín Lata 155 g', 'Barquillas rellenas de crema de chocolate y avellana.', 1, NOW(), NOW()),
	(66, 6, 'Flips Dulce de Leche 120 g', 'Cereal crujiente relleno de crema sabor a dulce de leche.', 1, NOW(), NOW()),
	(67, 6, 'Flips Chocolate 120 g', 'Cereal crujiente relleno de crema de chocolate.', 1, NOW(), NOW()),
	(68, 6, 'Galletas Oreo Tubo 108 g', 'Galletas de chocolate rellenas de crema de vainilla.', 1, NOW(), NOW()),
	(69, 6, 'Galletas María Puig Paquete (4 Tubos)', 'Galletas dulces tipo María tradicionales.', 1, NOW(), NOW()),
	(70, 6, 'Galletas Club Social Original (Paquete 6 Und)', 'Galletas saladas tipo cracker ideales para merendar.', 1, NOW(), NOW()),
	(71, 6, 'Chupeta Bon Bon Bum Fresa (Unidad)', 'Chupeta de caramelo duro sabor fresa con centro de chicle.', 1, NOW(), NOW()),
	(72, 6, 'Caramelos Masticables Lokino / Frunas (Paquete)', 'Caramelos masticables surtidos de sabores frutales.', 1, NOW(), NOW()),
	(73, 6, 'Gomitas Trululu Surtidas 90 g', 'Gomitas dulces de frutas con formas divertidas.', 1, NOW(), NOW()),
	(74, 6, 'Chicle Agogo / Bubbaloo (Unidad)', 'Goma de mascar rellena de líquido sabor tuttifrutti.', 1, NOW(), NOW()),

	-- CATEGORÍA 7: Refrescos, Jugos y Bebidas
	(75, 7, 'Refresco Coca-Cola 2 Litros', 'Bebida gaseosa sabor cola en botella retornable o no retornable.', 1, NOW(), NOW()),
	(76, 7, 'Refresco Pepsi-Cola 2 Litros', 'Bebida gaseosa sabor cola tamaño familiar.', 1, NOW(), NOW()),
	(77, 7, 'Refresco Frescolita 2 Litros', 'Bebida gaseosa tradicional sabor a kolita.', 1, NOW(), NOW()),
	(78, 7, 'Refresco Chinotto 1.5 Litros', 'Bebida gaseosa refrescante sabor lima-limón.', 1, NOW(), NOW()),
	(79, 7, 'Malta Maltín Polar 1.5 Litros', 'Bebida de malta nutritiva y refrescante.', 1, NOW(), NOW()),
	(80, 7, 'Malta Maltín Polar Botella 250 ml', 'Botella individual de malta fría.', 1, NOW(), NOW()),
	(81, 7, 'Jugo Yukery Naranja 1 Litro', 'Bebida pasteurizada sabor a naranja en envase Tetra Pak.', 1, NOW(), NOW()),
	(82, 7, 'Agua Mineral Minalba 1.5 Litros', 'Agua potable purificada de manantial.', 1, NOW(), NOW()),

	-- CATEGORÍA 8: Café, Azúcar y Panadería
	(83, 8, 'Café Molido Madrid 500 g', 'Café tostado y molido tradicional venezolano.', 1, NOW(), NOW()),
	(84, 8, 'Café Molido Amanecer 200 g', 'Café gourmet tostado y molido.', 1, NOW(), NOW()),
	(85, 8, 'Azúcar Refinada Montalbán 1 Kg', 'Azúcar blanca refinada de caña.', 1, NOW(), NOW()),
	(86, 8, 'Pan de Sándwich Blanco Bimbo Grande', 'Pan rebanado suave para sándwiches.', 1, NOW(), NOW()),

	-- CATEGORÍA 9 y 10: Limpieza e Higiene Básica de Bodega
	(87, 9, 'Detergente en Polvo Las Llaves 1 Kg', 'Detergente multiusos para lavar ropa.', 1, NOW(), NOW()),
	(88, 9, 'Jabón Azul en Panelas Las Llaves 250 g', 'Jabón en barra multiusos para ropa y platos.', 1, NOW(), NOW()),
	(89, 10, 'Papel Higiénico Rosal Plus (Paquete 4 Rollos)', 'Papel higiénico suave doble hoja.', 1, NOW(), NOW()),
	(90, 10, 'Crema Dental Colgate Triple Acción 90 ml', 'Pasta dental con flúor para higiene bucal.', 1, NOW(), NOW());

-- --------------------------------------------------------
-- 7. TABLA: stocks (Inventario con Precios en DÓLARES USD)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `stocks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `product_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` int NOT NULL,
  `vendor_id` int NOT NULL,
  `user_id` int NOT NULL,
  `chalan_no` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `buying_price` double NOT NULL,
  `selling_price` double NOT NULL,
  `discount` double NOT NULL DEFAULT '0',
  `stock_quantity` int NOT NULL,
  `current_quantity` int NOT NULL DEFAULT '0',
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  KEY `product_id` (`product_id`),
  KEY `vendor_id` (`vendor_id`),
  KEY `stock_quantity` (`stock_quantity`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `stocks`;
INSERT INTO `stocks` (`id`, `category_id`, `product_code`, `product_id`, `vendor_id`, `user_id`, `chalan_no`, `buying_price`, `selling_price`, `discount`, `stock_quantity`, `current_quantity`, `note`, `status`, `created_at`, `updated_at`) VALUES
	(1, 1, 'BOD-001', 1, 1, 12, 'FAC-2026-001', 1.15, 1.45, 0, 120, 115, 'Harina PAN Blanca en USD', 1, NOW(), NOW()),
	(2, 1, 'BOD-002', 4, 1, 12, 'FAC-2026-001', 1.10, 1.40, 0, 100, 96, 'Arroz Primor 1Kg en USD', 1, NOW(), NOW()),
	(3, 1, 'BOD-003', 6, 1, 12, 'FAC-2026-001', 1.25, 1.60, 0, 80, 80, 'Espagueti Primor 1Kg en USD', 1, NOW(), NOW()),
	(4, 1, 'BOD-004', 9, 4, 12, 'FAC-2026-002', 1.80, 2.30, 0, 50, 50, 'Caraotas Negras 1Kg en USD', 1, NOW(), NOW()),
	(5, 2, 'BOD-005', 15, 1, 12, 'FAC-2026-001', 2.40, 3.00, 0, 60, 58, 'Aceite Mazeite 1L en USD', 1, NOW(), NOW()),
	(6, 2, 'BOD-006', 17, 1, 12, 'FAC-2026-001', 1.90, 2.40, 0, 48, 48, 'Margarina Mavesa 500g en USD', 1, NOW(), NOW()),
	(7, 2, 'BOD-007', 19, 1, 12, 'FAC-2026-001', 2.30, 2.90, 0, 40, 40, 'Mayonesa Mavesa 445g en USD', 1, NOW(), NOW()),
	(8, 3, 'BOD-008', 26, 4, 12, 'FAC-2026-002', 1.35, 1.75, 0, 72, 72, 'Atún Margarita en USD', 1, NOW(), NOW()),
	(9, 3, 'BOD-009', 30, 4, 12, 'FAC-2026-002', 1.50, 1.95, 0, 60, 60, 'Diablitos Underwood en USD', 1, NOW(), NOW()),
	(10, 4, 'BOD-010', 34, 6, 12, 'FAC-2026-003', 4.50, 5.50, 0, 40, 39, 'Cartón de Huevos 30 und en USD', 1, NOW(), NOW()),
	(11, 4, 'BOD-011', 36, 6, 12, 'FAC-2026-003', 5.80, 7.20, 0, 30, 30, 'Leche La Campiña 900g en USD', 1, NOW(), NOW()),
	(12, 4, 'BOD-012', 39, 6, 12, 'FAC-2026-003', 4.80, 6.20, 0, 25, 25, 'Queso Blanco Llanero Kg en USD', 1, NOW(), NOW()),
	(13, 5, 'CHU-001', 43, 5, 12, 'FAC-2026-004', 0.85, 1.20, 0, 90, 88, 'Pepito Clásico 75g en USD', 1, NOW(), NOW()),
	(14, 5, 'CHU-002', 44, 5, 12, 'FAC-2026-004', 1.80, 2.40, 0, 60, 60, 'Doritos Mega Queso 145g en USD', 1, NOW(), NOW()),
	(15, 5, 'CHU-003', 46, 5, 12, 'FAC-2026-004', 0.65, 0.95, 0, 100, 100, 'Cheese Tris 42g en USD', 1, NOW(), NOW()),
	(16, 6, 'CHU-004', 55, 3, 12, 'FAC-2026-005', 2.20, 2.85, 0, 50, 50, 'Chocolate Carré Avellanas en USD', 1, NOW(), NOW()),
	(17, 6, 'CHU-005', 58, 3, 12, 'FAC-2026-005', 0.60, 0.85, 0, 120, 115, 'Cri Cri Individual 27g en USD', 1, NOW(), NOW()),
	(18, 6, 'CHU-006', 60, 3, 12, 'FAC-2026-005', 0.65, 0.90, 0, 120, 120, 'Samba Fresa 32g en USD', 1, NOW(), NOW()),
	(19, 6, 'CHU-007', 62, 3, 12, 'FAC-2026-005', 0.45, 0.65, 0, 150, 145, 'Toronto Savoy Unidad en USD', 1, NOW(), NOW()),
	(20, 6, 'CHU-008', 65, 2, 12, 'FAC-2026-006', 2.60, 3.40, 0, 40, 40, 'Pirulín Lata 155g en USD', 1, NOW(), NOW()),
	(21, 6, 'CHU-009', 68, 2, 12, 'FAC-2026-006', 0.95, 1.30, 0, 80, 80, 'Galletas Oreo Tubo en USD', 1, NOW(), NOW()),
	(22, 6, 'CHU-010', 71, 2, 12, 'FAC-2026-006', 0.15, 0.25, 0, 200, 190, 'Chupeta Bon Bon Bum en USD', 1, NOW(), NOW()),
	(23, 7, 'BEB-001', 75, 7, 12, 'FAC-2026-007', 1.65, 2.10, 0, 72, 70, 'Coca-Cola 2L en USD', 1, NOW(), NOW()),
	(24, 7, 'BEB-002', 79, 1, 12, 'FAC-2026-001', 1.40, 1.85, 0, 60, 60, 'Maltín Polar 1.5L en USD', 1, NOW(), NOW()),
	(25, 8, 'CAF-001', 83, 4, 12, 'FAC-2026-002', 3.40, 4.20, 0, 45, 45, 'Café Madrid 500g en USD', 1, NOW(), NOW());

-- --------------------------------------------------------
-- 8. TABLAS DE SISTEMA: menus, migrations, password_resets, permissions, roles, users
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `menus` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int NOT NULL DEFAULT '0',
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_url` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `menus`;
INSERT INTO `menus` (`id`, `parent_id`, `name`, `icon`, `menu_url`, `status`, `created_at`, `updated_at`) VALUES
	(1, 0, 'Clientes', 'contacts', 'customer.index', 0, '2020-07-29 13:17:51', '2020-07-29 13:17:56'),
	(2, 0, 'Gestión de Productos', 'category', NULL, 0, '2020-07-29 13:17:53', '2020-07-29 13:17:54'),
	(3, 0, 'Gestión de Existencias', 'assignment', NULL, 0, '2020-07-29 13:17:52', '2020-07-29 13:17:54'),
	(4, 0, 'Gestión de usuarios', 'supervised_user_circle', NULL, 0, '2020-07-29 13:17:51', '2020-07-29 13:17:56'),
	(5, 0, 'Reportes', 'receipt_long', 'report.index', 0, '2020-07-29 13:17:52', '2020-07-29 13:17:55'),
	(6, 0, 'Configuración', 'settings', NULL, 0, '2020-07-29 13:17:58', '2020-07-29 13:17:57'),
	(7, 2, 'Categorias', NULL, 'category.index', 0, '2020-07-29 13:17:50', '2020-07-29 13:17:57'),
	(8, 2, 'Productos', NULL, 'product.index', 0, '2020-07-29 13:17:49', '2020-07-29 13:17:59'),
	(9, 2, 'Proveedores', NULL, 'supplier.index', 0, '2020-07-29 13:17:49', '2020-07-29 13:18:00'),
	(10, 3, 'Entradas', NULL, 'stock.index', 0, '2020-07-29 13:17:48', '2020-07-29 13:18:00'),
	(11, 3, 'Salidas / Facturación', NULL, 'invoice.index', 0, '2020-07-29 13:17:47', '2020-07-29 13:18:01'),
	(12, 4, 'Gestión de roles', NULL, 'role.index', 0, '2020-07-29 13:17:46', '2020-07-29 13:17:46'),
	(13, 4, 'Usuarios', NULL, 'user.index', 0, '2020-07-29 13:17:44', '2020-07-29 13:17:44'),
	(14, 6, 'Información de la empresa', NULL, 'company.index', 0, '2020-07-29 13:17:43', '2020-07-29 13:17:45'),
	(15, 6, 'Cambiar la contraseña', NULL, 'password.index', 0, '2020-07-29 13:17:42', '2020-07-29 13:16:37'),
	(16, 0, 'Información', 'info', NULL, 0, NOW(), NOW()),
	(17, 16, 'Desarrollador', NULL, 'desarrollador.php', 0, NOW(), NOW());

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `migrations`;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '2014_10_12_000000_create_users_table', 1),
	(2, '2014_10_12_100000_create_password_resets_table', 1),
	(3, '2018_12_10_051212_create_products_table', 2),
	(4, '2018_12_10_052440_create_vendors_table', 2),
	(5, '2018_12_10_052501_create_customers_table', 2),
	(6, '2018_12_10_052521_create_stocks_table', 2),
	(7, '2018_12_10_052610_create_sells_table', 2),
	(8, '2018_12_10_052631_create_sell_details_table', 2),
	(9, '2018_12_10_075236_create_branches_table', 2),
	(10, '2018_12_31_160432_create_categories_table', 3),
	(11, '2019_01_12_163604_create_payments_table', 4),
	(12, '2019_01_19_152250_biye--tabl', 5),
	(13, '2019_02_10_113651_create_roles_table', 6),
	(14, '2019_02_10_114632_create_permissions_table', 6),
	(15, '2019_02_10_114735_create_menus_table', 6),
	(16, '2019_02_14_130126_create_companies_table', 7);

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `password_resets`;

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int NOT NULL,
  `menu_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=746 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `permissions`;
INSERT INTO `permissions` (`id`, `role_id`, `menu_id`, `created_at`, `updated_at`) VALUES
	(124, 5, 1, '2019-02-23 00:54:16', '2019-02-23 00:54:16'),
	(125, 5, 2, '2019-02-23 00:54:16', '2019-02-23 00:54:16'),
	(126, 5, 3, '2019-02-23 00:54:16', '2019-02-23 00:54:16'),
	(127, 5, 4, '2019-02-23 00:54:16', '2019-02-23 00:54:16'),
	(128, 5, 5, '2019-02-23 00:54:16', '2019-02-23 00:54:16'),
	(129, 5, 6, '2019-02-23 00:54:16', '2019-02-23 00:54:16'),
	(130, 5, 9, '2019-02-23 00:54:16', '2019-02-23 00:54:16'),
	(131, 5, 8, '2019-02-23 00:54:16', '2019-02-23 00:54:16'),
	(132, 5, 7, '2019-02-23 00:54:16', '2019-02-23 00:54:16'),
	(133, 5, 10, '2019-02-23 00:54:16', '2019-02-23 00:54:16'),
	(134, 5, 11, '2019-02-23 00:54:16', '2019-02-23 00:54:16'),
	(135, 5, 12, '2019-02-23 00:54:16', '2019-02-23 00:54:16'),
	(136, 5, 15, '2019-02-23 00:54:16', '2019-02-23 00:54:16'),
	(137, 6, 1, '2019-02-23 03:25:01', '2019-02-23 03:25:01'),
	(138, 6, 3, '2019-02-23 03:25:01', '2019-02-23 03:25:01'),
	(139, 6, 6, '2019-02-23 03:25:01', '2019-02-23 03:25:01'),
	(140, 6, 15, '2019-02-23 03:25:01', '2019-02-23 03:25:01'),
	(706, 4, 11, '2020-07-31 17:30:54', '2020-07-31 17:30:54'),
	(707, 4, 2, '2020-07-31 17:30:54', '2020-07-31 17:30:54'),
	(708, 4, 4, '2020-07-31 17:30:54', '2020-07-31 17:30:54'),
	(709, 4, 15, '2020-07-31 17:30:54', '2020-07-31 17:30:54'),
	(710, 4, 6, '2020-07-31 17:30:54', '2020-07-31 17:30:54'),
	(721, 3, 1, '2020-11-17 17:03:42', '2020-11-17 17:03:42'),
	(722, 3, 9, '2020-11-17 17:03:42', '2020-11-17 17:03:42'),
	(723, 3, 8, '2020-11-17 17:03:42', '2020-11-17 17:03:42'),
	(724, 3, 7, '2020-11-17 17:03:42', '2020-11-17 17:03:42'),
	(725, 3, 2, '2020-11-17 17:03:42', '2020-11-17 17:03:42'),
	(726, 3, 10, '2020-11-17 17:03:42', '2020-11-17 17:03:42'),
	(727, 3, 11, '2020-11-17 17:03:42', '2020-11-17 17:03:42'),
	(728, 3, 3, '2020-11-17 17:03:42', '2020-11-17 17:03:42'),
	(729, 2, 1, '2021-07-05 20:00:38', '2021-07-05 20:00:38'),
	(730, 2, 9, '2021-07-05 20:00:38', '2021-07-05 20:00:38'),
	(731, 2, 8, '2021-07-05 20:00:38', '2021-07-05 20:00:38'),
	(732, 2, 7, '2021-07-05 20:00:38', '2021-07-05 20:00:38'),
	(733, 2, 2, '2021-07-05 20:00:38', '2021-07-05 20:00:38'),
	(734, 2, 10, '2021-07-05 20:00:38', '2021-07-05 20:00:38'),
	(735, 2, 11, '2021-07-05 20:00:38', '2021-07-05 20:00:38'),
	(736, 2, 3, '2021-07-05 20:00:38', '2021-07-05 20:00:38'),
	(737, 2, 12, '2021-07-05 20:00:38', '2021-07-05 20:00:38'),
	(738, 2, 13, '2021-07-05 20:00:38', '2021-07-05 20:00:38'),
	(739, 2, 4, '2021-07-05 20:00:38', '2021-07-05 20:00:38'),
	(740, 2, 5, '2021-07-05 20:00:38', '2021-07-05 20:00:38'),
	(741, 2, 14, '2021-07-05 20:00:38', '2021-07-05 20:00:38'),
	(742, 2, 15, '2021-07-05 20:00:38', '2021-07-05 20:00:38'),
	(743, 2, 6, '2021-07-05 20:00:38', '2021-07-05 20:00:38'),
	(744, 2, 16, NOW(), NOW()),
	(745, 2, 17, NOW(), NOW());

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `role_name`, `created_at`, `updated_at`) VALUES
	(2, 'Superadministrador', '2019-02-12 03:59:54', '2023-04-17 04:53:28'),
	(3, 'Gerente', '2019-02-13 00:07:41', '2023-04-17 04:35:56'),
	(4, 'Vendedor', '2019-02-13 01:34:11', '2023-04-17 04:36:08'),
	(5, 'Controlador', '2019-02-13 05:53:15', '2023-04-17 04:41:36');

CREATE TABLE IF NOT EXISTS `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_id` int NOT NULL DEFAULT '1',
  `role_id` int NOT NULL DEFAULT '1',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- DELETE FROM `users`;
-- INSERT INTO `users` (`id`, `name`, `email`, `password`, `branch_id`, `role_id`, `remember_token`, `created_at`, `updated_at`) VALUES
	-- (12, 'Omar Pinto', 'op2020pk@gmail.com', '$2y$10$eImiTXuWVxfM37uY4JANjQ==', 1, 2, '6zd4CxMA9RCTnuJaxtyhByrBYJsPfV9GtAB4pJE3Ht6YTsIvQ7ETjUVO1jlv', '2020-07-31 17:27:25', NOW());

DELETE FROM `users`;
INSERT INTO `users` (`id`, `name`, `email`, `password`, `branch_id`, `role_id`, `remember_token`, `created_at`, `updated_at`) VALUES
	(12, 'Omar Pinto', 'op2020pk@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, 2, '6zd4CxMA9RCTnuJaxtyhByrBYJsPfV9GtAB4pJE3Ht6YTsIvQ7ETjUVO1jlv', '2020-07-31 17:27:25', NOW());

-- --------------------------------------------------------
-- 9. TABLAS DE VENTAS Y PAGOS: sells, sell_details, payments (en USD)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `sells` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `branch_id` int NOT NULL DEFAULT '1',
  `total_amount` double NOT NULL,
  `paid_amount` double NOT NULL DEFAULT '0',
  `sell_date` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` double NOT NULL DEFAULT '0',
  `payment_method` tinyint NOT NULL DEFAULT '0',
  `payment_status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `sells`;
INSERT INTO `sells` (`id`, `user_id`, `customer_id`, `branch_id`, `total_amount`, `paid_amount`, `sell_date`, `discount_amount`, `payment_method`, `payment_status`, `created_at`, `updated_at`) VALUES
	(1, 12, 1, 1, 13.25, 13.25, '2026-11-25', 0, 2, 1, NOW(), NOW());

CREATE TABLE IF NOT EXISTS `sell_details` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `stock_id` int NOT NULL,
  `sell_id` int NOT NULL,
  `product_id` int NOT NULL,
  `category_id` int NOT NULL,
  `vendor_id` int NOT NULL,
  `user_id` int NOT NULL,
  `chalan_no` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `selling_date` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sold_quantity` int NOT NULL,
  `buy_price` double NOT NULL,
  `sold_price` double NOT NULL,
  `total_buy_price` double NOT NULL,
  `total_sold_price` double NOT NULL,
  `discount` double NOT NULL,
  `discount_type` tinyint NOT NULL,
  `discount_amount` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_id` (`stock_id`),
  KEY `selling_date` (`selling_date`),
  KEY `product_id` (`product_id`),
  KEY `category_id` (`category_id`),
  KEY `vendor_id` (`vendor_id`),
  KEY `user_id` (`user_id`),
  KEY `sell_id` (`sell_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `sell_details`;
INSERT INTO `sell_details` (`id`, `stock_id`, `sell_id`, `product_id`, `category_id`, `vendor_id`, `user_id`, `chalan_no`, `selling_date`, `customer_id`, `sold_quantity`, `buy_price`, `sold_price`, `total_buy_price`, `total_sold_price`, `discount`, `discount_type`, `discount_amount`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 1, 1, 1, 12, 'FAC-2026-001', '2026-11-25', '1', 5, 1.15, 1.45, 5.75, 7.25, 0, 1, 0, NOW(), NOW()),
	(2, 5, 1, 15, 2, 1, 12, 'FAC-2026-001', '2026-11-25', '1', 2, 2.40, 3.00, 4.80, 6.00, 0, 1, 0, NOW(), NOW());

CREATE TABLE IF NOT EXISTS `payments` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `sell_id` int NOT NULL,
  `customer_id` int NOT NULL,
  `user_id` int NOT NULL,
  `date` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `paid_in` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_information` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `amount` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sell_id` (`sell_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `payments`;
INSERT INTO `payments` (`id`, `sell_id`, `customer_id`, `user_id`, `date`, `paid_in`, `bank_information`, `amount`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 12, '2026-11-25', 'efectivo_usd', 'Pago en divisas efectivo ($)', 13.25, NOW(), NOW());

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
